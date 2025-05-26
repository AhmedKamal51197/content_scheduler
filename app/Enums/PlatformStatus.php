<?php 
namespace App\Enums;
enum PlatformStatus: int{
    case Active = 1 ;
    case Inactive = 0 ;
    case draft = 2;
    case published = 3;
    case scheduled = 4;

    public static function getStatus(int $value)
    {
        return match($value)
        {
            1 => self::Active,
            0 => self::Inactive,
            2 => self::draft,
            3 => self::published,
            4 => self::scheduled,
            default => throw new \InvalidArgumentException("Invalid status value: $value"),
        };
    }
    public static function tryFromName(string $name): ?self
    {
        foreach (self::cases() as $case) {
            if (strcasecmp($case->name, $name) === 0) { // case-insensitive match
                return $case;
            }
        }
        return null;
    }
}