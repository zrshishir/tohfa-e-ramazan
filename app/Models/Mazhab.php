<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mazhab extends Model
{
    use HasFactory;

    protected $table = 'mazhabs';

    protected $fillable = [
        'user_id',
        'name',
        // NOT NULL on the table; without these Mazhab::create() cannot succeed.
        'bangla_text',
        'arabic_text',
    ];
}