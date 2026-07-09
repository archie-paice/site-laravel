<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class UserCertification extends Model
{
    protected $fillable = [
        'user_id',
        'certification_level_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function certificationLevel(): BelongsTo
    {
        return $this->belongsTo(CertificationLevel::class, 'certification_level_id');
    }

    public function facility(): HasOneThrough
    {
        return $this->hasOneThrough(
            CertificationFacility::class,
            CertificationLevel::class,
            'id',           // CertificationLevel.id
            'id',           // CertificationFacility.id
            'certification_level_id', // UserCertification.certification_level_id
            'facility_id',  // CertificationLevel.facility_id
        );
    }
}
