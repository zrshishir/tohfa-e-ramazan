# Manual Testing — Ramazan Calendar & Derived Iftar

**Branch:** `fix/zrshishir/ramazan-calendar-iftar`
**Target:** `development`
**Version:** 1.3.0
**Frontend counterpart:** follows separately

---

## The bug

`GET /api/ramazan-calendar` returned **500 on every request**:

```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'iftar' in 'field list'
select `id`, `day`, `month_id`, `sehri`, `magrib`, `iftar` from `permanent_calendars`
```

`permanent_calendars` has no `iftar` column. The fast is broken when Magrib begins, so
iftar was always meant to derive from `magrib` — `mazhab_wise_schedule_settings` even
carries an `iftar_time` offset for exactly that purpose.

This took down the Ramadan calendar and single-date screens, i.e. the headline feature of
an app called Tohfa-e-Ramazan.

**Found alongside:** `PermanentCalendar::$fillable` listed eight non-existent columns
(`sehri_time`, `magrib_and_iftar_time`, …) and none of the real ones. Every
`PermanentCalendar::create()` silently discarded its payload. The seeder writes through
`DB::table()`, which is why nobody noticed.

---

## Setup

```bash
git checkout fix/zrshishir/ramazan-calendar-iftar
composer install
php artisan migrate
php artisan db:seed
php artisan serve
```

---

## Automated checks

```bash
php artisan test
```

Expected: **40 passed** (9 new in `RamazanCalendarTest`).

---

## Test cases

### TC-01 — The endpoint no longer 500s

```bash
curl -i http://127.0.0.1:8000/api/ramazan-calendar
```

| Check | Expected |
|---|---|
| Status | `200` (was `500`) |
| `data.permanent_calendars` length | `30` |

---

### TC-02 — Every entry carries iftar

```bash
curl -s http://127.0.0.1:8000/api/ramazan-calendar \
  | jq '.data.permanent_calendars[0] | {day, magrib: .magrib.start_time, iftar: .iftar.start_time, sehri: .sehri.end_time}'
```

| Check | Expected |
|---|---|
| `iftar` present on every entry | ✅ |
| `iftar.text_en` | `"Iftar"` |
| `iftar.text_bn` / `text_ar` | Bangla / Arabic labels present |
| `iftar.start_time` | `magrib.start_time` raw value + `iftar_time` offset |

Confirm none of the 30 entries has a null `iftar`:

```bash
curl -s http://127.0.0.1:8000/api/ramazan-calendar \
  | jq '[.data.permanent_calendars[] | select(.iftar == null)] | length'   # expect 0
```

---

### TC-03 — Iftar does not inherit the Magrib offset

This is the subtle one. Both offsets default to 15, which would hide a double-application,
so set them apart:

```sql
UPDATE mazhab_wise_schedule_settings SET magrib_time = 30, iftar_time = 5 WHERE mazhab_id = 1;
```

With a raw `magrib.start_time` of `05:27 PM`:

| Field | Expected | Wrong answer that would indicate a bug |
|---|---|---|
| `magrib.start_time` | `05:57 PM` (+30) | — |
| `iftar.start_time` | `05:32 PM` (+5) | `06:02 PM` (+30 then +5) |

Restore afterwards:

```sql
UPDATE mazhab_wise_schedule_settings SET magrib_time = 15, iftar_time = 15 WHERE mazhab_id = 1;
```

---

### TC-04 — Iftar on the other calendar endpoints

`iftar` is additive across all four calendar responses:

```bash
curl -s http://127.0.0.1:8000/api/today-prayer          | jq '.data.prayer_times.iftar'
curl -s http://127.0.0.1:8000/api/permanent-calendar/1  | jq '.data.permanent_calendars[0].iftar'
curl -s -X POST http://127.0.0.1:8000/api/permanent-calendar | jq '.data.permanent_calendars.data[0].iftar'
curl -s http://127.0.0.1:8000/api/ramazan-calendar      | jq '.data.permanent_calendars[0].iftar'
```

All four must return an object, not `null`.

**No existing key changed** — this is purely additive, so an un-updated client keeps working.

---

### TC-05 — Month rollover

```bash
curl -s "http://127.0.0.1:8000/api/ramazan-calendar?day=25&month_id=1" \
  | jq '[.data.permanent_calendars[].month_id] | unique'
```

Expected: `[1, 2]` — starting on the 25th must span two months, still 30 entries.

---

### TC-06 — Missing mazhab setting

| Step | Action | Expected |
|---|---|---|
| 1 | `curl "http://127.0.0.1:8000/api/ramazan-calendar?mazhab_id=999"` | `200`, no fatal |
| 2 | Check `iftar` | Present, with **no** offset applied (raw magrib time) |

---

### TC-07 — Model writes actually persist

```bash
php artisan tinker
>>> $c = App\Models\PermanentCalendar::create(['month_id' => 1, 'day' => '99', 'sehri' => ['start_time' => '04:00 AM']]);
>>> $c->fresh()->toArray();
```

| Check | Expected |
|---|---|
| `month_id` | `1` |
| `day` | `'99'` |
| `sehri` | The array, not null |

**Previously:** all three were dropped and the insert carried only timestamps.

Clean up: `App\Models\PermanentCalendar::where('day','99')->delete();`

---

### TC-08 — No regressions

```bash
php artisan route:list --path=api
```

Then smoke-test every endpoint — calendar, quran, doa, asmaul-husna, tasbih, hadith,
masala — all `200`.

Also confirm the Filament admin at `/admin/permanent-calendars` still lists Sehri Start /
Sehri End etc. It reads through `data_get($record->sehri, 'start_time')`, so it is
unaffected by the `$fillable` change, but worth a glance.

---

## Automated verification performed

```
Tests:  40 passed (118 assertions)
```

Against real MySQL data:

```
GET /api/ramazan-calendar   HTTP 200   (was 500)
entries: 30
  magrib start: 06:50 PM
  iftar  start: 06:50 PM  (text: Iftar)
  sehri    end: 04:25 AM

GET /api/today-prayer
  iftar: 06:50 PM | magrib: 06:50 PM
```

Magrib and iftar coincide here only because the seeded mazhab uses `magrib_time = 15` and
`iftar_time = 15`. TC-03 separates them deliberately.

---

## Sign-off

| Check | Result |
|---|---|
| TC-01 no longer 500s | ☐ |
| TC-02 iftar on every entry | ☐ |
| TC-03 offset not double-applied | ☐ |
| TC-04 iftar on all endpoints | ☐ |
| TC-05 month rollover | ☐ |
| TC-06 missing mazhab | ☐ |
| TC-07 model writes persist | ☐ |
| TC-08 no regressions | ☐ |
| `php artisan test` green | ☐ |
