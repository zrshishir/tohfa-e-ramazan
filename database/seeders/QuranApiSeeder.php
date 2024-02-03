<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class QuranApiSeeder extends Seeder
{
    public function run()
    {
        $apiEndpoint = 'http://api.alquran.cloud/v1/quran/en.asad';

        $response = Http::get($apiEndpoint);
        dd($response->code);
        if ($response->successful()) {
            $quranData = [];

            $quranApiResponse = $response->json();

            foreach ($quranApiResponse['data']['ayahs'] as $ayah) {
                $quranData[] = [
                    'sura_id'     => $ayah['sura'],
                    'ayat_no'     => $ayah['numberInSurah'],
                    'arabic_text' => $ayah['text'],
                    'bangla_text' => '', // You may need to add the Bangla translation if available in the API response
                    'english_text' => $ayah['translation'],
                    'meaning'     => $ayah['tafsir'],
                    'reference'   => "Surah {$ayah['sura']}:{$ayah['numberInSurah']}",
                    'notes'       => '',
                    'status'      => 'active',
                    'audio'       => '', // Update with the correct audio URL
                    'video'        => '', // Update with the correct video URL
                    'image' => '', // Update with the correct image URL
                ];
            }

            // Insert data into the database
            DB::table('quran')->insert($quranData);
        }
    }
}
