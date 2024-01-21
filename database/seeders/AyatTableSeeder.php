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
            [
                'sura_id' => 1,
                'ayat_no' => 4,
                'arabic_text' => 'اِيَّاكَ نَعْبُدُ وَاِيَّاكَ نَسْتَعِينُ',
                'bangla_text' => 'আপনি ইয়াক নাছুয়াদু এবং আপনার কাছে আমরা সাহায্য চাই।',
                'english_text' => 'You alone we worship, and You alone we ask for help.',
                'meaning' => 'আপনি ইয়াক নাছুয়াদু এবং আপনার কাছে আমরা সাহায্য চাই।',
                'reference' => 'সূরা আল ফাতিহা ১:৪',
                'notes' => 'আপনি ইয়াক নাছুয়াদু এবং আপনার কাছে আমরা সাহায্য চাই।',
                'status' => 'active',
                'audio' => 'https://server8.mp3quran.net/basit/004.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id' => 1,
                'ayat_no' => 5,
                'arabic_text' => 'اِهْدِنَا الصِّرَاطَ الْمُسْتَقِيمَ',
                'bangla_text' => 'আমাদেরকে সরল পথে প্রবৃদ্ধি কর।',
                'english_text' => 'Guide us to the straight path.',
                'meaning' => 'আমাদেরকে সরল পথে প্রবৃদ্ধি কর।',
                'reference' => 'সূরা আল ফাতিহা ১:৫',
                'notes' => 'আমাদেরকে সরল পথে প্রবৃদ্ধি কর।',
                'status' => 'active',
                'audio' => 'https://server8.mp3quran.net/basit/005.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],


        ];

        DB::table('ayats')->insert($ayats);
    }
}
