<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $primaryKey = 'title_short';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'title_short',
        'title_long',
        'user_id'
    ];

    public function user() {
        return $this->hasOne(User::class);
    }

    public static function fromFacilityInfoDTO(\App\DTOs\VatusaFacilityInfoDTO $infoDTO)
    {
        static::upsert([
            'title_short' => 'ATM',
            'title_long' => 'Air Traffic Manager',
            'user_id' => $infoDTO->atmId
        ], ['title_short']);

        static::upsert([
            'title_short' => 'DATM',
            'title_long' => 'Deputy Air Traffic Manager',
            'user_id' => $infoDTO->datmId
        ], ['title_short']);

        static::upsert([
            'title_short' => 'TA',
            'title_long' => 'Training Administrator',
            'user_id' => $infoDTO->taId
        ], ['title_short']);

        static::upsert([
            'title_short' => 'WM',
            'title_long' => 'Webmaster',
            'user_id' => $infoDTO->wmId
        ], ['title_short']);

        static::upsert([
            'title_short' => 'EC',
            'title_long' => 'Events Coordinator',
            'user_id' => $infoDTO->ecId
        ], ['title_short']);

        static::upsert([
            'title_short' => 'FE',
            'title_long' => 'Facility Engineer',
            'user_id' => $infoDTO->feId
        ], ['title_short']);
    }
}
