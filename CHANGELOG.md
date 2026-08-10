# Changelog

All notable changes to the Tohfa-e-Ramazan backend are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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
