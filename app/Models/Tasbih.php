<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasbih extends Model
{
    use HasFactory;

    protected $table = 'tasbih';

    protected $fillable = [
        'user_id',
        'subhanallah',
        'alhamdulillah',
        'allahuakbar',
        'astagfirullah',
        'laillahaillallah',
        'subhanallahiwalhamdulillahi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}