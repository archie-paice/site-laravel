<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Feedback extends Model
{
    use Searchable;

    public const EXPERIENCES = ['Outstanding', 'Very Good', 'Good', 'Okay', 'Poor'];

    public const STATUS_PENDING = 'pending';

    public const STATUS_STASHED = 'stashed';

    public const STATUS_RELEASED = 'released';

    protected $table = 'feedback';

    protected $fillable = [
        'user_id',
        'controller_id',
        'position',
        'experience',
        'staff_followup',
        'comments',
        'status',
    ];

    protected $casts = [
        'staff_followup' => 'boolean',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function controller() {
        return $this->belongsTo(User::class, 'controller_id');
    }

    public function toSearchableArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'controller_id' => $this->controller_id,
            'submitter_name' => $this->user->name,
            'submitter_email' => $this->user->email,
            'controller_name' => $this->controller->name,
            'position' => $this->position,
            'experience' => $this->experience,
            'status' => $this->status,
        ];
    }
}
