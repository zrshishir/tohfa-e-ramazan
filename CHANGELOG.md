# Changelog

All notable changes to the Tohfa-e-Ramazan backend are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [3.2.1] - 2026-08-13

### Documentation

- **Pulling v3.2.0 deletes your local `.env`.** Untracking a previously-tracked file means
  git removes it from the working directory when the deletion arrives — `.gitignore` only
  protects files git has never tracked. The symptom is every route returning HTTP 500,
  because Laravel has no `APP_KEY`, which looks exactly like the upgrade having broken the
  application.

  `docs/env-and-secrets.md` now leads with backing up `.env` before pulling, and documents
  the recovery (`git show origin/main:.env > .env`, then `php artisan key:generate` so the
  machine gets a key that is not the published one).

  Found by hitting it: the API returned 500 across every endpoint after a routine pull,
  and the cause was the missing file rather than anything in the upgrade.

## [3.2.0] - 2026-08-13

### Security

- **`.env` is no longer tracked in git.** It had been committed since January 2024 in a
  **public** repository with 2 forks.

  Auditing every version of the file for non-empty, non-placeholder values, the exposure
  is narrow but includes the worst possible item:

  | Key | Exposed | Assessment |
  |---|---|---|
  | `APP_KEY` | all 4 commits, one value, **still in use** | Live secret |
  | `DB_PASSWORD` | 3 commits (2024) | `password` — the same value already public in `docker-compose.yml` |
  | `MAIL_USERNAME`, `MAIL_PASSWORD`, `REDIS_PASSWORD` | — | Literal `null`, Laravel's placeholder |
  | `AWS_*`, `PUSHER_*` | — | Empty throughout |

  > **`APP_KEY` must be rotated — this change does not do that for you.** A known
  > application key allows forging encrypted cookies and therefore sessions, forging
  > signed URLs, and is a documented route to remote code execution through Laravel's
  > decrypt-and-unserialize path.
  >
  > Rotation here is unusually cheap: nothing is encrypted at rest, and Sanctum tokens are
  > SHA-256 hashed rather than encrypted, so mobile users are unaffected. Only admin
  > sessions drop. See `docs/env-and-secrets.md`.

  History is deliberately **not** rewritten. The repository is public and forked, so the
  objects survive a rewrite; rotation is the only remedy that works, and a rewrite would
  break every existing clone for no benefit.

- **`.dockerignore` added**, excluding `.env` so the image cannot carry one. Previously
  there was no `.dockerignore` at all, so `COPY ./ ./` baked the committed `.env` —
  including `APP_ENV=local` and `APP_DEBUG=true` — into the production image. Laravel's
  dotenv is immutable so real environment variables still won, but any key the environment
  did not set fell through to those values, which in production means debug stack traces.

### Fixed

- **The image was shipping the developer's `vendor/` directory.** `composer install` runs
  early in the Dockerfile and writes `vendor/` inside the container, but the later
  `COPY ./ ./` overwrote it with whatever was on the host — resolved against macOS and the
  developer's PHP version. `.dockerignore` now excludes `vendor` and `node_modules`.

### Changed

- `.env.example` completed against every key the application actually reads: adds
  `OCTANE_SERVER`, `OCTANE_HTTPS` and `SANCTUM_STATEFUL_DOMAINS`, and documents the three
  values that must differ in production (`APP_ENV`, `APP_DEBUG`, `APP_URL`).

### Notes

- Local development is unaffected. `.env` stays on disk, and `docker-compose.yml`
  bind-mounts the working directory, so the container still reads it.
- CI was never affected: `test.yml` already ran `cp .env.example .env` followed by
  `php artisan key:generate`.
- `GOOGLE_MAPS_KEY` is read by `config/services.php` and used by `GET /api/geocode`, but
  was **absent from `.env`** — geocoding has been running without a key.
- `docs/env-and-secrets.md` records the audit, the rotation procedure with its verified
  blast radius, and three commands to determine how production supplies its configuration
  (which could not be established from the repository).

## [3.1.0] - 2026-08-13

The second half of the framework upgrade. 3.0.0 moved to Laravel 11, which was necessary
for Filament 3 but did not clear the outstanding advisories — all three are fixed only in
the 12.x line. **`composer audit` is now clean and the ignore list is gone.**

### Security

- **All three ignored advisories resolved**, and `config.policy.advisories.ignore-id`
  removed from `composer.json` entirely:

  | Advisory | Severity | Title | Fixed in |
  |---|---|---|---|
  | `PKSA-3r5d-mb8f-1qw9` | high | CRLF injection in the default email rule | 12.60.0 |
  | `PKSA-mdq4-51ck-6kdq` | — (CVE-2026-48019) | CRLF injection in the default email rule | 12.60.0 |
  | `PKSA-m5cs-t1y6-qpcs` | medium | Temporary signed URL path confusion | 12.61.1 |

  A fourth entry, `PKSA-zwc5-qtrz-zm1n`, was already stale and is dropped with the rest.

  `composer audit` reports **no advisories**. The framework floor is pinned at
  `^12.61.1` rather than `^12.0` so the fixes cannot be resolved away.

