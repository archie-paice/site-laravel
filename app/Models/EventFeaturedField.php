<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeaturedField extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_featured_fields', 'featured_field_id', 'event_id')
                    ->withTimestamps();
    }
}
