<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificationLevel extends Model
{
    protected $fillable = [
        'facility_id',
        'certification_level',
        'certification_name',
        'abbreviation',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function facility() {
        return $this->belongsTo(CertificationFacility::class, 'facility_id');
    }
}
