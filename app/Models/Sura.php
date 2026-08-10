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
        // NOT NULL on the table but previously absent here, so Sura::create() could
        // never satisfy the constraint. The seeder writes via DB::table(), which is
        // why nothing caught it.
        'bangla_text',
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
