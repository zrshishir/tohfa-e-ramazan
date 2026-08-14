# Deploying v3.2.1 to production

Production is currently 13 commits behind. Those commits are Laravel 10 → 12,
Filament 2 → 3, the admin access gate and the `.env` work. **All the functional fixes —
iftar derivation, the ayat seeder, the auth endpoints — are already live**; nothing in this
deploy changes what the API returns.

Read `env-and-secrets.md` alongside this. `APP_KEY` rotation happens during this deploy.

---

## What production will actually experience

| | |
|---|---|
| New migrations | **1** — the admin-role backfill |
| API response shapes | Unchanged |
| Frontend | Already released; no app store submission needed |
| Downtime | One container restart |
| Admin sessions | Dropped, by design — `APP_KEY` rotates |
| Mobile user sessions | **Unaffected** — Sanctum tokens are hashed, not encrypted |

---

## Before you start

- [ ] **Back up your local `.env`** — `cp .env .env.local-backup`. Pulling deletes it. See
      `env-and-secrets.md`; the symptom is every route returning 500.
- [ ] Take a database backup you have **actually restored from once**. The content import
      script takes its own, but that is not the same as knowing your restore works.
- [ ] Decide how production receives configuration — the three commands in
      `env-and-secrets.md` settle it. This matters now, because `.dockerignore` stops the
      image carrying a `.env`.

---

## 1. Configuration

Set these in production's environment (host panel, orchestrator, compose `environment:`,
or `--env-file`) — **not** in a committed file:

```
APP_KEY=<new value from `php artisan key:generate --show`>
APP_ENV=production
APP_DEBUG=false
APP_URL=https://prayerpulse.tazqiah.com
OCTANE_SERVER=swoole
GOOGLE_MAPS_KEY=<IP-restricted key>
DB_HOST=... DB_DATABASE=... DB_USERNAME=... DB_PASSWORD=...
```

`APP_DEBUG=false` and `APP_ENV=production` must be set **explicitly**. Do not rely on them
being absent — until now the image carried a `.env` with `APP_DEBUG=true`, and anything the
environment did not set fell through to it.

`GOOGLE_MAPS_KEY` was never in `.env` at all, so `GET /api/geocode` has been running
without a key.

---

## 2. Deploy the code

```bash
git checkout main && git pull            # after the release PR merges
docker compose build --no-cache          # picks up the new .dockerignore
docker compose up -d
```

The build now runs `composer install --no-scripts`, copies the application, then
`composer dump-autoload`, which is what publishes Filament's assets and regenerates
`bootstrap/cache/packages.php`.

If your deploy does not rebuild the image, delete the stale manifest by hand:

```bash
rm -f bootstrap/cache/packages.php bootstrap/cache/services.php
```

That file is why the application refused to boot mid-upgrade — it still referenced
`Akaunting\Money\Provider`, a package that left with Filament 2.

---

## 3. Migrate

```bash
php artisan migrate --force
```

One migration runs: `2026_08_12_000000_grant_admin_role_to_seeded_admin_user`. It sets
`role = 'admin'` on `admin@admin.com` and touches nothing else.

**Verified on a copy of the real 106 MB database**: rolled back, re-applied, 1.5 ms, all
42,035 content rows untouched.

> **If your admin account is not `admin@admin.com`**, the migration grants nobody the role
> and **you will be locked out of `/admin` with a 403**. Fix with:
> ```sql
> UPDATE users SET role = 'admin' WHERE email = 'your@email';
> ```

---

## 4. Content import

Only if production's content is stale — it has no hadiths, or its ayats still carry the
Bangla translation in `bangla_text`. Check first:

```sql
SELECT (SELECT COUNT(*) FROM hadiths) AS hadiths,
       (SELECT COUNT(*) FROM ayats)   AS ayats,
       (SELECT COUNT(*) FROM ayats WHERE bangla_text = meaning) AS ayats_with_duplicated_meaning;
```

