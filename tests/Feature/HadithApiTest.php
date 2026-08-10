<?php

namespace Tests\Feature;

use App\Models\Hadith;
use App\Models\HadithBook;
use App\Models\HadithChapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HadithApiTest extends TestCase
{
    use RefreshDatabase;

    private HadithBook $book;
    private HadithChapter $chapter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->book = HadithBook::create([
            'slug' => 'bukhari', 'name_en' => 'Sahih al-Bukhari', 'name_bn' => 'সহীহ বুখারী',
            'name_ar' => 'صحيح البخاري', 'author' => 'Imam al-Bukhari', 'sort_order' => 1, 'status' => true,
        ]);

        $this->chapter = HadithChapter::create([
            'hadith_book_id' => $this->book->id, 'chapter_no' => 1,
            'name_en' => 'Revelation', 'name_bn' => 'ওহীর সূচনা', 'name_ar' => 'الوحي', 'status' => true,
        ]);

        foreach ([1 => 'Actions are by intentions', 2 => 'The believer is a mirror', 3 => 'Faith has branches'] as $n => $en) {
            Hadith::create([
                'hadith_book_id' => $this->book->id,
                'hadith_chapter_id' => $this->chapter->id,
                'hadith_number' => $n,
                'arabic_text' => "عربي {$n}",
                'bangla_text' => "বাংলা {$n}",
                'english_text' => $en,
                'grade' => 'Sahih',
                'status' => true,
            ]);
        }
    }

    // ----------------------------------------------------------------- books

    public function test_books_endpoint_lists_books(): void
    {
        $this->getJson('/api/hadith-books')
            ->assertOk()
            ->assertJsonPath('status', 'Success')
            ->assertJsonPath('data.0.slug', 'bukhari')
            ->assertJsonPath('data.0.name_bn', 'সহীহ বুখারী');
    }

    public function test_books_endpoint_hides_unpublished_books(): void
    {
        $this->book->update(['status' => false]);

        $this->getJson('/api/hadith-books')->assertNoContent(204);
    }

    // -------------------------------------------------------------- chapters

    public function test_chapters_endpoint_returns_book_and_chapters(): void
    {
        $this->getJson("/api/hadith-books/{$this->book->id}/chapters")
            ->assertOk()
            ->assertJsonPath('data.book.slug', 'bukhari')
            ->assertJsonPath('data.chapters.0.name_bn', 'ওহীর সূচনা');
    }

    public function test_chapters_endpoint_404s_for_an_unknown_book(): void
    {
        $this->getJson('/api/hadith-books/9999/chapters')->assertNotFound();
    }

    // ----------------------------------------------------------------- index

    public function test_index_is_paginated(): void
    {
        $response = $this->getJson('/api/hadith?per_page=2')->assertOk();

        $this->assertCount(2, $response->json('data.data'));
        $this->assertSame(3, $response->json('data.total'));
        $this->assertSame(2, $response->json('data.per_page'));
    }

    public function test_index_filters_by_book(): void
    {
        $other = HadithBook::create(['slug' => 'muslim', 'name_en' => 'Sahih Muslim', 'sort_order' => 2, 'status' => true]);
        Hadith::create([
            'hadith_book_id' => $other->id, 'hadith_number' => 1,
            'english_text' => 'From Muslim', 'status' => true,
        ]);

        $response = $this->getJson("/api/hadith?book_id={$other->id}")->assertOk();

        $this->assertSame(1, $response->json('data.total'));
        $this->assertSame('From Muslim', $response->json('data.data.0.english_text'));
    }

    public function test_index_filters_by_chapter(): void
    {
        $empty = HadithChapter::create([
            'hadith_book_id' => $this->book->id, 'chapter_no' => 2, 'name_en' => 'Belief', 'status' => true,
        ]);

        $this->getJson("/api/hadith?chapter_id={$empty->id}")->assertNoContent(204);
        $this->getJson("/api/hadith?chapter_id={$this->chapter->id}")
            ->assertOk()
            ->assertJsonPath('data.total', 3);
    }

    public function test_index_searches_english_and_bangla(): void
    {
        $this->getJson('/api/hadith?q=intentions')
            ->assertOk()
            ->assertJsonPath('data.total', 1)
            ->assertJsonPath('data.data.0.hadith_number', 1);

        $this->getJson('/api/hadith?q=' . urlencode('বাংলা ২'))->assertNoContent(204);

        $this->getJson('/api/hadith?q=' . urlencode('বাংলা'))
            ->assertOk()
            ->assertJsonPath('data.total', 3);
    }

    public function test_search_rejects_a_too_short_term(): void
    {
        $this->getJson('/api/hadith?q=a')
            ->assertStatus(422)
            ->assertJsonValidationErrors('q');
    }

    public function test_index_rejects_an_unknown_book_filter(): void
    {
        $this->getJson('/api/hadith?book_id=9999')
            ->assertStatus(422)
            ->assertJsonValidationErrors('book_id');
    }

    public function test_per_page_is_capped(): void
    {
        $this->getJson('/api/hadith?per_page=5000')
            ->assertStatus(422)
            ->assertJsonValidationErrors('per_page');
    }

    public function test_index_hides_unpublished_hadiths(): void
    {
        Hadith::query()->update(['status' => false]);

        $this->getJson('/api/hadith')->assertNoContent(204);
    }

    public function test_index_embeds_book_and_chapter(): void
    {
        $first = $this->getJson('/api/hadith?per_page=1')->assertOk()->json('data.data.0');

        $this->assertSame('bukhari', $first['book']['slug']);
        $this->assertSame('Revelation', $first['chapter']['name_en']);
    }

    // ------------------------------------------------------------ show/random

    public function test_show_returns_a_single_hadith(): void
    {
        $hadith = Hadith::first();

        $this->getJson("/api/hadith/{$hadith->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $hadith->id)
            ->assertJsonPath('data.book.slug', 'bukhari');
    }

    public function test_show_404s_for_missing_or_unpublished(): void
    {
        $this->getJson('/api/hadith/9999')->assertNotFound();

        $hadith = Hadith::first();
        $hadith->update(['status' => false]);

        $this->getJson("/api/hadith/{$hadith->id}")->assertNotFound();
    }

    public function test_random_returns_one_hadith(): void
    {
        $this->getJson('/api/hadith-random')
            ->assertOk()
            ->assertJsonPath('data.book.slug', 'bukhari')
            ->assertJsonStructure(['data' => ['id', 'hadith_number', 'arabic_text', 'bangla_text', 'english_text']]);
    }

    public function test_random_204s_when_empty(): void
    {
        Hadith::query()->delete();

        $this->getJson('/api/hadith-random')->assertNoContent(204);
    }
}
