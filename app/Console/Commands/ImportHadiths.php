<?php

namespace App\Console\Commands;

use App\Models\Hadith;
use App\Models\HadithBook;
use App\Models\HadithChapter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * Imports the Siha Sittah from the fawazahmed0/hadith-api dataset on jsDelivr.
 *
 * Each book is published as one JSON file per language, all sharing the same
 * `hadithnumber` keys, so the three editions are fetched and joined:
 *
 *   { metadata: { name, sections: {"1": "Revelation", ...}, section_details: {...} },
 *     hadiths: [ { hadithnumber, arabicnumber, text, grades: [], reference: {book, hadith} } ] }
 *
 * `reference.book` is the section (chapter) number.
 *
 * Memory: holding three fully-decoded editions at once exhausts a 128M limit on the
 * larger books, so each edition is compacted to the fields actually needed and the
 * decoded payload is released before the next is fetched.
 *
 * Deliberately not wired into DatabaseSeeder: this pulls ~34k hadiths over the network
 * and has no business running on every `db:seed`.
 */
class ImportHadiths extends Command
{
    protected $signature = 'hadith:import
                            {--book= : Import a single book by slug (bukhari, muslim, abudawud, tirmidhi, nasai, ibnmajah)}
                            {--fresh : Delete existing hadiths for each book before importing}';

    protected $description = 'Import the Siha Sittah hadith collections with Arabic, Bangla and English text';

    private const CDN = 'https://cdn.jsdelivr.net/gh/fawazahmed0/hadith-api@1/editions';

    /** slug => [English name, author, sort order] */
    private const BOOKS = [
        'bukhari'  => ['Sahih al-Bukhari', 'Imam Muhammad al-Bukhari', 1],
        'muslim'   => ['Sahih Muslim', 'Imam Muslim ibn al-Hajjaj', 2],
        'abudawud' => ['Sunan Abu Dawud', 'Imam Abu Dawud', 3],
        'tirmidhi' => ['Jami at-Tirmidhi', 'Imam at-Tirmidhi', 4],
        'nasai'    => ["Sunan an-Nasa'i", "Imam an-Nasa'i", 5],
        'ibnmajah' => ['Sunan Ibn Majah', 'Imam Ibn Majah', 6],
    ];

    private const BANGLA_NAMES = [
        'bukhari'  => 'সহীহ বুখারী',
        'muslim'   => 'সহীহ মুসলিম',
        'abudawud' => 'সুনান আবু দাউদ',
        'tirmidhi' => 'জামে আত-তিরমিযী',
        'nasai'    => 'সুনান আন-নাসাঈ',
        'ibnmajah' => 'সুনান ইবনে মাজাহ',
    ];

    private const ARABIC_NAMES = [
        'bukhari'  => 'صحيح البخاري',
        'muslim'   => 'صحيح مسلم',
        'abudawud' => 'سنن أبي داود',
        'tirmidhi' => 'جامع الترمذي',
        'nasai'    => 'سنن النسائي',
        'ibnmajah' => 'سنن ابن ماجه',
    ];

    public function handle(): int
    {
        // Decoding a multi-megabyte edition needs more headroom than the default CLI limit.
        ini_set('memory_limit', '512M');

        $only = $this->option('book');

        if ($only && !isset(self::BOOKS[$only])) {
            $this->error("Unknown book '{$only}'. Expected one of: " . implode(', ', array_keys(self::BOOKS)));
            return self::FAILURE;
        }

        foreach ($only ? [$only] : array_keys(self::BOOKS) as $slug) {
            if ($this->importBook($slug) === self::FAILURE) {
                return self::FAILURE;
            }
        }

        $this->newLine();
        $this->info('Done. ' . Hadith::count() . ' hadiths across ' . HadithBook::count() . ' books.');

        return self::SUCCESS;
    }

