<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsmaulHusna extends Model
{
    use HasFactory;

    protected $fillable = [
        'arabic_name',
        'bangla_name',
        'english_name',
        'meaning',
    ];
}
