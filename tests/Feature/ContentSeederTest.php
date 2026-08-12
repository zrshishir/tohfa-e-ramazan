<?php

namespace Tests\Feature;

use App\Models\Masala;
use Database\Seeders\MasalaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_masala_seeder_populates_the_table(): void
    {
        $this->seed(MasalaSeeder::class);

        $this->assertGreaterThan(0, Masala::count());
    }

    public function test_seeder_is_idempotent(): void
    {
        $this->seed(MasalaSeeder::class);
        $count = Masala::count();

        // Re-running db:seed must not duplicate content rows.
        $this->seed(MasalaSeeder::class);

        $this->assertSame($count, Masala::count());
    }

    /**
     * Hadith content is no longer seeded. The Siha Sittah is ~34k hadiths pulled over
     * the network by `php artisan hadith:import`, which has no business running on
     * every `db:seed`. Coverage lives in HadithImportTest and HadithApiTest.
     */
    public function test_hadith_seeder_no_longer_exists(): void
    {
        $this->assertFalse(
            class_exists(\Database\Seeders\HadithSeeder::class),
            'HadithSeeder was replaced by the hadith:import command.'
        );
    }
}