    private function importBook(string $slug): int
    {
        [$nameEn, $author, $order] = self::BOOKS[$slug];

        $this->newLine();
        $this->info("Importing {$nameEn}...");

        // --- Arabic drives the row set -------------------------------------
        $payload = $this->fetch('ara', $slug);
        if ($payload === null) {
            return self::FAILURE;
        }

        $sectionsAr = $payload['metadata']['sections'] ?? [];
        $entries    = [];

        foreach ($payload['hadiths'] ?? [] as $h) {
            $entries[] = [
                'number'    => (int) ($h['hadithnumber'] ?? 0),
                'arabicNo'  => $h['arabicnumber'] ?? null,
                'text'      => $h['text'] ?? null,
                'chapterNo' => (int) ($h['reference']['book'] ?? 0),
            ];
        }
        unset($payload);

        // --- Bangla --------------------------------------------------------
        $payload = $this->fetch('ben', $slug);
        if ($payload === null) {
            return self::FAILURE;
        }

        $sectionsBn = $payload['metadata']['sections'] ?? [];
        $bangla     = [];

        foreach ($payload['hadiths'] ?? [] as $h) {
            $bangla[(int) ($h['hadithnumber'] ?? 0)] = $h['text'] ?? null;
        }
        unset($payload);

        // --- English -------------------------------------------------------
        $payload = $this->fetch('eng', $slug);
        if ($payload === null) {
            return self::FAILURE;
        }

        $sectionsEn = $payload['metadata']['sections'] ?? [];
        $details    = $payload['metadata']['section_details'] ?? [];
        $english    = [];
        $grades     = [];

        foreach ($payload['hadiths'] ?? [] as $h) {
            $number           = (int) ($h['hadithnumber'] ?? 0);
            $english[$number] = $h['text'] ?? null;
            $grades[$number]  = $this->grade($h);
        }
        unset($payload);

        $book = HadithBook::updateOrCreate(
            ['slug' => $slug],
            [
                'name_en'    => $nameEn,
                'name_bn'    => self::BANGLA_NAMES[$slug] ?? null,
                'name_ar'    => self::ARABIC_NAMES[$slug] ?? null,
                'author'     => $author,
                'sort_order' => $order,
                'status'     => true,
            ]
        );

        if ($this->option('fresh')) {
            Hadith::where('hadith_book_id', $book->id)->delete();
            HadithChapter::where('hadith_book_id', $book->id)->delete();
        }

        $chapterIds = $this->importChapters($book, $sectionsEn, $sectionsBn, $sectionsAr, $details);
        $this->importHadiths($book, $entries, $bangla, $english, $grades, $chapterIds);

        $book->update(['total_hadiths' => Hadith::where('hadith_book_id', $book->id)->count()]);
        $this->line("  {$book->total_hadiths} hadiths, " . count($chapterIds) . ' chapters');

        return self::SUCCESS;
    }

    private function fetch(string $code, string $slug): ?array
    {
        // throw: false — a persistent failure should surface as a clean error message
        // and a non-zero exit code, not an unhandled RequestException.
        $response = Http::timeout(180)->retry(3, 2000, throw: false)
            ->get(self::CDN . "/{$code}-{$slug}.min.json");

        if (!$response->successful()) {
            $this->error("  Could not fetch {$code}-{$slug} ({$response->status()}). Aborting.");
            return null;
        }

        $decoded = json_decode($response->body(), true);
        $this->line("  fetched {$code}-{$slug} (" . count($decoded['hadiths'] ?? []) . ' hadiths)');

        return $decoded;
    }

