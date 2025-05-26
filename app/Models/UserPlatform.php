<?php

namespace App\Models;

use App\Enums\PostPlatformStatus;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserPlatform extends Pivot
{
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'integer',
    ];

    protected $fillable = [
        'user_id',
        'platform_id',
        'status',
    ];
    public function setStatusAttribute($value)
    {
        if (is_int($value)) {
            // Convert int (0,1) to enum using getStatus()
            $enum = PostPlatformStatus::getStatus($value);
        } elseif (is_string($value)) {
            // Convert string ("Active","Inactive") to enum using tryFromName()
            $enum = PostPlatformStatus::tryFromName($value);

            if ($enum === null) {
                throw new \InvalidArgumentException("Invalid status name: {$value}");
            }
        } else {
            throw new \InvalidArgumentException('Invalid type for status attribute');
        }

        $this->attributes['status'] = $enum->value;
    }

    public function getStatusAttribute()
    {
        
        return PostPlatformStatus::getStatus($this->attributes['status'])->name;
    }   
}
