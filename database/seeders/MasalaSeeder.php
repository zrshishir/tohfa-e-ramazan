<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Masala;

class MasalaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $masalas = [
            [
                'title' => 'Intention for Fasting',
                'description' => 'The intention for obligatory fasting (like Ramadan) must be made before dawn.',
                'reference' => 'Fatawa Alamgiri',
                'status' => true,
            ],
            [
                'title' => 'Eating Forgetfully',
                'description' => 'If a person eats or drinks forgetfully, their fast is not broken.',
                'reference' => 'Hedaya',
                'status' => true,
            ]
        ];

        // Idempotent: re-running db:seed must not duplicate rows.
        foreach ($masalas as $masala) {
            Masala::updateOrCreate(['title' => $masala['title']], $masala);
        }
    }
}