### Changed

- **Laravel 11.55.0 → 12.66.0.**
- PHPUnit 10.5 → 11.5.56 and `nunomaduro/collision` 8.5 → 8.9.5. Not optional: Collision
  8.6+ is the first release compatible with Laravel 12, and it requires PHPUnit 11.
- `phpunit.xml` now references the 11.5 schema.

- **Test metadata moved from doc-comments to attributes.** `@dataProvider` is deprecated in
  PHPUnit 11 and removed in 12; `ModelFillableTest` and `FilamentResourceTest` now use
  `#[DataProvider]`. The suite runs with **zero deprecation notices**.

### Notes

- All 284 tests pass unchanged. No application code needed modifying for Laravel 12 — the
  work was confined to the test tooling.
- Verified against the live database as well as the fixture suite: all 18 admin resources
  render, and `today-prayer`, `ramazan-calendar`, `sura`, `ayat`, `doa-category` and
  `asmaul-husna` all serve real data.
- No migration uses `->change()`, so the Laravel 11 removal of doctrine/dbal-backed column
  changes has no effect here. `doctrine/dbal` remains in the tree only because
  `filament/support` requires it.
- The PHP floor is unchanged at `^8.2`, so the container image and the `config.platform`
  pin introduced in 3.0.0 both still hold.

## [3.0.0] - 2026-08-12

Laravel 10 → 11 and Filament 2 → 3. Laravel 10 left security support in February 2025,
and three advisories sit in `policy.advisories.ignore-id` with no fixed release in the
10.x line. Filament 2 does not support Laravel 11, so both had to move together.

### Changed

- **Laravel 10.50.2 → 11.55.0**, **Filament v2.17.59 → 3.3.54**, Livewire 2.12.8 → 3.8.4,
  Octane 1.5.6 → 2.19.0, Sanctum 3.3.3 → 4.3.3, PHPUnit 9 → 10.5.64, PHP floor `^8.1` → `^8.2`.

  The Laravel 10 application skeleton (`app/Http/Kernel.php`, `app/Console/Kernel.php`,
  `app/Exceptions/Handler.php`) still works under 11 and is deliberately left in place.
  Adopting the slim `bootstrap/app.php` layout is a separate change, not bundled here.

- **All 18 Filament resources** converted to `Filament\Forms\Form` / `Filament\Tables\Table`,
  with `Filament\Pages\Actions` renamed to `Filament\Actions` across 65 page classes.
  `config/filament.php` is replaced by `App\Providers\Filament\AdminPanelProvider`.

- `config/octane.php` brought in line with the Octane 2 defaults: added `state_file`, and
  registered `CloseMonologHandlers` on `WorkerStopping`.

- `OCTANE_SERVER` now defaults to `swoole` rather than `roadrunner`. The Dockerfile
  pecl-installs swoole and nothing else, so the previous default meant a missing env var
  left `octane:start` reaching for a RoadRunner binary that is not in the image.

- Docker base image unpinned from `php:8.2.0` to `php:8.2`. The 8.2.0 patch shipped in
  December 2022.

- **`config.platform.php` pinned to `8.2`.** Resolving the lock file on a PHP 8.3 machine
  pulled in `openspout/openspout` 4.32 and `laravel/pint` 1.30.5, both of which require
  PHP 8.3 — producing a `composer.lock` that `composer.json` claimed to support on 8.2 but
  that could not actually be installed there. The production image is PHP 8.2, so the
  Docker build would have failed on it. Composer now always resolves for the lowest
  supported version regardless of who runs the update.

### Added

- **`FlushOnce` listener on `OperationTerminated`.** Laravel 11 introduces the `once()`
  helper, which memoizes per object instance. Octane workers outlive the request, so
  without this flush a value memoized while serving one user is handed to the next.

- **Panel access control (`User::canAccessPanel`).** Filament 2 had no access check, so
  **every registered account — including every mobile app user — could sign in at `/admin`**
  and edit hadith, duas, masa-el and prayer times. Access now requires `role = 'admin'`.

  Filament 3 denies access outright when the user model does not implement `FilamentUser`
  and the environment is not `local`, so without this the panel would have returned 403 to
  everyone on deploy.

  A migration grants the role to `admin@admin.com`. **No other account can reach the admin.**

