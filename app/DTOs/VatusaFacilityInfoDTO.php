<?php

namespace App\DTOs;

readonly class VatusaFacilityInfoDTO {
    public int $atmId;
    public int $datmId;
    public int $taId;
    public int $wmId;
    public int $ecId;
    public int $feId;

    public function __construct(array $facilityInfo) {
        $this->atmId = $facilityInfo['facility']['info']['atm'];
        $this->datmId = $facilityInfo['facility']['info']['datm'];
        $this->taId = $facilityInfo['facility']['info']['ta'];
        $this->wmId = $facilityInfo['facility']['info']['wm'];
        $this->ecId = $facilityInfo['facility']['info']['ec'];
        $this->feId = $facilityInfo['facility']['info']['fe'];
    }
}
