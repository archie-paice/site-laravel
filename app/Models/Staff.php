<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title_short',
        'title_long',
        'user_id',
        'primary_contact'
    ];

    protected $casts = [
        'primary_contact' => 'boolean',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public static function fromFacilityInfoDTO(\App\DTOs\VatusaFacilityInfoDTO $infoDTO)
    {
        foreach ($infoDTO->roles as $role) {
            // User does not exist in users table - gather from out of division
            if (is_null(User::find($role['cid']))) {
                User::createFromVatusa($role['cid']);
            }

            switch($role['role']) {
                case 'ATM':
                    static::create([
                        'title_short' => 'ATM',
                        'title_long' => 'Air Traffic Manager',
                        'user_id' => $role['cid'],
                        'primary_contact' => true,
                    ]);

                    break;
                case 'DATM':
                    static::create([
                        'title_short' => 'DATM',
                        'title_long' => 'Deputy Air Traffic Manager',
                        'user_id' => $role['cid'],
                        'primary_contact' => true,
                    ]);

                    break;

                case 'TA':
                    static::create([
                        'title_short' => 'TA',
                        'title_long' => 'Training Administrator',
                        'user_id' => $role['cid'],
                        'primary_contact' => true,
                    ]);

                    break;

                case 'ATA':
                    static::create([
                        'title_short' => 'ATA',
                        'title_long' => 'Training Administrator',
                        'user_id' => $role['cid'],
                        'primary_contact' => false,
                    ]);

                    break;

                case 'WM':
                    static::create([
                        'title_short' => 'WM',
                        'title_long' => 'Webmaster',
                        'user_id' => $role['cid'],
                        'primary_contact' => $role['cid'] == $infoDTO->wmId,
                    ]);

                    break;

                case 'EC':
                    static::create([
                        'title_short' => 'EC',
                        'title_long' => 'Events Coordinator',
                        'user_id' => $role['cid'],
                        'primary_contact' => $role['cid'] == $infoDTO->ecId,
                    ]);

                    break;

                case 'FE':
                    static::create([
                        'title_short' => 'FE',
                        'title_long' => 'Facility Engineer',
                        'user_id' => $role['cid'],
                        'primary_contact' => $role['cid'] == $infoDTO->feId,
                    ]);

                    break;

                case 'INS':
                    static::create([
                        'title_short' => 'INS',
                        'title_long' => 'Instructor',
                        'user_id' => $role['cid'],
                        'primary_contact' => false,
                    ]);

                    break;

                case 'MTR':
                    static::create([
                        'title_short' => 'MTR',
                        'title_long' => 'Mentor',
                        'user_id' => $role['cid'],
                        'primary_contact' => false,
                    ]);

                    break;
            }
        }
    
    }
}
