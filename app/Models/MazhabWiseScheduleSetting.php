<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MazhabWiseScheduleSetting extends Model
{
    use HasFactory;

    protected $table = 'mazhab_wise_schedule_settings';

    protected $fillable = [
        'mazhab_id',
        'sehri_time',
        'fazr_time',
        'ishraq_time',
        'johr_time',
        'asr_time',
        'magrib_time',
        'iftar_time',
        'esha_time',
    ];

    public function mazhab()
    {
        return $this->belongsTo(Mazhab::class);
    }
}