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
     * APIs used:
     *   - Arabic text:   https://api.alquran.cloud/v1/quran/quran-uthmani
     *   - Transliteration: https://api.alquran.cloud/v1/quran/en.transliteration
     *   - English meaning: https://api.alquran.cloud/v1/quran/en.asad
     *   - Bangla meaning:  https://api.alquran.cloud/v1/quran/bn.bengali
     */
    public function run(): void
    {
        $this->command->info('Fetching Quran data from API...');

        $arabic       = $this->fetchEdition('quran-uthmani');
        $transliteration = $this->fetchEdition('en.transliteration');
        $english      = $this->fetchEdition('en.asad');
        $bangla       = $this->fetchEdition('bn.bengali');

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
                    'bangla_text'  => $bangla['surahs'][$suraIndex]['ayahs'][$ayatIndex]['text'] ?? '',
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
    private function fetchEdition(string $edition): ?array
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

        return $data['data'];
    }
}
