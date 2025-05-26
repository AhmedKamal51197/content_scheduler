<?php

namespace App\Models;

use Exception;
use App\Enums\PlatformType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    /**
     * Get the posts for the platform.
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_platform')
        ->withPivot('status');
    }
    /**
     * Set the type of the platform.
     */
    public function setTypeAttribute($value)
    {   try{
        $this->attributes['type'] = match ($value) {
            'LinkedIn' => 1,
            'Facebook' => 2,
            'Instagram' => 3,
            'Twitter' => 4,
            default => 5
        };
        }catch(Exception $e){dd($e->getMessage());}
    }
    /**
     * Get the type of the platform.
     */
    public function getTypeAttribute()
    {
        $typeValue = $this->attributes['type'] ?? null;

        if ($typeValue === null) {
            return null;
        }
    
        return PlatformType::getType($typeValue)->name;
    }
    /**
     * Get Users that have platform account.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_platform')
        ->using(UserPlatform::class)
        ->withPivot('status');
    }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
