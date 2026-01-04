<?php

namespace App\Models;

use App\Enums\VisitRequestStatus;
use Illuminate\Database\Eloquent\Model;

class VisitorRequest extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'reason',
        'admin_notes',
    ];

    public function casts() {
        return [
            'status' => VisitRequestStatus::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
