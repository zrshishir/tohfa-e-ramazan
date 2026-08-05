<?php

namespace Tests\Feature;

use App\Models\Hadith;
use App\Models\Masala;
use Database\Seeders\HadithSeeder;
use Database\Seeders\MasalaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_hadith_seeder_populates_the_table(): void
    {
        $this->seed(HadithSeeder::class);

        $this->assertGreaterThan(0, Hadith::count());
    }

    public function test_masala_seeder_populates_the_table(): void
    {
        $this->seed(MasalaSeeder::class);

        $this->assertGreaterThan(0, Masala::count());
    }

    public function test_seeders_are_idempotent(): void
    {
        $this->seed(HadithSeeder::class);
        $this->seed(MasalaSeeder::class);

        $hadithCount = Hadith::count();
        $masalaCount = Masala::count();

        // Re-running db:seed must not duplicate content rows.
        $this->seed(HadithSeeder::class);
        $this->seed(MasalaSeeder::class);

        $this->assertSame($hadithCount, Hadith::count());
        $this->assertSame($masalaCount, Masala::count());
    }
}
