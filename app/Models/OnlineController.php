<?php

namespace App\Models;

use App\DTOs\OnlineControllerDTO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OnlineController extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'callsign',
        'user_id',
        'start',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
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
