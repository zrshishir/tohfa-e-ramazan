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
                'bangla_text'  => '',
                'english_text' => 'In the name of Allah, the Most Gracious, the Most Merciful.',
                'meaning'      => 'In the name of Allah, the Most Merciful and Most Compassionate.',
                'reference'    => 'Surah Al-Fatiha 1:1',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_1_1', // Update with the correct audio URL
                'video' => 'video_url_for_1_1', // Update with the correct video URL
                'image' => 'image_url_for_1_1', // Update with the correct image URL
            ],
            [
                'sura_id'      => 1,
                'ayat_no'      => 2,
                'arabic_text'  => 'الْحَمْدُ لِلَّهِ رَبِّ الْعَالَمِينَ',
                'bangla_text'  => '',
                'english_text' => 'All praise is due to Allah, Lord of the worlds.',
                'meaning'      => 'Praise be to Allah, the Lord of all the worlds.',
                'reference'    => 'Surah Al-Fatiha 1:2',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_1_2', // Update with the correct audio URL
                'video' => 'video_url_for_1_2', // Update with the correct video URL
                'image' => 'image_url_for_1_2', // Update with the correct image URL
            ],
            [
                'sura_id'      => 1,
                'ayat_no'      => 3,
                'arabic_text'  => 'الرَّحْمَٰنِ الرَّحِيمِ',
                'bangla_text'  => '',
                'english_text' => 'The Most Gracious, the Most Merciful.',
                'meaning'      => 'The Most Merciful and Most Compassionate.',
                'reference'    => 'Surah Al-Fatiha 1:3',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_1_3', // Update with the correct audio URL
                'video' => 'video_url_for_1_3', // Update with the correct video URL
                'image' => 'image_url_for_1_3', // Update with the correct image URL
            ],
            [
                'sura_id'      => 1,
                'ayat_no'      => 4,
                'arabic_text'  => 'مَالِكِ يَوْمِ الدِّينِ',
                'bangla_text'  => '',
                'english_text' => 'Master of the Day of Judgment.',
                'meaning'      => 'Owner of the Day of Recompense.',
                'reference'    => 'Surah Al-Fatiha 1:4',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_1_4', // Update with the correct audio URL
                'video' => 'video_url_for_1_4', // Update with the correct video URL
                'image' => 'image_url_for_1_4', // Update with the correct image URL
            ],
            [
                'sura_id'      => 1,
                'ayat_no'      => 5,
                'arabic_text'  => 'إِيَّاكَ نَعْبُدُ وَإِيَّاكَ نَسْتَعِينُ',
                'bangla_text'  => '',
                'english_text' => 'You alone we worship, and You alone we ask for help.',
                'meaning'      => 'You alone we worship, and You alone we ask for help.',
                'reference'    => 'Surah Al-Fatiha 1:5',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_1_5', // Update with the correct audio URL
                'video' => 'video_url_for_1_5', // Update with the correct video URL
                'image' => 'image_url_for_1_5', // Update with the correct image URL
            ],
            [
                'sura_id'      => 1,
                'ayat_no'      => 6,
                'arabic_text'  => 'اهْدِنَا الصِّرَاطَ الْمُسْتَقِيمَ',
                'bangla_text'  => '',
                'english_text' => 'Guide us on the Straight Path,',
                'meaning'      => 'Guide us on the Straight Path,',
                'reference'    => 'Surah Al-Fatiha 1:6',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_1_6', // Update with the correct audio URL
                'video' => 'video_url_for_1_6', // Update with the correct video URL
                'image' => 'image_url_for_1_6', // Update with the correct image URL
            ],
            [
                'sura_id'      => 1,
                'ayat_no'      => 7,
                'arabic_text'  => 'صِرَاطَ الَّذِينَ أَنْعَمْتَ عَلَيْهِمْ غَيْرِ الْمَغْضُوبِ عَلَيْهِمْ وَلَا الضَّالِّينَ',
                'bangla_text'  => '',
                'english_text' => 'the path of those who have received Your grace; not the path of those who have brought down wrath upon themselves, nor of those who have gone astray.',
                'meaning'      => 'the path of those who have received Your grace; not the path of those who have brought down wrath upon themselves, nor of those who have gone astray.',
                'reference'    => 'Surah Al-Fatiha 1:7',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_1_7', // Update with the correct audio URL
                'video' => 'video_url_for_1_7', // Update with the correct video URL
                'image' => 'image_url_for_1_7', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 1,
                'arabic_text'  => 'الم',
                'bangla_text'  => 'আলিফ ল্যাম্ মীম্',
                'english_text' => 'Alif Lam Meem.',
                'meaning'      => 'আলিফ ল্যাম্ মীম্',
                'reference'    => 'Surah Al-Baqarah 2:1',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_2_1', // Update with the correct audio URL
                'video' => 'video_url_for_2_1', // Update with the correct video URL
                'image' => 'image_url_for_2_1', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 2,
                'arabic_text'  => 'ذَٰلِكَ الْكِتَابُ لَا رَيْبَ ۛ فِيهِ ۛ هُدًى لِّلْمُتَّقِينَ',
                'bangla_text'  => 'যালিকাল কিতাবু লা রাইবা ফীহি হুদাল্লিল মুত্তাকীন',
                'english_text' => 'zalikal kitabu la rayba feehi hudal lil muttaqeen',
                'meaning'      => 'এইটা সেই কিতাব যাতে কোন সন্দেহ নেই এবং তাতে মুত্তাকীদের জন্য হেদায়েত রয়েছে।',
                'reference'    => 'Surah Al-Baqarah 2:2',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_2_2', // Update with the correct audio URL
                'video' => 'video_url_for_2_2', // Update with the correct video URL
                'image' => 'image_url_for_2_2', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 3,
                'arabic_text'  => 'الَّذِينَ يُؤْمِنُونَ بِالْغَيْبِ وَيُقِيمُونَ الصَّلَاةَ وَمِمَّا رَزَقْنَاهُمْ يُنفِقُونَ',
                'bangla_text'  => 'আল্লাদ্যীনা ইউমিনুনা বিল গাইবি ওয়া ইকীমুনাস সালাতা ওয়া মিম্মা রাজাকনাহুম ইউনফিকুন',
                'english_text' => 'allazeena yu’minoon bil ghaibi wa yuqeemoonas salata wa mimma razaqnaahum yunfiqoon',
                'meaning'      => 'যারা গায়েব বিষয়ে ঈমান রাখে এবং সালাত আদায় করে এবং আমরা তাদের রিজক দিয়েছি তার পরিমাণ খরচ করে।',
                'reference'    => 'Surah Al-Baqarah 2:3',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_2_3', // Update with the correct audio URL
                'video' => 'video_url_for_2_3', // Update with the correct video URL
                'image' => 'image_url_for_2_3', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 4,
                'arabic_text'  => 'وَالَّذِينَ يُؤْمِنُونَ بِمَا أُنزِلَ إِلَيْكَ وَمَا أُنزِلَ مِن قَبْلِكَ وَبِالْآخِرَةِ هُمْ يُوقِنُونَ',
                'bangla_text'  => 'ওয়াল্লাদ্যীনা ইউমিনুনা বিমা উনজিলা ইলাইকা ওয়া মা উনজিলা মিন কাবলিকা ওয়া বিল আখিরাতি হুম ইউকিনুন',
                'english_text' => 'wallazeena yu’minoon bima unzila ilaika wa ma unzila min qablika wa bil aakhirati hum yuqinoon',
                'meaning'      => 'এবং যারা তোমার কাছে নাযিল করা হয়েছে এবং তোমার আগে নাযিল করা হয়েছে এবং আখেরাতে তারা নিশ্চয়তা রাখে।',
                'reference'    => 'Surah Al-Baqarah 2:4',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_2_4', // Update with the correct audio URL
                'video' => 'video_url_for_2_4', // Update with the correct video URL
                'image' => 'image_url_for_2_4', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 5,
                'arabic_text'  => 'أُولَٰئِكَ عَلَىٰ هُدًى مِّن رَّبِّهِمْ ۖ وَأُولَٰئِكَ هُمُ الْمُفْلِحُونَ',
                'bangla_text'  => 'উলাইকা আলা হুদাম্মি মির রাব্বিহিম ওয়াউলাইকা হুমুল মুফলিহুন',
                'english_text' => 'ulaaika ala hudam mir rabbi him wa ulaaika humul muflihoon',
                'meaning'      => 'এই লোকরা তাদের প্রতি তাদের রবের হেদায়েতের উপর আছে এবং এই লোকরা সফল হবে।',
                'reference'    => 'Surah Al-Baqarah 2:5',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_2_5', // Update with the correct audio URL
                'video' => 'video_url_for_2_5', // Update with the correct video URL
                'image' => 'image_url_for_2_5', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 6,
                'arabic_text'  => 'إِنَّ الَّذِينَ كَفَرُوا سَوَاءٌ عَلَيْهِمْ أَأَنذَرْتَهُمْ أَمْ ل   مْ تُنذِرُهُمْ لَا يُؤْمِنُونَ',
                'bangla_text'  => 'ইন্নাল্লাদ্যীনা কাফারু সাওয়ায়ুন আলায়হিম আআনযারতাহুম আম লা তুনযিরুহুম লা ইউমিনুন',
                'english_text' => 'inna allazeena kafaroo sawaaon alaihim a anzartahum am lam tunzirhum laa yu’minoon',
                'meaning'      => 'যারা কাফের তারা তাদের প্রতি তুমি কি সতর্ক করেছ নাকি তুমি তাদের সতর্ক করেছ নাকি তারা ঈমান না রাখে।',
                'reference'    => 'Surah Al-Baqarah 2:6',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_2_6', // Update with the correct audio URL
                'video' => 'video_url_for_2_6', // Update with the correct video URL
                'image' => 'image_url_for_2_6', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 7,
                'arabic_text'  => 'خَتَمَ اللَّهُ عَلَىٰ قُلُوبِهِمْ وَعَلَىٰ سَمْعِهِمْ ۖ وَعَلَىٰ أَبْصَارِهِمْ غِشَاوَةٌ ۖ وَلَهُمْ عَذَابٌ عَظِيمٌ',
                'bangla_text'  => 'খতামাল্লাহু আলা কুলুবিহিম ওয়া আলা সামি’হিম ওয়া আলা আবছারিহিম গিশাওতুন ওয়ালাহুম আযাবুন আযীম',
                'english_text' => 'khatamal laahu alaa quloobihim wa alaa sam’ihim wa alaa absaarihim gishaawatun wa lahum azaabun azeem',
                'meaning'      => 'আল্লাহ তাদের হৃদয়, কান ও চোখ বন্ধ করে দিয়েছেন এবং তাদের জন্য বড় আযাব আছে।',
                'reference'    => 'Surah Al-Baqarah 2:7',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_2_7', // Update with the correct audio URL
                'video' => 'video_url_for_2_7', // Update with the correct video URL
                'image' => 'image_url_for_2_7', // Update
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 8,
                'arabic_text'  => 'وَمِنَ النَّاسِ مَن يَقُولُ آمَنَّا بِاللَّهِ وَبِالْيَوْمِ الْآخِرِ وَمَا هُم بِمُؤْمِنِينَ',
                'bangla_text'  => 'ওয়ামিনাল্লাসি মান ইয়াকুলু আমান্না বিল্লাহি ওয়াবিল ইয়াউমিল আখিরি ওয়া মা হুম বিমুমিনীন',
                'english_text' => 'wa minal naasi mai yaqoolu aamannaa billaahi wa bil yaumil aakhiri wa maa hum bimu’mineen',
                'meaning'      => 'এবং মানুষের মধ্যে যারা বলে আমরা আল্লাহ ও আগামী দিনে ঈমান রাখি তারা ঈমান রাখে না।',
                'reference'    => 'Surah Al-Baqarah 2:8',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_2_8', // Update with the correct audio URL
                'video' => 'video_url_for_2_8', // Update with the correct video URL
                'image' => 'image_url_for_2_8', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 9,
                'arabic_text'  => 'يُخَادِعُونَ اللَّهَ وَالَّذِينَ آمَنُوا وَمَا يَخْدَعُونَ إِلَّا أَنفُسَهُمْ وَمَا يَشْعُرُونَ',
                'bangla_text'  => 'ইয়ুখাদি’উনাল্লাহা ওয়াল্লাদ্যীনা আমানু ওয়া মা ইয়াখদি’উনা ইল্লা আনফুসাহুম ওয়া মা ইয়াশ’উরুন',
                'english_text' => 'yukhaadi’oona allaaha wallazeena aamanoo wa maa yakhda’oona illaa anfusahum wa maa yash’uroon',
                'meaning'      => 'তারা আল্লাহকে ও যারা ঈমান রাখে তাদের সঙ্গে ছলনা করে এবং তারা কেবল নিজেদের সঙ্গে ছলনা করে এবং তারা সম্পূর্ণরূপে জানে না।',
                'reference'    => 'Surah Al-Baqarah 2:9',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_2_9', // Update with the correct audio URL
                'video' => 'video_url_for_2_9', // Update with the correct video URL
                'image' => 'image_url_for_2_9', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 10,
                'arabic_text'  => 'فِي قُلُوبِهِم مَّرَضٌ فَزَادَهُمُ اللَّهُ مَرَضًا ۖ وَلَهُمْ عَذَابٌ أَلِيمٌ بِمَا كَانُوا يَكْذِبُونَ',
                'bangla_text'  => 'ফী কুলুবিহিম মারাদুন ফাযা জাদাহুমুল্লাহু মারাদান ওয়ালাহুম আযাবুন আলীমুন বিমা কানু ইয়াকযিবুন',
                'english_text' => 'fee quloobihim maradun fazadahumul laahu marazan wa lahum azaabun aleemun bimaa kaanoo yakziboon',
                'meaning'      => 'তাদের হৃদয়ে রোগ আছে এবং আল্লাহ তাদের রোগ বাড়িয়ে দিয়েছেন এবং তাদের জন্য বড় আযাব আছে কারণ তারা মিথ্যা বলে থেকেছে।',
                'reference'    => 'Surah Al-Baqarah 2:10',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_2_10', // Update with the correct audio URL
                'video' => 'video_url_for_2_10', // Update with the correct video URL
                'image' => 'image_url_for_2_10', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 11,
                'arabic_text'  => 'وَإِذَا قِيلَ لَهُمْ لَا تُفْسِدُوا فِي الْأَرْضِ قَالُوا إِنَّمَا نَحْنُ مُصْلِحُونَ',
                'bangla_text'  => 'ওয়া ইযাজা কীলা লাহুম লা তুফসিদু ফীল আরদি কালু ইন্নামা নাহনু মুছলিহুন',
                'english_text' => 'wa izaa qeela lahum laa tufsidoo fil ardi qaaloo innamaa nahnu muslihoon',
                'meaning'      => 'এবং যখন তাদের বলা হয় তোমরা যমজমাত করো তাদের উত্তর হয় আমরা তো শুধু সংসার সংসার করি।',
                'reference'    => 'Surah Al-Baqarah 2:11',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_2_11', // Update with the correct audio URL
                'video' => 'video_url_for_2_11', // Update with the correct video URL
                'image' => 'image_url_for_2_11', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 12,
                'arabic_text'  => 'أَلَا إِنَّهُمْ هُمُ الْمُفْسِدُونَ وَلَٰكِن لَّا يَشْعُرُونَ',
                'bangla_text'  => 'আলা ইন্নাহুম হুমুল মুফসিদুনা ওয়ালাকিন লা ইয়াশ’উরুন',
                'english_text' => 'alaa innahum humul mufsideena wa laakin laa yash’uroon',
                'meaning'      => 'হ্যাঁ নিশ্চয় তারা সংসার সংসার করে এবং তারা জানে না।',
                'reference'    => 'Surah Al-Baqarah 2:12',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_2_12', // Update with the correct audio URL
                'video' => 'video_url_for_2_12', // Update with the correct video URL
                'image' => 'image_url_for_2_12', // Update with the correct image URL
            ],
            [
                'sura_id'      => 2,
                'ayat_no'      => 13,
                'arabic_text'  => 'وَإِذَا قِيلَ لَهُمْ آمِنُوا كَمَا آمَنَ النَّاسُ قَالُوا أَنُؤْمِنُ كَمَا آمَنَ السُّفَهَاءُ ۗ أَلَا إِنَّهُمْ هُمُ السُّفَهَاءُ وَلَٰكِن لَّا يَعْلَمُونَ',
                'bangla_text'  => 'ওয়া ইযাজা কীলা লাহুম আমিনু কামা আমানা ন্নাসু কালু ইনুমিনু কামা আমানা সুফাহা আলা ইন্নাহুম হুমুস সুফাহা ওয়ালাকিন লা ইয়ালামুন',
                'english_text' => 'wa izaa qeela lahum aaminoo kamaa aamana an naasu qaaloo anu’minu kamaa aamana sufahaaa alaa innahum humus sufahaaa wa laakin laa ya’lamoon',
                'meaning'      => 'এবং যখন তাদের বলা হয় তোমরা যমজমাত করো তাদের উত্তর হয় আমরা তো শুধু সংসার সংসার করি।',
                'reference'    => 'Surah Al-Baqarah 2:13',
                'notes'        => '',
                'status'       => 'active',
                'audio'        => 'audio_url_for_2_13', // Update with the correct audio URL
                'video' => 'video_url_for_2_13', // Update with the correct video URL
                'image' => 'image_url_for_2_13', // Update with the correct image URL
            ],
        ];

        DB::table('ayats')->insert($ayats);
    }
}
