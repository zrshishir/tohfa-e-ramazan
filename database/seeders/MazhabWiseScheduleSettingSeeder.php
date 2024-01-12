<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MazhabWiseScheduleSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mazhabWiseScheduleSettings = [
            [
                'mazhab_id' => 1,
                'sehri_time' => 15,
                'fazr_time' => 15,
                'ishraq_time' => 15,
                'johr_time' => 15,
                'asr_time' => 15,
                'magrib_time' => 15,
                'iftar_time' => 15,
                'esha_time' => 15,
            ],
            [
                'mazhab_id' => 2,
                'sehri_time' => 10,
                'fazr_time' => 10,
                'ishraq_time' => 10,
                'johr_time' => 10,
                'asr_time' => 10,
                'magrib_time' => 10,
                'iftar_time' => 10,
                'esha_time' => 10,
            ],
            [
                'mazhab_id' => 3,
                'sehri_time' => 5,
                'fazr_time' => 5,
                'ishraq_time' => 5,
                'johr_time' => 5,
                'asr_time' => 5,
                'magrib_time' => 5,
                'iftar_time' => 5,
                'esha_time' => 5,
            ],
            [
                'mazhab_id' => 4,
                'sehri_time' => 7,
                'fazr_time' => 7,
                'ishraq_time' => 7,
                'johr_time' => 7,
                'asr_time' => 7,
                'magrib_time' => 7,
                'iftar_time' => 7,
                'esha_time' => 7,
            ],
        ];

        DB::table('mazhab_wise_schedule_settings')->insert($mazhabWiseScheduleSettings);
    }
}