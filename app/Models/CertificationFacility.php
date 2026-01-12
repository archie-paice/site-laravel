<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificationFacility extends Model
{
    protected $fillable = [
        'identifier',
        'name',
    ];

    public function certificationLevels()
    {
        return $this->hasMany(CertificationLevel::class, 'facility_id');
    }
}
