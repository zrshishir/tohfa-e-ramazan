<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            ['name' => 'Dhaka'],
            ['name' => 'Chittagong'],
            ['name' => 'Khulna'],
            ['name' => 'Rajshahi'],
            ['name' => 'Barisal'],
            ['name' => 'Sylhet'],
            ['name' => 'Rangpur'],
        ];

        DB::table('districts')->insert($divisions);
    }
}
