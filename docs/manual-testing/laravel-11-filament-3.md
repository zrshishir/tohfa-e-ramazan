# Manual Testing — Laravel 11 + Filament 3

**Branch:** `feature/zrshishir/laravel-11-filament-3`
**Target:** `development`
**Version:** 3.0.0

> ⚠️ **Two changes here can lock you out. Read this section before deploying.**
>
> 1. **`/admin` now requires `role = 'admin'`.** A migration grants it to `admin@admin.com`.
>    If you sign in as anyone else, you get a 403 — that is correct behaviour, not a bug.
> 2. **`composer install` must run its scripts.** The build now depends on
>    `post-autoload-dump` to publish Filament's assets. A deploy that skips scripts ships
>    an admin with no CSS.

---

## What changed and why it needed doing

Laravel 10 left security support in February 2025. Three advisories have no fix in the
10.x line, so upgrading is the only way to a clean `composer audit`. Filament 2 does not
support Laravel 11, so the two moves are one change.

| Package | Before | After |
|---|---|---|
| `laravel/framework` | 10.50.2 | 11.55.0 |
| `filament/filament` | v2.17.59 | 3.3.54 |
| `livewire/livewire` | v2.12.8 | 3.8.4 |
| `laravel/octane` | v1.5.6 | 2.19.0 |
| `laravel/sanctum` | v3.3.3 | 4.3.3 |
| `phpunit/phpunit` | 9.x | 10.5.64 |

The Octane hop came along for free — Octane 2 was pulled in by the Laravel 11 resolve,
so the separately-planned Octane PR is absorbed into this one.

---

## Automated coverage

`php artisan test` → **284 passed**, up from 202. The 82 new tests all cover the admin,
which previously had none.

The edit-page test is the one worth understanding. For each of the 18 resources it builds
a record from the table definition, opens it in the edit form, saves **without changing
anything**, then compares every column against what was there before. A form that renders
correctly but silently drops a field fails this test — which is the failure mode this
codebase has actually shipped, five times, via phantom `$fillable` entries.

Three of the four failures it found on first run were artifacts of my fixture generator.
The fourth was real, and is fixed here — see *Admin user form* below.

---

## 1. Panel access — do this first

| # | Step | Expected |
|---|---|---|
| 1.1 | Visit `/admin` signed out | Redirects to `/admin/login` |
| 1.2 | Sign in as `admin@admin.com` | Dashboard loads, navigation groups render |
| 1.3 | Sign in as any app-registered user (e.g. `t1786421255429@example.com`) | **403 Forbidden** |
| 1.4 | Confirm that same user can still use the app | Login, tasbih sync and bookmarks all work |

**1.3 is the important one.** Before this change, Filament 2 applied no access check at
all — anyone who registered through the mobile app could sign in at `/admin` with their
app credentials and edit hadith, duas, masa-el and prayer times.

To grant another administrator:

```sql
UPDATE users SET role = 'admin' WHERE email = 'someone@example.com';
```

---

## 2. Admin user form

Previously this form could not be saved **at all**: `password` was `->required()`, but the
column is `$hidden` on the model so Filament could never fill it, and validation failed on
a field there was no way to satisfy. Separately, the `User` model has no `hashed` cast, so
whatever was typed went into the column as plaintext and could never match at login.

| # | Step | Expected |
|---|---|---|
| 2.1 | Users → Edit any user → change **Name** only → Save | Saves. Password unchanged — that user can still log in |
| 2.2 | Edit a user → type a new password → Save | Saves. Old password rejected at login, new one accepted |
| 2.3 | Check the stored value: `SELECT password FROM users WHERE id = ...` | Starts with `$2y$` — **never** the plaintext you typed |
| 2.4 | Create a new user, leaving password blank | Blocked: password is required on create |
| 2.5 | Edit a user who has no phone number | Saves. Phone is no longer required |

---

## 3. All 18 resources

Automated tests mount every list, create and edit page and prove the round trip, so this
pass is about how it looks and behaves rather than whether it errors.

For each resource: **open the list, open a record, change one field, save, reload, confirm
the change stuck.**

