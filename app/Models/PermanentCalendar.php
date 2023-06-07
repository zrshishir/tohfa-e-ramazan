<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermanentCalendar extends Model
{
    use HasFactory;

    protected $table = 'permanent_calendars';
    protected $fillable = [
        'sehri_time',
        'fazr_time',
        'sunrise_time',
        'ishraq_time',
        'johr_time',
        'asr_time',
        'magrib_and_iftar_time',
        'esha_time',
    ];
}
