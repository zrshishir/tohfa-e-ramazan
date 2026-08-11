<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $password = 'correct-horse-battery'): User
    {
        return User::create([
            'name'     => 'Reader',
            'email'    => 'reader@example.com',
            'password' => Hash::make($password),
        ]);
    }

    // ------------------------------------------------------------- register

    public function test_it_registers_and_returns_a_token(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name'                  => 'Reader',
            'email'                 => 'reader@example.com',
            'password'              => 'correct-horse-battery',
            'password_confirmation' => 'correct-horse-battery',
        ])->assertCreated();

        $this->assertNotEmpty($response->json('data.token'));
        $this->assertSame('reader@example.com', $response->json('data.user.email'));
        $this->assertDatabaseHas('users', ['email' => 'reader@example.com']);
    }

    public function test_the_password_hash_is_never_returned(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Reader', 'email' => 'reader@example.com',
            'password' => 'correct-horse-battery',
            'password_confirmation' => 'correct-horse-battery',
        ])->assertCreated();

        $this->assertArrayNotHasKey('password', $response->json('data.user'));
        $this->assertStringNotContainsString('$2y$', $response->getContent());
    }

    public function test_it_rejects_a_short_password(): void
    {
        $this->postJson('/api/auth/register', [
            'name' => 'Reader', 'email' => 'reader@example.com',
            'password' => 'short', 'password_confirmation' => 'short',
        ])->assertStatus(422)->assertJsonValidationErrors('password');
    }

    public function test_it_requires_password_confirmation(): void
    {
        $this->postJson('/api/auth/register', [
            'name' => 'Reader', 'email' => 'reader@example.com',
            'password' => 'correct-horse-battery',
            'password_confirmation' => 'something-else',
        ])->assertStatus(422)->assertJsonValidationErrors('password');
    }

    public function test_it_rejects_a_duplicate_email(): void
    {
        $this->makeUser();

        $this->postJson('/api/auth/register', [
            'name' => 'Someone', 'email' => 'reader@example.com',
            'password' => 'correct-horse-battery',
            'password_confirmation' => 'correct-horse-battery',
        ])->assertStatus(422)->assertJsonValidationErrors('email');
    }

    // ---------------------------------------------------------------- login

    public function test_it_logs_in_with_correct_credentials(): void
    {
        $this->makeUser();

        $response = $this->postJson('/api/auth/login', [
            'email' => 'reader@example.com', 'password' => 'correct-horse-battery',
        ])->assertOk();

        $this->assertNotEmpty($response->json('data.token'));
    }

    public function test_a_wrong_password_is_rejected(): void
    {
        $this->makeUser();

        $this->postJson('/api/auth/login', [
            'email' => 'reader@example.com', 'password' => 'wrong',
        ])->assertStatus(401);
    }

    public function test_an_unknown_email_gives_the_same_message_as_a_wrong_password(): void
    {
        $this->makeUser();

        $unknown = $this->postJson('/api/auth/login', [
            'email' => 'nobody@example.com', 'password' => 'correct-horse-battery',
        ])->assertStatus(401);

        $wrong = $this->postJson('/api/auth/login', [
            'email' => 'reader@example.com', 'password' => 'wrong',
        ])->assertStatus(401);

        // Otherwise the response reveals which addresses are registered.
        $this->assertSame($unknown->json('message'), $wrong->json('message'));
    }

    // ----------------------------------------------------------- me / logout

    public function test_me_requires_a_token(): void
    {
        $this->getJson('/api/auth/me')->assertStatus(401);
    }

    public function test_me_returns_the_authenticated_user(): void
    {
        Sanctum::actingAs($this->makeUser());

        $this->getJson('/api/auth/me')
            ->assertOk()
            ->assertJsonPath('data.email', 'reader@example.com');
    }

    public function test_logout_revokes_only_the_current_token(): void
    {
        $user = $this->makeUser();
        $phone   = $user->createToken('phone')->plainTextToken;
        $tablet  = $user->createToken('tablet')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$phone}")
            ->postJson('/api/auth/logout')->assertOk();

        $this->assertSame(1, $user->fresh()->tokens()->count());
        $this->assertSame('tablet', $user->fresh()->tokens()->first()->name);

        // The guard caches the resolved user for the lifetime of the test, so it has to
        // be forgotten before the revoked token is re-checked.
        $this->app['auth']->forgetGuards();

        // Signing out on one device must not sign the user out everywhere.
        $this->withHeader('Authorization', "Bearer {$phone}")
            ->getJson('/api/auth/me')->assertStatus(401);

        $this->app['auth']->forgetGuards();

        $this->withHeader('Authorization', "Bearer {$tablet}")
            ->getJson('/api/auth/me')->assertOk();
    }

    // -------------------------------------------------------- delete account

    public function test_it_deletes_the_account_with_the_correct_password(): void
    {
        $user = $this->makeUser();
        $token = $user->createToken('app')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$token}")
            ->deleteJson('/api/auth/account', ['password' => 'correct-horse-battery'])
            ->assertOk();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('personal_access_tokens', ['tokenable_id' => $user->id]);
    }

    public function test_deletion_requires_the_password(): void
    {
        $user = $this->makeUser();
        Sanctum::actingAs($user);

        $this->deleteJson('/api/auth/account', ['password' => 'wrong'])->assertStatus(401);
        $this->assertDatabaseHas('users', ['id' => $user->id]);

        $this->deleteJson('/api/auth/account')->assertStatus(422);
    }

    public function test_deletion_requires_a_token(): void
    {
        $this->deleteJson('/api/auth/account', ['password' => 'x'])->assertStatus(401);
    }

    // ------------------------------------------------------------ guest mode

    public function test_the_rest_of_the_api_stays_open_to_guests(): void
    {
        // Accounts are optional; nothing else may start demanding a token.
        foreach (['/api/mazhabs', '/api/sura', '/api/hadith-books', '/api/masala-categories'] as $endpoint) {
            $status = $this->getJson($endpoint)->getStatusCode();
            $this->assertNotSame(401, $status, "{$endpoint} must remain open to guests");
        }
    }
}
