# Manual Testing — Hadith & Masala API Integration

**Branch:** `fix/zrshishir/hadith-masala-integration`
**Target:** `development`
**Version:** 1.1.0

---

## Setup

```bash
git checkout fix/zrshishir/hadith-masala-integration
composer install
php artisan migrate
php artisan db:seed
php artisan serve
```

`php artisan db:seed` must now create Hadith and Masala rows. Before this branch it did not,
because the two seeders were never registered in `DatabaseSeeder`.

---

## Automated checks

```bash
php artisan test
```

Expected: **17 passed**. Tests now run against in-memory SQLite (enabled in `phpunit.xml`),
so no MySQL instance is required.

---

## Test cases

### TC-01 — Seeders populate the tables

| Step | Action | Expected |
|---|---|---|
| 1 | `php artisan migrate:fresh --seed` | Completes with no error |
| 2 | `php artisan tinker --execute="echo App\Models\Hadith::count();"` | `2` |
| 3 | `php artisan tinker --execute="echo App\Models\Masala::count();"` | `2` |

**Regression guarded:** previously both counts were `0`.

---

### TC-02 — Seeders are idempotent

| Step | Action | Expected |
|---|---|---|
| 1 | `php artisan db:seed --class=HadithSeeder` | Runs |
| 2 | Run it a second time | Runs |
| 3 | Check `Hadith::count()` | Still `2`, not `4` |

---

### TC-03 — `GET /api/hadith` returns the shared envelope

```bash
curl -s http://127.0.0.1:8000/api/hadith | jq
```

Expected shape:

```json
{
  "status": "Success",
  "statusCode": 200,
  "message": "Hadiths retrieved successfully",
  "data": [
    {
      "id": 1,
      "title": "Fasting is a Shield",
      "description": "Fasting is a shield or protection from the fire and from committing sins.",
      "reference": "Sahih Bukhari",
      "status": true,
      "created_at": "...",
      "updated_at": "..."
    }
  ]
}
```

Check specifically:

- Top-level key is `data` (not `success`) — matches `AsmaulHusnaController` and `AyatController`.
- `status` is JSON `true`, **not** `1`.
- Records are ordered by `id` ascending.

---

### TC-04 — `GET /api/masala` returns the shared envelope

```bash
curl -s http://127.0.0.1:8000/api/masala | jq
```

Same shape as TC-03 with `"message": "Masalas retrieved successfully"`.

---

### TC-05 — Inactive records are hidden

| Step | Action | Expected |
|---|---|---|
| 1 | In tinker: `App\Models\Hadith::first()->update(['status' => false]);` | — |
| 2 | `curl -s http://127.0.0.1:8000/api/hadith \| jq '.data \| length'` | `1` (was `2`) |
| 3 | Repeat for Masala | Same behaviour |
| 4 | Restore `status => true` | Count returns to `2` |

---

### TC-06 — Empty result returns 204

| Step | Action | Expected |
|---|---|---|
| 1 | In tinker: `App\Models\Hadith::query()->update(['status' => false]);` | — |
| 2 | `curl -i http://127.0.0.1:8000/api/hadith` | `HTTP/1.1 204 No Content`, **empty body** |

> **Note for the frontend PR:** a 204 carries no body, so `response.data` will be an empty
> string, not an object. The Vue screens must treat 204 as the no-data case and render
> `TheNoData` rather than trying to read `response.data.data`. This matches how
> `GET /api/ayat/{id}` already behaves.

---

### TC-07 — Detail endpoints

| Step | Action | Expected |
|---|---|---|
| 1 | `curl -s http://127.0.0.1:8000/api/hadith/1 \| jq` | 200, single object under `data` |
| 2 | `curl -i http://127.0.0.1:8000/api/hadith/9999` | `404` |
| 3 | Set hadith 1 to `status => false`, then `GET /api/hadith/1` | `404` (inactive is not exposed) |
| 4 | Repeat 1–3 for `/api/masala/{id}` | Same behaviour |

---

### TC-08 — No regression on existing endpoints

The `use` statements in `routes/api.php` were moved from the middle of the file to the top,
so confirm every route still resolves:

```bash
php artisan route:list --path=api
```

Then smoke-test the endpoints the app already depends on:

```bash
curl -s -o /dev/null -w "%{http_code} permanent-calendar\n" -X POST http://127.0.0.1:8000/api/permanent-calendar
curl -s -o /dev/null -w "%{http_code} today-prayer\n"       http://127.0.0.1:8000/api/today-prayer
curl -s -o /dev/null -w "%{http_code} ramazan-calendar\n"   http://127.0.0.1:8000/api/ramazan-calendar
curl -s -o /dev/null -w "%{http_code} sura\n"               http://127.0.0.1:8000/api/sura
curl -s -o /dev/null -w "%{http_code} ayat\n"               http://127.0.0.1:8000/api/ayat/1
curl -s -o /dev/null -w "%{http_code} doa-category\n"       http://127.0.0.1:8000/api/doa-category
curl -s -o /dev/null -w "%{http_code} asmaul-husna\n"       http://127.0.0.1:8000/api/asmaul-husna
curl -s -o /dev/null -w "%{http_code} mazhabs\n"            http://127.0.0.1:8000/api/mazhabs
curl -s -o /dev/null -w "%{http_code} tasbih\n"             http://127.0.0.1:8000/api/tasbih
```

All should return `200`.

---

## Out of scope for this PR

- Frontend screen fixes — follow-up PR on `tohfa-e-ramazan-front`, same branch name.
- Filament admin resources for Hadith/Masala — deferred to the schema redesign (F5 / F9).
- Pagination and search — deferred to F5 / F9.
- `TasbihController` `PUT`/`DELETE` returning 500, and `MazhabController::jsonResponse`
  returning a positional array instead of a keyed object — tracked separately as F2.

---

## Sign-off

| Check | Result |
|---|---|
| TC-01 seeders populate | ☐ |
| TC-02 idempotent | ☐ |
| TC-03 hadith envelope | ☐ |
| TC-04 masala envelope | ☐ |
| TC-05 inactive hidden | ☐ |
| TC-06 empty → 204 | ☐ |
| TC-07 detail endpoints | ☐ |
| TC-08 no regressions | ☐ |
| `php artisan test` green | ☐ |
