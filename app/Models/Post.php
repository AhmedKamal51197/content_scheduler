<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable =[
        'title',
        'content',
        'user_id',
        'image_url',
        'scheduled_time',
        'status'
    ];
    
    protected $casts = [
        'scheduled_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get the platforms for the post.
     */
    public function platforms()
    {
        return $this->belongsToMany(Platform::class, 'post_platform')
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
    public function getScheduledTimeAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    
}
