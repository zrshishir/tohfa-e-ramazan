# Manual Testing — Siha Sittah Hadith Library

**Branch:** `feature/zrshishir/siha-sittah-hadith`
**Target:** `development`
**Version:** 2.0.0
**Frontend counterpart:** follows separately — this is a breaking API change

---

## What changed

The `hadiths` table was a flat `title` / `description` / `reference` sheet holding two
placeholder rows. It is now three tables:

```
hadith_books  →  hadith_chapters  →  hadiths
   6 rows           333 rows          34,455 rows
```

Each hadith carries Arabic, Bangla and English text, a chapter link, a grade where the
source provides one, and a reference string.

---

## Setup

```bash
git checkout feature/zrshishir/siha-sittah-hadith
composer install
php artisan migrate
php artisan hadith:import          # ~34k hadiths, several minutes
php artisan serve
```

The import is **idempotent** — re-running upserts rather than duplicating. Use
`--fresh` to delete and reload a book, `--book=bukhari` to do just one.

> The migration **drops** the old `hadiths` table. Its only contents were two seeder
> placeholders. `down()` restores the original flat shape.

---

## Automated checks

```bash
php artisan test
```

Expected: **105 passed** (25 new across `HadithApiTest` and `HadithImportTest`).

---

## Test cases

### TC-01 — Import populates all six books

```bash
php artisan hadith:import
```

Expected final line: `Done. 34455 hadiths across 6 books.`

| Book | Hadiths | Chapters |
|---|---|---|
| Sahih al-Bukhari | 7,563 | 97 |
| Sahih Muslim | 7,563 | 56 |
| Sunan Abu Dawud | 5,274 | 43 |
| Jami at-Tirmidhi | 3,956 | 49 |
| Sunan an-Nasa'i | 5,758 | 51 |
| Sunan Ibn Majah | 4,341 | 37 |

> Bukhari reports 7,589 fetched but 7,563 stored. That is expected: the source repeats
> some hadith numbers, and the `(hadith_book_id, hadith_number)` unique constraint
> collapses them.

---

### TC-02 — Import is idempotent

| Step | Action | Expected |
|---|---|---|
| 1 | `php artisan hadith:import --book=bukhari` | Completes |
| 2 | Run it again | Completes |
| 3 | `App\Models\Hadith::where('hadith_book_id',1)->count()` | Still 7,563 |

---

### TC-03 — All three languages are populated

```bash
php artisan tinker --execute='
$q = App\Models\Hadith::where("hadith_book_id",1);
echo (clone $q)->whereNotNull("arabic_text")->count(), " arabic\n";
echo (clone $q)->whereNotNull("bangla_text")->count(), " bangla\n";
echo (clone $q)->whereNotNull("english_text")->count(), " english\n";'
```

Expected roughly `7563 / 7507 / 7563`. Bangla is 97–99.9% per book; the gap is chapter
headings and commentary that the dataset leaves in Arabic.

Spot-check that Bangla really is Bangla:

```bash
php artisan tinker --execute='
echo mb_substr(App\Models\Hadith::where("hadith_number",2)->first()->bangla_text,0,80);'
```

Should print Bengali script.

> Hadith 1 of Bukhari opens with an Arabic quotation before its Bengali translation —
> that is the source text, not a join error. The full field contains both.

---

### TC-04 — `GET /api/hadith-books`

```bash
curl -s http://127.0.0.1:8000/api/hadith-books | jq '.data[] | {slug, name_bn, total_hadiths}'
```

Six books in `sort_order`, each with a Bangla and Arabic name and a hadith count.

---

### TC-05 — `GET /api/hadith-books/{id}/chapters`

```bash
curl -s http://127.0.0.1:8000/api/hadith-books/1/chapters | jq '.data.chapters | length'
```

Expected `97`. The response carries `data.book` and `data.chapters`.

| Check | Expected |
|---|---|
| `name_en` | populated, e.g. "Revelation" |
| `name_bn` | **null** — see the note below |
| `total_hadiths` | non-zero |
| Unknown book id | `404` |

> **Chapter names are English only.** The Bengali and Arabic editions ship English
> chapter names in their metadata. Rather than store English in `name_bn`, the importer
> checks the script and stores null, so the client can fall back honestly instead of
> showing English labelled as Bengali. All 333 chapters have `name_bn = null` — this is
> correct, not a bug.

---

