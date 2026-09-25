<?php

namespace App\Console\Commands;

use App\Models\Ayat;
use App\Models\Sura;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Separate the Basmala from the first ayat of each sura.
 *
 * The alquran.cloud `quran-uthmani` edition prefixes بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ
 * onto the *text of ayat 1* for suras 2–114. The reader therefore showed the Basmala
 * run together with the opening verse, as though it were part of it.
 *
 * Three cases must not be touched, and each is excluded explicitly:
 *
 *   Sura 1 (Al-Fatihah)  The Basmala *is* ayat 1 in the Hafs numbering. Correct as-is.
 *   Sura 9 (At-Tawbah)   Has no Basmala at all. Nothing to split.
 *   Sura 27, ayat 30     The Basmala appears *inside* the verse, quoting Sulayman's
 *                        letter. Only ayat_no = 1 is ever considered, so this is safe.
 *
 * The separated Basmala is inserted as `ayat_no = 0` rather than renumbering. Shifting
 * every verse by one would break the canonical 6,236 ayat count, invalidate saved
 * bookmarks and make every verse reference in the app wrong. Zero is the conventional
 * marker for a sura-opening Basmala and leaves 1..n untouched.
 *
 * Matching is done on the diacritic-stripped skeleton, because the source data spells it
 * at least two ways — بِسْمِ in most suras and بِّسْمِ (an extra shadda) in 95 and 97.
 * Comparing consonants only catches every variant rather than a hardcoded list.
 */
class SplitBasmala extends Command
{
    protected $signature = 'ayats:split-basmala
                            {--dry-run : Report what would change without writing}';

    protected $description = 'Split the Basmala out of each sura\'s first ayat into its own ayat_no=0 row';

    /** Suras whose first ayat must be left exactly as it is. */
    private const SKIP_SURAS = [
        1, // the Basmala is genuinely ayat 1 here
        9, // At-Tawbah has no Basmala
    ];

    private const BOM = "\u{FEFF}";

    /**
     * Strip diacritics, unify the alef forms, remove all whitespace.
     *
     * Leaves only the consonant skeleton, so any vowelling of the Basmala compares equal.
     *
     * Whitespace is removed rather than collapsed so that the skeleton's length equals
     * the number of characters stripBasmalaPrefix() counts. Collapsing to single spaces
     * instead made the skeleton three characters longer than the count, and the cut
     * overshot into the following word — sura 4 lost the opening of يَٰٓأَيُّهَا.
     */
    private function skeleton(string $text): string
    {
        $text = str_replace(self::BOM, '', $text);

        // Arabic marks: harakat, superscript alef, Qur'anic annotation signs, tatweel.
        $text = preg_replace('/[\x{0610}-\x{061A}\x{064B}-\x{065F}\x{0670}\x{06D6}-\x{06ED}\x{0640}]/u', '', $text);

        // أ إ آ ٱ ا all normalise to bare alef.
        $text = preg_replace('/[\x{0622}\x{0623}\x{0625}\x{0671}]/u', "\u{0627}", $text);

        return preg_replace('/\s+/u', '', $text);
    }

