#!/usr/bin/env bash
#
# Production deploy, run on the server. Piped in over SSH by
# .github/workflows/deploy.yml, or run by hand:
#
#   bash deploy/production.sh [expected-commit-sha]
#
# Every guard below exists because that failure actually happened during the first
# manual deploy of v3.3.0. None of them are hypothetical.

set -euo pipefail

APP_DIR="${APP_DIR:-$HOME/subdomains/prayerpulse.tazqiah.com}"
EXPECTED_SHA="${1:-}"

cd "$APP_DIR"

echo "==> Deploying in $APP_DIR"
echo "    current: $(git rev-parse --short HEAD) $(git log -1 --format=%s)"

# ---------------------------------------------------------------------------
# 1. The environment file must exist before anything else
# ---------------------------------------------------------------------------
#
# .env is untracked now, so git will not delete it — but it was tracked until
# v3.2.0, and a checkout of that commit removed it and took the site down with it.
# If it is missing, stop before doing damage rather than after.
if [[ ! -f .env ]]; then
  echo "error: no .env in $APP_DIR. Refusing to deploy — every route would 500." >&2
  exit 1
fi

# ---------------------------------------------------------------------------
# 2. Fetch, and make a failed fetch fatal
# ---------------------------------------------------------------------------
#
# The first deploy fetched over SSH, hit "Permission denied (publickey)", and then
# `git reset --hard origin/main` silently reset to a two-year-old *cached* origin/main.
# The command reported success while moving production backwards by 116 commits.
# `set -e` plus an explicit SHA check makes that impossible to repeat.
echo "==> Fetching origin/main"
git fetch origin main --prune

TARGET_SHA=$(git rev-parse origin/main)

if [[ -n "$EXPECTED_SHA" && "$TARGET_SHA" != "$EXPECTED_SHA"* ]]; then
  echo "error: origin/main is $TARGET_SHA but the workflow expected $EXPECTED_SHA." >&2
  echo "       The fetch did not bring down the commit being deployed. Stopping." >&2
  exit 1
fi

# ---------------------------------------------------------------------------
# 3. Maintenance mode, with a trap so a failure cannot leave the site down
# ---------------------------------------------------------------------------
cleanup() {
  local code=$?
  if [[ $code -ne 0 ]]; then
    echo "==> Deploy failed (exit $code). Bringing the site back up." >&2
  fi
  php artisan up >/dev/null 2>&1 || true
  exit $code
}
trap cleanup EXIT

php artisan down --render="errors::503" --retry=60 >/dev/null 2>&1 || true

# ---------------------------------------------------------------------------
# 4. Code
# ---------------------------------------------------------------------------
echo "==> Checking out $TARGET_SHA"
git reset --hard "$TARGET_SHA"

# Belt and braces: confirm .env survived. It should, being untracked — but this is
# the exact thing that broke the first deploy, so it is worth one line to be sure.
if [[ ! -f .env ]]; then
  echo "error: .env disappeared during checkout. Restore it before continuing." >&2
  exit 1
fi

# ---------------------------------------------------------------------------
# 5. Dependencies
# ---------------------------------------------------------------------------
echo "==> composer install"
COMPOSER_MEMORY_LIMIT=-1 composer install \
  --no-dev --optimize-autoloader --no-interaction --prefer-dist

# ---------------------------------------------------------------------------
# 6. Database
# ---------------------------------------------------------------------------
echo "==> migrate"
php artisan migrate --force

# ---------------------------------------------------------------------------
# 7. Caches
# ---------------------------------------------------------------------------
#
# route:cache works now that /privacy-policy is a Route::view rather than a closure.
# Laravel cannot serialise closures, and a single closure route made the command fail
# for the whole application — so the first production deploy had to skip route caching.
echo "==> caches"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

chmod -R 775 storage bootstrap/cache

# ---------------------------------------------------------------------------
# 8. Up, then verify from the outside
# ---------------------------------------------------------------------------
php artisan up
trap - EXIT

echo "==> Deployed $(git rev-parse --short HEAD) $(git log -1 --format=%s)"

BASE="${APP_URL_CHECK:-https://prayerpulse.tazqiah.com}"
FAILED=0
for path in /api/today-prayer /api/sura /api/hadith-books /privacy-policy /admin/login; do
  code=$(curl -s -o /dev/null -w '%{http_code}' --max-time 20 "$BASE$path" || echo "000")
  printf "    %-22s %s\n" "$path" "$code"
  [[ "$code" == "200" ]] || FAILED=1
done

if [[ $FAILED -ne 0 ]]; then
  echo "error: one or more endpoints did not return 200 after deploy." >&2
  exit 1
fi

echo "==> OK"
