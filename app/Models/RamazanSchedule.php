<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RamazanSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ramazan_schedules';
    protected $fillable = [
        'district_id',
        'roza_no',
        'title',
        'sehri_time',
        'iftar_time',
        'date',
    ];
}
