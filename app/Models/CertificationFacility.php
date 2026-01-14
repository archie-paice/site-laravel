<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Model;
use Str;

class CertificationFacility extends Model
{
    protected $fillable = [
        'identifier',
        'name',
    ];

    protected function identifier(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => Str::upper($value),
        );
    }

    public function certificationLevels()
    {
        return $this->hasMany(CertificationLevel::class, 'facility_id');
    }

    public function defaultCertificationLevel()
    {
        return $this->hasOne(CertificationLevel::class, 'default_certification_level_id');
    }
}
