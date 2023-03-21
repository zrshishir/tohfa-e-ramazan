<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            ['division_id' => 1, 'name' => 'Dhaka'],
            ['division_id' => 1, 'name' => 'Faridpur'],
            ['division_id' => 1, 'name' => 'Gazipur'],
            ['division_id' => 1, 'name' => 'Gopalganj'],
            ['division_id' => 1, 'name' => 'Kishoreganj'],
            ['division_id' => 1, 'name' => 'Madaripur'],
            ['division_id' => 1, 'name' => 'Manikganj'],
            ['division_id' => 1, 'name' => 'Munshiganj'],
            ['division_id' => 1, 'name' => 'Narayanganj'],
            ['division_id' => 1, 'name' => 'Narsingdi'],
            ['division_id' => 1, 'name' => 'Rajbari'],
            ['division_id' => 1, 'name' => 'Shariatpur'],
            ['division_id' => 1, 'name' => 'Tangail'],
            ['division_id' => 2, 'name' => 'Bandarban'],
            ['division_id' => 2, 'name' => 'Brahmanbaria'],
            ['division_id' => 2, 'name' => 'Chandpur'],
            ['division_id' => 2, 'name' => 'Chittagong'],
            ['division_id' => 2, 'name' => 'Comilla'],
            ['division_id' => 2, 'name' => 'Cox\'s Bazar'],
            ['division_id' => 2, 'name' => 'Feni'],
            ['division_id' => 2, 'name' => 'Khagrachari'],
            ['division_id' => 2, 'name' => 'Lakshmipur'],
            ['division_id' => 2, 'name' => 'Noakhali'],
            ['division_id' => 2, 'name' => 'Rangamati'],
            ['division_id' => 3, 'name' => 'Bagerhat'],
            ['division_id' => 3, 'name' => 'Chuadanga'],
            ['division_id' => 3, 'name' => 'Jessore'],
            ['division_id' => 3, 'name' => 'Jhenaidah'],
            ['division_id' => 3, 'name' => 'Khulna'],
            ['division_id' => 3, 'name' => 'Kushtia'],
            ['division_id' => 3, 'name' => 'Magura'],
            ['division_id' => 3, 'name' => 'Meherpur'],
            ['division_id' => 3, 'name' => 'Narail'],
            ['division_id' => 3, 'name' => 'Satkhira'],
            ['division_id' => 4, 'name' => 'Bogra'],
            ['division_id' => 4, 'name' => 'Joypurhat'],
            ['division_id' => 4, 'name' => 'Naogaon'],
            ['division_id' => 4, 'name' => 'Natore'],
            ['division_id' => 4, 'name' => 'Nawabganj'],
            ['division_id' => 4, 'name' => 'Pabna'],
            ['division_id' => 4, 'name' => 'Rajshahi'],
            ['division_id' => 4, 'name' => 'Sirajganj'],
            ['division_id' => 5, 'name' => 'Dinajpur'],
            ['division_id' => 5, 'name' => 'Gaibandha'],
            ['division_id' => 5, 'name' => 'Kurigram'],
            ['division_id' => 5, 'name' => 'Lalmonirhat'],
            ['division_id' => 5, 'name' => 'Nilphamari'],
            ['division_id' => 5, 'name' => 'Panchagarh'],
            ['division_id' => 5, 'name' => 'Rangpur'],
            ['division_id' => 5, 'name' => 'Thakurgaon'],
            ['division_id' => 6, 'name' => 'Habiganj'],
            ['division_id' => 6, 'name' => 'Moulvibazar'],
            ['division_id' => 6, 'name' => 'Sunamganj'],
            ['division_id' => 6, 'name' => 'Sylhet'],
        ];

        DB::table('districts')->insert($districts);
    }
}
