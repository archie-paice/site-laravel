<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\DTOs\VatusaRosterUser;
use App\Enums\ControllerRating;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, LogsActivity, Searchable;

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
        'discord_id',
        'operating_initials'
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
            'first_name' => ucfirst($vatusaUser->firstName),
            'last_name' => ucfirst($vatusaUser->lastName),
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

    protected function operatingInitials(): Attribute {
        return Attribute::make(
            get: fn($value) => strtoupper($value),
            set: fn($value) => strtoupper($value)
        );
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => ucfirst($attributes['first_name']) . ' ' . ucfirst($attributes['last_name'])
        );
    }

    protected function nameReversed(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => ucfirst($attributes['last_name']. ', ' . ucfirst($attributes['first_name']))
        );
    }

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => ucfirst($value),
        );
    }

    protected function lastName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => ucfirst($value),
        );
    }

    public function staffRoles() {
        return $this->hasMany(Staff::class);
    }

    public function trainingAssignmentsAsStudent() {
        return $this->hasMany(TrainingAssignment::class, 'trainee_id')->orderBy('created_at', 'desc');
    }

    public function trainingAssignmentsAsInstructor() {
        return $this->hasMany(TrainingAssignment::class, 'instructor_id');
    }

    public function trainingTicketsAsStudent() {
        return $this->hasMany(TrainingTicket::class, 'user_id')->orderBy('created_at', 'desc');
    }

    public function trainingTicketsAsInstructor() {
        return $this->hasMany(TrainingAssignment::class, 'instructor_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['rating', 'email', 'first_name', 'last_name', 'id', 'operating_initials']);
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'id' => $this->id,
            'rating' => $this->rating->mapToString(),
            'facility' => $this->facility
        ];
    }
}
