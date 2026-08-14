#!/usr/bin/env bash
#
# Export the content tables only — no users, no tasbih counts, no bookmarks.
#
#   ./scripts/deploy/export-content.sh [output.sql]
#
# Reads database credentials from .env. Produces a data-only dump: no CREATE TABLE, no
# DROP TABLE. Structure belongs to the migrations, and recreating tables would drop the
# foreign keys that user tables rely on.
#
# The companion import script is the only thing that should load this file.

set -euo pipefail

cd "$(dirname "${BASH_SOURCE[0]}")/../.."

source scripts/deploy/content-tables.sh

OUT="${1:-storage/app/content-$(date +%Y%m%d-%H%M%S).sql}"

# ---------------------------------------------------------------------------
# Credentials from .env
# ---------------------------------------------------------------------------
if [[ ! -f .env ]]; then
  echo "error: no .env in $(pwd)" >&2
  exit 1
fi

# A real environment variable wins over .env, mirroring how Laravel's own dotenv behaves.
# Without this the script would read a different database than the application does.
envval() {
  if [[ -n "${!1:-}" ]]; then
    echo "${!1}"
    return
  fi
  grep -E "^$1=" .env | head -1 | cut -d= -f2- | sed -E 's/^"(.*)"$/\1/' | tr -d '\r'
}

DB_HOST=$(envval DB_HOST); DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=$(envval DB_PORT); DB_PORT=${DB_PORT:-3306}
DB_USER=$(envval DB_USERNAME)
DB_PASS=$(envval DB_PASSWORD)
DB_NAME=$(envval DB_DATABASE)

MYSQL_ARGS=(-h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" --default-character-set=utf8mb4)
[[ -n "$DB_PASS" ]] && MYSQL_ARGS+=("-p$DB_PASS")

echo "Exporting content from '$DB_NAME' on $DB_HOST"
echo

# ---------------------------------------------------------------------------
# Report what is going, and what is deliberately staying behind
# ---------------------------------------------------------------------------
printf "  %-34s %s\n" "TABLE" "ROWS"
total=0
for t in "${CONTENT_TABLES[@]}"; do
  n=$(mysql "${MYSQL_ARGS[@]}" -N -B -e "SELECT COUNT(*) FROM \`$t\`" "$DB_NAME" 2>/dev/null || echo "-")
  printf "  %-34s %s\n" "$t" "$n"
  [[ "$n" =~ ^[0-9]+$ ]] && total=$((total + n))
done
echo "  ----------------------------------------"
printf "  %-34s %s\n" "TOTAL" "$total"

echo
echo "  Excluded (belongs to the destination):"
for t in "${USER_TABLES[@]}"; do
  n=$(mysql "${MYSQL_ARGS[@]}" -N -B -e "SELECT COUNT(*) FROM \`$t\`" "$DB_NAME" 2>/dev/null || echo "-")
  printf "    %-32s %s\n" "$t" "$n"
done

# ---------------------------------------------------------------------------
# Dump
# ---------------------------------------------------------------------------
#
# --no-create-info      data only; structure comes from migrations
# --complete-insert     column names in every INSERT, so a column order change cannot
#                       silently shift values into the wrong fields
# --single-transaction  consistent snapshot without locking
# --default-character-set=utf8mb4  Arabic and Bangla survive the round trip
mkdir -p "$(dirname "$OUT")"

mysqldump "${MYSQL_ARGS[@]}" \
  --no-create-info \
  --complete-insert \
  --single-transaction \
  --skip-triggers \
  --no-tablespaces \
  "$DB_NAME" "${CONTENT_TABLES[@]}" > "$OUT"

echo
echo "Wrote $OUT ($(du -h "$OUT" | cut -f1))"
echo
echo "Next: copy it to the server and run"
echo "  ./scripts/deploy/import-content.sh $(basename "$OUT")"
