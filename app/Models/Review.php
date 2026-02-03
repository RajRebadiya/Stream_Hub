<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'platform_id',
        'rating',
        'review',
        'is_approved',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved')->where('is_approved', 1);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}