<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventPositionPreset extends Model
{
    protected $table = 'event_position_preset';
    
    protected $fillable = [
        'name',
        'positions',
    ];

    protected $casts = [
        'positions' => 'array',
    ];
}
