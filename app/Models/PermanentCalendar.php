<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermanentCalendar extends Model
{
    use HasFactory;

    protected $table = 'permanent_calendars';

    /**
     * These previously listed eight columns that do not exist on the table
     * (`sehri_time`, `fazr_time`, `magrib_and_iftar_time`, ...), so every
     * `PermanentCalendar::create()` silently discarded its payload and inserted
     * a row containing nothing but timestamps.
     *
     * The real columns are the JSON prayer blobs below. There is no `iftar`
     * column — iftar is derived from `magrib` in PermanentCalendarController.
     */
    protected $fillable = [
        'month_id',
        'day',
        'sehri',
        'fazr',
        'sunrise',
        'ishraq',
        'johr',
        'asr',
        'magrib',
        'esha',
        'tahazzud',
        'jummah',
        'forbidden',
    ];
    protected $casts = [
        'sehri' => 'json',
        'fazr' => 'json',
        'sunrise' => 'json',
        'ishraq' => 'json',
        'johr' => 'json',
        'asr' => 'json',
        'magrib' => 'json',
        'esha' => 'json',
        'tahazzud' => 'json',
        'jummah' => 'json',
        'forbidden' => 'json',
    ];

    public function month()
    {
        return $this->belongsTo( Month::class );
    }
}
