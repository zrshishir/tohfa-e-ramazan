<?php

namespace App\Console\Commands;

use App\Models\Ayat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Blank `bangla_text` wherever it merely repeats `meaning`.
 *
 * The two columns hold different things:
 *
 *   bangla_text  Bangla uccharon — how the Arabic is pronounced
 *   meaning      Bangla translation — what the Arabic says
 *
 * A seeder bug wrote the same `bn.bengali` edition into both, so the reader offered
 * "pronunciation" and "meaning" toggles that rendered identical text. Worse than
 * useless for a Qur'an app: someone reciting from that field is reading a translation
 * aloud in place of the verse.
 *
 * alquran.cloud publishes no Bengali transliteration edition — only Turkish, English
 * and Russian — so there is nothing to fill the column with. Requesting a made-up
 * edition name returned Arabic with HTTP 200, which is how the duplication arose in the
 * first place. Until a licensed Bangla uccharon source exists, empty is the honest
 * value, and it matches what AyatTableSeeder now writes.
 *
 * Rows where the two columns genuinely differ are left alone. On production that is 66
 * verses rescued from the pre-v3.3.0 database — the only authentic uccharon in the
 * project, and the reason this command targets `bangla_text = meaning` rather than
 * blanking the column wholesale.
 */
class BlankDuplicatedUccharon extends Command
{
    protected $signature = 'ayats:blank-duplicated-uccharon
                            {--dry-run : Report what would change without writing}';

    protected $description = 'Clear bangla_text where it duplicates meaning, preserving genuine uccharon';

    public function handle(): int
    {
        $total = Ayat::count();

        if ($total === 0) {
            $this->warn('No ayats found. Nothing to do.');

            return self::SUCCESS;
        }

        $duplicated = Ayat::whereColumn('bangla_text', 'meaning')->count();
        $genuine = Ayat::whereColumn('bangla_text', '!=', 'meaning')
            ->where('bangla_text', '!=', '')
            ->count();

        $this->newLine();
        $this->line("  Total ayats                        {$total}");
        $this->line("  bangla_text duplicating meaning    {$duplicated}");
        $this->line("  genuine uccharon (left untouched)  {$genuine}");
        $this->newLine();

        if ($duplicated === 0) {
            $this->info('Nothing duplicated. No changes needed.');

            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->comment("Dry run — {$duplicated} rows would be blanked.");

            return self::SUCCESS;
        }

        // The column is `text NOT NULL`, so empty string rather than null.
        $changed = DB::table('ayats')
            ->whereColumn('bangla_text', 'meaning')
            ->update(['bangla_text' => '']);

        $remaining = Ayat::where('bangla_text', '!=', '')->count();

        $this->info("Blanked {$changed} rows.");
        $this->line("  Rows still carrying uccharon: {$remaining}");

        if ($remaining !== $genuine) {
            $this->error('Unexpected: the genuine-uccharon count changed. Check the data.');

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
