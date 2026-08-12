<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HadithChapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'hadith_book_id',
        'chapter_no',
        'name_en',
        'name_bn',
        'name_ar',
        'hadith_first',
        'hadith_last',
        'total_hadiths',
        'status',
    ];

    protected $casts = [
        'status'        => 'boolean',
        'chapter_no'    => 'integer',
        'hadith_first'  => 'integer',
        'hadith_last'   => 'integer',
        'total_hadiths' => 'integer',
    ];

    public function book()
    {
        return $this->belongsTo(HadithBook::class, 'hadith_book_id');
    }

    public function hadiths()
    {
        return $this->hasMany(Hadith::class)->orderBy('hadith_number');
    }
}
