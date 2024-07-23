<?php

namespace App\Enum;

enum Region: string
{
    case AFRICA = 'africa';
    case ASIA = 'asia';
    case EUROPE = 'europe';
    case NORTH_AMERICA = 'north_america';
    case OCEANIA = 'oceania';
    case SOUTH_AMERICA = 'south_america';

    public const values = [
        self::AFRICA,
        self::ASIA,
        self::EUROPE,
        self::NORTH_AMERICA,
        self::OCEANIA,
        self::SOUTH_AMERICA,
    ];

    public function name(): string
    {
        return match ($this) {
            self::AFRICA => 'Afrique',
            self::ASIA => 'Asie',
            self::EUROPE => 'Europe',
            self::NORTH_AMERICA => 'Amérique du Nord',
            self::OCEANIA => 'Océanie',
            self::SOUTH_AMERICA => 'Amérique du Sud',
        };
    }
}