Local reference: **34,455 hadiths, 6,236 ayats, 0 duplicated**.

If production differs, export locally and import on the server:

```bash
# local
./scripts/deploy/export-content.sh
scp storage/app/content-*.sql user@server:/path/to/app/

# server
./scripts/deploy/import-content.sh content-20260813-120000.sql
php artisan optimize:clear
```

### What the import does and does not touch

**Replaced** (18 tables, 42,035 rows): countries, divisions, districts,
district_wise_schedule_settings, months, permanent_calendars, mazhabs,
mazhab_wise_schedule_settings, suras, ayats, doa_categories, doas, masala_categories,
masalas, hadith_books, hadith_chapters, hadiths, asmaul_husnas.

**Never touched**: users, tasbih, bookmarks, personal_access_tokens,
password_reset_tokens, feedbacks, blogs.

The script takes a full backup first and refuses to run without one, prints user-table
counts before and after and fails if any changed, and checks for orphaned bookmarks and
users afterwards.

It uses `DELETE` rather than `DROP`, so the foreign keys that `bookmarks` and `users`
depend on are never removed. `doas`, `doa_categories` and `mazhabs` carry a NOT NULL
`user_id`; those are remapped to **production's** admin, because the exported id refers to
a different person.

### How this was tested

Against a copy of the real database, seeded with three extra users, their tasbih counters
and a bookmark, then deliberately damaged — all hadiths deleted and 100 ayats corrupted:

- content repaired: 34,455 hadiths restored, 0 damaged ayats remaining
- all 3 users, 4 tasbih rows and 2 bookmarks unchanged
- no orphans
- **Arabic and Bangla byte-identical** — MD5 over the whole of `ayats.arabic_text`,
  `ayats.meaning` and `hadiths.bangla_text` matches the source exactly

---

## 5. Verify

```bash
curl -s -o /dev/null -w "%{http_code}\n" https://prayerpulse.tazqiah.com/api/today-prayer
```

| # | Check | Expected |
|---|---|---|
| 5.1 | `GET /api/today-prayer` | 200, waqts plus derived `iftar` |
| 5.2 | `GET /api/ramazan-calendar` | 200, 30 entries, current year |
| 5.3 | `GET /api/sura`, `/api/ayat/1` | 200 |
| 5.4 | `GET /api/hadith-books` | 200, 6 books |
| 5.5 | An **existing** mobile token against `/api/auth/me` | 200 — proves the key rotation left Sanctum alone |
| 5.6 | `/admin` signed out | 302 → `/admin/login` |
| 5.7 | `/admin` as `admin@admin.com` | Dashboard loads |
| 5.8 | `/admin` as an ordinary app user | **403** |
| 5.9 | Trigger any error | No stack trace — confirms `APP_DEBUG=false` |
| 5.10 | Edit and save a record in a few resources | Persists |

5.5 and 5.8 are the two worth not skipping. 5.5 confirms the rotation did not log out
every mobile user; 5.8 confirms the admin is no longer open to anyone with an account.

---

## Rollback

Code:

```bash
git checkout <previous main sha>
docker compose build && docker compose up -d
```

The one migration is reversible (`php artisan migrate:rollback --step=1`), though leaving
it applied is harmless — it only sets a role column.

**Keep the old `APP_KEY` until you are satisfied.** Rolling the code back while the new key
stays in place is fine; the two are independent.

Database, if the content import went wrong — the script prints this path as it runs:

```bash
mysql -h <host> -u <user> -p <db> < storage/app/backup-before-content-import-<timestamp>.sql
```

---

## After

- [ ] Confirm `composer audit` is clean on the deployed tree
- [ ] Rotate the frontend Google Maps key — still outstanding, unrelated to this deploy
- [ ] Device QA: notifications, Qibla, audio, account sync
- [ ] `bangla_text` still needs a real Bangla *uccharon* source; it is deliberately empty
      rather than duplicating the meaning