- **82 tests covering the admin panel**, where there were none:
  - every resource's list, create and edit page is mounted for real;
  - each resource round-trips a generated record through its edit form and asserts the
    stored row is byte-identical afterwards, which is what catches a form that renders but
    discards input;
  - panel access is asserted over HTTP, because mounting a Livewire component directly
    skips the middleware that enforces it.

- `composer.json` gained the standard Laravel script hooks. The file previously had an
  empty `scripts` block, so `package:discover` never ran after an install — which is why
  `bootstrap/cache/packages.php` still referenced `Akaunting\Money\Provider`, a package
  removed with Filament 2, and the application would not boot until the cache was deleted
  by hand.

### Fixed

- **The admin user form could not be saved at all, and stored passwords in plaintext.**
  `password` was `->required()` with no dehydration. The column is `$hidden` on the model,
  so Filament could never fill the field and every edit failed validation on a field the
  admin had no way to satisfy. Independently, the `User` model has no `hashed` cast, so
  anything typed there was written to the column verbatim — an admin-set password could
  never match at login.

  The field is now required only on create, hashed on the way in, and left untouched when
  submitted blank.

- `phone` is no longer required on the user form. API registration does not collect a phone
  number, so requiring one made every API-registered account unsaveable from the admin.

### Security

- Restricting `/admin` to `role = 'admin'` closes an unauthenticated-by-role hole: any user
  who registered through the mobile app could previously log into the admin with their app
  credentials.

### Notes

- Three advisories remain ignored in `composer.json`. They are fixed only in Laravel 12.60
  and 12.61, so they clear in the next hop, not this one.
- `.env` is tracked in this repository and contains the application key and database
  credentials. Untracking and rotating it is worth doing, but it is deployment-affecting
  and deliberately left out of this change.

## [2.8.1] - 2026-08-12

### Fixed

- **`AyatTableSeeder` wrote the Bangla translation into `bangla_text`**, the column
  reserved for the Bangla *uccharon*. It fed the same `bn.bengali` edition into both
  `bangla_text` and `meaning`, so the two were byte-identical in all 6,236 rows and the
  pronunciation was lost.

  `DoaSeeder` shows the intended convention, which duas follow correctly:

  | Column | Holds |
  |---|---|
  | `arabic_text` | Arabic |
  | `english_text` | Latin pronunciation |
  | `bangla_text` | Bangla uccharon |
  | `meaning` | Bangla meaning |

  `bangla_text` is now written empty rather than duplicating the meaning. **It still
  needs a source** — see below.

### Added

- **A guard against alquran.cloud's silent fallback.** Requesting an edition that does
  not exist — `bn.transliteration`, say — returns HTTP 200 with the **Arabic** text
  rather than an error, so a typo would seed Arabic into a translation column and look
  fine until somebody read it. `fetchEdition()` now compares against the Arabic edition
  and skips a response that matches it.
- 3 feature tests (`AyatSeederGuardTest`).

### Still needed: a Bangla uccharon source

alquran.cloud publishes only three transliteration editions — Turkish, English and
Russian. There is no Bengali one, so the pronunciation cannot be restored from the
current API. Once a source is available it is a one-line change in the seeder.


## [2.8.0] - 2026-08-11

### Added

- **Server-side bookmarks**, so a reading list survives a reinstall and follows a user
  between devices. Previously bookmarks lived only in the app's `localStorage`.

  ```
  GET    /api/bookmarks
  POST   /api/bookmarks              idempotent per ayat
  DELETE /api/bookmarks/{ayatId}     keyed by ayat, so the reader can toggle
  POST   /api/bookmarks/sync         merges the device into the account
  POST   /api/tasbih/sync            merges device counters into the account
  ```

- **Merge, not replace, on sign-in.** Someone who read on a phone before creating an
  account should not lose those bookmarks, and signing in on a second device should not
  wipe what is already on the account. Both sync endpoints merge and return the combined
  set so the device can adopt it wholesale.

- **Tasbih counters merge by taking the higher value.** A dhikr count only goes up, so
  whichever side synced last should not decide the result.

### Fixed

- `GET /api/tasbih` and `PUT /api/tasbih/{userId}` took the user from the URL or query
  string with no authentication, so **any caller could read or overwrite anyone's
  counters**. When a token is present it now wins over both. Guests keep the previous
  behaviour, since the app must work without an account.

### Note

Guests are unaffected throughout. Bookmarks and counters still live on the device without
an account; these endpoints exist only so a signed-in user's progress follows them.


## [2.7.0] - 2026-08-11

### Added

- **Optional accounts**, so tasbih counts and bookmarks can follow a user between
  devices. Everything else works exactly as before without one — a test asserts the rest
  of the API stays open to guests.

  ```
  POST   /api/auth/register    throttled 5/min
  POST   /api/auth/login       throttled 5/min
  GET    /api/auth/me
  POST   /api/auth/logout
  DELETE /api/auth/account
  ```

