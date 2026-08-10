<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasbih extends Model
{
    use HasFactory;

    protected $table = 'tasbih';

    /**
     * The `tasbih` table has exactly two writable columns: user_id and tasbih.
     * `tasbih` is a JSON array of dhikr objects, each shaped like:
     *   text_en, text_bn, text_ar, reset_on,
     *   count, today_count, monthly_count, yearly_count, total_count
     */
    protected $fillable = [
        'user_id',
        'tasbih',
    ];

    protected $casts = [
        'tasbih' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
