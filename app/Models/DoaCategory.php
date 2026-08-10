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
        // NOT NULL on the table; without these DoaCategory::create() cannot succeed.
        'bangla_text',
        'arabic_text',
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
