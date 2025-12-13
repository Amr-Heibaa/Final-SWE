<?php

namespace App\Enums;

enum SizeEnum: string
{
    case XS   = 'XS';
    case S    = 'S';
    case M    = 'M';
    case L    = 'L';
    case XL   = 'XL';
    case XXL  = 'XXL';
    case XXXL = '3XL';

    public function label(): string
    {
        return match($this) {
            self::XS   => 'Extra Small',
            self::S    => 'Small',
            self::M    => 'Medium',
            self::L    => 'Large',
            self::XL   => 'Extra Large',
            self::XXL  => 'Extra Extra Large',
            self::XXXL => '3X Large',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function sortOrder(): int
    {
        return match($this) {
            self::XS   => 1,
            self::S    => 2,
            self::M    => 3,
            self::L    => 4,
            self::XL   => 5,
            self::XXL  => 6,
            self::XXXL => 7,
        };
    }
}