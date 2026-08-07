<?php

namespace App\Models;

use App\Enums\EventType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'presetPositions',
        'event_image_route',
        'positions_locked',
        'published',
        'archived',
        'archived_at',
    ];

    protected $casts = [
        'type' => EventType::class,
        'start' => 'datetime',
        'end' => 'datetime',
        'featured_fields' => 'array',
        'hidden' => 'boolean',
        'positions_locked' => 'boolean',
        'published' => 'boolean',
        'presetPositions' => 'array',
        'archived' => 'boolean',
        'archived_at' => 'datetime',
    ];

    public function positionRequests()
    {
        return $this->hasMany(EventPosition::class);
    }

    public function getFormattedRangeAttribute()
    {
        return $this->start?->utc()->format('m/d/Y H:i:s').'z - '.$this->end?->utc()->format('m/d/Y H:i:s').'z';
    }

    public function getFormattedTimeAttribute()
    {
        return $this->start?->utc()->format('H:i:s').'z - '.$this->end?->utc()->format('H:i:s').'z';

    }
}
