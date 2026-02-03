<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SubscriptionCredential extends Model
{
    use HasFactory;

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

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'pin',
    ];

    // Relationships
    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function assignedToUser()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'credentials_id');
    }

    // Accessors & Mutators for Encryption
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Crypt::encryptString($value);
        }
    }

    public function getPasswordAttribute($value)
    {
        if ($value) {
            return Crypt::decryptString($value);
        }
        return null;
    }

    public function setPinAttribute($value)
    {
        if ($value) {
            $this->attributes['pin'] = Crypt::encryptString($value);
        }
    }

    public function getPinAttribute($value)
    {
        if ($value) {
            return Crypt::decryptString($value);
        }
        return null;
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }
}