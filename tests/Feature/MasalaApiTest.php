<?php

namespace Tests\Feature;

use App\Models\Masala;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasalaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_active_masalas_in_the_shared_envelope(): void
    {
        Masala::create([
            'title'       => 'Intention for Fasting',
            'description' => 'The intention for obligatory fasting must be made before dawn.',
            'reference'   => 'Fatawa Alamgiri',
            'status'      => true,
        ]);

        $this->getJson('/api/masala')
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'statusCode',
                'message',
                'data' => [
                    ['id', 'title', 'description', 'reference', 'status'],
                ],
            ])
            ->assertJsonPath('status', 'Success')
            ->assertJsonPath('data.0.title', 'Intention for Fasting')
            ->assertJsonPath('data.0.reference', 'Fatawa Alamgiri');
    }

    public function test_index_hides_inactive_masalas(): void
    {
        Masala::create([
            'title'       => 'Visible',
            'description' => 'Shown to the app.',
            'reference'   => 'Hedaya',
            'status'      => true,
        ]);
        Masala::create([
            'title'       => 'Hidden',
            'description' => 'Unpublished draft.',
            'reference'   => 'Hedaya',
            'status'      => false,
        ]);

        $this->getJson('/api/masala')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Visible');
    }

    public function test_status_is_cast_to_a_boolean(): void
    {
        Masala::create([
            'title'       => 'Cast check',
            'description' => 'Status must serialise as true, not 1.',
            'reference'   => 'Hedaya',
            'status'      => true,
        ]);

        $this->getJson('/api/masala')
            ->assertOk()
            ->assertJsonPath('data.0.status', true);
    }

    public function test_index_returns_no_content_when_empty(): void
    {
        $this->getJson('/api/masala')->assertNoContent(204);
    }

    public function test_show_returns_a_single_masala(): void
    {
        $masala = Masala::create([
            'title'       => 'Eating Forgetfully',
            'description' => 'If a person eats or drinks forgetfully, their fast is not broken.',
            'reference'   => 'Hedaya',
            'status'      => true,
        ]);

        $this->getJson("/api/masala/{$masala->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $masala->id)
            ->assertJsonPath('data.title', 'Eating Forgetfully');
    }

    public function test_show_returns_404_for_a_missing_or_inactive_masala(): void
    {
        $this->getJson('/api/masala/9999')->assertNotFound();

        $inactive = Masala::create([
            'title'       => 'Draft',
            'description' => 'Not published.',
            'reference'   => 'Hedaya',
            'status'      => false,
        ]);

        $this->getJson("/api/masala/{$inactive->id}")->assertNotFound();
    }
}
