<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mazhab extends Model
{
    use HasFactory;

    protected $table = 'mazhabs';

    protected $fillable = [
        'name',
    ];
}