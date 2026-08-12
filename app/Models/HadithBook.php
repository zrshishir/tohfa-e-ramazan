<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HadithBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name_en',
        'name_bn',
        'name_ar',
        'author',
        'total_hadiths',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status'        => 'boolean',
        'total_hadiths' => 'integer',
        'sort_order'    => 'integer',
    ];

    public function chapters()
    {
        return $this->hasMany(HadithChapter::class)->orderBy('chapter_no');
    }

    public function hadiths()
    {
        return $this->hasMany(Hadith::class);
    }
}
