<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            ['name' => 'Dhaka', 'country_id' => 18],
            ['name' => 'Chittagong', 'country_id' => 18],
            ['name' => 'Khulna', 'country_id' => 18],
            ['name' => 'Rajshahi', 'country_id' => 18],
            ['name' => 'Barisal', 'country_id' => 18],
            ['name' => 'Sylhet', 'country_id' => 18],
            ['name' => 'Rangpur', 'country_id' => 18],
            ['name' => 'Mymensingh', 'country_id' => 18],
        ];

        DB::table('divisions')->insert($divisions);
    }
}
