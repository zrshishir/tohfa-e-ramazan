<?php

namespace Tests\Feature;

use App\Models\Hadith;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HadithApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_active_hadiths_in_the_shared_envelope(): void
    {
        Hadith::create([
            'title'       => 'Fasting is a Shield',
            'description' => 'Fasting is a shield or protection from the fire.',
            'reference'   => 'Sahih Bukhari',
            'status'      => true,
        ]);

        $response = $this->getJson('/api/hadith');

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'statusCode',
                'message',
                'data' => [
                    ['id', 'title', 'description', 'reference', 'status'],
                ],
            ])
            ->assertJsonPath('status', 'Success')
            ->assertJsonPath('data.0.title', 'Fasting is a Shield')
            ->assertJsonPath('data.0.reference', 'Sahih Bukhari');
    }

    public function test_index_hides_inactive_hadiths(): void
    {
        Hadith::create([
            'title'       => 'Visible',
            'description' => 'Shown to the app.',
            'reference'   => 'Sahih Muslim',
            'status'      => true,
        ]);
        Hadith::create([
            'title'       => 'Hidden',
            'description' => 'Unpublished draft.',
            'reference'   => 'Sahih Muslim',
            'status'      => false,
        ]);

        $this->getJson('/api/hadith')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Visible');
    }

    public function test_status_is_cast_to_a_boolean(): void
    {
        Hadith::create([
            'title'       => 'Cast check',
            'description' => 'Status must serialise as true, not 1.',
            'reference'   => 'Sunan Abu Dawud',
            'status'      => true,
        ]);

        $this->getJson('/api/hadith')
            ->assertOk()
            ->assertJsonPath('data.0.status', true);
    }

    public function test_index_returns_no_content_when_empty(): void
    {
        $this->getJson('/api/hadith')->assertNoContent(204);
    }

    public function test_show_returns_a_single_hadith(): void
    {
        $hadith = Hadith::create([
            'title'       => 'Rewards of Ramadan',
            'description' => 'Whoever fasts during Ramadan out of sincere faith...',
            'reference'   => 'Sahih Bukhari',
            'status'      => true,
        ]);

        $this->getJson("/api/hadith/{$hadith->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $hadith->id)
            ->assertJsonPath('data.title', 'Rewards of Ramadan');
    }

    public function test_show_returns_404_for_a_missing_or_inactive_hadith(): void
    {
        $this->getJson('/api/hadith/9999')->assertNotFound();

        $inactive = Hadith::create([
            'title'       => 'Draft',
            'description' => 'Not published.',
            'reference'   => 'Jami at-Tirmidhi',
            'status'      => false,
        ]);

        $this->getJson("/api/hadith/{$inactive->id}")->assertNotFound();
    }
}