    /** @return array<int,int> chapter_no => hadith_chapters.id */
    private function importChapters(
        HadithBook $book,
        array $english,
        array $bangla,
        array $arabic,
        array $details
    ): array {
        $ids = [];

        foreach ($english as $no => $nameEn) {
            $no = (int) $no;

            // Section 0 is a placeholder with an empty name in every edition.
            if ($no === 0) {
                continue;
            }

            $chapter = HadithChapter::updateOrCreate(
                ['hadith_book_id' => $book->id, 'chapter_no' => $no],
                [
                    'name_en'      => $nameEn ?: null,
                    'name_bn'      => $this->inScript($bangla[$no] ?? null, 'bengali'),
                    'name_ar'      => $this->inScript($arabic[$no] ?? null, 'arabic'),
                    'hadith_first' => $details[$no]['hadithnumber_first'] ?? null,
                    'hadith_last'  => $details[$no]['hadithnumber_last'] ?? null,
                    'status'       => true,
                ]
            );

            $ids[$no] = $chapter->id;
        }

        return $ids;
    }

    private function importHadiths(
        HadithBook $book,
        array $entries,
        array $bangla,
        array $english,
        array $grades,
        array $chapterIds
    ): void {
        $bar = $this->output->createProgressBar(count($entries));
        $bar->start();

        $now = now();

        foreach (array_chunk($entries, 500) as $chunk) {
            $rows = [];

            foreach ($chunk as $entry) {
                $number = $entry['number'];

                $rows[] = [
                    'hadith_book_id'    => $book->id,
                    'hadith_chapter_id' => $chapterIds[$entry['chapterNo']] ?? null,
                    'hadith_number'     => $number,
                    'arabic_number'     => $entry['arabicNo'],
                    'arabic_text'       => $entry['text'],
                    'bangla_text'       => $this->inScript($bangla[$number] ?? null, 'bengali'),
                    'english_text'      => $english[$number] ?? null,
                    'grade'             => $grades[$number] ?? null,
                    'reference'         => $book->name_en . ' ' . $number,
                    'status'            => true,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ];
            }

            // upsert keeps re-runs idempotent without deleting rows first.
            Hadith::upsert(
                $rows,
                ['hadith_book_id', 'hadith_number'],
                ['hadith_chapter_id', 'arabic_number', 'arabic_text', 'bangla_text',
                 'english_text', 'grade', 'reference', 'status', 'updated_at']
            );

            $bar->advance(count($chunk));
        }

        $bar->finish();
        $this->newLine();

        $this->recountChapters($book);
    }

    private function recountChapters(HadithBook $book): void
    {
        DB::table('hadith_chapters')->where('hadith_book_id', $book->id)->update(['total_hadiths' => 0]);

        $counts = Hadith::where('hadith_book_id', $book->id)
            ->whereNotNull('hadith_chapter_id')
            ->selectRaw('hadith_chapter_id, count(*) as aggregate')
            ->groupBy('hadith_chapter_id')
            ->pluck('aggregate', 'hadith_chapter_id');

        foreach ($counts as $chapterId => $count) {
            DB::table('hadith_chapters')->where('id', $chapterId)->update(['total_hadiths' => $count]);
        }
    }

    /**
     * The dataset is not uniformly translated. The Bengali editions ship English chapter
     * names in `metadata.sections`, and a handful of entries (chapter headings and
     * commentary) are left in Arabic. Storing those under `name_bn` / `bangla_text`
     * would label English or Arabic as Bengali, so anything not written in the expected
     * script is stored as null and the client falls back honestly.
     */
    private function inScript(?string $value, string $script): ?string
    {
        if (!filled($value)) {
            return null;
        }

        $pattern = match ($script) {
            'bengali' => '/[\x{0980}-\x{09FF}]/u',
            'arabic'  => '/[\x{0600}-\x{06FF}]/u',
        };

        return preg_match($pattern, $value) ? $value : null;
    }

    private function grade(array $entry): ?string
    {
        $grades = $entry['grades'] ?? [];

        if (!$grades) {
            return null;
        }

        $first = $grades[0];
        $text  = trim(($first['grade'] ?? '') . (isset($first['name']) ? " ({$first['name']})" : ''));

        return $text ?: null;
    }
}
