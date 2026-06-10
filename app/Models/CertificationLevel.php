<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificationLevel extends Model
{
    protected $fillable = [
        'facility_id',
        'level',
        'name',
        'abbreviation',
    ];


    public function facility() {
        return $this->belongsTo(CertificationFacility::class, 'facility_id');
    }
}
