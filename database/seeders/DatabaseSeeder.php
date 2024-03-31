<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // write code to link CountrySeeder to this class
        // sql query to delete all tables of database

        $this->call([
            UsersTableSeeder::class,
            CountriesSeeder::class,
            DivisionSeeder::class,
            DistrictSeeder::class,
            MonthSeeder::class,
            PermanentCalendarSeeder::class,
            DoaCategorySeeder::class,
            MazhabTableSeeder::class,
            MazhabWiseScheduleSettingSeeder::class,
            TasbihTableSeeder::class,
            DoaSeeder::class,
            SuraTableSeeder::class,
            AyatTableSeeder::class,
            AsmaulHusnaTableSeeder::class,
            DistrictWiseTableSeeder::class,
        ]);
    }
}
