<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\DTOs\VatusaRosterUser;
use App\Enums\ControllerRating;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'rating',
        'joined_at',
        'division',
        'facility',
        'rostered',
        'discord_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'rating' => ControllerRating::class,
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public static function updateFromVatusa(VatusaRosterUser $vatusaUser) {
        $user = static::upsert([
            'id' => $vatusaUser->cid,
            'first_name' => $vatusaUser->firstName,
            'last_name' => $vatusaUser->lastName,
            'email' => $vatusaUser->email,
            'rating' => $vatusaUser->rating,
            'joined_at' => $vatusaUser->joinedFacility,
            'division' => 'USA',
            'facility' => $vatusaUser->facility,
            'rostered' => true,
            'discord_id' => $vatusaUser->discordId
        ],
        ['id']);
    }
}
