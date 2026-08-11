<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasalaCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_bn',
        'name_ar',
        'slug',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status'     => 'boolean',
        'sort_order' => 'integer',
    ];

    public function masalas()
    {
        return $this->hasMany(Masala::class)->orderBy('sort_order')->orderBy('id');
    }
}
