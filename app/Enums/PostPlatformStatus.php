<?php 
namespace App\Enums;
enum PostPlatformStatus: int{
    case draft = 1;
    case published = 2;
    case scheduled = 3;

    public static function getStatus(int $value)
    {
        return match($value)
        {
           
            1 => self::draft,
            2 => self::published,
            3 => self::scheduled,
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