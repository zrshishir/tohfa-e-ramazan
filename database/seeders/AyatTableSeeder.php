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
            [
                'sura_id'      => 2,
                'ayat_no'      => 8,
                'arabic_text'  => 'اللَّهُ لَا إِلَٰهَ إِلَّا هُوَ الْحَيُّ الْقَيُّومُ',
                'english_text' => 'al-laa-hu laa i-laa-ha il-laa hu-wal-hay-yul-qay-yoom',
                'bangla_text'  => 'আল্লাহু লা ইলাহা ইল্লা হুয়াল হায়্যুল কাইয়্যুম',
                'meaning'      => 'আল্লাহ ছাড়া কোন মাবুদ নেই, তিনি চিরস্থায়ী ও জীবিত।',
                'reference'    => 'সূরা আল বাকারা ২:৮',
                'notes'        => 'আল্লাহ ছাড়া কোন মাবুদ নেই, তিনি চিরস্থায়ী ও জীবিত।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 9,
                'arabic_text'  => 'لَا تُدْرِكُهُ الْأَبْصَارُ وَهُوَ يُدْرِكُ الْأَبْصَارَ ۖ وَهُوَ اللَّطِيفُ الْخَبِيرُ',
                'english_text' => 'laa tud-ri-ku-hul-ab-saar wa hu-wa yud-ri-kul-ab-saar wa hu-wal-la-tee-fu-l-kha-beer',
                'bangla_text'  => 'লা তুদরিকুহুল আবসারু ওয়া হুয়া ইউদরিকুল আবসারা ওয়া হুয়াল লাতীফুল খাবীর',
                'meaning'      => 'চোখ তাকে ধরতে পারে না, কিন্তু তিনি চোখ ধরে রাখেন। তিনি সুক্ষ্মতামূলক ও সকল বিষয়ে জ্ঞানী।',
                'reference'    => 'সূরা আল বাকারা ২:৯',
                'notes'        => 'চোখ তাকে ধরতে পারে না, কিন্তু তিনি চোখ ধরে রাখেন। তিনি সুক্ষ্মতামূলক ও সকল বিষয়ে জ্ঞানী।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 10,
                'arabic_text'  => 'وَمِنَ النَّاسِ مَن يَقُولُ آمَنَّا بِاللَّهِ فَإِذَا أُوذِيَ فِي اللَّهِ جَعَلَ فِتْنَةَ النَّاسِ كَعَذَابِ اللَّهِ ۚ وَلَئِن جَاءَ نَصْرٌ مِّن رَّبِّكَ لَيَقُولُنَّ إِنَّا كُنَّا مَعَكُمْ ۚ أَوَلَيْسَ اللَّهُ بِأَعْلَمَ بِمَا فِي صُدُورِ الْعَالَمِينَ',
                'english_text' => 'wa mi-nan-naa-si man ya-quu-lu a-maan-naa bil-laa-hi fa-i-zaa u-wu-zi-ya fi-l-laa-hi ja-a-la fit-na-tan-naa-si ka-‘a-zaa-bil-laa-hi wa la-‘in jaa-a nas-run min rab-bi-ka la-ya-quu-lun-na in-naa ku-nnaa ma-‘a-ku-ma a-wa-laysal-laa-hu bi-a’-la-ma bi-maa fi su-du-ri-l-‘aa-la-meen',
                'bangla_text'  => 'ওয়া মিনান্নাসি মান ইয়াকুলু আমান্না বিল্লাহি ফা-ইয়া উয়ুয়িয়া ফিল্লাহি জা-আলা ফিত্নাতান্নাসি কা-আয়াজাবিল্লাহি ওলাইন জা-আ নাসরুন মিন রাব্বিকা লা-য়াকুলুন্না ইন্না কুন্না মা-আকুম অ-ওয়ালাইসাল্লাহু বি-আলামা বিমা ফি সুদুরি ল-আলামীন',
                'meaning'      => 'এবং মানুষের মধ্যে কেউ আছে যিনি বলে, আমরা আল্লাহের বিশ্বাস করি। কিন্তু যখন তাঁকে আপত্তি করা হয়, তখন তিনি মানুষের প্রতিবাদ আল্লাহের শাস্তির মতো করেন। এবং যদি তোমার প্রভুর পক্ষ থেকে সাহায্য আসে, তারা বলবে, আমরা তোমাদের সঙ্গে ছিলাম। কি আল্লাহ সমস্ত জগতের হৃদয়ে যা আছে তা আলাম।',
                'reference'    => 'সূরা আল বাকারা ২:১০',
                'notes'        => 'এবং মানুষের মধ্যে কেউ আছে যিনি বলে, আমরা আল্লাহের বিশ্বাস করি। কিন্তু যখন তাঁকে আপত্তি করা হয়, তখন তিনি মানুষের প্রতিবাদ আল্লাহের শাস্তির মতো করেন। এবং যদি তোমার প্রভুর পক্ষ থেকে সাহায্য আসে, তারা বলবে, আমরা তোমাদের সঙ্গে ছিলাম। কি আল্লাহ সমস্ত জগতের হৃদয়ে যা আছে তা আলাম।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 11,
                'arabic_text'  => 'وَإِذَا قِيلَ لَهُمْ لَا تُفْسِدُوا فِي الْأَرْضِ قَالُوا إِنَّمَا نَحْنُ مُصْلِحُونَ',
                'english_text' => 'wa i-zaa qi-la la-hum laa tuf-si-du fi-l-ar-di qa-luu in-na-maa na-hnu mus-li-hoon',
                'bangla_text'  => 'ওয়া ইয়ায়া কিলা লাহুম লা তুফসিদু ফিল আর্দি কালু ইন্নামা নাহনু মুসলিহুন',
                'meaning'      => 'এবং যখন তাদের বলা হয়, তোমরা পৃথিবীতে অপরাধ করবে না, তখন তারা বলে, আমরা কেবল সুধারক।',
                'reference'    => 'সূরা আল বাকারা ২:১১',
                'notes'        => 'এবং যখন তাদের বলা হয়, তোমরা পৃথিবীতে অপরাধ করবে না, তখন তারা বলে, আমরা কেবল সুধারক।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],

            [
                'sura_id'      => 2,
                'ayat_no'      => 12,
                'arabic_text'  => 'وَمِنَ النَّاسِ مَن يَقُولُ آمَنَّا بِاللَّهِ وَبِالْيَوْمِ الْآخِرِ وَمَا هُم بِمُؤْمِنِينَ',
                'english_text' => 'wa mi-nan-naa-si man ya-quu-lu a-maan-naa bil-laa-hi wa bi-l-yaw-mil-aakhir wa maa hum bi-mu’-mi-nin',
                'bangla_text'  => 'ওয়া মিনান্নাসি মান ইয়াকুলু আমান্না বিল্লাহি ওয়া বিল ইয়াউমিল আখিরি ওয়া মা হুম বিমুমিনীন',
                'meaning'      => 'এবং মানুষের মধ্যে কেউ আছে যিনি বলে, আমরা আল্লাহের ও আগামী দিনের বিশ্বাস করি কিন্তু তারা বিশ্বাসী নয়।',
                'reference'    => 'সূরা আল বাকারা ২:১২',
                'notes'        => 'এবং মানুষের মধ্যে কেউ আছে যিনি বলে, আমরা আল্লাহের ও আগামী দিনের বিশ্বাস করি কিন্তু তারা বিশ্বাসী নয়।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 13,
                'arabic_text'  => 'وَإِذَا قِيلَ لَهُمْ آمِنُوا كَمَا آمَنَ النَّاسُ قَالُوا أَنُؤْمِنُ كَمَا آمَنَ السُّفَهَاءُ ۗ أَلَا إِنَّهُمْ هُمُ السُّفَهَاءُ وَلَٰكِن لَّا يَعْلَمُونَ',
                'english_text' => 'wa i-zaa qi-la la-hum a-mi-nuu ka-maa a-ma-na-nnaa-su qa-luu a-nu’-mi-nu ka-maa a-ma-na-s-su-fa-haa-u a-laa in-nahum humus-su-fa-haa-u wa laa ya’-la-muun',
                'bangla_text'  => 'ওয়া ইয়ায়া কিলা লাহুম আমিনু কামা আমানা নাসু কালু আনুমিনু কামা আমানা সুফাহাউ আলা ইন্নাহুম হুমুসুফাহাউ ওয়া লাকিন লা ইয়ালামুন',
                'meaning'      => 'এবং যখন তাদের বলা হয়, তোমরা বিশ্বাস করো যথাযথভাবে যেমন মানুষ বিশ্বাস করে, তারা বলে, আমরা যথাযথভাবে বিশ্বাস করবো যেমন মূর্খরা বিশ্বাস করে। নিশ্চয়ই তারা মূর্খরা, কিন্তু তারা জানে না।',
                'reference'    => 'সূরা আল বাকারা ২:১৩',
                'notes'        => 'এবং যখন তাদের বলা হয়, তোমরা বিশ্বাস করো যথাযথভাবে যেমন মানুষ বিশ্বাস করে, তারা বলে, আমরা যথাযথভাবে বিশ্বাস করবো যেমন মূর্খরা বিশ্বাস করে। নিশ্চয়ই তারা মূর্খরা, কিন্তু তারা জানে না।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 14,
                'arabic_text'  => 'وَإِذَا لَقُوا الَّذِينَ آمَنُوا قَالُوا آمَنَّا وَإِذَا خَلَوْا إِلَىٰ شَيَاطِينِهِمْ قَالُوا إِنَّا مَعَكُمْ إِنَّمَا نَحْنُ مُسْتَهْزِئُونَ',
                'english_text' => 'wa i-zaa la-quul-lazi-na a-ma-nuu qa-luu a-ma-nnaa wa i-zaa kha-la-wa i-laa sha-ya-tii-ni-him qa-luu in-naa ma-‘a-ku-m in-na-maa na-hnu mus-tah-zee-uun',
                'bangla_text'  => 'ওয়া ইয়ায়া লাকুল্লাজিনা আমানু কালু আমান্না ওয়া ইয়ায়া খালাও ইলা শায়াতীনিহিম কালু ইন্না মা-আকুম ইন্নামা নাহনু মুস্তাহজীউন',
                'meaning'      => 'এবং যখন তারা যারা বিশ্বাস করে, তাদের সঙ্গে মিলে, তখন তারা বলে, আমরা বিশ্বাস করি। এবং যখন তারা তাদের শয়তানদের সঙ্গে একা থাকে, তখন তারা বলে, আমরা তোমাদের সঙ্গে ছিলাম। নিশ্চয়ই তারা আপত্তিকারী।',
                'reference'    => 'সূরা আল বাকারা ২:১৪',
                'notes'        => 'এবং যখন তারা যারা বিশ্বাস করে, তাদের সঙ্গে মিলে, তখন তারা বলে, আমরা বিশ্বাস করি। এবং যখন তারা তাদের শয়তানদের সঙ্গে একা থাকে, তখন তারা বলে, আমরা তোমাদের সঙ্গে ছিলাম। নিশ্চয়ই তারা আপত্তিকারী।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],

            [
                'sura_id'      => 2,
                'ayat_no'      => 15,
                'arabic_text'  => 'وَإِذَا لَقُوا الَّذِينَ آمَنُوا قَالُوا آمَنَّا وَإِذَا خَلَوْا إِلَىٰ شَيَاطِينِهِمْ قَالُوا إِنَّا مَعَكُمْ إِنَّمَا نَحْنُ مُسْتَهْزِئُونَ',
                'english_text' => 'wa i-zaa la-quul-lazi-na a-ma-nuu qa-luu a-ma-nnaa wa i-zaa kha-la-wa i-laa sha-ya-tii-ni-him qa-luu in-naa ma-‘a-ku-m in-na-maa na-hnu mus-tah-zee-uun',
                'bangla_text'  => 'ওয়া ইয়ায়া লাকুল্লাজিনা আমানু কালু আমান্না ওয়া ইয়ায়া খালাও ইলা শায়াতীনিহিম কালু ইন্না মা-আকুম ইন্নামা নাহনু মুস্তাহজীউন',
                'meaning'      => 'এবং যখন তারা যারা বিশ্বাস করে, তাদের সঙ্গে মিলে, তখন তারা বলে, আমরা বিশ্বাস করি। এবং যখন তারা তাদের শয়তানদের সঙ্গে একা থাকে, তখন তারা বলে, আমরা তোমাদের সঙ্গে ছিলাম। নিশ্চয়ই তারা আপত্তিকারী।',
                'reference'    => 'সূরা আল বাকারা ২:১৪',
                'notes'        => 'এবং যখন তারা যারা বিশ্বাস করে, তাদের সঙ্গে মিলে, তখন তারা বলে, আমরা বিশ্বাস করি। এবং যখন তারা তাদের শয়তানদের সঙ্গে একা থাকে, তখন তারা বলে, আমরা তোমাদের সঙ্গে ছিলাম। নিশ্চয়ই তারা আপত্তিকারী।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 16,
                'arabic_text'  => 'أُولَٰئِكَ الَّذِينَ اشْتَرَوُا الضَّلَالَةَ بِالْهُدَىٰ فَمَا رَبِحَت تِّجَارَتُهُمْ وَمَا كَانُوا مُهْتَدِينَ',
                'english_text' => 'u-laa-‘i-ka-l-lazi-na shtarawu-za-la-la-ta bi-l-hu-da fa-maa ra-bi-hat-ti-ja-ra-tu-hum wa maa ka-nu muh-ta-diin',
                'bangla_text'  => 'উলাইকা ল্লাজিনা আশ্তারাউয়ায়া যালালাতা বিল হুদা ফা-মা রাবিহাত্তি জারাতুহুম ওয়া মা কানু মুহতাদীন',
                'meaning'      => 'তারা হল সেই লোকজন যারা হেদায়েত দিয়ে ভুল পথ কেনার বদলে পথ কেনেছে। তাদের ব্যবসায় কোন লাভ হয়নি এবং তারা হেদায়ে নেই।',
                'reference'    => 'সূরা আল বাকারা ২:১৬',
                'notes'        => 'তারা হল সেই লোকজন যারা হেদায়েত দিয়ে ভুল পথ কেনার বদলে পথ কেনেছে। তাদের ব্যবসায় কোন লাভ হয়নি এবং তারা হেদায়ে নেই।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 17,
                'arabic_text'  => 'مَثَلُهُمْ كَمَثَلِ الَّذِي اسْتَوْقَدَ نَارًا فَلَمَّا أَضَاءَتْ مَا حَوْلَهُ ذَهَبَ اللَّهُ بِنُورِهِمْ وَتَرَكَهُمْ فِي ظُلُمَاتٍ لَّا يُبْصِرُونَ',
                'english_text' => 'ma-sa-lu-hum ka-ma-sa-li-l-lazi is-taw-qa-da naa-ran fa-lam-maa a-da-a-t ma ha-wla-hu za-ha-bal-laa-hu bi-nu-ri-him wa ta-ra-ka-hum fi zul-maa-tin laa yub-si-ruun',
                'bangla_text'  => 'মাসালুহুম কামাসালিল্লাজি ইস্তাওকাদা নারান ফালমা আদাআত মা হাওলাহু জাহাবাল্লাহু বিনূরিহিম ওয়া তারাকাহুম ফি জুলুমাতিন লা ইউবসিরুন',
                'meaning'      => 'তাদের মিথ্যা যেমন একটি লোকের মিথ্যা, যে একটি আগুন আলোকিত করে, তখন তার চারপাশের আলো চলে গেল। আল্লাহ তাদের আলো নিয়ে চলে গেল এবং তারা অন্ধকারে থেকে বাচ্চা রয়ে গেল।',
                'reference'    => 'সূরা আল বাকারা ২:১৭',
                'notes'        => 'তাদের মিথ্যা যেমন একটি লোকের মিথ্যা, যে একটি আগুন আলোকিত করে, তখন তার চারপাশের আলো চলে গেল। আল্লাহ তাদের আলো নিয়ে চলে গেল এবং তারা অন্ধকারে থেকে বাচ্চা রয়ে গেল।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 18,
                'arabic_text'  => 'صُمٌّ بُكْمٌ عُمْيٌ فَهُمْ لَا يَرْجِعُونَ',
                'english_text' => 'sum-bun bu-kmun ‘um-yun fa-hum laa yar-ji-‘uun',
                'bangla_text'  => 'সুম্মুন বুকমুন আমিউন ফাহুম লা ইয়ারজিউন',
                'meaning'      => 'তারা বধ্য সুন্দর বুকমুন এবং অন্ধকারে তারা ফিরে আসতে পারবে না।',
                'reference'    => 'সূরা আল বাকারা ২:১৮',
                'notes'        => 'তারা বধ্য সুন্দর বুকমুন এবং অন্ধকারে তারা ফিরে আসতে পারবে না।',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
                'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
                'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '19',
                'arabic_text'  => 'أَوْ كَصَيِّبٍ مِّنَ السَّمَاءِ فِيهِ ظُلُمَاتٌ وَرَ‌عْدٌ وَبَرْ‌قٌ‌ۙ يَجْعَلُونَ أَصَابِعَهُمْ فِي آذَانِهِم مِّنَ الصَّوَاعِقِ حَذَرَ‌ الْمَوْتِ‌ۚ وَاللَّـهُ مُحِيطٌ بِالْكَافِرِ‌ينَ',
                'english_text' => 'aw ka-say-yibin minas-sama-i feehi zulumaatun wa ra\'dun wa barqun yaj-\'aluuna asaabii-\'ahum fi aadhaanihim minas-sawa-\'iqi hadaral-mawt wallaahu muhitum bil-kaafiriin',
                'bangla_text'  => 'আউ কা-সাই-বিন্ মিনাস্-সামা-ই ফীহি যুলুমাতুন্ ওয়া রাযাদুন্ ওয়া বার্কুন্ ইয়াজ-\'আলুনা আসাবী-\'অহুম্ ফী আয়াদানিহিম্ মিনাস্-সাওআ\'ইকি হাজারাল-মাওতি ওয়াল্লাহু মুহিতুম্ বিল্-কাফিরীন্',
                'meaning'      => 'অথবা আকাশ থেকে একটি ঝর্ণা এসে গেছে, যেখানে অন্ধকার, গর্জন ও বজ্রপাত রয়েছে। তারা তার আঙ্গুল কানে ধরে মৃত্যুর ভয়ে। এবং আল্লাহ কাফেরদের ঘিরে আছেন।',
                'reference'    => 'কুরআন ২:১৯',
                'notes'        => '',
                'status'       => '',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '20',
                'arabic_text'  => 'يَكَادُ الْبَرْ‌قُ يَخْطَفُ أَبْصَارَ‌هُمْ‌ۖ كُلَّمَا أَضَاءَ لَهُم مَّشَوْا فِيهِ وَإِذَا أَظْلَمَ عَلَيْهِمْ قَامُوا‌ۚ وَلَوْ شَاءَ اللَّـهُ لَذَهَبَ بِسَمْعِهِمْ وَأَبْصَارِ‌هِمْ‌ۚ إِنَّ اللَّـهَ عَلَىٰ كُلِّ شَيْءٍ قَدِيرٌ‌ۗ',
                'english_text' => 'ya-kaa-du-l-barqu ya-kh-ta-fu absaa-ra-hum kul-lamaa a-da-a la-hum ma-shaw feehi wa idhaa a-zla-ma \'alai-him qaamuu wa law shaa-\'a-l-laa-hu la-za-ha-ba bi-sam-\'ihim wa absaari-him innal-laaha \'alaa kulli shay\'in qadiir',
                'bangla_text'  => 'ইয়াকাদুল্-বার্কু ইয়াখতাফু আবসারাহুম্ কুল্লামা আদাআ লাহুম্ মাশাও ফীহি ওয়া ইয়ায়াজ-লাম আলাইহিম্ কামু ওয়া লো-শাআআল্লা-হু লাজাহাব বিসাম-আইহিম্ ওয়া আবসারিহিম্ ইন্নাল্লা-হা আলা কুল্লি শাইইন্ কাদীরুন্',
                'meaning'      => 'বিদ্যুতের আলো তাদের দৃষ্টিকে ধরে নিতে অন্ধকার করতে। যখন সে তাদের প্রকাশ করে, তারা প্রবৃত্তি করে; এবং যখন অন্ধকার তাদের উপর পড়ে, তারা দাঁড়ায়। আর যদি আল্লাহ চাইতেন, তবে তিনি তাদের শ্রবণ ও দৃষ্টিকে অগ্রসর করেন; নিশ্চয় আল্লাহ সর্বকিছুতে ক্ষমতাশালী।',
                'reference'    => 'কুরআন ২:২০',
                'notes'        => '',
                'status'       => '',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '21',
                'arabic_text'  => 'يَا أَيُّهَا النَّاسُ اعْبُدُوا رَبَّكُمُ الَّذِي خَلَقَكُمْ وَالَّذِينَ مِن قَبْلِكُمْ لَعَلَّكُمْ تَتَّقُونَ',
                'english_text' => 'yaa ayyu-han-naas u\'bu-duu rab-bakumul-ladhee khala-qakum wal-ladheena min qabli-kum la-\'allakum tatta-qoon',
                'bangla_text'  => 'ইয়া ঐয়ুহান্-নাস্ ইউবুদু রাব্বাকুমুল্লায়ি খালাকাকুম ওয়াল্লায়িনা মিন কাব্লিকুম লা-আল্লাকুম তাত্তাকুন',
                'meaning'      => 'হে মানুষগণ, আপনাদের পালনকর্তা যিনি আপনাদেরকে ও আপনাদের প্রধানগণকে সৃষ্টি করেছেন এবং আপনাদের পূর্বদেরকে ও তা যেন আপনি ভয় পাওয়া যায়।',
                'reference'    => 'কুরআন ২:২১',
                'notes'        => '',
                'status'       => '',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '22',
                'arabic_text'  => 'الَّذِي جَعَلَ لَكُمُ الْأَرْ‌ضَ فِرَ‌اشًا وَالسَّمَاءَ بِنَاءً وَأَنزَلَ مِنَ السَّمَاءِ مَاءً فَأَخْرَ‌جَ بِهِ مِنَ الثَّمَرَ‌اتِ رِ‌زْقًا لَّكُمْ ۖ فَلَا تَجْعَلُوا لِلَّـهِ أَندَادًا وَأَنتُمْ تَعْلَمُونَ',
                'english_text' => 'al-ladhee ja\'ala-lakumul-arda fira-shan wal-samaa\'a binaa\'an wa anzala minas-samaa\'i maa\'an fa-akh-raja bihi minas-thamaraati rizqal-lakum fa-laa taj\'alu-lil-laahi andaadaan wa antum ta\'-lamoon',
                'bangla_text'  => 'আল্লায়ি জা\'অলা-লাকুমুল-আর্ডা ফিরাশান্ ওয়াস্সামা বিনাআন্ ওয়াঅনজালা মিনাস্-সামাই মাআন্ ফাঅখ-রাজা বিহি মিনাস্-সামারাতি রিজকাল-লাকুম ফালা তাজ\'অলু লিল্লাহি আন্দাদান্ ওয়াঅন্তুম্ তাআলামুন্',
                'meaning'      => 'যিনি আপনাদের জন্য জমিনি এবং আকাশ একটি স্থায়ী ঘর নির্মাণ করেছেন এবং আপনাদের জন্য আকাশ থেকে পানি নামান এবং সে পানি দিয়ে আপনাদের জন্য ফল উত্পন্ন করেন যাতে আপনাদের জন্য খাবার হয়। তাই আল্লাহকে কোন সহকারী প্রতিষ্ঠা করবেন না, আপনি এটা অবশ্যই জানেন।',
                'reference'    => 'কুরআন ২:২২',
                'notes'        => '',
                'status'       => '',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '23',
                'arabic_text'  => 'وَإِن كُنتُمْ فِي رَ‌يْبٍ مِّمَّا نَزَّلْنَا عَلَىٰ عَبْدِنَا فَأْتُوا بِسُورَ‌ةٍ مِّن مِّثْلِهِ وَادْعُوا شُهَدَاءَكُم مِّن دُونِ اللَّـهِ إِن كُنتُمْ صَادِقِينَ',
                'english_text' => 'wa in kun-tum fiy ray-bin mim-maa nazzal-naa \'alaa \'ab-di-naa fa\'-tuwaa bisu-ra-tim min mis-li-hii wad\'u shuhaa-da-\'akum min duuni-l-laahi in kun-tum saadiqiin',
                'bangla_text'  => 'ওয়া ইন্ কুন্তুম্ ফি রাইবিন্ মিম্মানাজ্জাল্না আলা আব্দিনা ফাআতুওবিসুরাতিম মিন মিসলিহী ওয়াদু শুহাদাআকুম্ মিন দুয়ুনিল্লাহি ইন্ কুন্তুম্ সাদিকীন',
                'meaning'      => 'আর যদি আপনারা আমার সে বান্দার উপর সঙ্গে সঙ্গে সন্দেহ করেন তাহলে আপনারা এর মতো একটি সূরা আসিয়ে প্রমাণ করুন',
                'reference'    => 'কুরআন ২:২৩',
                'notes'        => '',
                'status'       => '',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '24',
                'arabic_text'  => 'ثُمَّ إِن لَّمْ تَفْعَلُوا وَلَن تَفْعَلُوا فَاتَّقُوا النَّارَ الَّتِي وَقُودُهَا النَّاسُ وَالْحِجَارَةُ ۖ أُعِدَّتْ لِلْكَافِرِينَ',
                'english_text' => 'thumma in lam taf\'alu wa lan taf\'alu fa-ttaqu-n-naara-llatee waquuduhall-naasu wal-hijaaratu u\'iddat lilkafiriin',
                'bangla_text'  => 'থুম্মা ইন্ লাম্ তাফ\'আলু ওয়া লান্ তাফ\'আলু ফাত্তাকুন্ নারাল্লাতি ওয়াকুদুহাল্লাসু ওয়াল-হিজারাতু উই\'ইদ্দাত্ লিলকাফিরীনা',
                'meaning'      => 'তারপর, যদি তোমরা এটা না করো এবং নিশ্চয়ই তোমরা এটা করবে না, তাহলে তোমরা তৎপর হোক তার প্রতি যে অগ্নি, যার লগন মানুষ এবং পাথর, তা প্রায়শই কাফিরদের জন্য প্রস্তুত করা হয়েছে।',
                'reference'    => 'কুরআন ২:২৪',
                'notes'        => '',
                'status'       => '',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '25',
                'arabic_text'  => 'وَبَشِّرِ الَّذِينَ آمَنُوا وَعَمِلُوا الصَّالِحَاتِ أَنَّ لَهُمْ جَنَّاتٍ تَجْرِي مِن تَحْتِهَا الْأَنْهَارُ ۖ كُلَّمَا رُ‌زِقُوا مِنْهَا مِن ثَمَرَ‌ةٍ رِّ‌زْقًا قَالُوا هَـٰذَا الَّذِي رُ‌زِقْنَا مِن قَبْلُ ۖ وَأُتُوا بِهِ مُتَشَابِهًا ۖ وَلَهُمْ فِيهَا أَزْوَاجٌ مُّطَهَّرَ‌ةٌ ۖ وَهُمْ فِيهَا خَالِدُونَ',
                'english_text' => 'wa bash-shiri al-ladheena aamanuu wa \'amilus-saalihaati anna lahum jannaatin tajrii min tahtiha-l-anhaar; kullamaa ruziquu minhaa min samaratin rizqan qaalu haadha-alladhee ruziqnaa min qablu; wa uutuw bihi mutashaabihan; wa lahum feehaa azwaajun mutahharatun; wa hum feehaa khaalidoon',
                'bangla_text'  => 'ওয়া বাশ্শিরিল্লাজিনা আমানুওও ওয়া আমিলুস্সা-লিহাতি অন্না লাহুম জান্নাতিন তাজরি মিন তাহতিহাল-আনহারু; কুল্লামা রুয়িকুমিন্ হামিন সামারাতিন রিজকান কালু হাজাল্লাজী রুয়িকনা মিন কাবলু; ওয়াউতুবিহি মুতাশাবিহান; ওয়া লাহুম ফীহা আয়জুন্ মুতাহারাতুন; ওয়া হুম্ ফীহা খালিদুন',
                'meaning'      => 'এবং তাদেরকে আমন্ত্রণ কর যারা ঈমান এনেছে এবং ভালো কাজ করেছে, যে সবার জন্য একটি জান্নাত আছে যার নিচে নদীর প্রবাহ আছে; যেখানে তারা যাই কিছু ফল পান, সেই ফল তারা বলে, এটা সেই ফল যা আমরা পূর্বেই পেয়েছি, এবং তারা তাদের জন্য প্রতিষ্ঠিত হবে, এবং তাদেরকে পরিষ্কার স্ত্রী হিসেবে দেওয়া হবে; এবং তারা সেখানে চিরকাল থাকবে।',
                'reference'    => 'কুরআন ২:২৫',
                'notes'        => '',
                'status'       => '',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '26',
                'arabic_text'  => 'إِنَّ اللَّـهَ لَا يَسْتَحْيِي أَن يَضْرِ‌بَ مَثَلًا مَّا بَعُوضَةً فَمَا فَوْقَهَا ۚ فَأَمَّا الَّذِينَ آمَنُوا فَيَعْلَمُونَ أَنَّهُ الْحَقُّ مِن رَّ‌بِّهِمْ ۖ وَأَمَّا الَّذِينَ كَفَرُ‌وا فَيَقُولُونَ مَاذَا أَرَ‌ادَ اللَّـهُ بِهَـٰذَا مَثَلًا ۘ يُضِلُّ بِهِ كَثِيرً‌ا وَيَهْدِي بِهِ كَثِيرً‌ا ۚ وَمَا يُضِلُّ بِهِ إِلَّا الْفَاسِقِينَ',
                'english_text' => 'in-nallaaha laa yastahyee an yad-rib-a masalan maa ba\'uudhatan famaa fawqahaa; fa-ammal-ladheena aamanuu faya\'lamuuna annahu-l-haqq-u mir-rabbi-him; wa-ammal-ladheena kafaruu fayaquuluuna maazaa araada-llaahu bihaadha masalan; yudillu bihi kathiiran wa yahdii bihi kathiiran; wa maa yudillu bihi illal-faasiqiin',
                'bangla_text'  => 'ইন্নাল্লাহা লা ইয়াস্তাহি অন ইয়াডরিবা মাসালান মা বাউধাতান ফামা ফাওকহা; ফাঅম্মাল্লাজিনা আমানু ফাযালামুনা অন্নাহুল্-হাক্কু মিররাব্বিহিম্; ওয়া আম্মাল্লাজিনা কাফারু ফায়াকুলুনা মা৛া আরাদাল্লাহু বিহাদা মাসালান; ইয়ুডিল্লু বিহি কাথীরান ওয়া ইয়াহদি বিহি কাথীরান; ওয়া মা ইয়ুডিল্লু বিহি ইল্লাল-ফাসিকীন',
                'meaning'      => 'সত্যিই, আল্লাহ তার সৃষ্টির মধ্যে যে উপমা দেন সেটা দান ছোঁড়ার ক্ষমতা আছেন না যে সর্বোচ্চ অন্য কারো উপর একটি উপমা দেন, তবে তার ওপরে যে কিছু আছে সেই উপমা নাহানির উপরের সম্মান। তবে যারা ঈমান এনেছে তারা সেটা বুঝে, তারা বিশ্বস্ত যে সত্যিই এটি তাদের প্রভুর কাছ থেকে এসেছে। আর যারা ঈমান লাভ করেনি, তারা বলে, "আল্লাহ এটার মাধ্যমে কী প্রয়োজন পূরণ করতে চাইলেন?" এটি অনেকের পথে বিপথ দেয় এবং অনেকের পথে পরিচালনা করেন, আর যারা এর মাধ্যমে পথ হারিয়ে যান তারা শুধুমাত্র পাপে প্রবৃদ্ধি করেন।',
                'reference'    => 'কুরআন ২:২৬',
                'notes'        => '',
                'status'       => '',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '27',
                'arabic_text'  => 'الَّذِينَ يَنقُضُونَ عَهْدَ اللَّـهِ مِن بَعْدِ مِيثَاقِهِ وَيَقْطَعُونَ مَا أَمَرَ‌ اللَّـهُ بِهِ أَن يُوصَلَ وَيُفْسِدُونَ فِي الْأَرْ‌ضِ ۚ أُولَـٰئِكَ هُمُ الْخَاسِرُ‌ونَ',
                'english_text' => 'al-ladheena yanqudhoona \'ahda-llahi min ba\'di miithaaqi-hi wa yaqtha\'uuna maa amara-llahu bihi an yuusala wa yufsiduuna fil-ardi; oolaa-ika humu-l-khaasiruun',
                'bangla_text'  => 'আল্লাজিনা ইয়ানকুয়ুনা আহ্‌দাল্লাহি মিন বা’দি মীথাকি-হি ওয়া ইয়াকতাউনা মা অমরাল্লাহু বিহি আন ইউসালা ওয়া ইউফসিদুনা ফি লা-আর্‌দি; উলাইকা হুমুল-খাসিরুন',
                'meaning'      => 'যারা আল্লাহর আহদ ভাঙ্গতে পারলে, এবং যা আল্লাহ তাদের দেখাচ্ছেনা তা কেটে ফেলে, এবং যা আল্লাহ যুক্ত করে তা ভেঙ্গে ফেলে, এবং ধার্মিকতা নিয়ে জমিন নেয়া বা আল্লাহর নামে সম্পত্তি ধরে ধরে ভেঙ্গে ফেলে, তারা নিশ্চয়ই হানিকারক।',
                'reference'    => 'কুরআন ২:২৭',
                'notes'        => '',
                'status'       => '',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '28',
                'arabic_text'  => 'كَيْفَ تَكْفُرُ‌ونَ بِاللَّـهِ وَكُنتُمْ أَمْوَاتًا فَأَحْيَاكُمْ ۖ ثُمَّ يُمِيتُكُمْ ثُمَّ يُحْيِيكُمْ ثُمَّ إِلَيْهِ تُرْ‌جَعُونَ',
                'english_text' => 'kayfa takfuruuna billaahi wa kuntum amwaata fa-aHyaa-kum; thumma yumeetu-kum thumma yuHyii-kum thumma ilayhi turja\'uun',
                'bangla_text'  => 'কাইফা তাকফুরুনা বিল্লাহি ওয়া কুন্তুম অমওয়াতান ফা-অহযাকুম; তুম্মা ইয়ুমীতুকুম তুম্মা ইয়ুহ্যীকুম তুম্মা ইলাইহি তুরজাউন',
                'meaning'      => 'কিভাবে তোমরা আল্লাহর নামে কাফের ছিলে, যখন তোমরা মৃত্যুবাদী ছিলে, তাঁ তোমাদের জীবন দান করেছিলেন, তারপর তোমাদেরকে মৃত্যুতে প্রাপ্ত করেছিলেন, পুনরায় জীবিত করেছিলেন, তারপর তোমাকে আবার তাঁর কাছে ফিরে যেতে দেবেন।',
                'reference'    => 'কুরআন ২:২৮',
                'notes'        => '',
                'status'       => '',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '29',
                'arabic_text'  => 'هُوَ الَّذِي خَلَقَ لَكُم مَّا فِي الْأَرْ‌ضِ جَمِيعًا ثُمَّ اسْتَوَىٰ إِلَى السَّمَاءِ فَسَوَّاهُنَّ سَبْعَ سَمَاوَاتٍ ۚ وَهُوَ بِكُلِّ شَيْءٍ عَلِيمٌ',
                'english_text' => 'huwal-ladhee khalaqa lakum maa fil-ardi jamee\'an; thumma-stawaa ilaa-s-samaa\'i fasa-waahunna sab\'a samaawaatin; wa huwa bikulli shai-in \'aleem',
                'bangla_text'  => 'হুয়া আল্লাজি খালাকা লাকুম মা ফিল-আর্‌দি জামি-আন; তুম্মা ইস্তাওয়া ইলা-স্সামা-ই ফাসাওয়াহুন্না সাব্‌’ সামাওয়াতিন; ওহুয়া বিকুল্লি শাই-ইন ‘আলীম।',
                'meaning'      => 'তিনি তারা আকাশে উঠে গেলেন এবং তারা সাতটি আকাশ বানালেন। এবং তিনি সব কিছুতে জ্ঞানী।',
                'reference'    => 'কুরআন ২:২৯',
                'notes'        => '',
                'status'       => '',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '30',
                'arabic_text'  => 'وَإِذْ قَالَ رَ‌بُّكَ لِلْمَلَائِكَةِ إِنِّي جَاعِلٌ فِي الْأَرْ‌ضِ خَلِيفَةً ۖ قَالُوا أَتَجْعَلُ فِيهَا مَن يُفْسِدُ فِيهَا وَيَسْفِكُ الدِّمَاءَ وَنَحْنُ نُسَبِّحُ بِحَمْدِكَ وَنُقَدِّسُ لَكَ ۖ قَالَ إِنِّي أَعْلَمُ مَا لَا تَعْلَمُونَ',
                'english_text' => 'wa iz qaala rabbuka lil-malaaa’ikati innee ja’ ilun fil-‘ardi khaleefah; qaalu a-taj’alu feehaa mai yufsidu feehaa wa yasfiku-d-dimaaa’a wa nahnu nusabbihu bihamdika wa nuqaddisu laka; qaala innee a’lamu maa laa ta’lamuun',
                'bangla_text'  => 'ওয়া ইজ কালা রাব্বুকা লিল-মালাইকাতি ইন্নী জাইলুন ফিল-আর্‌দি খালীফাহ; কালু আতাজ’আলু ফিহা মান ইউফসিদু ফিহা ওয়া ইয়াসফিকুদ-দিমাআ’ও ওয়া নাহনু নুসাব্বিহু বিহামদিকা ওয়া নুকাদ্দিসু লাকা; কালা ইন্নী আ’লামু মা লা তা’লামুন।',
                'meaning'      => 'এবং তোমার রব বললেন, আমি আর্দে একটি খালিফা স্থাপন করব। মলাইকারা বললো, আপনি কি তারে সৃষ্টি করতে চান, যে অত্যাচার করে এবং তাতে রক্তচক্ষন করে, আর আমরা আপনাকে সত্বর সত্তায় আদর করি এবং আপনার পবিত্র করি। আমার জানা সব জিনিস তোমাদের জানা নেই।',
                'reference'    => 'কুরআন ২:৩০',
                'notes'        => '',
                'status'       => '',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '31',
                'arabic_text'  => 'وَعَلَّمَ آدَمَ الْأَسْمَاءَ كُلَّهَا ثُمَّ عَرَ‌ضَهُمْ عَلَى الْمَلَائِكَةِ فَقَالَ أَنبِئُونِي بِأَسْمَاءِ هَـٰؤُلَاءِ إِن كُنتُمْ صَادِقِينَ',
                'english_text' => 'wa \'allama aadamal asmaaa\'a kullaha thumma \'araadahum \'alal malaaa\'ikati faqaala anbi-ooni bi-asmaaa\'i haaa\'ulaaa\'i in kuntum saadiqeen',
                'bangla_text'  => 'ওয়া আল্লামা আদামাল অসমাআ’ কুল্লাহা থুম্মা আরাদাহুম ‘আলাল মালা’ইকাতি ফাকালা অনবিঊনি বিআসমা’ই হা’উলাই ইন কুনতুম সাদিকীন।',
                'meaning'      => 'এবং আল্লাহ আদমকে সব নাম শেখায় তারপর তিনি তাদেরকে মালাইকারা দেখিয়ে দেন এবং বলেন, যদি তোমরা সত্যিক হয়, তাদের নামগুলো আমার জানাও।',
                'reference'    => 'কুরআন ২:৩১',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '32',
                'arabic_text'  => 'قَالُوا سُبْحَانَكَ لَا عِلْمَ لَنَا إِلَّا مَا عَلَّمْتَنَا ۖ إِنَّكَ أَنتَ الْعَلِيمُ الْحَكِيمُ',
                'english_text' => 'qaalu subhaanaka laa \'ilma lanaaa \'illaa maa \'allamtanaa; \'innaka \'anta al-\'aleemul hakeem',
                'bangla_text'  => 'কালু সুব্‌হানাকা লা আইল্মা লানা ইল্লা মা আল্লামতানা; ইন্নাকা আনতাল আলীমুল হাকীম।',
                'meaning'      => 'তারা বললো, তোমার পবিত্রতা তার প্রশংসা যে, আমাদের আছে তো শুধুমাত্র সেই জ্ঞান যেটা তুমি আমাদেরকে শেখায়েছ। নিশ্চয়, তুমি বিশ্বজ্ঞ ও জ্ঞানী।',
                'reference'    => 'কুরআন ২:৩২',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '33',
                'arabic_text'  => 'وَإِذْ قُلْنَا لِلْمَلَائِكَةِ اسْجُدُوا لِآدَمَ فَسَجَدُوا إِلَّا إِبْلِيسَ أَبَىٰ وَاسْتَكْبَرَ وَكَانَ مِنَ الْكَافِرِينَ',
                'english_text' => 'wa \'idh qulnaa lil-malaaa\'ikati \'usjudoo li-aadam fasajadoo \'illaaa iblisa \'abaa wastakbarawa wa kaana minal kaafireen',
                'bangla_text'  => 'ওয়া ইয়্যালাম কুলনা লিল মালা’ইকাতিস ইসজুদুলিআদাম ফাসজাদু ইল্লা ইবলিসা আবা ওয়াস্তাকবারা ওয়াকানা মিনাল কাফিরীন।',
                'meaning'      => 'এবং সময় যখন আমরা মালাইকাদের জন্য আদমকে সিজদাকরণী দেওয়ার নির্দেশ দিলাম, তারা সবাই সিজদা করল শুধু ইবলিস অপরিচিত হয়ে থাকা মত অস্বীকার করেন, প্রতিষ্ঠানের প্রতি অহঙ্কার অনুভব করেন, এবং তিনি কাফিরের অধিকারে থাকতেন।',
                'reference'    => 'কুরআন ২:৩৩',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '34',
                'arabic_text'  => 'وَقُلْنَا يَا آدَمُ اسْكُنْ أَنتَ وَزَوْجُكَ الْجَنَّةَ وَكُلَا مِنْهَا رَغَدًا حَيْثُ شِئْتُمَا وَلَا تَقْرَبَا هَـٰذِهِ الشَّجَرَةَ فَتَكُونَا مِنَ الظَّالِمِينَ',
                'english_text' => 'wa qulnaa yaaa aadamus kun \'anta wazawjuka al-jannata wa kulaaa minhaa raghadan haythu shi\'tumaa wa laa taqrabaa haadhihi ash-shajarata fatakoonaa minaz-zaalimeen',
                'bangla_text'  => 'ওয়া কুলনা ইয়া আদামু স্কুন আনত ওয়াজাউজুকাল জান্নাতা ওয়া কুলা মিনহা রাগদান হাইথু শিতুমা ওয়া লা তাকরাবা হা’য়যি আশ্শাজারাতা ফাতাকূনা মিনাজ্জালিমীন।',
                'meaning'      => 'আমরা বললাম, “হে আদম, তুমি এবং তোমার স্ত্রী জান্নাতে বাস করো এবং যেখানে ইচ্ছা তার প্রতিটি ফল খাও। কিন্তু এই গাছের কাছে আসা না করো, অন্যথায় তোমরা অত্যন্ত দুর্বল হবে।”',
                'reference'    => 'কুরআন ২:৩৪',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://www.youtube.com/watch?v=XXXXX',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '35',
                'arabic_text'  => 'فَأَزَلَّهُمَا الشَّيْطَانُ عَنْهَا فَأَخْرَ‌جَهُمَا مِمَّا كَانَا فِيهِ ۖ وَقُلْنَا اهْبِطُوا بَعْضُكُمْ لِبَعْضٍ عَدُوٌّ ۖ وَلَكُمْ فِي الْأَرْ‌ضِ مُسْتَقَرٌّ‌ وَمَتَاعٌ إِلَىٰ حِينٍ',
                'english_text' => 'fa-azallahumash-shaytaanu \'anhaa fa-akhrajahumaa mimmaa kaanaa feehi wa qulnaa ihbitoo ba\'dukum liba\'din \'aduww
un wa lakum fil-ardi mustaqarrunw wa mataa\'un ilaa hii',
                'bangla_text'  => 'ফা-আজাল্লাহুমা আশ্‌-শাইতানু আনহা ফা-আখরাজাহুমা মিম্মা কানা ফীহি ওয়া কুলনা ইহবিতু বা’দুকুম লিবা’দিন ‘আদুওন; ওয়া লাকুম ফিল-আর্‌দি মুস্তাকারুন ওয়া মাতা’উন ইলা হীন।',
                'meaning'      => 'তাহলে ইবলিস তাদেরকে জান্নাত থেকে প্রবৃদ্ধি করে দিল এবং তাদেরকে তার অংশ থেকে বের করে দিল। আমরা বললাম, "তোমরা এখন একে অপরের শত্রু। তোমাদের জন্য পৃথিবীতে আবাসন এবং একটি নির্দিষ্ট সময় পর্যন্ত সুখবর্জন আছে।"',
                'reference'    => 'কুরআন ২:৩৫',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://www.youtube.com/watch?v=XXXXX',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '36',
                'arabic_text'  => 'فَتَلَقَّىٰ آدَمُ مِن رَّ‌بِّهِ كَلِمَاتٍ فَتَابَ عَلَيْهِ ۚ إِنَّهُ هُوَ التَّوَّابُ الرَّ‌حِيمُ',
                'english_text' => 'fa-talaqqaa aadamu mir-rabbihi kalimaatin fataaba ‘alayhi; ‘innahu huwat-tawwaabur-raheem',
                'bangla_text'  => 'ফাতালাক্কা আদামু মিন রাব্বিহি কালিমাতিন ফাতাবা আলাইহি; ইন্নাহু হুয়াত্তাওয়াবুর-রাহীম।',
                'meaning'      => 'তারপর আদম তার প্রভুর কাছ থেকে কিছু কথা শুনে তার প্রভুর প্রতি তাওবা করলেন। নিশ্চয়ই তিনি তাওবা গ্রহণ করা সম্পর্কে অত্যন্ত করুণাময়ী।',
                'reference'    => 'কুরআন ২:৩৬',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://www.youtube.com/watch?v=XXXXX',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '37',
                'arabic_text'  => 'ثُمَّ اجْتَبَاهُ رَ‌بُّهُ فَتَابَ عَلَيْهِ وَهَدَىٰ',
                'english_text' => 'thumma ijtabaahu rabbuhu fataaba ‘alayhi wa hadaa',
                'bangla_text'  => 'তুম্মা ইজতাবাহু রাব্বুহু ফাতাবা আলাইহি ওয়া হাদা',
                'meaning'      => 'তারপর তার প্রভু তাকে বাছাই করেন এবং তার প্রতি তাওবা গ্রহণ করেন এবং তাকে হিদায়াত দিলেন।',
                'reference'    => 'কুরআন ২:৩৭',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://www.youtube.com/watch?v=XXXXX',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '38',
                'arabic_text'  => 'قُلْنَا اهْبِطُوا مِنْهَا جَمِيعًا ۖ فَإِمَّا يَأْتِيَنَّكُمْ مِنِّي هُدًى فَمَنِ اتَّبَعَ هُدَايَ فَلَا خَوْفٌ عَلَيْهِمْ وَلَا هُمْ يَحْزَنُونَ',
                'english_text' => 'qulnaa ihbitoo minhaa jamee\'an, fa\'immaa yatiyannakum minnee hudan famani ittaba\'a hudaya falaa khawfun \'alayhim wala hum yahzanoon',
                'bangla_text'  => 'কুলনা ইহ্‌বিতু মিনহা জামিআন। ফা-ইম্মা ইয়াতিয়ান্নাকুম মিন্নী হুদা, ফমানি ইত্তাবাআ হুদায় ফালা খওফুন \'আলায়্হিম ওয়ালা হুম ইয়াহ্‌জানূন।',
                'meaning'      => 'আমরা বললাম, “সবাই এখন জান্নাত থেকে অবতীর্ণ হও। যদি আমার হাদী তোমাদের উপর আসে, যার অনুসরণ করে সে কখনও ভয় ও দুঃখ পাবে না।"',
                'reference'    => 'কুরআন ২:৩৮',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg',
            ],
            [

                'sura_id'      => '2',
                'ayat_no'      => '39',
                'arabic_text'  => 'وَالَّذِينَ كَفَرُ‌وا وَكَذَّبُوا بِآيَاتِنَا أُولَـٰئِكَ أَصْحَابُ النَّارِ‌ ۖ هُمْ فِيهَا خَالِدُونَ',
                'english_text' => 'wallazeena kafaroo wa kazzaboo bi-aayaatinaa oolaaa-ika ashaabun-naar; hum feehaa khaalidoon',
                'bangla_text'  => 'ওয়াল্লাজীনা কাফারুও ওয়া কাজ্জাবু বি-আয়াতিনা উলাইকা আশাবুন্নার; হুম ফীহা খালিদুন।',
                'meaning'      => 'এবং যারা নাস্তিক হয়ে আমার আযাতগুলো মিথ্যা বলে তারা আগুনের বাসী। তারা সেখানে চিরকাল থাকবে।',
                'reference'    => 'কুরআন ২:৩৯',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://i.ytimg.com/vi/XXXXX',
            ],
            [
                'sura_id'      => '2',
                'ayat_no'      => '40',
                'arabic_text'  => 'يَا بَنِي إِسْرَ‌ائِيلَ اذْكُرُ‌وا نِعْمَتِيَ الَّتِي أَنْعَمْتُ عَلَيْكُمْ وَأَوْفُوا بِعَهْدِي أُوفِ بِعَهْدِكُمْ وَإِيَّايَ فَارْ‌هَبُونِ',
                'english_text' => 'yaa banee israaa-eela-udhkuroo ni’matiyallatee an’amtu ‘alaykum wa awfoo bi-‘ahdee oofee bi-‘ahdikum wa iyyaaya farhaboon',
                'bangla_text'  => 'ইয়া বানী ইসরাইল ইয়াদকুরু নিমাতিয়াল্লাতি অন’আমতু আলাইকুম ওয়া আউফু বি-আহদি উফী বি-আহদিকুম ওয়া ইয়্যায়ায়া ফারহাবুনি।',
                'meaning'      => 'হে ইসরাইলের সন্তানরা, আমার নিয়মগুলো মনে করো যে আমি তোমাদের উপর অনুগ্রহ করেছি এবং তোমাদের আমার প্রতি প্রতিশ্রুতি মেনে চলো এবং তোমাদের প্রতি তোমাদের প্রতিশ্রুতি মেনে চলো এবং আমার প্রতি ভয় পাও না।',
                'reference'    => 'কুরআন ২:৪০',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'https://server8.mp3quran.net/basit/002.mp3',
                'video'        => 'https://www.youtube.com/watch?v=XXXXX',
                'image'        => 'https://i.ytimg.com/vi/XXXXX',
            ],
            [

            ],

        ];
        DB::table('ayats')->insert($ayats);
    }
}

// fill a data structure with following array structure of sura no 2 ayat no from 38 to 40. english_text will be english_text's pronunciation and bangla_text will be also arabic_text pronunciation and meaning will be in bangla language. bangla_text is not bangla meaning it is the pronunciation of arabic_text in bangla language. give the reference of the ayat. status will be active. audio, video and image will be empty. you can use any audio, video and image URL. you can use the same URL for all the ayats. you can use the following array structure to fill the data structure.
// [
//                 'sura_id'      => '',
//                 'ayat_no'      => '',
//                 'arabic_text'  => '',
//                 'english_text' => '',
//                 'bangla_text'  => '',
//                 'meaning'      => '',
//                 'reference'    => '',
//                 'notes'        => '',
//                 'status'       => '',
//                 'audio'        => 'https://server8.mp3quran.net/basit/002.mp3', // Update with the correct audio URL
//                 'video' => 'https://www.youtube.com/watch?v=XXXXX', // Update with the correct video URL
//                 'image' => 'https://i.ytimg.com/vi/XXXXX/maxresdefault.jpg', // Update with the correct image URL
//             ],
