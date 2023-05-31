<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'iso',
        'name',
        'nice_name',
        'iso3',
        'num_code',
        'phone_code'
    ];

    public function divisions() {
        return $this->hasMany(Division::class);
    }

    public function users() {
        return $this->hasMany(User::class);
    }
}