### TC-06 — `GET /api/hadith` paging and filters

```bash
curl -s "http://127.0.0.1:8000/api/hadith?per_page=2"          | jq '.data | {total, per_page}'
curl -s "http://127.0.0.1:8000/api/hadith?book_id=2&per_page=1" | jq '.data.total'
curl -s "http://127.0.0.1:8000/api/hadith?chapter_id=1&per_page=1" | jq '.data.total'
```

| Request | Expected |
|---|---|
| no filter | `total` 34,455 |
| `book_id=2` | 7,563 |
| `per_page=5000` | `422`, error on `per_page` (capped at 100) |
| `book_id=9999` | `422`, error on `book_id` |
| `q=a` | `422`, error on `q` (min 2 chars) |

Each item embeds `book` and `chapter`.

---

### TC-07 — Search

```bash
curl -s "http://127.0.0.1:8000/api/hadith?q=intention&per_page=1" | jq '.data.total'
curl -s "http://127.0.0.1:8000/api/hadith?q=%E0%A6%A8%E0%A6%BF%E0%A6%AF%E0%A6%BC%E0%A7%8D%E0%A6%AF%E0%A6%A4&per_page=1" | jq '.data.total'
```

English and Bangla both match. Arabic is deliberately **not** searched — without
diacritic normalisation a LIKE match on it is unreliable.

---

### TC-08 — Single hadith and random

```bash
ID=$(php artisan tinker --execute='echo App\Models\Hadith::min("id");' | tail -1)
curl -s "http://127.0.0.1:8000/api/hadith/$ID" | jq '.data | {hadith_number, reference, book: .book.name_en, chapter: .chapter.name_en}'
curl -s http://127.0.0.1:8000/api/hadith-random | jq '.data.hadith_number'
```

| Request | Expected |
|---|---|
| valid id | `200` with `book` and `chapter` embedded |
| `/api/hadith/999999` | `404` |
| unpublished hadith | `404` |
| `/api/hadith-random` repeated | different hadith each time |

> Ids do not start at 1 after `--fresh`, since rows are deleted and reinserted. Use the
> `min("id")` lookup above rather than guessing.

---

### TC-09 — Failure handling

| Step | Action | Expected |
|---|---|---|
| 1 | `php artisan hadith:import --book=nonsense` | Clean error naming the valid slugs, exit code 1 |
| 2 | Disconnect the network, `php artisan hadith:import --book=bukhari` | Retries, then a clear "Could not fetch" message and exit code 1 — no partial rows |

---

### TC-10 — No regressions

```bash
php artisan route:list --path=api
```

Smoke-test the calendar, quran, doa, asmaul-husna, tasbih and masala endpoints — all
`200`. `GET /api/masala` is untouched by this change.

---

## Automated verification performed

```
Tests:  105 passed (251 assertions)
```

Real import against MySQL:

```
Done. 34455 hadiths across 6 books.

book                 total  bangla english  arabic  chaps
Sahih al-Bukhari      7563    7507    7563    7563     97
Sahih Muslim          7563    7360    7563    7563     56
Sunan Abu Dawud       5274    5270    5274    5274     43
Jami at-Tirmidhi      3956    3875    3956    3956     49
Sunan an-Nasa'i       5758    5651    5758    5758     51
Sunan Ibn Majah       4341    4334    4341    4341     37
```

All endpoints returned `200` against the imported data; search for "intention" matched
256 hadiths.

---

## Known limitations

- **Chapter names are English only** (TC-05).
- **Grades are sparse.** Bukhari and Muslim carry none — they are Sahih by definition —
  and the source only grades some entries elsewhere.
- **No Filament admin** for the new tables yet. Content is import-driven; an admin UI
  for 34k rows is a separate piece of work.
- **Search is `LIKE`-based.** Fine at this size, but a fulltext index is the natural
  next step if search feels slow.

---

## Sign-off

| Check | Result |
|---|---|
| TC-01 import populates | ☐ |
| TC-02 idempotent | ☐ |
| TC-03 three languages | ☐ |
| TC-04 books endpoint | ☐ |
| TC-05 chapters endpoint | ☐ |
| TC-06 paging and filters | ☐ |
| TC-07 search | ☐ |
| TC-08 single and random | ☐ |
| TC-09 failure handling | ☐ |
| TC-10 no regressions | ☐ |
| `php artisan test` green | ☐ |
