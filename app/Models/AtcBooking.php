<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtcBooking extends Model
{
    protected $fillable = [
        'user_id',
        'position',
        'start',
        'end',
        'description',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
