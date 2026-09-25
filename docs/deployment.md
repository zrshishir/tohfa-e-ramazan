# Deploying

Production runs on **cPanel shared hosting** — Apache/LiteSpeed with PHP-FPM, behind
Cloudflare.

> An earlier version of this document described a Docker and Octane deployment. That was
> wrong: `Dockerfile`, `docker-compose.yml` and `OCTANE_SERVER` exist in the repository but
> describe a setup this project does not run. Following it would have sent you looking for
> containers that aren't there.

| | |
|---|---|
| Host | cPanel, user `tazqiahcp` |
| App directory | `/home/tazqiahcp/subdomains/prayerpulse.tazqiah.com` |
| Document root | that directory's `public/` |
| PHP | 8.3 web **and** CLI |
| Composer | `/opt/cpanel/composer/bin/composer` |
| Database | MariaDB, `tazqiahcp_prayer_pulse` |
| Edge | Cloudflare (proxied — the domain's DNS does not reach the origin) |

---

## Automatic deploys

**Push to `main` and it deploys.** `.github/workflows/deploy.yml` runs the test suite,
then pipes `deploy/production.sh` to the server over SSH. A red build never reaches
production. The same workflow can be run by hand from the Actions tab.

### One-time setup

Four repository secrets (Settings → Secrets and variables → Actions):

| Secret | Value |
|---|---|
| `DEPLOY_HOST` | the real SSH hostname — **not** the Cloudflare-proxied domain |
| `DEPLOY_USER` | `tazqiahcp` |
| `DEPLOY_PORT` | cPanel's SSH port (rarely 22) |
| `DEPLOY_SSH_KEY` | private half of a **dedicated** deploy keypair |

Generate a keypair specifically for deployment — never reuse a personal key:

```bash
ssh-keygen -t ed25519 -C "prayerpulse-deploy" -f ~/.ssh/prayerpulse_deploy -N ""
cat ~/.ssh/prayerpulse_deploy.pub     # authorise this in cPanel → SSH Access
cat ~/.ssh/prayerpulse_deploy         # paste this into DEPLOY_SSH_KEY
```

A separate key means a repository compromise costs one revocation rather than the whole
hosting account.

---

## What the deploy script guards against

Every check in `deploy/production.sh` exists because it actually went wrong during the
first manual deploy of v3.3.0.

**A failed fetch must not look like success.** The server authenticated to GitHub over SSH
without an authorised key, `git fetch` failed, and `git reset --hard origin/main` then
reset to a **two-year-old cached** `origin/main`. The command reported success while moving
production backwards by 116 commits, and the only visible symptom was Composer complaining
about an unrelated lock file. The script now fails hard on a failed fetch and verifies the
resolved SHA matches the commit being deployed.

The remote is HTTPS rather than SSH, which needs no key at all while the repository is
public.

**`.env` must exist before and after.** It was tracked in git until v3.2.0, so checking out
any later commit *deletes it* — `.gitignore` only protects files git never tracked. Every
route then returns 500, which reads exactly like the deploy having broken the application.
The script refuses to start without `.env` and re-checks after the reset.

**A failed deploy must not leave the site down.** `artisan down` runs under a trap that
brings it back up on any non-zero exit.

**A deploy that "succeeds" but serves errors is not a success.** The script curls five
endpoints afterwards and exits non-zero unless all return 200.

---

## Manual deploy

```bash
cd ~/subdomains/prayerpulse.tazqiah.com
bash deploy/production.sh
```

Or step by step, if you want to watch each part:

```bash
git fetch origin main --prune
git reset --hard origin/main
composer install --no-dev --optimize-autoloader --no-interaction
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache && php artisan route:cache && php artisan view:cache
chmod -R 775 storage bootstrap/cache
```

---

## Environment

`.env` lives only on the server and is never committed. Required values:

```
APP_ENV=production
APP_DEBUG=false                              # exposes config and stack traces if true
APP_URL=https://prayerpulse.tazqiah.com
APP_KEY=base64:...                           # rotate if ever exposed
LOG_LEVEL=error                              # debug fills the disk quota on shared hosting
DB_DATABASE=tazqiahcp_prayer_pulse
GOOGLE_MAPS_KEY=...                          # /api/geocode needs it; IP-restrict it
```

`APP_ENV` and `APP_DEBUG` had been `local` and `true` in production until v3.3.0.

Run `config:cache` **after** editing `.env` — it snapshots the values, so caching first
bakes in whatever was there before.

---

## Database

The content tables can be replaced from a local export without touching user accounts:

```bash
./scripts/deploy/export-content.sh          # local
./scripts/deploy/import-content.sh dump.sql # server — backs up first, verifies after
```

For a full replacement, `mysqldump | gzip` locally, upload to `~/` via cPanel File Manager,
then:

```bash
gunzip -c ~/dump.sql.gz | mysql -u USER -p DBNAME && echo "IMPORT OK"
```

**Keep the `&& echo`.** A failed `gunzip` pipes nothing into `mysql`, which exits cleanly
having imported nothing — a failed import that looks identical to a successful one. That
happened, and the subsequent `migrate` then ran against the old schema instead.

---

## Known issues

**`bangla_text` duplicates `meaning` in 6,170 of 6,236 ayats.** The reader shows the Bangla
translation in the pronunciation field. 66 verses hold genuine uccharon, rescued from the
pre-v3.3.0 production database and re-applied after the import; they are the only authentic
pronunciation data that exists. The seeder deliberately writes this column empty rather than
duplicating the meaning, so **re-running `AyatTableSeeder` would blank all 6,236**, including
those 66. Do not re-seed ayats without exporting them first. alquran.cloud publishes no
Bengali transliteration edition; a licensed source is still needed.

**`/api/masala-category` returns 404.** The route does not exist under that name. Present
on local and production alike — not a deployment fault.

**`/api/mazhabs` returns a positional array** (`["success",200,...]`) rather than the
`{status,message,data}` envelope every other endpoint uses. Pre-existing inconsistency.

---

## Rollback

```bash
cd ~/subdomains/prayerpulse.tazqiah.com
git reset --hard <previous-sha>
composer install --no-dev --optimize-autoloader --no-interaction
php artisan optimize:clear && php artisan config:cache
```

Migrations are forward-only in practice. The only migration in v3.3.0 sets a role column
and is harmless to leave applied.
