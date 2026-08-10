<?php

namespace Tests\Feature;

use App\Models\Tasbih;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TasbihApiTest extends TestCase
{
    use RefreshDatabase;

    private function dhikr(array $overrides = []): array
    {
        return array_merge([
            'text_en'       => 'Subhanallah',
            'text_bn'       => 'সুবহানআল্লাহ',
            'text_ar'       => 'سبحان الله',
            'reset_on'      => 33,
            'count'         => 0,
            'today_count'   => 0,
            'monthly_count' => 0,
            'yearly_count'  => 0,
            'total_count'   => 0,
        ], $overrides);
    }

    private function makeUser(): User
    {
        return User::create([
            'name'     => 'Test User',
            'email'    => 'tasbih' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    private function makeTasbih(User $user, array $dhikrs = null): Tasbih
    {
        return Tasbih::create([
            'user_id' => $user->id,
            'tasbih'  => $dhikrs ?? [$this->dhikr()],
        ]);
    }

    // ---------------------------------------------------------------- read

    public function test_index_returns_the_tasbih_payload_as_an_array(): void
    {
        $user = $this->makeUser();
        $this->makeTasbih($user);

        $response = $this->getJson('/api/tasbih?user_id=' . $user->id);

        $response->assertOk()
            ->assertJsonPath('status', 'Success')
            ->assertJsonPath('data.user_id', $user->id)
            ->assertJsonPath('data.tasbih.0.text_en', 'Subhanallah')
            ->assertJsonPath('data.tasbih.0.reset_on', 33);

        // Regression: `tasbih` used to serialise as a JSON *string*, forcing the
        // client to JSON.parse it. The `array` cast means it is now real JSON.
        $this->assertIsArray($response->json('data.tasbih'));
    }

    public function test_index_returns_404_when_the_user_has_no_row(): void
    {
        $this->getJson('/api/tasbih?user_id=999')->assertNotFound();
    }

    public function test_show_returns_the_row_for_a_user(): void
    {
        $user = $this->makeUser();
        $this->makeTasbih($user);

        $this->getJson("/api/tasbih/{$user->id}")
            ->assertOk()
            ->assertJsonPath('data.user_id', $user->id);
    }

    public function test_show_returns_404_for_an_unknown_user(): void
    {
        $this->getJson('/api/tasbih/999')->assertNotFound();
    }

    // --------------------------------------------------------------- write

    public function test_store_creates_a_row(): void
    {
        $user = $this->makeUser();

        $this->postJson('/api/tasbih', [
            'user_id' => $user->id,
            'tasbih'  => [$this->dhikr(['total_count' => 7])],
        ])->assertOk()
            ->assertJsonPath('data.tasbih.0.total_count', 7);

        // Regression: `tasbih` was missing from $fillable, so mass assignment
        // silently dropped the only column that carries data.
        $this->assertSame(7, Tasbih::where('user_id', $user->id)->first()->tasbih[0]['total_count']);
    }

    public function test_store_overwrites_an_existing_row_instead_of_duplicating(): void
    {
        $user = $this->makeUser();
        $this->makeTasbih($user);

        $this->postJson('/api/tasbih', [
            'user_id' => $user->id,
            'tasbih'  => [$this->dhikr(['total_count' => 99])],
        ])->assertOk();

        $this->assertSame(1, Tasbih::where('user_id', $user->id)->count());
        $this->assertSame(99, Tasbih::where('user_id', $user->id)->first()->tasbih[0]['total_count']);
    }

    public function test_update_persists_counters(): void
    {
        $user = $this->makeUser();
        $this->makeTasbih($user);

        // Regression: PUT /tasbih had no {id} segment while the controller method
        // required one, so every call was a 500 before it could validate anything.
        $this->putJson("/api/tasbih/{$user->id}", [
            'tasbih' => [$this->dhikr(['count' => 12, 'total_count' => 120])],
        ])->assertOk()
            ->assertJsonPath('data.tasbih.0.count', 12)
            ->assertJsonPath('data.tasbih.0.total_count', 120);

        $this->assertSame(120, Tasbih::where('user_id', $user->id)->first()->tasbih[0]['total_count']);
    }

    public function test_update_returns_404_for_an_unknown_user(): void
    {
        $this->putJson('/api/tasbih/999', ['tasbih' => [$this->dhikr()]])
            ->assertNotFound();
    }

    public function test_destroy_removes_the_row(): void
    {
        $user = $this->makeUser();
        $this->makeTasbih($user);

        $this->deleteJson("/api/tasbih/{$user->id}")->assertOk();

        $this->assertSame(0, Tasbih::where('user_id', $user->id)->count());
    }

    public function test_destroy_returns_404_for_an_unknown_user(): void
    {
        $this->deleteJson('/api/tasbih/999')->assertNotFound();
    }

    // ---------------------------------------------------------- validation

    public function test_store_rejects_a_missing_tasbih_payload(): void
    {
        $user = $this->makeUser();

        $this->postJson('/api/tasbih', ['user_id' => $user->id])
            ->assertStatus(422)
            ->assertJsonValidationErrors('tasbih');
    }

    public function test_store_rejects_an_unknown_user(): void
    {
        $this->postJson('/api/tasbih', [
            'user_id' => 999,
            'tasbih'  => [$this->dhikr()],
        ])->assertStatus(422)->assertJsonValidationErrors('user_id');
    }

    public function test_store_rejects_a_dhikr_without_text(): void
    {
        $user = $this->makeUser();

        $this->postJson('/api/tasbih', [
            'user_id' => $user->id,
            'tasbih'  => [['count' => 1]],
        ])->assertStatus(422)->assertJsonValidationErrors('tasbih.0.text_en');
    }

    public function test_update_rejects_negative_counters(): void
    {
        $user = $this->makeUser();
        $this->makeTasbih($user);

        // Regression: update() called ->fails() on the *array* returned by
        // $request->validate(), a fatal error on every request that validated.
        $this->putJson("/api/tasbih/{$user->id}", [
            'tasbih' => [$this->dhikr(['count' => -5])],
        ])->assertStatus(422)->assertJsonValidationErrors('tasbih.0.count');
    }
}
