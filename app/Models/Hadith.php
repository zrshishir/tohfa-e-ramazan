<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hadith extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'reference',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
