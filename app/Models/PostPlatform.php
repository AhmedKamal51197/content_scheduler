<?php

namespace App\Models;

use App\Enums\PlatformStatus;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PostPlatform extends Pivot
{
    protected $table = 'post_platform';
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'integer',
    ];

    protected $fillable = [
        'post_id',
        'platform_id',
        'status',
    ];

    public function setStatusAttribute($value)
    {
        if (is_int($value)) {
            // Convert int (0,1) to enum using getStatus()
            $enum = PlatformStatus::getStatus($value);
        } elseif (is_string($value)) {
            // Convert string ("Active","Inactive") to enum using tryFromName()
            $enum = PlatformStatus::tryFromName($value);

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
        
        return PlatformStatus::getStatus($this->attributes['status'])->name;
    } 
}
