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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('is_active', 1);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_available', '>', 0);
    }
}