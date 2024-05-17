<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MazhabTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mazhabs = [
            [
                'user_id'     => 1,
                'name'        => 'Hanafi',
                'bangla_text' => 'হানাফি',
                'arabic_text' => 'حنفي',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Shafi\'i',
                'bangla_text' => 'শাফেই',
                'arabic_text' => 'شافعي',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Maliki',
                'bangla_text' => 'মালেকি',
                'arabic_text' => 'مالكي',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Hanbali',
                'bangla_text' => 'হানবলি',
                'arabic_text' => 'حنبلي',
            ],
        ];

        DB::table('mazhabs')->insert($mazhabs);
    }
}
