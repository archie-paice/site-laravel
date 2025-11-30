<?php

namespace App\Enums;

enum TrainingStatus: string
{
    case ACTIVE = 'active';
    case SOLO = 'solo';
    case MOCK = 'mock';
    case CHECKOUT = 'checkout';
    case COMPLETE = 'complete';
    case FORFEIT = 'forfeit';

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

    public function badgeClass(): string
    {
        return match ($this) {
            self::ACTIVE => 'badge badge-accent',
            self::SOLO => 'badge badge-secondary',
            self::MOCK => 'badge badge-warning',
            self::CHECKOUT => 'badge badge-info',
            self::COMPLETE => 'badge badge-success',
            self::FORFEIT => 'badge badge-error',
        };
    }

    public static function fromValue(string $value): ?self
    {
        return self::tryFrom($value);
    }
}
