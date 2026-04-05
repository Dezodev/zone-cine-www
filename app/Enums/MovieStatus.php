<?php

namespace App\Enums;

enum MovieStatus: string
{
    case Released       = 'Released';
    case PostProduction = 'Post Production';
    case InProduction   = 'In Production';
    case Planned        = 'Planned';
    case Rumored        = 'Rumored';
    case Canceled       = 'Canceled';

    public function label(): string
    {
        return match ($this) {
            self::Released       => 'Sorti',
            self::PostProduction => 'Post-production',
            self::InProduction   => 'En production',
            self::Planned        => 'Prévu',
            self::Rumored        => 'Annoncé',
            self::Canceled       => 'Annulé',
        };
    }
}
