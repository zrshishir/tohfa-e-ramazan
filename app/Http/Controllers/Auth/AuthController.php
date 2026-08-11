<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tasbih;
use App\Models\User;
use App\Traits\HelperTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Accounts are entirely optional. Everything in the app works without one — the point of
 * signing in is to carry tasbih counts and bookmarks between devices, nothing more.
 *
 * Responses use the shared HelperTrait envelope so clients parse auth the same way they
 * parse every other endpoint.
 */
class AuthController extends Controller
{
    use HelperTrait;

    /**
     * POST /api/auth/register
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            // Laravel's Password rule gives a length floor and a breach check, rather
            // than accepting any non-empty string.
            'password' => ['required', 'confirmed', Password::min(8)->uncompromised()],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        return $this->successResponse('Registered successfully', [
            'user'  => $this->publicUser($user),
            'token' => $user->createToken('app')->plainTextToken,
        ], 201);
    }

    /**
     * POST /api/auth/login
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        // One message for both cases, so the response cannot be used to discover which
        // addresses are registered.
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return $this->unauthorizedResponse('Those credentials do not match our records', []);
        }

        return $this->successResponse('Logged in successfully', [
            'user'  => $this->publicUser($user),
            'token' => $user->createToken('app')->plainTextToken,
        ]);
    }

    /**
     * POST /api/auth/logout
     * Revokes only the token that made this request, so signing out on one device leaves
     * the others signed in.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse('Logged out successfully', []);
    }

    /**
     * GET /api/auth/me
     */
    public function me(Request $request)
    {
        return $this->successResponse('User retrieved successfully', $this->publicUser($request->user()));
    }

    /**
     * DELETE /api/auth/account
     *
     * Google Play and the App Store both require account deletion to be reachable inside
     * the app, not only through a website.
     */
    public function destroy(Request $request)
    {
        $request->validate(['password' => 'required|string']);

        $user = $request->user();

        if (!Hash::check($request->input('password'), $user->password)) {
            return $this->unauthorizedResponse('Password is incorrect', []);
        }

        // Tokens first: the account must not stay usable if the delete half-fails.
        $user->tokens()->delete();

        // The tasbih foreign key has no ON DELETE rule, so a user with saved counters
        // cannot be removed until their row goes. Bookmarks cascade on their own.
        Tasbih::where('user_id', $user->id)->delete();

        // forceDelete, not delete: the User model soft-deletes, and a soft-deleted row
        // still holds the person's name and email and still blocks that address from
        // being registered again. Store policy expects the data to actually go.
        $user->forceDelete();

        return $this->successResponse('Account deleted', []);
    }

    /** Never return the password hash or internal columns to a client. */
    private function publicUser(User $user): array
    {
        return [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
        ];
    }
}
