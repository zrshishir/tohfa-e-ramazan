<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsmaulHusnaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $asmaulHusnas = [
            [
                'arabic_name'  => 'الله',
                'bangla_name'  => 'আল্লাহ',
                'english_name' => 'Allah',
                'meaning'      => 'আল্লাহ',
                'description'  => 'আল্লাহর সকল নামের মধ্যে সবচেয়ে বড় নাম',
            ],
            [
                'arabic_name'  => 'الرحمن',
                'bangla_name'  => 'আর রহমান',
                'english_name' => 'Ar-Rahman',
                'meaning'      => 'অতি দয়ালু',
                'description'  => 'যিনি সকল সৃষ্টির উপর তাঁর দয়া বর্ষাতে থাকেন।',
            ],
            [
                'arabic_name'  => 'الرحيم',
                'bangla_name'  => 'আর রহীম',
                'english_name' => 'Ar-Rahim',
                'meaning'      => 'অতি দয়ালু',
                'description'  => 'যিনি সকল সৃষ্টির উপর তাঁর দয়া বর্ষাতে থাকেন।',
            ],
            [
                'arabic_name'  => 'الملك',
                'bangla_name'  => 'আল মালিক',
                'english_name' => 'Al-Malik',
                'meaning'      => 'সর্বশক্তিমান',
                'description'  => 'যিনি সকল সৃষ্টির উপর তাঁর দয়া বর্ষাতে থাকেন।',
            ],
            [
                'arabic_name'  => 'القدوس',
                'bangla_name'  => 'আল কুদ্দুস',
                'english_name' => 'Al-Quddus',
                'meaning'      => 'পবিত্র',
                'description'  => 'যিনি সকল সৃষ্টির উপর তাঁর দয়া বর',
            ],
            [
                'arabic_name'  => 'السلام',
                'bangla_name'  => 'আস সালাম',
                'english_name' => 'As-Salam',
                'meaning'      => 'শান্তির সৃষ্টিকর্তা',
                'description'  => 'যিনি সকল সৃষ্টির উপর তাঁর দয়া বর্ষাতে থাকেন।',
            ],
            [
                'arabic_name'  => 'المؤمن',
                'bangla_name'  => 'আল মুমিন',
                'english_name' => 'Al-Mu\'min',
                'meaning'      => 'ঈমানদারদের নিরাপদ রক্ষক',
                'description'  => 'যিনি সকল সৃষ্টির উপর তাঁর দয়া বর্ষাতে থাকেন।',
            ],
            [
                'arabic_name'  => 'المهيمن',
                'bangla_name'  => 'আল মুহাইমিন',
                'english_name' => 'Al-Muhaymin',
                'meaning'      => 'সকল সৃষ্টির নিরাপদ রক্ষক ও পরিচালক',
                'description'  => 'যিনি সকল সৃষ্টির উপর তাঁর দয়া বর',
            ],
            [
                'arabic_name'  => 'العزيز',
                'bangla_name'  => 'আল আজিজ',
                'english_name' => 'Al-Aziz',
                'meaning'      => 'অতি শক্তিশালী ও অবিচল',
                'description'  => 'যিনি সকল সৃষ্টির উপর তাঁর দয়া ব',
            ],
            [
                'arabic_name'  => 'الجبار',
                'bangla_name'  => 'আল জাব্বার',
                'english_name' => 'Al-Jabbar',
                'meaning'      => 'সকল সৃষ্টির উপর পরাজিত করার ক্ষমতা ও শক্তি যিনি পালন করেন।',
                'description'  => 'যিনি সকল সৃষ্টির উপর পরাজিত করার ক্ষমতা ও শক্তি যিনি পালন করেন।',
            ],
            [
                'arabic_name'  => 'المتكبر',
                'bangla_name'  => 'আল মুতাকাব্বির',
                'english_name' => 'Al-Mutakabbir',
                'meaning'      => 'অতি মহান ও অতিশ্রেষ্ঠ',
                'description'  => 'যিনি সকল সৃষ্টির উপর পরাজিত করার ক্ষমতা ও শক্তি যিনি পালন করেন',
            ],
            [
                'arabic_name'  => 'الخالق',
                'bangla_name'  => 'আল খালিক',
                'english_name' => 'Al-Khaliq',
                'meaning'      => 'সকল সৃষ্টির সৃষ্টিকর্তা',
                'description'  => 'যিনি সকল সৃষ্টির সৃষ্টিকর্তা',
            ],
            [
                'arabic_name'  => 'البارئ',
                'bangla_name'  => 'আল বারি',
                'english_name' => 'Al-Bari\'',
                'meaning'      => 'সকল সৃষ্টির সৃষ্টিকর্তা',
                'description'  => 'যিনি সকল সৃষ্টির সৃষ্টিকর্তা',
            ],
            [
                'arabic_name'  => 'المصور',
                'bangla_name'  => 'আল মুসাওয়ীর',
                'english_name' => 'Al-Musawwir',
                'meaning'      => 'সকল সৃষ্টির সৃষ্টিকর্তা',
                'description'  => 'যিনি সকল সৃষ্টির সৃষ্টিকর্তা',
            ],
            [
                'arabic_name'  => 'الغفار',
                'bangla_name'  => 'আল গাফফার',
                'english_name' => 'Al-Ghaffar',
                'meaning'      => 'অতি ক্ষমাশীল',
                'description'  => 'যিনি সকল সৃষ্টির সৃষ্টিকর্তা',
            ],
            [
                'arabic_name'  => 'القهار',
                'bangla_name'  => 'আল কাহার',
                'english_name' => 'Al-Qahhar',
                'meaning'      => 'সকল সৃষ্টির উপর পরাজিত করার ক্ষমতা ও শক্তি যিনি পালন করেন',
                'description'  => 'যিনি সকল সৃষ্টির উপর পরাজিত করার ক্ষমতা ও শক্তি যিনি পালন করেন',
            ],
            [
                'arabic_name'  => 'الوهاب',
                'bangla_name'  => 'আল ওয়াহ্হাব',
                'english_name' => 'Al-Wahhab',
                'meaning'      => 'অতি দাতা ও উদার',
                'description'  => 'যিনি সকল সৃষ্টির উপর পরাজিত করার ক্ষমতা ও শক্তি যিনি পালন করেন',
            ],
            [
                'arabic_name'  => 'الرزاق',
                'bangla_name'  => 'আর রজ্জাক',
                'english_name' => 'Ar-Razzaq',
                'meaning'      => 'সকল সৃষ্টির উপর পরাজিত করার ক্ষমতা ও শক্তি যিনি পালন করেন',
                'description'  => 'যিনি সকল সৃষ্টির উপর পরাজিত করার ক্ষমতা ও শক্তি যিনি পালন করেন',

            ],
        ];

        DB::table('asmaul_husnas')->insert($asmaulHusnas);
    }
}
