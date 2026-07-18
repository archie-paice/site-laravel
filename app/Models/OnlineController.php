<?php

namespace App\Models;

use App\DTOs\OnlineControllerDTO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnlineController extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'callsign',
        'user_id',
        'start',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function fromDTO(OnlineControllerDTO $onlineControllerDTO): self
    {

        $controller = OnlineController::create([
            'callsign' => $onlineControllerDTO->callsign,
            'user_id' => $onlineControllerDTO->id,
            'start' => $onlineControllerDTO->start,
        ]);

        return $controller;
    }
}
