<?php

namespace App\Enums;

enum VisitRequestStatus: int
{
    case PENDING = 0;
    case APPROVED = 1;
    case DENIED = 2;

    public function label(): string
    {
        return match ($this) {
            VisitRequestStatus::PENDING => 'Pending',
            VisitRequestStatus::APPROVED => 'Approved',
            VisitRequestStatus::DENIED => 'Denied',
        };
    }

    public static function fromInt(int $value): VisitRequestStatus
    {
        return match ($value) {
            0 => VisitRequestStatus::PENDING,
            1 => VisitRequestStatus::APPROVED,
            2 => VisitRequestStatus::DENIED,
            default => throw new \InvalidArgumentException("Invalid VisitRequestStatus value: $value"),
        };
    }
}
