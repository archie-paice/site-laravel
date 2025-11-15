<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingTicket extends Model
{
    public $fillable = [
        'user_id',
        'instructor_id',
        'session_date',
        'duration',
        'movements',
        'score',
        'notes',
        'location',
        'ots_status',
        'solo_granted',
        'vatusa_id',
        'vatusa_synced'
    ];

    public function student() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function instructor() {
        return $this->belongsTo('App\Models\User', 'instructor_id');
    }
}
