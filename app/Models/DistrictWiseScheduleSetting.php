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
        // Minutes relative to Dhaka. The Islamic Foundation publishes separate values
        // for sehri and iftar, so one combined offset cannot represent a district.
        'sehri_offset',
        'iftar_offset',
        'is_active',
    ];

    protected $casts = [
        'sehri_offset' => 'integer',
        'iftar_offset' => 'integer',
        'is_active'    => 'boolean',
    ];

    public function district() {
        return $this->belongsTo(District::class);
    }
}
