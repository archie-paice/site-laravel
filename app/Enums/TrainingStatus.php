<?php

namespace App\Enums;

enum TrainingStatus: int
{
    case ACTIVE = 1;
    case SOLO = 2;
    case MOCK = 3;
    case CHECKOUT = 4;
    case COMPLETE = 5;
    case FORFEIT = 6;

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::SOLO => 'Solo Cert',
            self::MOCK => 'Mock OTS',
            self::CHECKOUT => 'Checkout',
            self::COMPLETE => 'Complete',
            self::FORFEIT => 'Forfeit',
        };
    }

    public static function fromValue(int $value): ?self
    {
        return self::tryFrom($value);
    }
}
