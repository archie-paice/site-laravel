<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventPosition extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'requested_position',
        'assigned_position',
        'start',
        'end',
        'notes',
        'position_status',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
}