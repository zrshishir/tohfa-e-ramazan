<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'doa_for',
        'description',
        'arabic_text',
        'bangla_text',
        'english_tex',
        'reference',
        'when_to_use',
        'notes',
        'when_not_to_use',
        'status'
    ]
}
