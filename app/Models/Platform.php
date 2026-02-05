<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Platform extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'is_active',
        'sort_order',
        'status',
        'token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from name if not provided
        static::creating(function ($platform) {
            if (empty($platform->slug)) {
                $platform->slug = Str::slug($platform->name);
            }
        });

        static::updating(function ($platform) {
            if (empty($platform->slug)) {
                $platform->slug = Str::slug($platform->name);
            }
        });
    }

    // Relationships
    public function subscriptionPlans()
    {
        return $this->hasMany(SubscriptionPlan::class);
    }
    /**
     * Get active subscription plans for the platform.
     */
    public function activeSubscriptionPlans()
    {
        return $this->hasMany(SubscriptionPlan::class)->where('is_active', true);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function userTokens()
    {
        return $this->hasMany(UserToken::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('is_active', 1);
    }
    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Get the logo URL.
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }

    /**
     * Get the platform status badge.
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
     * Get the platform active badge.
     */
    public function getActiveBadgeAttribute()
    {
        return $this->is_active
            ? '<span class="badge badge-soft-success"><i class="ti ti-check"></i> Yes</span>'
            : '<span class="badge badge-soft-danger"><i class="ti ti-x"></i> No</span>';
    }
}