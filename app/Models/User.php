<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The only role permitted into the Filament admin panel.
     *
     * Every mobile app user is a row in this same table, so panel access has to be an
     * explicit opt-in rather than a side effect of having an account.
     */
    public const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country_id',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Gate the Filament admin panel.
     *
     * Filament 2 had no such check, so every registered account — including every mobile
     * app user — could sign in at /admin and edit hadith, duas, masa-el and prayer times.
     * Filament 3 calls this method instead, and denies access outright when the model does
     * not implement FilamentUser and the environment is not 'local'.
     */
    public function canAccessPanel( Panel $panel ): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function blog() {
        return $this->hasMany(Blog::class);
    }

    public function country() {
        return $this->belongsTo(Country::class);
    }
}
