<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// OrderItem Model
class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'subscription_plan_id',
        'quantity',
        'price',
        'subtotal',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Get the order that owns the item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the subscription plan for the item.
     */
    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    /**
     * Get the item status badge.
     */
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'delivered' => '<span class="badge badge-soft-success">Delivered</span>',
            'processing' => '<span class="badge badge-soft-info">Processing</span>',
            'cancelled' => '<span class="badge badge-soft-danger">Cancelled</span>',
            'pending' => '<span class="badge badge-soft-warning">Pending</span>',
            default => '<span class="badge badge-soft-secondary">Unknown</span>',
        };
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-calculate subtotal before saving
        static::saving(function ($item) {
            $item->subtotal = $item->price * $item->quantity;
        });
    }
}