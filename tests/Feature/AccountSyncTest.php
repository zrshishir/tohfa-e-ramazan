<?php

namespace Tests\Feature;

use App\Models\Ayat;
use App\Models\Bookmark;
use App\Models\Sura;
use App\Models\Tasbih;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AccountSyncTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Sura $sura;
    private Ayat $ayat;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Reader', 'email' => 'reader@example.com',
            'password' => Hash::make('correct-horse-battery'),
        ]);

        $this->sura = Sura::create([
            'name' => 'Al-Fatihah', 'arabic_name' => 'الفاتحة', 'english_name' => 'The Opening',
            'bangla_text' => 'আল-ফাতিহা', 'meaning' => 'The Opening',
            'ayat_count' => 7, 'type' => 'Makki', 'status' => '1',
        ]);

        $this->ayat = Ayat::create([
            'sura_id' => $this->sura->id, 'ayat_no' => 1,
            'arabic_text' => 'بِسْمِ اللَّهِ', 'bangla_text' => 'পরম করুণাময়',
            'english_text' => 'In the name of Allah', 'meaning' => '', 'reference' => '1:1',
            'notes' => '', 'status' => '1',
        ]);
    }

    private function dhikr(string $label, int $total): array
    {
        return [
            'text_en' => $label, 'text_bn' => $label, 'text_ar' => $label,
            'reset_on' => 33, 'count' => 0, 'today_count' => 0,
            'monthly_count' => 0, 'yearly_count' => 0, 'total_count' => $total,
        ];
    }

    // ----------------------------------------------------------- bookmarks

    public function test_bookmarks_require_a_token(): void
    {
        $this->getJson('/api/bookmarks')->assertStatus(401);
        $this->postJson('/api/bookmarks', ['ayat_id' => $this->ayat->id])->assertStatus(401);
        $this->deleteJson("/api/bookmarks/{$this->ayat->id}")->assertStatus(401);
    }

    public function test_it_stores_and_lists_a_bookmark(): void
    {
        Sanctum::actingAs($this->user);

        $this->postJson('/api/bookmarks', [
            'ayat_id' => $this->ayat->id, 'sura_id' => $this->sura->id,
            'ayat_no' => 1, 'page' => 1,
        ])->assertCreated();

        $response = $this->getJson('/api/bookmarks')->assertOk();

        $this->assertSame($this->ayat->id, $response->json('data.0.ayat_id'));
        $this->assertSame('Al-Fatihah', $response->json('data.0.sura.name'));
        $this->assertSame('In the name of Allah', $response->json('data.0.ayat.english_text'));
    }

    public function test_bookmarking_twice_does_not_duplicate(): void
    {
        Sanctum::actingAs($this->user);

        $this->postJson('/api/bookmarks', ['ayat_id' => $this->ayat->id, 'page' => 1])->assertCreated();
        $this->postJson('/api/bookmarks', ['ayat_id' => $this->ayat->id, 'page' => 3])->assertCreated();

        $this->assertSame(1, Bookmark::where('user_id', $this->user->id)->count());
        $this->assertSame(3, Bookmark::first()->page);
    }

    public function test_an_empty_list_returns_204(): void
    {
        Sanctum::actingAs($this->user);

        $this->getJson('/api/bookmarks')->assertNoContent(204);
    }

    public function test_a_bookmark_can_be_removed_by_ayat(): void
    {
        Sanctum::actingAs($this->user);

        $this->postJson('/api/bookmarks', ['ayat_id' => $this->ayat->id])->assertCreated();
        $this->deleteJson("/api/bookmarks/{$this->ayat->id}")->assertOk();

        $this->assertSame(0, Bookmark::count());
        $this->deleteJson("/api/bookmarks/{$this->ayat->id}")->assertNotFound();
    }

    public function test_one_user_cannot_see_or_delete_anothers_bookmarks(): void
    {
        $other = User::create([
            'name' => 'Other', 'email' => 'other@example.com', 'password' => Hash::make('x'),
        ]);
        Bookmark::create(['user_id' => $other->id, 'ayat_id' => $this->ayat->id]);

        Sanctum::actingAs($this->user);

        $this->getJson('/api/bookmarks')->assertNoContent(204);
        $this->deleteJson("/api/bookmarks/{$this->ayat->id}")->assertNotFound();
        $this->assertSame(1, Bookmark::where('user_id', $other->id)->count());
    }

    public function test_sync_merges_rather_than_replacing(): void
    {
        $second = Ayat::create([
            'sura_id' => $this->sura->id, 'ayat_no' => 2,
            'arabic_text' => 'ا', 'bangla_text' => 'খ', 'english_text' => 'b',
            'meaning' => '', 'reference' => '1:2', 'notes' => '', 'status' => '1',
        ]);

        // Already on the account, from another device.
        Bookmark::create(['user_id' => $this->user->id, 'ayat_id' => $second->id]);

        Sanctum::actingAs($this->user);

        // Coming from this device, bookmarked before signing in.
        $response = $this->postJson('/api/bookmarks/sync', [
            'bookmarks' => [['ayat_id' => $this->ayat->id, 'page' => 1]],
        ])->assertOk();

        // Neither side is thrown away.
        $this->assertCount(2, $response->json('data'));
        $this->assertSame(2, Bookmark::where('user_id', $this->user->id)->count());
    }

    public function test_sync_accepts_an_empty_device_list(): void
    {
        Bookmark::create(['user_id' => $this->user->id, 'ayat_id' => $this->ayat->id]);
        Sanctum::actingAs($this->user);

        $this->postJson('/api/bookmarks/sync', ['bookmarks' => []])
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_sync_rejects_an_unknown_ayat(): void
    {
        Sanctum::actingAs($this->user);

        $this->postJson('/api/bookmarks/sync', ['bookmarks' => [['ayat_id' => 99999]]])
            ->assertStatus(422)
            ->assertJsonValidationErrors('bookmarks.0.ayat_id');
    }

    // -------------------------------------------------------------- tasbih

    public function test_tasbih_sync_requires_a_token(): void
    {
        $this->postJson('/api/tasbih/sync', ['tasbih' => [$this->dhikr('Subhanallah', 5)]])
            ->assertStatus(401);
    }

    public function test_tasbih_sync_keeps_the_higher_count(): void
    {
        Tasbih::create([
            'user_id' => $this->user->id,
            'tasbih'  => [$this->dhikr('Subhanallah', 100)],
        ]);

        Sanctum::actingAs($this->user);

        // The device is behind the account; its lower number must not win.
        $response = $this->postJson('/api/tasbih/sync', [
            'tasbih' => [$this->dhikr('Subhanallah', 40)],
        ])->assertOk();

        $this->assertSame(100, $response->json('data.tasbih.0.total_count'));
    }

    public function test_tasbih_sync_accepts_a_higher_device_count(): void
    {
        Tasbih::create([
            'user_id' => $this->user->id,
            'tasbih'  => [$this->dhikr('Subhanallah', 10)],
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/tasbih/sync', [
            'tasbih' => [$this->dhikr('Subhanallah', 250)],
        ])->assertOk();

        $this->assertSame(250, $response->json('data.tasbih.0.total_count'));
    }

    public function test_tasbih_sync_creates_a_row_for_a_new_account(): void
    {
        Sanctum::actingAs($this->user);

        $this->postJson('/api/tasbih/sync', ['tasbih' => [$this->dhikr('Alhamdulillah', 7)]])
            ->assertOk()
            ->assertJsonPath('data.tasbih.0.total_count', 7);

        $this->assertSame(1, Tasbih::where('user_id', $this->user->id)->count());
    }

    public function test_a_signed_in_user_cannot_read_another_users_tasbih(): void
    {
        $other = User::create([
            'name' => 'Other', 'email' => 'other@example.com', 'password' => Hash::make('x'),
        ]);
        Tasbih::create(['user_id' => $other->id, 'tasbih' => [$this->dhikr('Subhanallah', 999)]]);
        Tasbih::create(['user_id' => $this->user->id, 'tasbih' => [$this->dhikr('Subhanallah', 1)]]);

        Sanctum::actingAs($this->user);

        // The query string points at someone else; the token must win.
        $response = $this->getJson("/api/tasbih?user_id={$other->id}")->assertOk();

        $this->assertSame(1, $response->json('data.tasbih.0.total_count'));
    }

    public function test_guests_still_reach_the_shared_tasbih(): void
    {
        Tasbih::create(['user_id' => $this->user->id, 'tasbih' => [$this->dhikr('Subhanallah', 3)]]);

        // No token: the app works without an account, as it always has.
        $this->getJson("/api/tasbih?user_id={$this->user->id}")
            ->assertOk()
            ->assertJsonPath('data.tasbih.0.total_count', 3);
    }
}
