<?php

namespace Database\Seeders;

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
                'sura_id'      => 1,
                'ayat_no'      => 1,
                'arabic_text'  => 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ',
                'english_text' => 'bis-mil-laa-hir-rah-maa-nir-ra-heem',
                'bangla_text'  => 'বিসমিল্লাহির রাহমানির রাহীম',
                'meaning'      => 'আল্লাহর নামে যিনি পরম করুণাময়, অতি দয়ালু।',
                'reference'    => 'সূরা আল ফাতিহা ১:১',
                'notes'        => 'আল্লাহর নামে যিনি পরম করুণাময়, অতি দয়ালু।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/001.mp3',
                'video'        => 'https://www.youtube.com/watch?v=VHfJY3YpLj8',
                'image'        => 'https://i.ytimg.com/vi/VHfJY3YpLj8/maxresdefault.jpg',
            ],
            [
                'sura_id'      => 1,
                'ayat_no'      => 2,
                'arabic_text'  => 'الْحَمْدُ لِلَّهِ رَبِّ الْعَالَمِينَ',
                'english_text' => 'al-ham-du lil-laa-hi rab-bil-‘aa-la-meen',
                'bangla_text'  => 'আলহামদুলিল্লাহি রাব্বিল আলামীন',
                'meaning'      => 'সকল প্রশংসা আল্লাহর, পৃথিবীর প্রভুর,',
                'reference'    => 'সূরা আল ফাতিহা ১:২',
                'notes'        => 'সকল প্রশংসা আল্লাহর, পৃথিবীর প্রভুর,',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=VHfJY3YpLj8',
                'image'        => 'https://i.ytimg.com/vi/VHfJY3YpLj8/maxresdefault.jpg',
            ],
            [
                'sura_id'      => 1,
                'ayat_no'      => 3,
                'araic_text'   => 'الرَّحْمَٰنِ الرَّحِيمِ',
                'english_text' => 'ar-rah-maa-nir-ra-heem',
                'bangla_text'  => 'আর রহমান আর রহীম',
                'meaning'      => 'যিনি পরম করুণাময়, অতি দয়ালু।',
                'reference'    => 'সূরা আল ফাতিহা ১:৩',
                'notes'        => 'যিনি পরম করুণাময়, অতি দয়ালু।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/003.mp3',
                'video'        => 'https://www.youtube.com/watch?v=VHfJY3YpLj8',
                'image'        => 'https://i.ytimg.com/vi/VHfJY3YpLj8/maxresdefault.jpg',
            ],
            [
                'sura_id'      => 1,
                'ayat_no'      => 4,
                'arabic_text'  => 'اِيَّاكَ نَعْبُدُ وَاِيَّاكَ نَسْتَعِينُ',
                'english_text' => 'iyyaa-ka na’-budu wa iyyaa-ka nas-ta-‘een',
                'bangla_text'  => 'ইয়্যা-কা না’বুদু ও ইয়্যা-কা নাস্তাইন',
                'meaning'      => 'আমরা আপনার ইবাদত করি এবং আপনার কাছে সাহায্য চাই।',
                'reference'    => 'সূরা আল ফাতিহা ১:৪',
                'notes'        => 'আপনি ইয়াক নাছুয়াদু এবং আপনার কাছে আমরা সাহায্য চাই।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/004.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id'      => 1,
                'ayat_no'      => 5,
                'arabic_text'  => 'اِهْدِنَا الصِّرَاطَ الْمُسْتَقِيمَ',
                'english_text' => 'ih-di-nas-si-raa-tal-mus-ta-qiim',
                'bangla_text'  => 'ইহদিনাস সিরাতাল মুসতাকীম',
                'meaning'      => 'আমাদেরকে সরল পথে প্রবৃদ্ধি কর।',
                'reference'    => 'সূরা আল ফাতিহা ১:৫',
                'notes'        => 'আমাদেরকে সরল পথে প্রবৃদ্ধি কর।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/005.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id'      => 1,
                'ayat_no'      => 6,
                'arabic_text'  => 'صِرَاطَ الَّذِينَ اَنْعَمْتَ عَلَيْهِمْ غَيْرِ الْمَغْضُوبِ عَلَيْهِمْ وَلَا الضَّالِّينَ',
                'english_text' => 'si-raa-tal-lazi-na an-‘am-ta ‘alay-him ghay-ri-l-maghdoo-bi ‘alay-him wa la-d-daa-l-leen',
                'bangla_text'  => 'সিরাতাল লাজিনা আনআমতা আলাইহিম গাইরিল মাগদুবি আলাইহিম ওয়া লাদ্দাল্লিন',
                'meaning'      => 'তাদের পথ, যাদের উপর আপনি অনুগ্রহ করেছেন, তাদের পথ, যাদের উপর গজব না আসে এবং যাদের পথ, যারা হারানো গেছে।',
                'reference'    => 'সূরা আল ফাতিহা ১:৬',
                'notes'        => 'তাদের পথ, যাদের উপর আপনি অনুগ্রহ করেছেন, তাদের পথ, যাদের উপর গজব না আসে এবং যাদের পথ, যারা হারানো গেছে।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/006.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 1,
                'arabic_text'  => 'الم',
                'english_text' => 'alif-laam-meem',
                'bangla_text'  => 'আলিফ লাম মীম',
                'meaning'      => 'আলিফ লাম মীম',
                'reference'    => 'সূরা আল বাকারা ২:১',
                'notes'        => 'আলিফ লাম মীম',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 2,
                'arabic_text'  => 'ذَٰلِكَ الْكِتَابُ لَا رَيْبَ ۛ فِيهِ ۛ هُدًى لِّلْمُتَّقِينَ',
                'english_text' => 'zaalika-l-kitaabu laa ray-ba feehi hudal-lil-mutta-qiin',
                'bangla_text'  => 'যালিকাল কিতাবু লা রাইবা ফীহি হুদাল লিল মুত্তাকীন',
                'meaning'      => 'এই কিতাবে কোন সন্দেহ নেই, এটি ভক্তদের জন্য পথ প্রদর্শন করে।',
                'reference'    => 'সূরা আল বাকারা ২:২',
                'notes'        => 'এই কিতাবে কোন সন্দেহ নেই, এটি ভক্তদের জন্য পথ প্রদর্শন করে।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 3,
                'arabic_text'  => 'الَّذِينَ يُؤْمِنُونَ بِالْغَيْبِ وَيُقِيمُونَ الصَّلَاةَ وَمِمَّا رَزَقْنَاهُمْ يُنفِقُونَ',
                'english_text' => 'al-lazi-na yu’-mi-nu-na bil-ghay-bi wa yu-qi-mu-na-s-sa-laata wa mim-maa ra-zaq-naa-hum yun-fi-qoon',
                'bangla_text'  => 'আল্লাজিনা ইউমিনুনা বিল গাইবি ও ইউকিমুনা সালাতা ও মিম্মা রাজাকনাহুম ইউনফিকুন',
                'meaning'      => 'যারা গায়েবে বিশ্বাস রাখে এবং সালাত পালন করে এবং তাদের প্রাপ্তি করা রিয়ায়ত দান করে।',
                'reference'    => 'সূরা আল বাকারা ২:৩',
                'notes'        => 'যারা গায়েবে বিশ্বাস রাখে এবং সালাত পালন করে এবং তাদের প্রাপ্তি করা রিয়ায়ত দান করে।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 4,
                'arabic_text'  => 'وَالَّذِينَ يُؤْمِنُونَ بِمَا أُنزِلَ إِلَيْكَ وَمَا أُنزِلَ مِن قَبْلِكَ وَبِالْآخِرَةِ هُمْ يُوقِنُونَ',
                'english_text' => 'wal-lazi-na yu’-mi-nu-na bimaa un-zi-la ilay-ka wa maa un-zi-la min qab-li-ka wa bil-aakhirati hum yu-qin-oon',
                'bangla_text'  => 'ওয়াল্লাজিনা ইউমিনুনা বিমা উনজিলা ইলাইকা ও মা উনজিলা মিন কাবলিকা ও বিল আখিরাতি হুম ইউকিনুন',
                'meaning'      => 'যারা আপনার প্রেরিত করা হুকুম এবং আপনার প্রেরিত করা হুকুম এবং আখেরাতে বিশ্বাস রাখে।',
                'reference'    => 'সূরা আল বাকারা ২:৪',
                'notes'        => 'যারা আপনার প্রেরিত করা হুকুম এবং আপনার প্রেরিত করা হুকুম এবং আখেরাতে বিশ্বাস রাখে।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 5,
                'arabic_text'  => 'أُولَٰئِكَ عَلَىٰ هُدًى مِّن رَّبِّهِمْ ۖ وَأُولَٰئِكَ هُمُ الْمُفْلِحُونَ',
                'english_text' => 'ulaa-‘ika ‘alaa hudam-mir-rab-bihim wa ulaa-‘ika humul-muf-li-hoon',
                'bangla_text'  => 'উলাইকা আলা হুদাম মির রাব্বিহিম ও উলাইকা হুমুল মুফলিহুন',
                'meaning'      => 'তারা তাদের প্রভুর পথে প্রবৃদ্ধি করা হুকুম পেয়েছে এবং তারা সফল হয়েছে।',
                'reference'    => 'সূরা আল বাকারা ২:৫',
                'notes'        => 'তারা তাদের প্রভুর পথে প্রবৃদ্ধি করা হুকুম পেয়েছে এবং তারা সফল হয়েছে।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 6,
                'arabic_text'  => 'إِنَّ الَّذِينَ كَفَرُوا سَوَاءٌ عَلَيْهِمْ أَأَنذَرْتَهُمْ أَمْ لَمْ تُنذِرْهُمْ لَا يُؤْمِنُونَ',
                'english_text' => 'in-nal-lazi-na kafaruu sawaaa’un ‘alay-him a-an-zar-ta-hum am-lam tun-zir-hum laa yu’-mi-nuun',
                'bangla_text'  => 'ইন্নাল্লাজিনা কাফারু সাওয়াউন আলাইহিম আঅনযরতাহুম অম লাম তুনজিরহুম লা ইউমিনুন',
                'meaning'      => 'যদি তুমি তাদের পরবর্তী করার জন্য বিপদ দেন অথবা তুমি তাদের পরবর্তী করার জন্য বিপদ না দেন, তারা বিশ্বাস না করে।',
                'reference'    => 'সূরা আল বাকারা ২:৬',
                'notes'        => 'যদি তুমি তাদের পরবর্তী করার জন্য বিপদ দেন অথবা তুমি তাদের পরবর্তী করার জন্য বিপদ না দেন, তারা বিশ্বাস না করে।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 7,
                'arabic_text'  => 'خَتَمَ اللَّهُ عَلَىٰ قُلُوبِهِمْ وَعَلَىٰ سَمْعِهِمْ ۖ وَعَلَىٰ أَبْصَارِهِمْ غِشَاوَةٌ ۖ وَلَهُمْ عَذَابٌ عَظِيمٌ',
                'english_text' => 'kha-tama-l-laa-hu ‘alaa quluu-bi-him wa ‘alaa sam’-i-him wa ‘alaa ab-saari-him ghi-shaa-wa-tun wa la-hum ‘azaabun ‘aziim',
                'bangla_text'  => 'খাতামাল্লাহু আলা কুলুবিহিম ও আলা সাম’ইহিম ও আলা অবসারিহিম ঘিশাওয়াতুন ও লাহুম আযাবুন আযীম',
                'meaning'      => 'আল্লাহ তাদের হৃদয়ের ও শ্রবণের ও দৃষ্টিতে পর্দা পড়িয়েছে এবং তাদের জন্য বড় শাস্তি আছে।',
                'reference'    => 'সূরা আল বাকারা ২:৭',
                'notes'        => 'আল্লাহ তাদের হৃদয়ের ও শ্রবণের ও দৃষ্টিতে পর্দা পড়িয়েছে এবং তাদের জন্য বড় শাস্তি আছে।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
        ];

        DB::table('ayats')->insert($ayats);
    }
}
