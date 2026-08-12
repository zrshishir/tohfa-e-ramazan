<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ayat extends Model
{
    use HasFactory;

    protected $table = 'ayats';

    protected $fillable = [
        'sura_id',
        // ayat_no and notes are NOT NULL on the table but were absent here, so
        // Ayat::create() could never satisfy the constraints.
        'ayat_no',
        'notes',
        'arabic_text',
        'bangla_text',
        'english_text',
        'meaning',
        'reference',
        'status',
        'audio',
        'video',
        'image'
    ];

    public function sura()
    {
        return $this->belongsTo(Sura::class);
    }
}
