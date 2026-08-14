# Manual Testing — Laravel 12

**Branch:** `feature/zrshishir/laravel-12`
**Target:** `development`
**Version:** 3.1.0

The point of this hop is one thing: **`composer audit` comes back clean.**

3.0.0 moved to Laravel 11 because Filament 3 required it, but Laravel 11 does not fix any
of the three outstanding advisories — all three land in the 12.x line.

---

## What actually changed

| Package | Before | After |
|---|---|---|
| `laravel/framework` | 11.55.0 | 12.66.0 |
| `phpunit/phpunit` | 10.5.64 | 11.5.56 |
| `nunomaduro/collision` | v8.5.0 | v8.9.5 |

PHPUnit had to move: Collision 8.6 is the first release compatible with Laravel 12, and it
requires PHPUnit 11.

**No application code changed.** Every edit in this branch is `composer.json`,
`composer.lock`, `phpunit.xml`, and test metadata. That is worth knowing when reviewing —
if something misbehaves after this merge, the cause is a framework behaviour change, not a
line someone wrote here.

---

## The advisories

| Advisory | Severity | Title | Fixed in |
|---|---|---|---|
| `PKSA-3r5d-mb8f-1qw9` | high | CRLF injection in the default email rule | 12.60.0 |
| `PKSA-mdq4-51ck-6kdq` | CVE-2026-48019 | CRLF injection in the default email rule | 12.60.0 |
| `PKSA-m5cs-t1y6-qpcs` | medium | Temporary signed URL path confusion | 12.61.1 |

`config.policy.advisories.ignore-id` is **deleted** from `composer.json` — not shortened.
The framework floor is `^12.61.1`, not `^12.0`, so a future resolve cannot quietly drop
back below the fixes.

| # | Step | Expected |
|---|---|---|
| 0.1 | `composer audit` | `No security vulnerability advisories found.` |
| 0.2 | `grep -c ignore-id composer.json` | `0` |

The CRLF advisories affect the `email` validation rule, which this app uses on the
registration path — so 0.1 is the acceptance test for the whole branch.

---

## 1. Automated

| # | Step | Expected |
|---|---|---|
| 1.1 | `php artisan test` | **284 passed**, same count as 3.0.0 |
| 1.2 | `vendor/bin/phpunit --display-deprecations --display-phpunit-deprecations` | **Zero deprecations** |

1.2 matters because PHPUnit 11 deprecates `@dataProvider` doc-comments and PHPUnit 12
removes them. `ModelFillableTest` and `FilamentResourceTest` now use `#[DataProvider]`
attributes, so the next PHPUnit major will not break the suite.

---

## 2. Regression pass

Already checked against the live database, but worth confirming on your own environment.

| # | Endpoint | Expected |
|---|---|---|
| 2.1 | `GET /api/today-prayer` | Waqts plus derived `iftar` |
| 2.2 | `GET /api/ramazan-calendar` | 30 entries, current year |
| 2.3 | `GET /api/sura`, `GET /api/ayat/1` | Unchanged shape |
| 2.4 | `GET /api/doa-category`, `GET /api/asmaul-husna` | Unchanged shape |
| 2.5 | Register → login → `/api/auth/me` → logout | Sanctum tokens issue and revoke |
| 2.6 | `/admin` as `admin@admin.com` | Dashboard loads |
| 2.7 | `/admin` as an app user | 403, as established in 3.0.0 |
| 2.8 | Open and save a record in any resource | Saves and persists |

---

## 3. Deployment

Nothing new beyond the 3.0.0 checklist. The PHP floor is still `^8.2`, so the container
image and the `config.platform` pin both still hold, and `composer install` must still run
its scripts.

1. `composer install` (scripts enabled).
2. `php artisan migrate` — no new migrations in this release.
3. `php artisan optimize:clear`.
4. `composer audit` on the deployed tree; expect it clean.

---

## Still outstanding

Unchanged from 3.0.0, and none of it is blocked by the framework any more:

- **`.env` is tracked in this repository**, including the app key and database credentials.
- **The Google Maps key** still needs rotating.
- **PHP 8.3/8.4 for the container.** Laravel 12 supports up to 8.4; CI tests 8.2 and 8.3
  and the image is on 8.2. Moving needs a real image build to confirm swoole compiles.
- **Device QA** — notifications, Qibla, audio and account sync.
- **Bangla *uccharon* source** for `bangla_text`, still deliberately empty rather than
  duplicating the meaning.
