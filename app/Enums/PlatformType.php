<?php
namespace App\Enums;

enum PlatformType: int
{
    case LinkedIn = 1;
    case Facebook = 2;
    case Instagram = 3;
    case Twitter = 4;
    case otherWebsite = 5;

    public static function getType(int $value): self
    {
        return match ($value) {
            1 => self::LinkedIn,
            2 => self::Facebook,
            3 => self::Instagram,
            4 => self::Twitter,
            default => self::otherWebsite,
        };
    }
}
