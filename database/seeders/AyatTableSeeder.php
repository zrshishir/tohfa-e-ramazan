<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AyatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // fill this array with ayat data from https://quran.com/
        $ayats = [
            [
                'sura_id' => 1,
                'ayat_no' => 1,
                'arabic_text' => 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ',
                'bangla_text' => 'আল্লাহর নামে যিনি পরম করুণাময়, অতি দয়ালু।',
                'english_text' => 'In the name of Allah, the Entirely Merciful, the Especially Merciful.',
                'meaning' => 'আল্লাহর নামে যিনি পরম করুণাময়, অতি দয়ালু।',
                'reference' => 'সূরা আল ফাতিহা ১:১',
                'notes' => 'আল্লাহর নামে যিনি পরম করুণাময়, অতি দয়ালু।',
                'status' => 'active',
                'audio' => 'https://server8.mp3quran.net/basit/001.mp3',
                'video' => 'https://www.youtube.com/watch?v=VHfJY3YpLj8',
                'image' => 'https://i.ytimg.com/vi/VHfJY3YpLj8/maxresdefault.jpg',
            ],
            [
                'sura_id' => 1,
                'ayat_no' => 2,
                'arabic_text' => 'الْحَمْدُ لِلَّهِ رَبِّ الْعَالَمِينَ',
                'bangla_text' => 'সকল প্রশংসা আল্লাহর, পৃথিবীর প্রভুর,',
                'english_text' => 'All praise is [due] to Allah, Lord of the worlds -',
                'meaning' => 'সকল প্রশংসা আল্লাহর, পৃথিবীর প্রভুর,',
                'reference' => 'সূরা আল ফাতিহা ১:২',
                'notes' => 'সকল প্রশংসা আল্লাহর, পৃথিবীর প্রভুর,',
                'status' => 'active',
                'audio' => 'https://server8.mp3quran.net/basit/002.mp3',
                'video' => 'https://www.youtube.com/watch?v=VHfJY3YpLj8',
                'image' => 'https://i.ytimg.com/vi/VHfJY3YpLj8/maxresdefault.jpg',
            ],
            [
                'sura_id' => 1,
                'ayat_no' => 3,
                'araic_text' => 'الرَّحْمَٰنِ الرَّحِيمِ',
                'bangla_text' => 'যিনি পরম করুণাময়, অতি দয়ালু।',
                'english_text' => 'The Entirely Merciful, the Especially Merciful,',
                'meaning' => 'যিনি পরম করুণাময়, অতি দয়ালু।',
                'reference' => 'সূরা আল ফাতিহা ১:৩',
                'notes' => 'যিনি পরম করুণাময়, অতি দয়ালু।',
                'status' => 'active',
                'audio' => 'https://server8.mp3quran.net/basit/003.mp3',
                'video' => 'https://www.youtube.com/watch?v=VHfJY3YpLj8',
                'image' => 'https://i.ytimg.com/vi/VHfJY3YpLj8/maxresdefault.jpg',
            ],

        ];

        DB::table('ayats')->insert($ayats);
    }
}