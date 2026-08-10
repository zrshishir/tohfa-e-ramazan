# Changelog

All notable changes to the Tohfa-e-Ramazan backend are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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
