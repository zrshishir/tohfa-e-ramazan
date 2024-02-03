<?php

// Example using Laravel Eloquent and QuranAPI
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class QuranSeeder extends Seeder
{
    public function run()
    {
        // Replace 'YOUR_API_KEY' with your actual API key
        $apiKey   = 'YOUR_API_KEY';
        $response = Http::get("https://api.quran.com/v1/quran/$apiKey");

        if ($response->successful()) {
            $quranData = [];

            $quranApiResponse = $response->json();

            foreach ($quranApiResponse['data']['verses'] as $verse) {
                $quranData[] = [
                    'sura_id'      => $verse['verse_key']['sura_id'],
                    'ayat_no'      => $verse['verse_key']['verse_id'],
                    'arabic_text'  => $verse['verse_key']['verse_key'],
                    'bangla_text'  => $verse['translations']['bn']['text'],
                    'english_text' => $verse['translations']['en']['text'],
                    'meaning'      => $verse['translations']['en']['translation'],
                    'reference'    => "Surah {$verse['verse_key']['sura_id']}:{$verse['verse_key']['verse_id']}",
                    'notes'        => '',
                    'status'       => 'active',
                    'audio'        => '', // Update with the correct audio URL
                    'video' => '', // Update with the correct video URL
                    'image' => '', // Update with the correct image URL
                ];
            }

            // Insert data into the database
            DB::table('quran')->insert($quranData);
        }
    }
}
