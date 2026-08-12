<?php

namespace Tests\Feature;

use Database\Seeders\AyatTableSeeder;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * alquran.cloud answers 200 with the Arabic Quran when an edition identifier does not
 * exist. Without a guard, a typo silently seeds Arabic into a translation column.
 */
class AyatSeederGuardTest extends TestCase
{
    private function edition(string $text): array
    {
        return ['code' => 200, 'status' => 'OK', 'data' => ['surahs' => [[
            'number' => 1, 'englishName' => 'Al-Faatiha',
            'ayahs' => [['number' => 1, 'numberInSurah' => 1, 'text' => $text]],
        ]]]];
    }

    private function fetchVia(string $edition, ?array $arabic): ?array
    {
        $seeder = new AyatTableSeeder();
        $seeder->setCommand($this->createMock(\Illuminate\Console\Command::class));

        $method = new \ReflectionMethod($seeder, 'fetchEdition');
        $method->setAccessible(true);

        return $method->invoke($seeder, $edition, $arabic);
    }

    public function test_it_rejects_an_edition_that_returns_the_arabic_text(): void
    {
        Http::fake(['*' => Http::response($this->edition('بِسْمِ اللَّهِ'))]);

        $arabic = ['surahs' => [['ayahs' => [['text' => 'بِسْمِ اللَّهِ']]]]];

        $this->assertNull($this->fetchVia('bn.transliteration', $arabic));
    }

    public function test_it_accepts_a_genuine_translation(): void
    {
        Http::fake(['*' => Http::response($this->edition('শুরু করছি আল্লাহর নামে'))]);

        $arabic = ['surahs' => [['ayahs' => [['text' => 'بِسْمِ اللَّهِ']]]]];

        $this->assertNotNull($this->fetchVia('bn.bengali', $arabic));
    }

    public function test_the_arabic_edition_itself_is_never_rejected(): void
    {
        Http::fake(['*' => Http::response($this->edition('بِسْمِ اللَّهِ'))]);

        $arabic = ['surahs' => [['ayahs' => [['text' => 'بِسْمِ اللَّهِ']]]]];

        $this->assertNotNull($this->fetchVia('quran-uthmani', $arabic));
    }
}
