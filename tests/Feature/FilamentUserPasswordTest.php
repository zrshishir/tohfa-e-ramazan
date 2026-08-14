<?php

namespace Tests\Feature;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * The admin user form used to be unusable in both directions.
 *
 * `password` was ->required() with no dehydration. The column is $hidden on the model, so
 * Filament could never fill the field — every edit failed validation on a field the admin
 * had no way to satisfy. And because the User model has no 'hashed' cast, whatever was
 * typed went into the column verbatim, so an admin-set password was stored in plaintext
 * and Hash::check() could never match it at login.
 *
 * These tests pin down both halves.
 */
class FilamentUserPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));
    }

    public function test_creating_a_user_stores_a_hashed_password(): void
    {
        Livewire::test(UserResource\Pages\CreateUser::class)
            ->fillForm([
                'name' => 'Admin Created',
                'email' => 'created@example.test',
                'password' => 'correct-horse-battery',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $user = User::where('email', 'created@example.test')->firstOrFail();

        $this->assertNotSame(
            'correct-horse-battery',
            $user->password,
            'The password was stored in plaintext.'
        );

        $this->assertTrue(
            Hash::check('correct-horse-battery', $user->password),
            'The stored password does not verify, so this user could never log in.'
        );
    }

    public function test_editing_a_user_without_touching_the_password_keeps_it(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('original-password'),
        ]);

        $hashBefore = $user->password;

        Livewire::test(UserResource\Pages\EditUser::class, ['record' => $user->getKey()])
            ->fillForm(['name' => 'Renamed'])
            ->call('save')
            ->assertHasNoFormErrors();

        $user->refresh();

        $this->assertSame('Renamed', $user->name);
        $this->assertSame($hashBefore, $user->password, 'An unrelated edit changed the password.');
        $this->assertTrue(Hash::check('original-password', $user->password));
    }

    public function test_editing_a_user_with_a_new_password_hashes_it(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('original-password'),
        ]);

        Livewire::test(UserResource\Pages\EditUser::class, ['record' => $user->getKey()])
            ->fillForm(['password' => 'a-brand-new-password'])
            ->call('save')
            ->assertHasNoFormErrors();

        $user->refresh();

        $this->assertTrue(
            Hash::check('a-brand-new-password', $user->password),
            'The new password does not verify.'
        );

        $this->assertFalse(Hash::check('original-password', $user->password));
    }

    /**
     * Users created through the API have no phone number. The form used to require one,
     * which meant those accounts could not be edited from the admin at all.
     */
    public function test_a_user_without_a_phone_number_can_still_be_saved(): void
    {
        $user = User::factory()->create(['phone' => null]);

        Livewire::test(UserResource\Pages\EditUser::class, ['record' => $user->getKey()])
            ->fillForm(['role' => 'editor'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('editor', $user->refresh()->role);
    }
}
