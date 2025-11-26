<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TrainingAssignment extends Model
{
    use LogsActivity, Searchable;
    protected $fillable = [
        'id',
        'instructor_id',
        'training_type',
        'user_id',
        'status',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id', 'instructor_id', 'status']);
    }

    public function toSearchableArray(): array {
        return [
            'name' => $this->student->name,
            'trainingType' => $this->training_type,
            'status' => $this->status,
            'date' => $this->created_at
        ];
    }
}
