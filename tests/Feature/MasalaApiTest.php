<?php

namespace Tests\Feature;

use App\Models\Masala;
use App\Models\MasalaCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasalaApiTest extends TestCase
{
    use RefreshDatabase;

    private MasalaCategory $sawm;
    private MasalaCategory $salat;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sawm = MasalaCategory::create([
            'name_en' => 'Fasting', 'name_bn' => 'রোযা', 'name_ar' => 'الصوم',
            'slug' => 'sawm', 'sort_order' => 1, 'status' => true,
        ]);

        $this->salat = MasalaCategory::create([
            'name_en' => 'Prayer', 'name_bn' => 'নামায', 'name_ar' => 'الصلاة',
            'slug' => 'salat', 'sort_order' => 2, 'status' => true,
        ]);

        Masala::create([
            'masala_category_id' => $this->sawm->id,
            'question' => 'Intention for Fasting',
            'answer'   => 'The intention must be made before dawn.',
            'reference' => 'Fatawa Alamgiri', 'sort_order' => 1, 'status' => true,
        ]);

        Masala::create([
            'masala_category_id' => $this->sawm->id,
            'question' => 'Eating Forgetfully',
            'answer'   => 'The fast is not broken.',
            'reference' => 'Hedaya', 'sort_order' => 2, 'status' => true,
        ]);
    }

    // ------------------------------------------------------------ categories

    public function test_categories_endpoint_returns_categories_with_counts(): void
    {
        $response = $this->getJson('/api/masala-categories')->assertOk();

        $this->assertSame('Fasting', $response->json('data.0.name_en'));
        $this->assertSame('রোযা', $response->json('data.0.name_bn'));
        $this->assertSame(2, $response->json('data.0.masalas_count'));
        $this->assertSame(0, $response->json('data.1.masalas_count'));
    }

    public function test_category_count_excludes_unpublished_masalas(): void
    {
        Masala::first()->update(['status' => false]);

        $this->assertSame(1, $this->getJson('/api/masala-categories')->json('data.0.masalas_count'));
    }

    public function test_categories_endpoint_hides_unpublished_categories(): void
    {
        MasalaCategory::query()->update(['status' => false]);

        $this->getJson('/api/masala-categories')->assertNoContent(204);
    }

    public function test_categories_are_ordered_by_sort_order(): void
    {
        $this->salat->update(['sort_order' => 0]);

        $this->assertSame('Prayer', $this->getJson('/api/masala-categories')->json('data.0.name_en'));
    }

    // ---------------------------------------------------------------- index

    public function test_index_is_paginated_and_embeds_the_category(): void
    {
        $response = $this->getJson('/api/masala?per_page=1')->assertOk();

        $this->assertCount(1, $response->json('data.data'));
        $this->assertSame(2, $response->json('data.total'));
        $this->assertSame('sawm', $response->json('data.data.0.category.slug'));
    }

    public function test_index_filters_by_category(): void
    {
        Masala::create([
            'masala_category_id' => $this->salat->id,
            'question' => 'Missed prayer', 'answer' => 'It must be made up.',
            'sort_order' => 1, 'status' => true,
        ]);

        $response = $this->getJson("/api/masala?category_id={$this->salat->id}")->assertOk();

        $this->assertSame(1, $response->json('data.total'));
        $this->assertSame('Missed prayer', $response->json('data.data.0.question'));
    }

    public function test_index_searches_question_and_answer(): void
    {
        $this->assertSame(1, $this->getJson('/api/masala?q=Intention')->json('data.total'));
        $this->assertSame(1, $this->getJson('/api/masala?q=dawn')->json('data.total'));
        $this->getJson('/api/masala?q=zzzzzz')->assertNoContent(204);
    }

    public function test_index_respects_sort_order(): void
    {
        Masala::where('question', 'Eating Forgetfully')->update(['sort_order' => 0]);

        $this->assertSame(
            'Eating Forgetfully',
            $this->getJson('/api/masala')->json('data.data.0.question')
        );
    }

    public function test_index_hides_unpublished_masalas(): void
    {
        Masala::query()->update(['status' => false]);

        $this->getJson('/api/masala')->assertNoContent(204);
    }

    public function test_index_rejects_bad_filters(): void
    {
        $this->getJson('/api/masala?category_id=9999')->assertStatus(422)
            ->assertJsonValidationErrors('category_id');
        $this->getJson('/api/masala?q=a')->assertStatus(422)
            ->assertJsonValidationErrors('q');
        $this->getJson('/api/masala?per_page=5000')->assertStatus(422)
            ->assertJsonValidationErrors('per_page');
    }

    // ----------------------------------------------------------------- show

    public function test_show_returns_a_single_masala(): void
    {
        $masala = Masala::first();

        $this->getJson("/api/masala/{$masala->id}")
            ->assertOk()
            ->assertJsonPath('data.question', 'Intention for Fasting')
            ->assertJsonPath('data.category.name_bn', 'রোযা');
    }

    public function test_show_404s_for_missing_or_unpublished(): void
    {
        $this->getJson('/api/masala/9999')->assertNotFound();

        $masala = Masala::first();
        $masala->update(['status' => false]);

        $this->getJson("/api/masala/{$masala->id}")->assertNotFound();
    }

    public function test_a_masala_without_a_category_still_lists(): void
    {
        // masala_category_id is nullable, so an uncategorised entry must not 500.
        Masala::create([
            'question' => 'Uncategorised', 'answer' => 'Still visible.',
            'sort_order' => 9, 'status' => true,
        ]);

        $this->assertSame(3, $this->getJson('/api/masala')->json('data.total'));
    }
}
