<?php

namespace App\Enums;

enum TrainingType: int
{
    case S1 = 1;
    case S2 = 2;
    case S3 = 3;
    case C1 = 4;
    case MCO_GND = 5;
    case MCO_TWR = 6;
    case MCO_APP = 7;

    public function mapToString(): string {
        return match ($this) {
            self::S1 => "S1",
            self::S2 => "S2",
            self::S3 => "S3",
            self::C1 => "C1",
            self::MCO_GND => "MCO GND",
            self::MCO_TWR => "MCO TWR",
            self::MCO_APP => "F11 TRACON",
            default => "Unknown",
        };
    }


}
