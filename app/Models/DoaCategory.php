<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoaCategory extends Model
{
    use HasFactory;

    protected $table = 'doa_categories';

    protected $fillable = [
        'user_id',
        'name',
    ];

    public function doas()
    {
        return $this->hasMany(Doa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