- **In-app account deletion**, which Google Play and the App Store both require of any
  app offering account creation. It re-checks the password, revokes all tokens, and
  **force-deletes** — `User` soft-deletes, and a soft-deleted row still holds the name
  and email and still blocks that address from registering again.

- 15 feature tests (`AuthApiTest`).

### Security notes

- Passwords go through Laravel's `Password::min(8)->uncompromised()` rule — a length
  floor plus a breach check — and require confirmation.
- Login answers with the **same message** for an unknown email as for a wrong password,
  so the endpoint cannot be used to discover which addresses are registered. A test
  compares the two responses.
- Logout revokes **only the calling token**, so signing out on a phone leaves a tablet
  signed in.
- The password hash is never serialised into a response; a test asserts no `$2y$` string
  appears in the body.

### Note

This replaces PR #6, open since January 2024. That branch was 45 commits behind,
conflicted with the updated `composer.lock`, accepted any non-empty string as a
password, had no rate limiting, and duplicated `HelperTrait` in a parallel
`ResponseHelper`.


## [2.6.0] - 2026-08-11

### Security

- **44 advisories across 15 packages reduced to 3 across 1.** Dependencies were pinned at
  mid-2023 releases; `composer update` within the existing constraints brought 104
  packages forward. No constraint in `composer.json` was changed, so no major version
  jumps.

  | Package | From | To |
  |---|---|---|
  | `laravel/framework` | v10.13.2 | 10.50.2 |
  | `guzzlehttp/guzzle` | 7.7.0 | 7.15.3 |
  | `guzzlehttp/psr7` | 2.5.0 | 2.13.0 |
  | `league/commonmark` | 2.4.0 | 2.9.2 |
  | `symfony/http-foundation` | v6.3.0 | v6.4.43 |
  | `filament/filament` | v2.17.45 | v2.17.59 |
  | `phpunit/phpunit` | 10.2.1 | 10.5.64 |

  Closed among others **CVE-2024-52301** (Laravel environment manipulation via query
  string, high) and **CVE-2025-27515** (file validation bypass).

### ⚠️ Three advisories remain, and cannot be fixed on Laravel 10

`laravel/framework` carries three advisories with **no patched release in the 10.x line**:

| Advisory | Fixed in |
|---|---|
| `PKSA-mdq4-51ck-6kdq` — CRLF injection in the default email rule | 11.x+ only |
| `PKSA-3r5d-mb8f-1qw9` — CRLF injection in the default email rule | 12.60.0 |
| `PKSA-m5cs-t1y6-qpcs` — temporary signed URL path confusion | 12.61.1 |

They are listed in `policy.advisories.ignore-id` so Composer can resolve at all —
without it **no version of Laravel 10 installs**, since Composer 2.10 blocks packages
with known advisories.

**The real fix is upgrading Laravel.** 10.x reached end of security support in February
2025. That also requires Filament 2 → 3, since Filament 2 does not support Laravel 11,
so it is a migration rather than a dependency bump — deliberately not attempted here.

Practical exposure is limited: the two CRLF issues are in the `email` validation rule,
which this codebase uses only in the unrouted registration path, and signed URLs are
not used.


## [2.5.0] - 2026-08-11

### Security

- **The Google Maps key is no longer handled by the client.** The app called the
  Geocoding API directly with the key inlined in its bundle, where anyone could extract
  it. `GET /api/geocode` now proxies the call and the key lives only on the server, read
  from `GOOGLE_MAPS_KEY`.

  ⚠️ **The previously exposed key still needs rotating** — it is in git history and in
  every build already shipped. This change stops the leak; it does not undo it.

### Added

- `GET /api/geocode?lat=&lng=` — reverse geocoding, throttled to 30/minute since each
  cache miss is a billed Google call, and cached for 30 days per coordinate rounded to
  ~110m.
- The response also **matches the place against the districts table** and returns a
  `district`, so the app can suggest a district rather than asking the user to find it
  in a list. Google's current spellings are mapped onto the older ones the table uses
  (Chattogram → Chittagong, Cumilla → Comilla, Jashore → Jessore, and others).
- 9 feature tests (`GeocodeApiTest`).

### Changed

- A missing server key returns `503` and an upstream failure `502`, rather than a `500`.
- Coordinates are validated; an unmatched place returns a null district rather than an
  error, since the city and division are still useful.


## [2.4.1] - 2026-08-11

### Fixed

- **`composer.lock` could not install on PHP 8.3.** Three packages were pinned to
  versions declaring `php <8.3`, so `composer install` failed outright — while the
  development machine runs 8.3.27, meaning the local `vendor/` was out of step with the
  committed lock and with whatever CI or a server would build.

  A targeted update of just the blocking packages, rather than a full `composer update`,
  to keep the change small:

  | Package | From | To |
  |---|---|---|
  | `laminas/laminas-diactoros` | 2.25.2 | 2.26.0 |
  | `nette/schema` | v1.2.3 | v1.3.5 |
  | `nette/utils` | v4.0.0 | v4.1.5 |
  | `psr/http-factory` | 1.0.2 | 1.1.0 |

  Laravel, Filament and everything else are untouched.

