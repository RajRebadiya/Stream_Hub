<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionCredential;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegistrationSubscriptionService
{
    /**
     * Create auto-order for user after registration with selected subscriptions
     * 
     * Supports future payment gateway integration - payment_status stays flexible
     * 
     * @param User $user
     * @param array $subscriptionPlanIds - Subscription plan IDs to subscribe
     * @param string $paymentStatus - 'free', 'pending', 'paid' (default: 'free' for registration)
     * @return Order|null
     */
    public function createRegistrationOrder(User $user, array $subscriptionPlanIds = [], string $paymentStatus = 'free'): ?Order
    {
        if (empty($subscriptionPlanIds)) {
            return null; // No subscriptions selected
        }

        try {
            return DB::transaction(function () use ($user, $subscriptionPlanIds, $paymentStatus) {
                // Fetch subscription plans
                $plans = SubscriptionPlan::whereIn('id', $subscriptionPlanIds)
                    ->where('is_active', true)
                    ->get();

                if ($plans->isEmpty()) {
                    throw new \Exception('No valid subscription plans found');
                }

                // Calculate totals
                $totalAmount = $plans->sum('selling_price');
                $discountAmount = $plans->sum(function ($plan) {
                    return ($plan->original_price - $plan->selling_price);
                });
                $finalAmount = $totalAmount;

                // Create Order
                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => $this->generateOrderNumber(),
                    'total_amount' => $totalAmount,
                    'discount_amount' => $discountAmount,
                    'final_amount' => $finalAmount,
                    'payment_status' => $paymentStatus, // 'free' for registration, can be 'pending' for payment flow
                    'payment_method' => null, // Will be set when payment is processed
                    'transaction_id' => null, // Will be set when payment gateway returns
                    'status' => 'completed', // Auto-completed for free registrations
                ]);

                // Create OrderItems for each subscription plan
                foreach ($plans as $plan) {
                    $orderItem = OrderItem::create([
                        'order_id' => $order->id,
                        'subscription_plan_id' => $plan->id,
                        'quantity' => 1,
                        'price' => $plan->selling_price,
                        'subtotal' => $plan->selling_price,
                        'status' => 'pending',
                    ]);

                    // Create UserSubscription
                    $this->createUserSubscription($user, $plan, $orderItem);
                }

                return $order;
            });
        } catch (\Exception $e) {
            \Log::error('Registration subscription creation failed', [
                'user_id' => $user->id,
                'plans' => $subscriptionPlanIds,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Create user subscription and assign credentials if available
     */
    private function createUserSubscription(User $user, SubscriptionPlan $plan, OrderItem $orderItem): UserSubscription
    {
        $startDate = now()->toDateString();
        $endDate = now()->addMonths($plan->duration_months)->toDateString();

        // Try to get available credential
        $credential = SubscriptionCredential::where('subscription_plan_id', $plan->id)
            ->where('status', 'available')
            ->whereNull('assigned_to_user_id')
            ->first();

        $subscription = UserSubscription::create([
            'user_id' => $user->id,
            'order_item_id' => $orderItem->id,
            'subscription_plan_id' => $plan->id,
            'credentials_id' => $credential?->id,
            'status' => 'active',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'auto_renewal' => false, // Can be enabled in settings later
        ]);

        // Mark credential as assigned if found
        if ($credential) {
            $credential->update([
                'status' => 'assigned',
                'assigned_to_user_id' => $user->id,
                'assigned_at' => now(),
            ]);
        }

        return $subscription;
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . strtoupper(Str::random(3)) . '-' . time();
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Mark order as paid (for future payment gateway integration)
     * 
     * @param Order $order
     * @param string $paymentMethod - Payment method used (UPI, Card, Wallet, etc.)
     * @param string $transactionId - Transaction ID from payment gateway
     * @return bool
     */
    public function markOrderAsPaid(Order $order, string $paymentMethod, string $transactionId): bool
    {
        try {
            return DB::transaction(function () use ($order, $paymentMethod, $transactionId) {
                $order->update([
                    'payment_status' => 'paid',
                    'payment_method' => $paymentMethod,
                    'transaction_id' => $transactionId,
                    'status' => 'completed',
                ]);

                // Mark all order items as delivered
                $order->orderItems()->update(['status' => 'delivered']);

                return true;
            });
        } catch (\Exception $e) {
            \Log::error('Failed to mark order as paid', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
