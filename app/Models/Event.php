<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\EventType;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start',
        'end',
        'type',
        'featured_fields',
        'hidden',
        'image_url',
        'presetPositions',
    ];

    protected $casts = [
        'type' => EventType::class,
        'start' => 'datetime',
        'end' => 'datetime',
        'featured_fields' => 'array',
        'hidden' => 'boolean',
        'presetPositions' => 'array',
    ];

    public function positionRequests() {
        return $this->hasMany(EventPosition::class);
    }
}

