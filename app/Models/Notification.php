<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'read_at',
        'status',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread')->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read')->whereNotNull('read_at');
    }

    // Helper Methods
    public function markAsRead()
    {
        $this->update([
            'read_at' => now(),
            'status' => 'read',
        ]);
    }
}