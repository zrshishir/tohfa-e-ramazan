<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AyatTableSeeder extends Seeder
{
    /**
     * Fetch all 114 suras and their ayats from the Quran API
     * and seed the ayats table.
     *
     * Column convention, matching DoaSeeder:
     *   arabic_text  Arabic
     *   english_text Latin pronunciation
     *   bangla_text  Bangla uccharon (pronunciation)
     *   meaning      Bangla meaning
     *
     * APIs used:
     *   - Arabic text:     https://api.alquran.cloud/v1/quran/quran-uthmani
     *   - Transliteration: https://api.alquran.cloud/v1/quran/en.transliteration
     *   - English meaning: https://api.alquran.cloud/v1/quran/en.asad
     *   - Bangla meaning:  https://api.alquran.cloud/v1/quran/bn.bengali
     *
     * ⚠️ bangla_text has no source. alquran.cloud publishes only three transliteration
     * editions — Turkish, English and Russian — and no Bengali one. Requesting a made-up
     * identifier such as `bn.transliteration` returns HTTP 200 with the *Arabic* text
     * rather than an error, which is exactly how bad data slips in unnoticed; the guard
     * in fetchEdition() now catches that. Until a Bangla uccharon source is available,
     * bangla_text is written empty rather than being filled with the meaning, which is
     * what it held before and what made it identical to `meaning` in all 6,236 rows.
     */
    public function run(): void
    {
        $this->command->info('Fetching Quran data from API...');

        $arabic          = $this->fetchEdition('quran-uthmani');
        $transliteration = $this->fetchEdition('en.transliteration', $arabic);
        $english         = $this->fetchEdition('en.asad', $arabic);
        $bangla          = $this->fetchEdition('bn.bengali', $arabic);

        if (!$arabic || !$transliteration || !$english || !$bangla) {
            $this->command->error('Failed to fetch one or more Quran editions. Aborting.');
            return;
        }

        $rows = [];

        foreach ($arabic['surahs'] as $suraIndex => $sura) {
            $suraId = $sura['number'];

            $this->command->info("Processing Sura {$suraId}: {$sura['englishName']}");

            foreach ($sura['ayahs'] as $ayatIndex => $ayat) {
                $rows[] = [
                    'sura_id'      => $suraId,
                    'ayat_no'      => $ayat['numberInSurah'],
                    'arabic_text'  => $ayat['text'],
                    'english_text' => $transliteration['surahs'][$suraIndex]['ayahs'][$ayatIndex]['text'] ?? '',
                    // Left empty on purpose: this column is for the Bangla uccharon,
                    // and filling it from the Bangla translation made it a duplicate of
                    // `meaning`. See the note above.
                    'bangla_text'  => '',
                    'meaning'      => $bangla['surahs'][$suraIndex]['ayahs'][$ayatIndex]['text'] ?? '',
                    'reference'    => $sura['englishName'] . ' ' . $suraId . ':' . $ayat['numberInSurah'],
                    'notes'        => $english['surahs'][$suraIndex]['ayahs'][$ayatIndex]['text'] ?? '',
                    'status'       => 'active',
                    'audio'        => 'https://cdn.islamic.network/quran/audio/128/ar.alafasy/' . $ayat['number'] . '.mp3',
                    'video'        => null,
                    'image'        => null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }
        }

        // Insert in chunks to avoid memory/query size issues
        $this->command->info('Inserting ' . count($rows) . ' ayats into the database...');

        DB::table('ayats')->truncate();

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('ayats')->insert($chunk);
        }

        $this->command->info('Done! All ayats seeded successfully.');
    }

    /**
     * Fetch a single Quran edition from alquran.cloud API.
     */
    private function fetchEdition(string $edition, ?array $arabic = null): ?array
    {
        $response = Http::timeout(60)->get("https://api.alquran.cloud/v1/quran/{$edition}");

        if ($response->failed()) {
            $this->command->warn("Failed to fetch edition: {$edition}");
            return null;
        }

        $data = $response->json();

        if (($data['code'] ?? null) !== 200 || empty($data['data']['surahs'])) {
            $this->command->warn("Unexpected response for edition: {$edition}");
            return null;
        }

        $payload = $data['data'];

        /*
         * alquran.cloud answers 200 with the Arabic Quran when an edition identifier does
         * not exist, instead of failing. Without this check a typo silently seeds Arabic
         * into a translation column and nothing looks wrong until a user reads it.
         */
        if ($arabic && $edition !== 'quran-uthmani') {
            $first    = $payload['surahs'][0]['ayahs'][0]['text'] ?? '';
            $arabicFirst = $arabic['surahs'][0]['ayahs'][0]['text'] ?? '';

            if ($first !== '' && $first === $arabicFirst) {
                $this->command->warn(
                    "Edition '{$edition}' returned the Arabic text, so it probably does not exist. Skipping."
                );
                return null;
            }
        }

        return $payload;
    }
}
