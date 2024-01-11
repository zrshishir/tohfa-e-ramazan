<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ['name' => 'Hanafi'],
            ['name' => 'Shafi'],
            ['name' => 'Maliki'],
            ['name' => 'Hanbali'],
        ];

        DB::table('mazhabs')->insert($mazhabs);
    }
}
