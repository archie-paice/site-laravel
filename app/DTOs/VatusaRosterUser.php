<?php

namespace App\DTOs;

use App\Enums\ControllerRating;
use DateTime;
use Exception;

readonly class VatusaRosterUser
{
    public int $cid;

    public string $firstName;

    public string $lastName;

    public string $email;

    public string $facility;

    public DateTime $joinedFacility;

    public DateTime $lastActivity;

    public ControllerRating $rating;

    public DateTime $createdAt;

    public DateTime $updatedAt;

    public ?string $discordId;

    public bool $needsBasic;

    public bool $hasTransferOverride;

    public bool $isHomeController;

    public bool $broadcastOptIn;

    public bool $namePrivacy;

    public bool $preventStaffAssingnment;

    public bool $isPromotionEligible;

    public bool $isTransferEligible;

    public bool $isMentor;

    public bool $isSupOrIns;

    public DateTime $lastPromotion;

    public array $roles;

    public function __construct(array $data)
    {
        try {
            $this->cid = $data['cid'];
            $this->firstName = $data['fname'];
            $this->lastName = $data['lname'];
            $this->rating = ControllerRating::from($data['rating']);
            $this->email = $data['email'];
            $this->facility = $data['facility'];
            $this->createdAt = new DateTime($data['created_at']);
            $this->updatedAt = new DateTime($data['updated_at']);
            $this->needsBasic = $data['flag_needbasic'] || false;
            $this->hasTransferOverride = $data['flag_xferOverride'] || false;
            $this->joinedFacility = new DateTime($data['facility_join']);
            $this->isHomeController = $data['flag_homecontroller'] || false;
            $this->lastActivity = new DateTime($data['lastactivity']);
            $this->broadcastOptIn = $data['flag_broadcastOptedIn'] || false;
            $this->preventStaffAssingnment = $data['flag_preventStaffAssign'] || false;
            $this->discordId = $data['discord_id'];
            $this->namePrivacy = $data['flag_nameprivacy'] || false;
            $this->isPromotionEligible = $data['promotion_eligible'] || false;
            $this->isTransferEligible = $data['transfer_eligible'] || false;
            $this->roles = $data['roles'];
            $this->isMentor = $data['isMentor'] || false;
            $this->isSupOrIns = $data['isSupIns'] || false;
            $this->lastPromotion = new DateTime($data['last_promotion']);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
/*
"cid": 1834068,
"fname": "David",
"lname": "Acero",
"email": "colombia9611@gmail.com",
"facility": "ZJX",
"rating": 1,
"created_at": "2025-01-14T00:25:17+00:00",
"updated_at": "2025-10-30T15:05:07+00:00",
"flag_needbasic": false,
"flag_xferOverride": false,
"facility_join": "2025-06-27T03:25:28+00:00",
"flag_homecontroller": true,
"lastactivity": "2025-09-21T21:31:09+00:00",
"flag_broadcastOptedIn": false,
"flag_preventStaffAssign": false,
"discord_id": 852243573511290900,
"last_cert_sync": "2025-10-30 15:05:07",
"flag_nameprivacy": true,
"last_competency_date": null,
"promotion_eligible": true,
"transfer_eligible": null,
"roles": [],
"rating_short": "OBS",
"isMentor": false,
"isSupIns": false,
"last_promotion": null,
"membership": "home"*/
