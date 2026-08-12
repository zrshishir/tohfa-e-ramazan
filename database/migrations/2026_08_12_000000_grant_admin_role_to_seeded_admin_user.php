<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Filament 3 refuses panel access to any user whose role is not 'admin' (see
 * User::canAccessPanel). Nobody currently holds that role — the column exists but has
 * never been written to — so without this backfill the upgrade would lock everyone out
 * of the admin the moment it deploys.
 *
 * Scoped to the seeded administrator account only. Every other user is a mobile app
 * account and must stay out of the panel.
 */
return new class extends Migration
{
    private const ADMIN_EMAIL = 'admin@admin.com';

    public function up(): void
    {
        DB::table('users')
            ->where('email', self::ADMIN_EMAIL)
            ->update(['role' => User::ROLE_ADMIN]);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('email', self::ADMIN_EMAIL)
            ->where('role', User::ROLE_ADMIN)
            ->update(['role' => null]);
    }
};