    public function handle(): int
    {
        $basmalaSkeleton = $this->skeleton('بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ');
        $basmalaLength = mb_strlen($basmalaSkeleton);

        // Reference rendering and translations come from sura 1 ayat 1, which is the
        // Basmala on its own — so whatever this database holds for it stays consistent.
        $template = Ayat::where('sura_id', 1)->where('ayat_no', 1)->first();

        if (!$template) {
            $this->error('Sura 1 ayat 1 not found — cannot source the Basmala text.');

            return self::FAILURE;
        }

        $templateArabic = str_replace(self::BOM, '', $template->arabic_text);

        // The fetched source left a UTF-8 BOM at the head of some verses. It is invisible
        // but real: it breaks prefix matching, sorting and copy-paste. Same origin as the
        // merge, so it is cleaned here rather than in a separate pass.
        //
        // Compared in PHP rather than SQL. MySQL's utf8mb4 collation treats the BOM as an
        // ignorable character, so `LIKE '%<BOM>%'` matches every row in the table and
        // `LIKE '<BOM>%'` reports a number unrelated to reality — it claimed 200 rows on a
        // table where a byte check found a different set entirely. This also keeps the
        // command working on SQLite, which the tests use.
        $bomRows = 0;
        $dryRun = $this->option('dry-run');

        Ayat::select('id', 'arabic_text')->chunkById(500, function ($rows) use (&$bomRows, $dryRun) {
            foreach ($rows as $row) {
                if (!str_starts_with($row->arabic_text ?? '', self::BOM)) {
                    continue;
                }

                $bomRows++;

                if (!$dryRun) {
                    DB::table('ayats')
                        ->where('id', $row->id)
                        ->update(['arabic_text' => trim(str_replace(self::BOM, '', $row->arabic_text))]);
                }
            }
        });

        $firstAyats = Ayat::where('ayat_no', 1)
            ->whereNotIn('sura_id', self::SKIP_SURAS)
            ->orderBy('sura_id')
            ->get();

        $toSplit = [];
        $alreadyDone = 0;
        $noBasmala = [];

        foreach ($firstAyats as $ayat) {
            if (Ayat::where('sura_id', $ayat->sura_id)->where('ayat_no', 0)->exists()) {
                $alreadyDone++;

                continue;
            }

            $skeleton = $this->skeleton($ayat->arabic_text);

            if (!str_starts_with($skeleton, $basmalaSkeleton)) {
                $noBasmala[] = $ayat->sura_id;

                continue;
            }

            $remainder = $this->stripBasmalaPrefix($ayat->arabic_text, $basmalaLength);

            if ($remainder === '') {
                // Nothing would be left of ayat 1 — that means the row *is* just the
                // Basmala, so splitting would destroy the verse. Never seen, but the
                // check costs nothing and the failure would be silent.
                $this->warn("  sura {$ayat->sura_id}: ayat 1 is only the Basmala — skipped.");

                continue;
            }

            $toSplit[] = [$ayat, $remainder];
        }

        $this->newLine();
        $this->line('  stray BOMs removed            ' . $bomRows
            . ($this->option('dry-run') && $bomRows ? ' (would be)' : ''));
        $this->line('  first ayats considered        ' . $firstAyats->count());
        $this->line('  already split                 ' . $alreadyDone);
        $this->line('  no Basmala prefix             ' . count($noBasmala)
            . (count($noBasmala) ? ' (suras ' . implode(', ', $noBasmala) . ')' : ''));
        $this->line('  to split                      ' . count($toSplit));
        $this->line('  deliberately skipped          suras ' . implode(', ', self::SKIP_SURAS)
            . ' (Basmala is ayat 1 / sura has none)');
        $this->newLine();

        if (!$toSplit) {
            $this->info('Nothing to do.');

            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            foreach (array_slice($toSplit, 0, 3) as [$ayat, $remainder]) {
                $this->line("  sura {$ayat->sura_id} ayat 1 would become:");
                $this->line('    ' . mb_substr($remainder, 0, 60));
            }
            $this->comment(count($toSplit) . ' rows would be split. Nothing written.');

            return self::SUCCESS;
        }

        DB::transaction(function () use ($toSplit, $template, $templateArabic) {
            foreach ($toSplit as [$ayat, $remainder]) {
                Ayat::query()->forceCreate([
                    'sura_id' => $ayat->sura_id,
                    'ayat_no' => 0,
                    'arabic_text' => $templateArabic,
                    'english_text' => $template->english_text,
                    'bangla_text' => $template->bangla_text,
                    'meaning' => $template->meaning,
                    'reference' => $ayat->reference ?? '',
                    'notes' => '',
                    'status' => $ayat->status ?? 'active',
                ]);

                $ayat->arabic_text = $remainder;
                $ayat->save();
            }
        });

        $inserted = Ayat::where('ayat_no', 0)->count();
        $numbered = Ayat::where('ayat_no', '>', 0)->count();

        $this->info('Split ' . count($toSplit) . ' suras.');
        $this->line("  Basmala rows (ayat_no = 0)    {$inserted}");
        $this->line("  numbered ayats                {$numbered}");

        // The canonical Hafs count is 6,236 and splitting must not change it — the
        // Basmala rows are ayat_no = 0 and therefore outside the numbering. Only worth
        // enforcing against a complete Qur'an; test fixtures hold a handful of verses.
        if (Sura::count() === 114 && $numbered !== 6236) {
            $this->error("Numbered ayat count is {$numbered}, expected 6236. Investigate before deploying.");

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    /**
     * Remove the Basmala from the front of the original text, preserving its diacritics.
     *
     * Walks the original character by character, counting only the characters that
     * survive skeletonisation, so the cut lands exactly where the Basmala ends however
     * it happens to be vowelled.
     */
    private function stripBasmalaPrefix(string $original, int $skeletonLength): string
    {
        $text = str_replace(self::BOM, '', $original);
        $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);

        $counted = 0;
        $cutAt = 0;

        foreach ($chars as $index => $char) {
            if ($this->skeleton($char) !== '') {
                $counted++;
            }

            if ($counted >= $skeletonLength) {
                $cutAt = $index + 1;
                break;
            }
        }

        $remainder = implode('', array_slice($chars, $cutAt));

        // The cut lands immediately after the final consonant of the Basmala, so its
        // own closing vowel mark — the kasra of ٱلرَّحِيمِ — would otherwise be left
        // stranded at the head of the next verse. Drop any leading marks and spacing.
        $remainder = preg_replace(
            '/^[\s\x{0610}-\x{061A}\x{064B}-\x{065F}\x{0670}\x{06D6}-\x{06ED}]+/u',
            '',
            $remainder
        );

        return trim($remainder);
    }
}
