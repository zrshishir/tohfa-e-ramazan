<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoaCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doaCategories = [
            [
                'user_id'     => 1,
                'name'        => 'Salat',
                'bangla_text' => 'নামাজ',
                'arabic_text' => 'الصلاة',
                'icon'        => 'fas fa-praying-hands',
                'url'         => '',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Siam',
                'bangla_text' => 'রোজা',
                'arabic_text' => 'الصيام',
                'icon'        => 'fas fa-star-and-crescent',
                'url'         => '',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Zikr',
                'bangla_text' => 'যিকর',
                'arabic_text' => 'الذِّكْر',
                'icon'        => 'fas fa-praying-hands',
                'url'         => '',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Hajj',
                'bangla_text' => 'হজ্জ',
                'arabic_text' => 'الحج',
                'icon'        => 'fas fa-praying-hands',
                'url'         => '',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Ruquiya',
                'bangla_text' => 'রুকইয়া',
                'arabic_text' => 'الرقية',
                'icon'        => 'fas fa-praying-hands',
                'url'         => '',
            ],
        ];

        DB::table('doa_categories')->insert($doaCategories);
    }
}