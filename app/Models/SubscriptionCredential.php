<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SubscriptionCredential extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subscription_plan_id',
        'email',
        'password',
        'profile_name',
        'pin',
        'status',
        'notes',
        'assigned_to_user_id',
        'assigned_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'pin',
    ];

    /**
     * Get the subscription plan that owns the credential.
     */
    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    /**
     * Get the user to whom this credential is assigned.
     */
    public function assignedToUser()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    /**
     * Set the password attribute with encryption.
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Crypt::encryptString($value);
        }
    }

    /**
     * Get the decrypted password attribute.
     */
    public function getPasswordAttribute($value)
    {
        if (!empty($value)) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * Set the pin attribute with encryption.
     */
    public function setPinAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['pin'] = Crypt::encryptString($value);
        }
    }

    /**
     * Get the decrypted pin attribute.
     */
    public function getPinAttribute($value)
    {
        if (!empty($value)) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * Scope a query to only include available credentials.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope a query to only include assigned credentials.
     */
    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    /**
     * Scope a query to only include expired credentials.
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    /**
     * Scope a query to only include blocked credentials.
     */
    public function scopeBlocked($query)
    {
        return $query->where('status', 'blocked');
    }

    /**
     * Scope a query to filter by subscription plan.
     */
    public function scopeByPlan($query, $planId)
    {
        return $query->where('subscription_plan_id', $planId);
    }

    /**
     * Scope a query to filter by assigned user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('assigned_to_user_id', $userId);
    }

    /**
     * Get the status badge HTML.
     */
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'available' => '<span class="badge badge-soft-success">Available</span>',
            'assigned' => '<span class="badge badge-soft-info">Assigned</span>',
            'expired' => '<span class="badge badge-soft-warning">Expired</span>',
            'blocked' => '<span class="badge badge-soft-danger">Blocked</span>',
            default => '<span class="badge badge-soft-secondary">Unknown</span>',
        };
    }

    /**
     * Check if credential is available.
     */
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    /**
     * Check if credential is assigned.
     */
    public function isAssigned()
    {
        return $this->status === 'assigned' && !is_null($this->assigned_to_user_id);
    }

    /**
     * Check if credential is blocked.
     */
    public function isBlocked()
    {
        return $this->status === 'blocked';
    }

    /**
     * Assign credential to user.
     */
    public function assignTo($userId)
    {
        $this->update([
            'assigned_to_user_id' => $userId,
            'assigned_at' => now(),
            'status' => 'assigned',
        ]);
    }

    /**
     * Unassign credential from user.
     */
    public function unassign()
    {
        $this->update([
            'assigned_to_user_id' => null,
            'assigned_at' => null,
            'status' => 'available',
        ]);
    }

    /**
     * Mark credential as blocked.
     */
    public function block()
    {
        $this->update(['status' => 'blocked']);
    }

    /**
     * Mark credential as available.
     */
    public function makeAvailable()
    {
        $this->update([
            'status' => 'available',
            'assigned_to_user_id' => null,
            'assigned_at' => null,
        ]);
    }

    /**
     * Get masked password for display.
     */
    public function getMaskedPasswordAttribute()
    {
        if (!empty($this->password)) {
            return str_repeat('*', 8);
        }
        return null;
    }

    /**
     * Get masked pin for display.
     */
    public function getMaskedPinAttribute()
    {
        if (!empty($this->pin)) {
            return str_repeat('*', 4);
        }
        return null;
    }
}