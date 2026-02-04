<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'city',
        'state',
        'pincode',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active profiles.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to filter by state.
     */
    public function scopeByState($query, $state)
    {
        return $query->where('state', $state);
    }

    /**
     * Scope a query to filter by city.
     */
    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Get the full address.
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->pincode,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get the location (city, state).
     */
    public function getLocationAttribute()
    {
        $parts = array_filter([$this->city, $this->state]);
        return implode(', ', $parts);
    }

    /**
     * Check if profile is complete.
     */
    public function getIsCompleteAttribute()
    {
        return !empty($this->phone) &&
            !empty($this->address) &&
            !empty($this->city) &&
            !empty($this->state) &&
            !empty($this->pincode);
    }

    /**
     * Get profile completion percentage.
     */
    public function getCompletionPercentageAttribute()
    {
        $fields = ['phone', 'address', 'city', 'state', 'pincode'];
        $completed = 0;

        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $completed++;
            }
        }

        return ($completed / count($fields)) * 100;
    }

    /**
     * Get the status badge HTML.
     */
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'active' => '<span class="badge badge-soft-success">Active</span>',
            'inactive' => '<span class="badge badge-soft-danger">Inactive</span>',
            default => '<span class="badge badge-soft-secondary">Unknown</span>',
        };
    }

    /**
     * Check if profile has phone number.
     */
    public function hasPhone()
    {
        return !empty($this->phone);
    }

    /**
     * Check if profile has address.
     */
    public function hasAddress()
    {
        return !empty($this->address) || !empty($this->city) || !empty($this->state);
    }

    /**
     * Get formatted phone number.
     */
    public function getFormattedPhoneAttribute()
    {
        if (empty($this->phone)) {
            return null;
        }

        // Simple formatting - can be customized based on requirements
        return $this->phone;
    }
}