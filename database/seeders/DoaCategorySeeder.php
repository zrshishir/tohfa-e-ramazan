<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ['user_id' => 1, 'name' => 'Salat'],
            ['user_id' => 1, 'name' => 'Siam'],
            ['user_id' => 1, 'name' => 'Zikir'],
            ['user_id' => 1, 'name' => 'Every Day'],
        ];

        DB::table('doa_categories')->insert($doaCategories);
    }
}
