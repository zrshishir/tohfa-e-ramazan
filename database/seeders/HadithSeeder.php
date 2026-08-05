<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hadith;

class HadithSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hadiths = [
            [
                'title' => 'Fasting is a Shield',
                'description' => 'Fasting is a shield or protection from the fire and from committing sins.',
                'reference' => 'Sahih Bukhari',
                'status' => true,
            ],
            [
                'title' => 'Rewards of Ramadan',
                'description' => 'Whoever fasts during Ramadan out of sincere faith and hoping to attain Allah\'s rewards, then all his past sins will be forgiven.',
                'reference' => 'Sahih Bukhari',
                'status' => true,
            ]
        ];

        // Idempotent: re-running db:seed must not duplicate rows.
        foreach ($hadiths as $hadith) {
            Hadith::updateOrCreate(['title' => $hadith['title']], $hadith);
        }
    }
}
