<?php

namespace App\DTOs;
class VisitingChecklistDTO
{
    public bool $error = false;
    public bool $hasHomeFacility;
    public bool $needsBasic;
    public int $visitingDays;
    public bool $visitingDaysMet; // 60 days
    public bool $ninetyDaysSincePromotion;
    public bool $fiftyHoursSincePromotion; 
    public bool $visitEligible;

    public function __construct(array|null $data)
    {
        if (is_null($data)) {
            $this->error = true;
            $this->visitEligible = false;
            return;
        }

        $data = $data['data'];

        $this->hasHomeFacility = $data['homecontroller'];
        $this->needsBasic = $data['needbasic'];
        $this->visitingDays = $data['visitingDays'] ?? 0;
        $this->visitingDaysMet = $data['60days'];
        $this->ninetyDaysSincePromotion = $data['90days'];
        $this->fiftyHoursSincePromotion = $data['50hrs'];
        $this->visitEligible = $data['visiting'];
    }
}

/*
{
  "data": {
    "homecontroller": true,
    "needbasic": true,
    "pending": true,
    "initial": true,
    "90days": true,
    "promo": true,
    "50hrs": true,
    "override": false,
    "is_first": false,
    "days": 530,
    "visitingDays": 21,
    "60days": false,
    "hasHome": true,
    "hasRating": true,
    "instructor": true,
    "staff": false,
    "visiting": false,
    "overall": false
  },
  "testing": false
}*/