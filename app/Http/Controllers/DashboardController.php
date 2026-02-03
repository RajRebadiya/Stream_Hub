<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\UserSubscription;
use App\Models\Platform;
use App\Models\SubscriptionPlan;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // dd('here');
        // Get statistics for dashboard
        $stats = [
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('final_amount'),
            'active_subscriptions' => UserSubscription::where('status', 'active')->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'expiring_soon' => UserSubscription::expiring(7)->count(),
        ];

        // Recent orders
        $recent_orders = Order::with(['user', 'orderItems.subscriptionPlan.platform'])
            ->latest()
            ->take(10)
            ->get();

        // Revenue chart data (last 12 months)
        $revenue_data = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(final_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Popular platforms
        $popular_platforms = Platform::withCount([
            'subscriptionPlans as total_sales' => function ($query) {
                $query->join('order_items', 'subscription_plans.id', '=', 'order_items.subscription_plan_id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('orders.payment_status', 'paid');
            }
        ])
            ->orderBy('total_sales', 'desc')
            ->take(5)
            ->get();

        // Active subscriptions by platform
        $subscriptions_by_platform = UserSubscription::where('user_subscriptions.status', 'active')
            ->join('subscription_plans', 'user_subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->join('platforms', 'subscription_plans.platform_id', '=', 'platforms.id')
            ->select('platforms.name', DB::raw('COUNT(user_subscriptions.id) as total'))
            ->groupBy('platforms.name')
            ->get();


        // dd($stats);

        return view('admin.dashboard', compact(
            'stats',
            'recent_orders',
            'revenue_data',
            'popular_platforms',
            'subscriptions_by_platform'
        ));
    }
}