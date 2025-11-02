<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OnlineController extends Model
{
    protected $fillable = [
        'callsign',
        'user_id',
        'start'
    ];

    public function user(): HasOne {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
