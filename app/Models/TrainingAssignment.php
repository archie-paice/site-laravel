<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingAssignment extends Model
{
    protected $fillable = [
        'id',
        'instructor_id',
        'training_type',
        'trainee_id',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
