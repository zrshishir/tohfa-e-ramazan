<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hadith extends Model
{
    use HasFactory;

    protected $fillable = [
        'hadith_book_id',
        'hadith_chapter_id',
        'hadith_number',
        'arabic_number',
        'arabic_text',
        'bangla_text',
        'english_text',
        'grade',
        'reference',
        'status',
    ];

    protected $casts = [
        'status'        => 'boolean',
        'hadith_number' => 'integer',
        'arabic_number' => 'integer',
    ];

    public function book()
    {
        return $this->belongsTo(HadithBook::class, 'hadith_book_id');
    }

    public function chapter()
    {
        return $this->belongsTo(HadithChapter::class, 'hadith_chapter_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    /**
     * Free-text search across the Bangla and English translations.
     * Arabic is excluded: without diacritic normalisation a LIKE match on it is
     * unreliable, and users search in the language they read.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!filled($term)) {
            return $query;
        }

        $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $term) . '%';

        return $query->where(function (Builder $q) use ($like) {
            $q->where('bangla_text', 'like', $like)
              ->orWhere('english_text', 'like', $like);
        });
    }
}
