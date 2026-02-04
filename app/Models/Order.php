<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'discount_amount',
        'final_amount',
        'payment_status',
        'payment_method',
        'transaction_id',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for the order.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope a query to only include paid orders.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope a query to only include pending orders.
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope a query to only include completed orders.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to filter by payment status.
     */
    public function scopeByPaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    /**
     * Scope a query to filter by order status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get the payment status badge.
     */
    public function getPaymentStatusBadgeAttribute()
    {
        return match ($this->payment_status) {
            'paid' => '<span class="badge badge-soft-success">Paid</span>',
            'pending' => '<span class="badge badge-soft-warning">Pending</span>',
            'failed' => '<span class="badge badge-soft-danger">Failed</span>',
            'refunded' => '<span class="badge badge-soft-info">Refunded</span>',
            default => '<span class="badge badge-soft-secondary">Unknown</span>',
        };
    }

    /**
     * Get the order status badge.
     */
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'completed' => '<span class="badge badge-soft-success">Completed</span>',
            'processing' => '<span class="badge badge-soft-info">Processing</span>',
            'cancelled' => '<span class="badge badge-soft-danger">Cancelled</span>',
            'pending' => '<span class="badge badge-soft-warning">Pending</span>',
            default => '<span class="badge badge-soft-secondary">Unknown</span>',
        };
    }

    /**
     * Check if order is paid.
     */
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if order is completed.
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if order is cancelled.
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Get total items count.
     */
    public function getTotalItemsAttribute()
    {
        return $this->orderItems->sum('quantity');
    }

    /**
     * Get discount percentage.
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->total_amount > 0) {
            return ($this->discount_amount / $this->total_amount) * 100;
        }
        return 0;
    }
}