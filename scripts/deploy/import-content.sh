#!/usr/bin/env bash
#
# Import a content dump, leaving every user-owned row untouched.
#
#   ./scripts/deploy/import-content.sh content-20260813-120000.sql
#
# Replaces the content tables with the dump's contents. Users, tasbih counters, bookmarks
# and tokens are never read or written — except that user_id columns inside content tables
# are remapped to THIS database's admin, because the exported ids refer to a different
# person here.
#
# Takes a full backup first, and refuses to proceed if it cannot.

set -euo pipefail

cd "$(dirname "${BASH_SOURCE[0]}")/../.."

source scripts/deploy/content-tables.sh

DUMP="${1:-}"
if [[ -z "$DUMP" || ! -f "$DUMP" ]]; then
  echo "usage: $0 <content-dump.sql>" >&2
  exit 1
fi

if [[ ! -f .env ]]; then
  echo "error: no .env in $(pwd)" >&2
  exit 1
fi

# A real environment variable wins over .env, mirroring how Laravel's own dotenv behaves.
# Without this the script would write to a different database than the application reads.
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

q() { mysql "${MYSQL_ARGS[@]}" -N -B -e "$1" "$DB_NAME"; }

echo "Target database: '$DB_NAME' on $DB_HOST"
echo "Dump:            $DUMP ($(du -h "$DUMP" | cut -f1))"
echo

# ---------------------------------------------------------------------------
# 1. Record the user data we must not disturb
# ---------------------------------------------------------------------------
echo "User data before (must be identical afterwards):"

# Plain "table=count" strings rather than an associative array: macOS ships bash 3.2,
# which has no `declare -A`, and the export half of this pair runs on a developer's Mac.
BEFORE=()
for t in "${USER_TABLES[@]}"; do
  n=$(q "SELECT COUNT(*) FROM \`$t\`" 2>/dev/null || echo "-")
  BEFORE+=("$t=$n")
  printf "  %-30s %s\n" "$t" "$n"
done

before_count() {
  local entry
  for entry in "${BEFORE[@]}"; do
    if [[ "${entry%%=*}" == "$1" ]]; then
      echo "${entry#*=}"
      return
    fi
  done
  echo "?"
}

ADMIN_ID=$(q "SELECT id FROM users WHERE role = 'admin' ORDER BY id LIMIT 1" 2>/dev/null || true)
if [[ -z "$ADMIN_ID" ]]; then
  echo
  echo "error: no user with role='admin' in this database." >&2
  echo "       Run 'php artisan migrate' first — the admin-role migration grants it." >&2
  exit 1
fi
echo
echo "  Content will be attributed to admin user id $ADMIN_ID"

# ---------------------------------------------------------------------------
# 2. Full backup — non-negotiable
# ---------------------------------------------------------------------------
BACKUP="storage/app/backup-before-content-import-$(date +%Y%m%d-%H%M%S).sql"
mkdir -p "$(dirname "$BACKUP")"

echo
echo "Backing up the entire database to $BACKUP ..."
mysqldump "${MYSQL_ARGS[@]}" --single-transaction --no-tablespaces --routines --triggers \
  "$DB_NAME" > "$BACKUP"

if [[ ! -s "$BACKUP" ]]; then
  echo "error: backup is empty — refusing to continue." >&2
  exit 1
fi
echo "  done ($(du -h "$BACKUP" | cut -f1))"

# ---------------------------------------------------------------------------
# 3. Confirm
# ---------------------------------------------------------------------------
echo
echo "About to replace ${#CONTENT_TABLES[@]} content tables in '$DB_NAME'."
echo "User tables are left alone. Restore with:"
echo "  mysql -h $DB_HOST -u $DB_USER -p $DB_NAME < $BACKUP"
echo
read -r -p "Type 'yes' to continue: " CONFIRM
[[ "$CONFIRM" == "yes" ]] || { echo "Aborted."; exit 1; }

# ---------------------------------------------------------------------------
# 4. Replace content
# ---------------------------------------------------------------------------
#
# DELETE rather than DROP: dropping the tables would take the foreign keys with them,
# including the ones bookmarks and users depend on. Structure is the migrations' job.
echo
echo "Importing ..."

{
  echo "SET FOREIGN_KEY_CHECKS = 0;"
  echo "START TRANSACTION;"
  for t in "${CONTENT_TABLES[@]}"; do
    echo "DELETE FROM \`$t\`;"
  done
  cat "$DUMP"
  for t in "${USER_OWNED_CONTENT[@]}"; do
    echo "UPDATE \`$t\` SET user_id = ${ADMIN_ID};"
  done
  echo "COMMIT;"
  echo "SET FOREIGN_KEY_CHECKS = 1;"
} | mysql "${MYSQL_ARGS[@]}" "$DB_NAME"

echo "  done"

# ---------------------------------------------------------------------------
# 5. Verify
# ---------------------------------------------------------------------------
echo
echo "Content now:"
for t in "${CONTENT_TABLES[@]}"; do
  printf "  %-34s %s\n" "$t" "$(q "SELECT COUNT(*) FROM \`$t\`")"
done

echo
echo "User data after:"
FAILED=0
for t in "${USER_TABLES[@]}"; do
  n=$(q "SELECT COUNT(*) FROM \`$t\`" 2>/dev/null || echo "-")
  was=$(before_count "$t")
  if [[ "$n" == "$was" ]]; then
    printf "  %-30s %-8s unchanged\n" "$t" "$n"
  else
    printf "  %-30s %-8s CHANGED from %s\n" "$t" "$n" "$was"
    FAILED=1
  fi
done

# Referential integrity: the rows most likely to be orphaned by swapping content out from
# under them are bookmarks, which point at specific ayat and sura ids.
echo
echo "Referential integrity:"
# The IS NOT NULL guards matter: a bookmark may legitimately have no sura_id (an
# ayat-only bookmark), and a bare LEFT JOIN would report every one of those as an orphan.
ORPHAN_AYAT=$(q "SELECT COUNT(*) FROM bookmarks b LEFT JOIN ayats a ON a.id = b.ayat_id WHERE b.ayat_id IS NOT NULL AND a.id IS NULL")
ORPHAN_SURA=$(q "SELECT COUNT(*) FROM bookmarks b LEFT JOIN suras s ON s.id = b.sura_id WHERE b.sura_id IS NOT NULL AND s.id IS NULL")
ORPHAN_CTRY=$(q "SELECT COUNT(*) FROM users u LEFT JOIN countries c ON c.id = u.country_id WHERE u.country_id IS NOT NULL AND c.id IS NULL")

printf "  %-40s %s\n" "bookmarks with a missing ayat" "$ORPHAN_AYAT"
printf "  %-40s %s\n" "bookmarks with a missing sura" "$ORPHAN_SURA"
printf "  %-40s %s\n" "users with a missing country" "$ORPHAN_CTRY"

if [[ "$ORPHAN_AYAT" != "0" || "$ORPHAN_SURA" != "0" || "$ORPHAN_CTRY" != "0" ]]; then
  FAILED=1
fi

echo
if [[ "$FAILED" == "0" ]]; then
  echo "OK — content replaced, user data intact, no orphans."
  echo "Now clear caches:  php artisan optimize:clear"
else
  echo "PROBLEM — review the output above." >&2
  echo "Restore with: mysql -h $DB_HOST -u $DB_USER -p $DB_NAME < $BACKUP" >&2
  exit 1
fi
