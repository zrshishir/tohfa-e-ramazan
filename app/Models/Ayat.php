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
