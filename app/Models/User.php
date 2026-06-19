<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\DTOs\VatusaRosterUser;
use App\Enums\ControllerRating;
use Http;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
            'joined_at' => 'datetime',
            'rostered' => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::created(function ($user) {
            $user->assignRole('core'); // default role
        });
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

        return $user;
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

    public function staffRoles(): HasMany {
        return $this->hasMany(Staff::class, 'user_id');
    }

    public function trainingAssignmentsAsStudent(): HasMany {
        return $this->hasMany(TrainingAssignment::class, 'user_id')->orderBy('created_at', 'desc');
    }

    public function trainingAssignmentsAsInstructor(): HasMany {
        return $this->hasMany(TrainingAssignment::class, 'instructor_id');
    }

    public function trainingTicketsAsStudent(): HasMany {
        return $this->hasMany(TrainingTicket::class, 'user_id')->orderBy('created_at', 'desc');
    }

    public function trainingTicketsAsInstructor(): HasMany {
        return $this->hasMany(TrainingAssignment::class, 'instructor_id');
    }

    public function soloCerts(): HasMany {
        return $this->hasMany(SoloCert::class, 'user_id')->orderBy('created_at', 'desc');
    }

    public function visitRequests(): HasMany {
        return $this->hasMany(VisitorRequest::class, 'user_id')->orderBy('created_at', 'desc');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['rating', 'email', 'first_name', 'last_name', 'id', 'operating_initials']);
    }

    public function certifications(): HasMany {
        return $this->hasMany(UserCertification::class, 'user_id');
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

    public function events() {
        return $this->belongsToMany(Event::class, 'event_positions')
                    ->withPivot('requested_position', 'start', 'end', 'note', 'position_status')
                    ->withTimestamps();
    }

    public static function createFromVatusa(int $id) {
        $userData = Http::get(config('app.vatusa_api_url') . '/v2/user/' . $id, [
            'apikey' => config('app.vatusa_api_key')
        ])->throw()->json()['data'] ?? throw new \Exception('Failed to fetch user data for CID ' . $id);

        $vatusaUser = new VatusaRosterUser($userData);
        self::updateFromVatusa($vatusaUser);
    }
}
