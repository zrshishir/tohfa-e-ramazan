<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DistrictWiseScheduleSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'district_id',
        'sehri_time',
        'iftar_time',
        'is_active',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}