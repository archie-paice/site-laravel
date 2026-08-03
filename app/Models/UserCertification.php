<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCertification extends Model
{
    protected $fillable = [
        'user_id',
        'facility_certification_level_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function certificationLevel()
    {
        return $this->belongsTo(CertificationLevel::class, 'facility_certification_level_id');
    }

    public function facility()
    {
        return $this->certificationLevel->facility();
    }
}
