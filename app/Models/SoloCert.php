<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class SoloCert extends Model
{

    use Searchable;
    protected $fillable = [
        'user_id',
        'issued_by_id',
        'position'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function issuedBy(): BelongsTo {
        return $this->belongsTo(User::class, 'issued_by_id');
    }

    public function expires(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at->addDays(30),
        );
    }

    public function toSearchableArray(): array {
        return [
            'user_id' => $this->user->fullName,
            'position' => $this->position,
            'issued_by_id' => $this->issued_by->name
        ];
    }
}
