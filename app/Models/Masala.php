<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masala extends Model
{
    use HasFactory;

    protected $fillable = [
        'masala_category_id',
        'question',
        'answer',
        'reference',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status'     => 'boolean',
        'sort_order' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(MasalaCategory::class, 'masala_category_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    /** Free-text search across the question and answer. */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!filled($term)) {
            return $query;
        }

        $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $term) . '%';

        return $query->where(function (Builder $q) use ($like) {
            $q->where('question', 'like', $like)
              ->orWhere('answer', 'like', $like);
        });
    }
}
