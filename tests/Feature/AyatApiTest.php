<?php

namespace Tests\Feature;

use App\Models\Ayat;
use App\Models\Sura;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AyatApiTest extends TestCase
{
    use RefreshDatabase;

    private Sura $sura;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sura = Sura::create([
            'name'         => 'Al-Baqarah',
            'arabic_name'  => 'البقرة',
            'english_name' => 'The Cow',
            'bangla_text'  => 'আল-বাকারা',
            'meaning'      => 'The Cow',
            'type'         => 'Madani',
            'ayat_count'   => 25,
            'status'       => '1',
        ]);

        for ($i = 1; $i <= 25; $i++) {
            Ayat::create([
                'sura_id'      => $this->sura->id,
                'ayat_no'      => $i,
                'arabic_text'  => "آية {$i}",
                'bangla_text'  => "আয়াত {$i}",
                'english_text' => "Verse {$i}",
                'meaning'      => '',
                'reference'    => "2:{$i}",
                'notes'        => '',
                'status'       => '1',
            ]);
        }
    }

    public function test_ayats_are_paginated(): void
    {
        $response = $this->getJson("/api/ayat/{$this->sura->id}")->assertOk();

        // Regression: the endpoint used to return every ayat of a sura in one response.
        $this->assertCount(10, $response->json('data.data'));
        $this->assertSame(25, $response->json('data.total'));
        $this->assertSame(10, $response->json('data.per_page'));
        $this->assertSame(3, $response->json('data.last_page'));
    }

    public function test_page_two_continues_the_sequence(): void
    {
        $page = $this->getJson("/api/ayat/{$this->sura->id}?page=2")->assertOk();

        $this->assertSame(11, $page->json('data.data.0.ayat_no'));
        $this->assertSame(2, $page->json('data.current_page'));
    }

    public function test_ayats_are_ordered_by_number(): void
    {
        $numbers = collect($this->getJson("/api/ayat/{$this->sura->id}")->json('data.data'))
            ->pluck('ayat_no')
            ->all();

        $sorted = $numbers;
        sort($sorted);

        $this->assertSame($sorted, $numbers);
    }

    public function test_per_page_is_honoured_and_capped(): void
    {
        $this->assertCount(5, $this->getJson("/api/ayat/{$this->sura->id}?per_page=5")->json('data.data'));

        // Anything above the cap falls back to 100 rather than dumping the table.
        $this->assertSame(100, $this->getJson("/api/ayat/{$this->sura->id}?per_page=5000")->json('data.per_page'));
    }

    public function test_query_string_is_preserved_in_pagination_links(): void
    {
        $links = $this->getJson("/api/ayat/{$this->sura->id}?per_page=5")->json('data.next_page_url');

        $this->assertStringContainsString('per_page=5', $links);
    }

    public function test_a_sura_with_no_ayats_returns_204(): void
    {
        $empty = Sura::create([
            'name' => 'Empty', 'arabic_name' => '-', 'english_name' => '-', 'bangla_text' => '-', 'meaning' => '-',
            'type' => 'Makki', 'ayat_count' => 0, 'status' => '1',
        ]);

        $this->getJson("/api/ayat/{$empty->id}")->assertNoContent(204);
    }
}
