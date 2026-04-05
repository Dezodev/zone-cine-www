<?php

namespace App\Enums;

enum TvShowType: string
{
    case Scripted    = 'Scripted';
    case TalkShow    = 'Talk Show';
    case Reality     = 'Reality';
    case News        = 'News';
    case Documentary = 'Documentary';
    case Miniseries  = 'Miniseries';
    case Video       = 'Video';

    public function label(): string
    {
        return match ($this) {
            self::Scripted    => 'Série fictionnelle',
            self::TalkShow    => 'Talk-show',
            self::Reality     => 'Téléréalité',
            self::News        => 'Actualités',
            self::Documentary => 'Documentaire',
            self::Miniseries  => 'Minisérie',
            self::Video       => 'Vidéo',
        };
    }
}