| Group | Resources |
|---|---|
| Quran | Sura, Ayat |
| Dua | Doa, Doa Category |
| Masa-el | Masala, Masala Category |
| Calendar | Permanent Calendar, Mazhab, Mazhab Wise Schedule Setting |
| Geography | Country, Division, District, District Wise Schedule Setting |
| Content | Blog, Category, Asmaul Husna |
| Users | User, Tasbih |

Worth extra attention:

- **Tasbih** — the only `Repeater` in the admin, and v3 changed repeater state handling.
  Add a dhikr, reorder the list, delete one, save, and confirm the JSON column matches.
- **Ayat** — 6,236 rows. Check pagination and search are responsive.
- **Permanent Calendar** — 365 rows. Note the form only exposes `month_id` and `day`; the
  prayer-time JSON columns are not editable here. That is pre-existing, not a regression,
  but worth knowing if you expected to edit times from the admin.

---

## 4. API regression pass

The upgrade touched Sanctum. These should be unchanged.

| # | Endpoint | Expected |
|---|---|---|
| 4.1 | `POST /api/auth/register` | 201, token returned |
| 4.2 | `POST /api/auth/login` | 200, token returned |
| 4.3 | `GET /api/auth/me` with token | 200, the user |
| 4.4 | `POST /api/auth/logout` | 204, token no longer works |
| 4.5 | `DELETE /api/auth/account` | Account and its tasbih row both gone |
| 4.6 | `GET /api/today-prayer` | Waqts plus derived `iftar` |
| 4.7 | `GET /api/ramazan-calendar` | 30 entries, correct year |
| 4.8 | `GET /api/sura`, `GET /api/ayat/1` | Unchanged shape |

---

## 5. Octane — test on a real container, not `artisan serve`

`serve` boots a fresh application per request, so it cannot surface the bugs Octane
introduces. This section needs an actual `octane:start`.

| # | Step | Expected |
|---|---|---|
| 5.1 | Build the image and start it | Boots on swoole |
| 5.2 | Hit `/api/today-prayer` repeatedly in one worker | Identical results; no drift between calls |
| 5.3 | Request two different districts in succession | Second response reflects the second district, not the first |
| 5.4 | Log in to `/admin`, browse several resources | Session holds; no cross-request bleed |

5.3 targets `PermanentCalendarController::$districtSetting`, a per-request property on a
controller. Controllers resolve fresh per request even under Octane, so it should be fine —
it is checked because "should be fine" is not the same as verified.

### Two deployment changes to be aware of

- **`OCTANE_SERVER` now defaults to `swoole`.** It was `roadrunner`, while the Dockerfile
  installs only swoole — so a missing env var pointed Octane at a binary that is not in the
  image. If your environment sets `OCTANE_SERVER` explicitly, nothing changes.
- **The Dockerfile now runs `composer install --no-scripts`, then `composer dump-autoload`
  after copying the app.** Scripts cannot run before `artisan` exists. The later
  `dump-autoload` is what publishes Filament's assets and rebuilds the package manifest.

---

## 6. Deployment checklist

1. `php artisan migrate` — grants `role = 'admin'` to `admin@admin.com`.
2. Confirm `composer install` ran its scripts; `public/css/filament` and
   `public/js/filament` must exist. They are no longer committed.
3. Delete `bootstrap/cache/packages.php` and `bootstrap/cache/services.php` if the deploy
   does not rebuild them. The stale v2 manifest referenced `Akaunting\Money\Provider` and
   prevented the app from booting until removed by hand.
4. `php artisan optimize:clear`.
5. Sign in at `/admin` as `admin@admin.com` **before** announcing the deploy.

---

## Still outstanding

- **Three advisories remain ignored.** Fixed only in Laravel 12.60/12.61, so they clear in
  the next hop.
- **`.env` is tracked in this repository**, including the application key and database
  credentials. Untracking and rotating it is worth doing but is deployment-affecting, so it
  is deliberately not in this change.
- **The Google Maps key** still needs rotating — unrelated to this work, still open.
- **PHP 8.3 for the container.** CI tests 8.2 and 8.3; the image is on 8.2. Moving it needs
  a real image build to confirm the swoole pecl extension still compiles.
