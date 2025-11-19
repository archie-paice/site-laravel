<?php
// https://vatusa-api.ztlartcc.org/#tag/training/paths/~1user~1%7Bcid%7D~1training~1record/post
namespace App\Models;

use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TrainingTicket extends Model
{
    use LogsActivity;
    use Searchable;

    public $fillable = [
        'user_id',
        'instructor_id',
        'movements',
        'score',
        'notes',
        'location',
        'ots_status',
        'solo_granted',
        'vatusa_id',
        'vatusa_synced',
        'position',
        'session_start',
        'session_end',
    ];

    public function student() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function instructor() {
        return $this->belongsTo('App\Models\User', 'instructor_id');
    }

    public function duration(): Attribute {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                try {
                    $startDate = new DateTime($attributes['session_start']);
                    $endDate = new DateTime($attributes['session_end']);
                    $duration = $startDate->diff($endDate);
                } catch (Exception $e) {
                    return "00:00";
                }

                return $duration->format('%H:%I');
            }
        );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id', 'instructor_id', 'session_date', 'duration', 'movements', 'score', 'notes', 'location', 'ots_status']);
    }

    public function toSearchableArray(): array
    {
        return [
            'user_id' => $this->student->id,
            'instructor_id' => $this->instructor->id,
            'student_name' => $this->student->name,
            'instructor_name' => $this->instructor->name,
            'position' => $this->position,
            'date' => $this->session_start
        ];
    }
}
