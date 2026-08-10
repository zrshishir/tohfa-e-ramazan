# Manual Testing — Tasbih API Contract

**Branch:** `fix/zrshishir/tasbih-api-contract`
**Target:** `development` (stacked on `fix/zrshishir/hadith-masala-integration`)
**Version:** 1.2.0

> ⚠️ **Breaking change.** `data.tasbih` is now a JSON array instead of a JSON-encoded
> string. The current app calls `JSON.parse()` on it and will break until the paired
> frontend PR ships. Do not deploy this alone.

---

## Background — four incompatible naming schemes

The tasbih slice had the same six dhikr named four different ways, none of which agreed:

| Layer | Names used |
|---|---|
| **Table** (`tasbih`) | `id`, `user_id`, `tasbih` (JSON array), timestamps |
| **Model** `$fillable` | `subhanallah`, `alhamdulillah`, `allahuakbar`, `astagfirullah`, `laillahaillallah`, `subhanallahiwalhamdulillahi` |
| **Controller** validation | `subhanallah`, ..., `la_ilaha_illallah`, `subhanallahi_wabi_hamdihi_wa_subhanallahil_azeem` |
| **Filament resource** | same as the controller |

Only the table was real. Because `tasbih` was missing from `$fillable`, every write
silently discarded its payload.

---

## Setup

```bash
git checkout fix/zrshishir/tasbih-api-contract
composer install
php artisan migrate
php artisan db:seed --class=TasbihTableSeeder
php artisan serve
```

---

## Automated checks

```bash
php artisan test
```

Expected: **31 passed** (14 new in `TasbihApiTest`).

---

## Test cases

### TC-01 — `PUT` no longer 500s

The headline regression: the route had no `{id}` segment while the controller method
required one, so **every** `PUT` was a 500.

```bash
curl -i -X PUT http://127.0.0.1:8000/api/tasbih/1 \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -d '{"tasbih":[{"text_en":"Subhanallah","reset_on":33,"count":5,"today_count":5,"monthly_count":5,"yearly_count":5,"total_count":5}]}'
```

| Check | Expected |
|---|---|
| Status | `200` (was `500`) |
| `data.tasbih[0].count` | `5` |
| Re-run `GET /api/tasbih` | Counter persisted |

> Restore the full six-dhikr list afterwards with
> `php artisan db:seed --class=TasbihTableSeeder`.

---

### TC-02 — `DELETE` no longer 500s

```bash
curl -i -X DELETE http://127.0.0.1:8000/api/tasbih/1 -H "Accept: application/json"
```

Expected `200`, then `GET /api/tasbih` returns `404`. Re-seed to restore.

---

### TC-03 — `tasbih` is a real array

```bash
curl -s http://127.0.0.1:8000/api/tasbih | jq '.data.tasbih | type'
```

Expected: `"array"` — previously `"string"`, forcing clients to `JSON.parse()`.

```bash
curl -s http://127.0.0.1:8000/api/tasbih | jq '.data.tasbih | length'
```

Expected: `6`.

---

### TC-04 — Writes actually persist

```bash
curl -s -X POST http://127.0.0.1:8000/api/tasbih \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -d '{"user_id":1,"tasbih":[{"text_en":"Subhanallah","total_count":42}]}' | jq '.data.tasbih'
```

| Check | Expected |
|---|---|
| Response contains `total_count: 42` | ✅ |
| `GET /api/tasbih` reflects it | ✅ |
| Row count stays at 1 for user 1 | ✅ (upsert, not duplicate) |

**Regression guarded:** `tasbih` was absent from `$fillable`, so this value used to be
dropped silently while the endpoint still answered `200`.

---

### TC-05 — Validation

| Request | Expected |
|---|---|
| `POST /api/tasbih` with `user_id` only | `422`, error on `tasbih` |
| `POST` with `user_id: 999` | `422`, error on `user_id` |
| `POST` with `tasbih: [{"count": 1}]` | `422`, error on `tasbih.0.text_en` |
| `PUT /api/tasbih/1` with `count: -5` | `422`, error on `tasbih.0.count` |

**Regression guarded:** `update()` previously called `->fails()` on the array returned by
`$request->validate()` — a fatal error on every request that passed validation.

---

### TC-06 — Missing rows return 404

| Request | Expected |
|---|---|
| `GET /api/tasbih?user_id=999` | `404` |
| `GET /api/tasbih/999` | `404` |
| `PUT /api/tasbih/999` | `404` |
| `DELETE /api/tasbih/999` | `404` |

Previously `200` with `data: null`.

---

### TC-07 — Seeder is idempotent

```bash
php artisan db:seed --class=TasbihTableSeeder
php artisan db:seed --class=TasbihTableSeeder
php artisan tinker --execute="echo App\Models\Tasbih::count();"
```

Expected: `1`, not `2`. The seeder previously used a raw `DB::table()->insert()`.

---

### TC-08 — Filament admin

| Step | Action | Expected |
|---|---|---|
| 1 | Log in to `/admin/tasbihs` | List loads |
| 2 | Check the table | Columns are User / **Dhikrs** (a count) / timestamps — no empty columns |
| 3 | Edit the row | A **Dhikr list** repeater with 6 collapsible entries, each with English / Bangla / Arabic / Resets at / counters |
| 4 | Change a counter and save | Value persists; `GET /api/tasbih` reflects it |
| 5 | Create a new row | User is a dropdown, not a free-text id |

**Regression guarded:** the form showed six empty text inputs for columns that do not
exist, and the table showed six permanently blank columns.

---

### TC-09 — Route table

```bash
php artisan route:list --path=tasbih
```

Expected:

```
GET|HEAD  api/tasbih            TasbihController@index
POST      api/tasbih            TasbihController@store
GET|HEAD  api/tasbih/{userId}   TasbihController@show
PUT       api/tasbih/{userId}   TasbihController@update
DELETE    api/tasbih/{userId}   TasbihController@destroy
```

`{userId}` is constrained to digits, so `/api/tasbih/abc` must not match.

---

### TC-10 — No regression on other endpoints

```bash
php artisan route:list --path=api
```

Smoke-test the calendar, quran, doa, asmaul-husna, hadith and masala endpoints — all `200`.

---

## Known limitation

`{userId}` is unauthenticated. Any caller can read or overwrite any user's counters, and
the app has no accounts yet, so every device shares user 1. This is deliberate for now —
per-user isolation arrives with the auth work. **Do not expose this API publicly without
that.**

---

## Sign-off

| Check | Result |
|---|---|
| TC-01 PUT works | ☐ |
| TC-02 DELETE works | ☐ |
| TC-03 array not string | ☐ |
| TC-04 writes persist | ☐ |
| TC-05 validation | ☐ |
| TC-06 404s | ☐ |
| TC-07 seeder idempotent | ☐ |
| TC-08 Filament admin | ☐ |
| TC-09 route table | ☐ |
| TC-10 no regressions | ☐ |
| `php artisan test` green | ☐ |
