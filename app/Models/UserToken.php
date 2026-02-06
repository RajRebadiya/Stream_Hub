<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'platform_id',
        'token',
        'ip_address',
        'status',
        'use_status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the token
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the platform associated with the token
     */
    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    /**
     * Scope to get only active tokens
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get only inactive tokens
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope to get tokens for a specific user and platform
     */
    public function scopeForUserAndPlatform($query, $userId, $platformId)
    {
        return $query->where('user_id', $userId)->where('platform_id', $platformId);
    }
}
