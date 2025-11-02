<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class StatisticsPrefixes extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    protected function name(): Attribute {
        return Attribute::make(
            get: fn($value) => strtoupper($value),
            set: fn($value) => strtoupper($value)
        );
    }
}
