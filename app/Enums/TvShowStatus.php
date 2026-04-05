<?php

namespace App\Enums;

enum TvShowStatus: string
{
    case ReturningS  = 'Returning Series';
    case Ended       = 'Ended';
    case Canceled    = 'Canceled';
    case InProduction = 'In Production';
    case Planned     = 'Planned';
    case Pilot       = 'Pilot';

    public function label(): string
    {
        return match ($this) {
            self::ReturningS   => 'En cours',
            self::Ended        => 'Terminée',
            self::Canceled     => 'Annulée',
            self::InProduction => 'En production',
            self::Planned      => 'Prévue',
            self::Pilot        => 'Pilote',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::ReturningS   => 'badge-success',
            self::Ended        => 'badge-neutral',
            self::Canceled     => 'badge-error',
            self::InProduction => 'badge-warning',
            self::Planned      => 'badge-info',
            self::Pilot        => 'badge-info',
        };
    }
}
