<?php

namespace App\DTOs;

use DateTime;

readonly class OnlineControllerDTO
{
    public int $id;

    public string $callsign;

    public DateTime $start;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->callsign = $data['callsign'];
        $this->start = new DateTime($data['start']);
    }
}
