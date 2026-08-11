<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ayat_id',
        'sura_id',
        'ayat_no',
        'page',
    ];

    protected $casts = [
        'ayat_no' => 'integer',
        'page'    => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ayat()
    {
        return $this->belongsTo(Ayat::class);
    }

    public function sura()
    {
        return $this->belongsTo(Sura::class);
    }
}
