<?php

namespace Tests\Feature;

use App\Models\District;
use App\Models\Division;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GeocodeApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.google_maps.key' => 'test-key']);
        Cache::flush();

        DB::table('countries')->insert(['id' => 1, 'iso' => 'BD', 'name' => 'BANGLADESH',
            'nice_name' => 'Bangladesh', 'iso3' => 'BGD', 'num_code' => 50, 'phone_code' => 880]);

        $division = Division::create(['name' => 'Chittagong', 'country_id' => 1]);
        District::create(['name' => 'Chittagong', 'division_id' => $division->id]);
        District::create(['name' => 'Sylhet', 'division_id' => $division->id]);
    }

    private function fakeGoogle(array $components): void
    {
        Http::fake([
            'maps.googleapis.com/*' => Http::response([
                'results' => [['address_components' => $components]],
                'status'  => 'OK',
            ]),
        ]);
    }

    private function addressPart(string $name, array $types): array
    {
        return ['long_name' => $name, 'short_name' => $name, 'types' => $types];
    }

    public function test_it_resolves_a_city_and_division(): void
    {
        $this->fakeGoogle([
            $this->addressPart('Sylhet', ['locality']),
            $this->addressPart('Sylhet Division', ['administrative_area_level_1']),
        ]);

        $this->getJson('/api/geocode?lat=24.8949&lng=91.8687')
            ->assertOk()
            ->assertJsonPath('data.city', 'Sylhet')
            ->assertJsonPath('data.division', 'Sylhet Division')
            ->assertJsonPath('data.district.name', 'Sylhet');
    }

    public function test_it_maps_a_current_spelling_onto_the_stored_one(): void
    {
        // Google says Chattogram; the districts table says Chittagong.
        $this->fakeGoogle([
            $this->addressPart('Chattogram', ['locality']),
            $this->addressPart('Chattogram District', ['administrative_area_level_2']),
        ]);

        $this->getJson('/api/geocode?lat=22.3569&lng=91.7832')
            ->assertOk()
            ->assertJsonPath('data.district.name', 'Chittagong');
    }

    public function test_an_unmatched_place_returns_a_null_district_not_an_error(): void
    {
        $this->fakeGoogle([
            $this->addressPart('Nowhere', ['locality']),
        ]);

        $this->getJson('/api/geocode?lat=10&lng=10')
            ->assertOk()
            ->assertJsonPath('data.city', 'Nowhere')
            ->assertJsonPath('data.district', null);
    }

    public function test_missing_address_components_are_tolerated(): void
    {
        // Coordinates outside a city often have no locality at all.
        $this->fakeGoogle([
            $this->addressPart('Bangladesh', ['country']),
        ]);

        $this->getJson('/api/geocode?lat=23&lng=90')
            ->assertOk()
            ->assertJsonPath('data.city', null)
            ->assertJsonPath('data.division', null);
    }

    public function test_it_validates_coordinates(): void
    {
        $this->getJson('/api/geocode')->assertStatus(422)
            ->assertJsonValidationErrors(['lat', 'lng']);
        $this->getJson('/api/geocode?lat=999&lng=0')->assertStatus(422)
            ->assertJsonValidationErrors('lat');
        $this->getJson('/api/geocode?lat=0&lng=999')->assertStatus(422)
            ->assertJsonValidationErrors('lng');
    }

    public function test_it_reports_a_missing_server_key(): void
    {
        config(['services.google_maps.key' => null]);

        $this->getJson('/api/geocode?lat=23&lng=90')->assertStatus(503);
    }

    public function test_an_upstream_failure_is_not_a_500(): void
    {
        Http::fake(['maps.googleapis.com/*' => Http::response([], 500)]);

        $this->getJson('/api/geocode?lat=23&lng=90')->assertStatus(502);
    }

    public function test_repeat_requests_are_served_from_cache(): void
    {
        $this->fakeGoogle([$this->addressPart('Sylhet', ['locality'])]);

        $this->getJson('/api/geocode?lat=24.8949&lng=91.8687')->assertOk();
        $this->getJson('/api/geocode?lat=24.8949&lng=91.8687')->assertOk();

        // Each miss is a billed Google call, so a repeat must not reach them.
        Http::assertSentCount(1);
    }

    public function test_nearby_coordinates_share_a_cache_entry(): void
    {
        $this->fakeGoogle([$this->addressPart('Sylhet', ['locality'])]);

        // Rounded to 3dp (~110m), which is far finer than a district.
        $this->getJson('/api/geocode?lat=24.89491&lng=91.86871')->assertOk();
        $this->getJson('/api/geocode?lat=24.89492&lng=91.86872')->assertOk();

        Http::assertSentCount(1);
    }
}
