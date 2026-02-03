<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform_id',
        'name',
        'duration_months',
        'original_price',
        'selling_price',
        'discount_percentage',
        'max_screens',
        'quality',
        'description',
        'features',
        'is_active',
        'stock_available',
        'status',
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
        'duration_months' => 'integer',
        'max_screens' => 'integer',
        'stock_available' => 'integer',
    ];
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-calculate discount percentage before saving
        static::saving(function ($plan) {
            if ($plan->original_price > 0 && $plan->selling_price > 0 && $plan->original_price > $plan->selling_price) {
                $plan->discount_percentage = (($plan->original_price - $plan->selling_price) / $plan->original_price) * 100;
            } else {
                $plan->discount_percentage = 0;
            }
        });
    }

    // Relationships
    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function credentials()
    {
        return $this->hasMany(SubscriptionCredential::class);
    }

    /**
     * Scope a query to only include active plans.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'active');
    }

    /**
     * Scope a query to only include plans with available stock.
     */
    public function scopeInStock($query)
    {
        return $query->where('stock_available', '>', 0);
    }

    /**
     * Scope a query to filter by platform.
     */
    public function scopeByPlatform($query, $platformId)
    {
        return $query->where('platform_id', $platformId);
    }

    /**
     * Scope a query to order by duration.
     */
    public function scopeOrderedByDuration($query)
    {
        return $query->orderBy('duration_months', 'asc');
    }

    /**
     * Get the formatted duration.
     */
    public function getFormattedDurationAttribute()
    {
        return $this->duration_months . ' ' . ($this->duration_months == 1 ? 'Month' : 'Months');
    }

    /**
     * Get the savings amount.
     */
    public function getSavingsAmountAttribute()
    {
        return $this->original_price - $this->selling_price;
    }

    /**
     * Get the monthly price.
     */
    public function getMonthlyPriceAttribute()
    {
        return $this->selling_price / $this->duration_months;
    }

    /**
     * Check if plan is in stock.
     */
    public function getIsInStockAttribute()
    {
        return $this->stock_available > 0;
    }

    /**
     * Check if plan has discount.
     */
    public function getHasDiscountAttribute()
    {
        return $this->discount_percentage > 0;
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
     * Get the active badge HTML.
     */
    public function getActiveBadgeAttribute()
    {
        return $this->is_active
            ? '<span class="badge badge-soft-success"><i class="ti ti-check"></i> Yes</span>'
            : '<span class="badge badge-soft-danger"><i class="ti ti-x"></i> No</span>';
    }

    /**
     * Get the stock badge HTML.
     */
    public function getStockBadgeAttribute()
    {
        if ($this->stock_available > 10) {
            return '<span class="badge badge-soft-success">' . $this->stock_available . ' Available</span>';
        } elseif ($this->stock_available > 0) {
            return '<span class="badge badge-soft-warning">' . $this->stock_available . ' Left</span>';
        } else {
            return '<span class="badge badge-soft-danger">Out of Stock</span>';
        }
    }

    /**
     * Decrease stock when purchased.
     */
    public function decreaseStock($quantity = 1)
    {
        if ($this->stock_available >= $quantity) {
            $this->decrement('stock_available', $quantity);
            return true;
        }
        return false;
    }

    /**
     * Increase stock (for refunds/cancellations).
     */
    public function increaseStock($quantity = 1)
    {
        $this->increment('stock_available', $quantity);
        return true;
    }
}