### Changed

- CI tests against **PHP 8.2 and 8.3** again. The 8.3 leg was the check that found this,
  and had been narrowed to 8.2 as a stopgap.

### Note

`composer audit` reports 44 advisories across 15 packages, and two dependencies
(`league/uri-parser`, `tgalopin/html-sanitizer`) are abandoned. Out of scope here —
worth its own pass.


## [2.4.0] - 2026-08-11

### ⚠️ Breaking

- `masalas.title` and `masalas.description` are renamed to **`question`** and
  **`answer`**, and `GET /api/masala` is now paginated. Requires the paired frontend
  release.

### Added

- **Masa-el categories.** A new `masala_categories` table seeded with the standard
  chapters of fiqh — Purification, Prayer, Fasting, Zakat, Hajj, Funeral rites,
  Transactions, Family, Miscellaneous — each with Bangla and Arabic names.
- `GET /api/masala-categories` — categories with a published count, so the list screen
  needs one request.
- `GET /api/masala` now supports `category_id`, `q` (search across question and answer),
  `page` and `per_page`.
- **Filament admin for Masa-el, which had none at all** — content could previously only
  be added by editing a seeder. Both `MasalaResource` and `MasalaCategoryResource`, with
  a category filter and slug auto-fill.
- 14 feature tests (`MasalaApiTest`).

### Changed

- `masala_category_id` is nullable, so an uncategorised entry still lists rather than
  disappearing or erroring.
- Entries are ordered by `sort_order` then id, editable in admin.

### Note on content

Only the category headings are seeded — those are structural. The two existing example
masalas are preserved and moved under Fasting. **Actual rulings are not seeded**: fiqh
content should come from a source you trust rather than being generated, and the admin
now exists to enter it.


## [2.3.0] - 2026-08-11

### 🔴 Fixed — every prayer time was 15 minutes late

`permanent_calendars` already holds correct published times for Dhaka, but each mazhab
carried a flat offset applied to **every** waqt (Hanafi +15, Shafi'i +10, Maliki +5,
Hanbali +7). Since Hanafi is the default, every displayed time ran 15 minutes late:

| | Published, 11 Aug 2026 | Stored | Displayed before this fix |
|---|---|---|---|
| Fajr | 4:11 AM | 04:10 | 04:25 |
| Maghrib | 6:35 PM | 06:35 | 06:50 |
| Isha | 7:56 PM | 07:55 | 08:10 |

**Sehri end and iftar were both late** — the direction that invalidates a fast. Users
would have kept eating 15 minutes past the true end of sehri, and broken their fast 15
minutes after Maghrib.

All offsets are reset to zero, so the app now shows the stored published times unmodified.
Verified against published Dhaka times: Fajr 04:10 vs 4:11, Maghrib 06:35 vs 6:35,
Isha 07:55 vs 7:56.

### Changed

- A mazhab can now only affect **Zuhr, Asr and Isha**. Fajr, sunrise, Maghrib — and
  therefore sehri and iftar — are astronomical and identical across all four schools;
  they have been removed from the offset map so this cannot recur. District offsets still
  apply to sehri and iftar, being geographic rather than juristic.
- `deriveIftar()` no longer adds a mazhab offset. Iftar is Maghrib.

### Research behind the change

- **Asr** is the substantive difference: Hanafi holds Asr begins when an object's shadow
  is **twice** its length plus the noon shadow; Maliki, Shafi'i and Hanbali say **once**.
  Worth 30–90 minutes depending on season and latitude, so it cannot be a fixed offset.
  Left at 0 pending a computed Asr or verified per-month values.
- **Zuhr** does not differ in when it starts. It ends when Asr begins, so the Hanafi Zuhr
  window is simply longer — that follows from the Asr rule.
- **Isha** is a genuine but smaller difference: Abu Hanifa held it begins when the *white*
  twilight goes; Abu Yusuf, Muhammad and the other three schools say the *red* twilight,
  around 10–15 minutes earlier. Most Hanafi timetables, Bangladesh's included, follow the
  red-twilight position, so 0 matches local practice.

### Added

- 4 feature tests (`MazhabOffsetTest`) guarding that no mazhab offset can move sehri,
  fajr, magrib or iftar, while Zuhr, Asr and Isha still respond.


## [2.2.0] - 2026-08-11

### Added

- **District-wise sehri and iftar times.** All four calendar endpoints now accept
  `district_id` and shift sehri and iftar by that district's Islamic Foundation offset
  relative to Dhaka. No other waqt is district-adjusted.
