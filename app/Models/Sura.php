<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sura extends Model
{
    use HasFactory;

    protected $table = 'suras';

    protected $fillable = [
        'name',
        'arabic_name',
        'english_name',
        'meaning',
        'ayat_count',
        'type',
        'revelation_order',
        'rukus',
        'place_of_revelation',
        'status',
        'audio',
        'video',
        'image'
    ];
}
