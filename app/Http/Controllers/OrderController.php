<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.subscriptionPlan.platform']);

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by order status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by order number or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        // Calculate statistics
        $statistics = [
            'total_orders' => Order::count(),
            'paid_orders' => Order::where('payment_status', 'paid')->count(),
            'pending_orders' => Order::where('payment_status', 'pending')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('final_amount'),
        ];

        return view('admin.orders.index', compact('orders', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        $plans = SubscriptionPlan::with('platform')->active()->inStock()->get();

        return view('admin.orders.create', compact('users', 'plans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.subscription_plan_id' => 'required|exists:subscription_plans,id',
            'items.*.quantity' => 'required|integer|min:1',
            'discount_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        // Calculate totals
        $totalAmount = 0;
        $itemsData = [];

        foreach ($validated['items'] as $item) {
            $plan = SubscriptionPlan::findOrFail($item['subscription_plan_id']);
            $quantity = $item['quantity'];
            $price = $plan->selling_price;
            $subtotal = $price * $quantity;

            $totalAmount += $subtotal;

            $itemsData[] = [
                'subscription_plan_id' => $plan->id,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $subtotal,
                'status' => 'pending',
            ];
        }

        $discountAmount = $validated['discount_amount'] ?? 0;
        $finalAmount = $totalAmount - $discountAmount;

        // Create order
        $order = Order::create([
            'user_id' => $validated['user_id'],
            'order_number' => $this->generateOrderNumber(),
            'total_amount' => $totalAmount,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
            'payment_status' => $validated['payment_status'],
            'payment_method' => $validated['payment_method'] ?? null,
            'status' => $validated['status'],
        ]);

        // Create order items
        foreach ($itemsData as $itemData) {
            $order->orderItems()->create($itemData);

            // Decrease stock
            $plan = SubscriptionPlan::find($itemData['subscription_plan_id']);
            $plan->decreaseStock($itemData['quantity']);
        }

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user.profile', 'orderItems.subscriptionPlan.platform']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order->load(['orderItems.subscriptionPlan']);
        $users = User::orderBy('name')->get();
        $plans = SubscriptionPlan::with('platform')->active()->get();

        return view('admin.orders.edit', compact('order', 'users', 'plans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'payment_method' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update($validated);

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Order updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            // Restore stock for cancelled orders
            foreach ($order->orderItems as $item) {
                $plan = SubscriptionPlan::find($item->subscription_plan_id);
                if ($plan) {
                    $plan->increaseStock($item->quantity);
                }
            }

            $order->delete();

            return redirect()
                ->route('admin.orders.index')
                ->with('success', 'Order deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.orders.index')
                ->with('error', 'Error deleting order.');
        }
    }

    /**
     * Update order payment status
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'transaction_id' => 'nullable|string|max:100',
        ]);

        $order->update([
            'payment_status' => $request->payment_status,
            'transaction_id' => $request->transaction_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully!',
        ]);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        // Update all order items status
        if ($request->status === 'completed') {
            $order->orderItems()->update(['status' => 'delivered']);
        } elseif ($request->status === 'cancelled') {
            $order->orderItems()->update(['status' => 'cancelled']);

            // Restore stock
            foreach ($order->orderItems as $item) {
                $plan = SubscriptionPlan::find($item->subscription_plan_id);
                if ($plan) {
                    $plan->increaseStock($item->quantity);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully!',
        ]);
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(6));

        $orderNumber = $prefix . $date . $random;

        // Ensure uniqueness
        while (Order::where('order_number', $orderNumber)->exists()) {
            $random = strtoupper(Str::random(6));
            $orderNumber = $prefix . $date . $random;
        }

        return $orderNumber;
    }

    /**
     * Get order statistics
     */
    public function getStatistics()
    {
        $stats = [
            'total_orders' => Order::count(),
            'paid_orders' => Order::where('payment_status', 'paid')->count(),
            'pending_orders' => Order::where('payment_status', 'pending')->count(),
            'failed_orders' => Order::where('payment_status', 'failed')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('final_amount'),
            'pending_revenue' => Order::where('payment_status', 'pending')->sum('final_amount'),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
        ];

        return response()->json($stats);
    }
}