- `GET /api/divisions` (with districts embedded) and `GET /api/districts` (with division
  and offsets), backing the app's location picker. `DivisionController` and
  `CountryController` existed but had never been routed.
- Offsets for all 64 districts, seeded and editable in the Filament admin.
- 10 feature tests (`DistrictOffsetTest`).

### Fixed

- **The districts table had its divisions wrong.** Division 5, labelled "Barisal", held
  every *Rangpur* district; division 7 "Rangpur" was empty. Anyone picking Barisal would
  have been shown Dinajpur, Rangpur, Thakurgaon and the rest.
- **Ten districts were missing** (54 of 64): all six of Barisal's own districts, and the
  entire Mymensingh division, which has existed since 2015 and was absent from the
  divisions table altogether.

### Changed

- `district_wise_schedule_settings` replaces `time_addition_subtraction` + `am_pm` with
  `sehri_offset` and `iftar_offset`. The Islamic Foundation publishes **separate** values
  for the two — Cox's Bazar is −1 sehri but −10 iftar — which one column could not
  represent. The table was empty, so nothing was migrated.

### ⚠️ Data provenance

The offsets come from a secondary source citing the Islamic Foundation
([iqbir.com](https://iqbir.com/article/namaz-roza-time-difference-dhaka/)), **not** from
islamicfoundation.gov.bd directly. **Spot-check them before release.** They are editable
per district in the Filament admin.

Note also that for Ramadan 2026 the Islamic Foundation moved to publishing 64 separate
district schedules instead of offsets. The offset model is kept here because this app
serves a year-round permanent calendar, which IF does not publish per district.

### Not changed

Mazhab offsets are untouched. The current flat per-mazhab shift applied to every prayer
does not reflect how the madhhabs actually differ — the substantive difference is the Asr
shadow ratio (Hanafi 2×, the other three 1×), which is seasonal and far larger than the
stored values, and Isha follows the calculation authority rather than the madhhab. This
needs verified data before a picker is built on it.

## [2.1.0] - 2026-08-10

### Changed

- `GET /api/ayat/{sura_id}` is now **paginated** (10 per page by default, `per_page` up
  to 100, query string preserved in the page links). It previously returned every ayat of
  a sura in one response — 286 rows and ~29× the payload for Al-Baqarah. The frontend
  already ships a paginated reader with a fallback for an unpaginated response, so this
  is not breaking.
- Ayats are explicitly ordered by `ayat_no`.

### Fixed

- Four more models could not be mass-assigned at all, because columns that are `NOT NULL`
  with no default were missing from `$fillable` — `Model::create()` threw an integrity
  constraint violation every time:

  | Model | Missing |
  |---|---|
  | `Sura` | `bangla_text` |
  | `Ayat` | `ayat_no`, `notes` |
  | `DoaCategory` | `bangla_text`, `arabic_text` |
  | `Mazhab` | `user_id`, `bangla_text`, `arabic_text` |

  All four went unnoticed because their seeders write through `DB::table()`, which
  bypasses `$fillable` entirely.

- `AyatController::index()` used `empty()` on an Eloquent collection, which is never
  truthy, so the `204` branch was unreachable.

### Added

- `ModelFillableTest::test_required_columns_are_fillable` — the inverse of the existing
  phantom-column guard. It reads column metadata and asserts that every `NOT NULL`
  column without a default is present in `$fillable`. This is what found the four models
  above.
- `AyatApiTest` — 6 tests covering pagination, ordering, the `per_page` cap, query-string
  preservation and the empty-sura `204`.

## [2.0.0] - 2026-08-10

### ⚠️ Breaking

- The `hadiths` table is restructured. It was a flat `title` / `description` /
  `reference` sheet holding two placeholder rows; it is now
  `hadith_books` → `hadith_chapters` → `hadiths`, with Arabic, Bangla and English text
  per hadith. `GET /api/hadith` is now **paginated** and returns entirely different
  fields. Requires the paired frontend release.
- `HadithSeeder` is removed. Hadith content now comes from `php artisan hadith:import`.

### Added

- **The full Siha Sittah — 34,455 hadiths across 333 chapters**, each with Arabic,
  Bangla and English text:

  | Book | Hadiths | Chapters | Bangla coverage |
  |---|---|---|---|
  | Sahih al-Bukhari | 7,563 | 97 | 99.3% |
  | Sahih Muslim | 7,563 | 56 | 97.3% |
  | Sunan Abu Dawud | 5,274 | 43 | 99.9% |
  | Jami at-Tirmidhi | 3,956 | 49 | 98.0% |
  | Sunan an-Nasa'i | 5,758 | 51 | 98.1% |
  | Sunan Ibn Majah | 4,341 | 37 | 99.8% |

- `php artisan hadith:import [--book=slug] [--fresh]` — fetches the three language
  editions per book from the fawazahmed0/hadith-api dataset on jsDelivr and joins them
  on `hadithnumber`. Idempotent via upsert; safe to re-run.
- New endpoints:
  - `GET /api/hadith-books` — the six collections.
  - `GET /api/hadith-books/{bookId}/chapters` — chapters for a book.
  - `GET /api/hadith` — paginated, filterable by `book_id` and `chapter_id`,
    searchable via `q` across the Bangla and English text.
  - `GET /api/hadith/{id}` — single hadith with its book and chapter embedded.
  - `GET /api/hadith-random` — backs a "hadith of the day" card.
- `HadithBook` and `HadithChapter` models; `Hadith::published()` and `Hadith::search()`
  scopes.
- 25 feature tests across `HadithApiTest` and `HadithImportTest`. The importer is
  exercised against faked HTTP responses, so the join logic is covered without a 34k-row
  download in CI.

### Notes on the data

- **Chapter names are English only.** The Bengali and Arabic editions ship English
  chapter names in their metadata. Rather than store English in `name_bn`, the importer
  checks the script and stores null, so the client falls back honestly instead of
  displaying English labelled as Bengali.
- Roughly 1–3% of entries per book have no Bengali translation — these are chapter
  headings and commentary the dataset leaves in Arabic. Same script check applies.
- Grades are only populated where the source provides them; Bukhari and Muslim carry
  none, being Sahih by definition.
- Hadith numbering has gaps where the source repeats a number: 7,589 raw entries in
  Bukhari collapse to 7,563 rows under the `(book, hadith_number)` unique constraint.

## [1.3.1] - 2026-08-10

### Fixed

- `Doa::$fillable` listed **`english_tex`** — a typo for the real `english_text` column.
  The Filament admin has an English-text field for duas, so every edit to a dua's English
  translation was silently discarded on save. Eloquent drops unknown keys during mass
  assignment without raising anything.
- `Category::$fillable` listed `is_active`, which is not a column on `categories`.
- `RamazanSchedule::$fillable` listed `sehri_time` (the column is `shehri_time`) and
  omitted `day`.

### Added

- `ModelFillableTest` — a guard covering **every** model in `app/Models`, asserting that
  each `$fillable` entry is a real column and that no model is left un-mass-assignable.
  42 tests. Verified to fail with a readable message when the `english_tex` typo is
  reintroduced.

  This bug class has now been found five times in this codebase: `Tasbih`,
  `PermanentCalendar`, `Doa`, `Category` and `RamazanSchedule`. The guard makes a sixth
  occurrence a test failure rather than silent data loss.

## [1.3.0] - 2026-08-10

### Fixed

- **`GET /api/ramazan-calendar` returned 500 on every request**, taking down the Ramadan
  calendar and single-date screens — the app's headline feature. The query selected an
  `iftar` column that does not exist:

  ```
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'iftar' in 'field list'
  select `id`, `day`, `month_id`, `sehri`, `magrib`, `iftar` from `permanent_calendars`
  ```

- The `PermanentCalendar` model's `$fillable` listed eight columns that do not exist
  (`sehri_time`, `fazr_time`, `sunrise_time`, `ishraq_time`, `johr_time`, `asr_time`,
  `magrib_and_iftar_time`, `esha_time`) and none of the real ones, so every
  `PermanentCalendar::create()` silently discarded its payload and inserted a row
  containing nothing but timestamps. The existing seeder writes via `DB::table()` and so
  was unaffected, which is why this went unnoticed.

### Added

- A derived `iftar` object on **all** calendar responses — `POST /permanent-calendar`,
  `GET /permanent-calendar/{month_id}`, `GET /today-prayer` and `GET /ramazan-calendar`.
  There is no `iftar` column because the fast is broken when Magrib begins; iftar is now
  computed from `magrib` with the mazhab's own `iftar_time` offset.
  This is additive — no existing key changed.
- 9 feature tests (`RamazanCalendarTest`) covering the 500 regression, the 30-day window,
  month rollover, iftar derivation on every endpoint, and the absence of a mazhab row.

### Changed

- `iftar` removed from `PRAYER_OFFSET_MAP`, where it could never have matched a column.
- Iftar is derived from the **raw** `magrib` value, captured before `magrib_time` is
  applied, so it carries only `iftar_time` and never inherits Magrib's offset on top.

## [1.2.0] - 2026-08-10

### ⚠️ Breaking

- `GET /api/tasbih` now returns `data.tasbih` as a **JSON array**, not a JSON-encoded
  string. Clients must stop calling `JSON.parse()` on it. Requires the paired frontend
  release.
- `PUT` and `DELETE /api/tasbih` now require a user segment: `/api/tasbih/{userId}`.
  The previous parameterless routes could never have worked (see below).

### Fixed

- `PUT /api/tasbih` and `DELETE /api/tasbih` were declared without a `{id}` segment while
  `TasbihController::update()` and `destroy()` both required one — **every call returned
  a 500** before reaching any logic.
- `update()` assigned the *array* returned by `$request->validate()` to `$validator` and
  then called `$validator->fails()` on it, a fatal error on every request that passed
  validation.
- The `Tasbih` model's `$fillable` listed six columns that do not exist
  (`subhanallah`, `alhamdulillah`, `allahuakbar`, `astagfirullah`, `laillahaillallah`,
  `subhanallahiwalhamdulillahi`) and omitted `tasbih`, the only column carrying data — so
  mass assignment silently discarded every write.
- `TasbihController` validated a *third* set of invented field names, matching neither the
  table nor `$fillable`, so no valid payload could ever be constructed.
- The Filament `TasbihResource` referenced the same phantom fields, rendering empty form
  inputs and empty admin table columns. It now edits the dhikr array through a repeater.
- `index()` returned `Tasbih::first()` — always user 1's row regardless of caller.
- `TasbihTableSeeder` used a raw `DB::table()->insert()`, duplicating the row on every
  `db:seed`. It now goes through the model and is idempotent.

### Added

- `tasbih` cast to `array` on the model.
- `GET /api/tasbih/{userId}` alongside `GET /api/tasbih?user_id=`.
- Real validation of the dhikr array: `text_en` required per entry, counters must be
  non-negative integers, `user_id` must exist.
- 14 feature tests covering all five tasbih endpoints (`TasbihApiTest`).
- Manual testing document at `docs/manual-testing/tasbih-api-contract.md`.

### Changed

- All tasbih responses use the shared `HelperTrait` envelope.
- Missing rows return `404` instead of `200` with `data: null`.
- Tasbih routes use the `[Controller::class, 'method']` array syntax and constrain
  `{userId}` to digits.

## [1.1.0] - 2026-08-05

### Added

- `GET /api/hadith/{id}` and `GET /api/masala/{id}` detail endpoints.
- Feature tests for the Hadith and Masala APIs and for the content seeders
  (`HadithApiTest`, `MasalaApiTest`, `ContentSeederTest`) — 15 new tests.
- Manual testing document at `docs/manual-testing/hadith-masala-integration.md`.
- This changelog.

### Fixed

- `HadithSeeder` and `MasalaSeeder` were never registered in `DatabaseSeeder`, so
  `php artisan db:seed` left both tables empty and the API returned nothing.
- `HadithController` and `MasalaController` returned a bespoke `{success, data}` envelope
  that did not match any other endpoint. Both now use `HelperTrait` and return the shared
  `{status, statusCode, message, data}` shape used by `AsmaulHusnaController` and
  `AyatController`.
- Empty results now return `204 No Content` instead of `200` with an empty array,
  matching `GET /api/ayat/{id}`.
- Requesting a non-existent or unpublished record now returns `404` instead of `200`.
- `status` is cast to a boolean on the `Hadith` and `Masala` models, so the API emits
  `true` / `false` rather than `1` / `0`.
- `HadithSeeder` and `MasalaSeeder` are now idempotent; re-running `db:seed` no longer
  duplicates rows.

### Changed

- `use` statements for `HadithController` and `MasalaController` moved from the middle of
  `routes/api.php` to the top of the file.
- Hadith and Masala listings are explicitly ordered by `id` ascending.
- Test suite runs against in-memory SQLite (`phpunit.xml`), so `php artisan test` no longer
  requires a running MySQL instance.

## [1.0.0] - 2026-05-27

### Added

- Permanent prayer calendar with mazhab-wise time offsets
  (`POST /api/permanent-calendar`, `GET /api/permanent-calendar/{month_id}`,
  `GET /api/today-prayer`, `GET /api/ramazan-calendar`).
- Quran: 114 suras and 6236 ayats seeded from the alquran.cloud API
  (`GET /api/sura`, `GET /api/ayat/{id}` with pagination).
- Duas by category (`GET /api/doa-category`, `GET /api/doa/{id}`).
- Asmaul Husna (`GET /api/asmaul-husna`).
- Tasbih (`GET /api/tasbih`).
- Mazhab list (`GET /api/mazhabs`).
- Hadith and Masala tables, models and list endpoints.
- Feedback form and privacy policy web routes.
- Filament admin panel with 14 resources.

[Unreleased]: https://github.com/zrshishir/tohfa-e-ramazan/compare/v1.1.0...HEAD
[1.1.0]: https://github.com/zrshishir/tohfa-e-ramazan/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/zrshishir/tohfa-e-ramazan/releases/tag/v1.0.0
