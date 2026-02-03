<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SubscriptionPlan::with('platform');

        // Filter by platform
        if ($request->filled('platform_id')) {
            $query->where('platform_id', $request->platform_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $plans = $query->orderBy('platform_id')
            ->orderBy('duration_months')
            ->paginate(15);

        $platforms = Platform::active()->ordered()->get();

        return view('admin.subscription-plans.index', compact('plans', 'platforms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $platforms = Platform::active()->ordered()->get();
        return view('admin.subscription-plans.create', compact('platforms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform_id' => 'required|exists:platforms,id',
            'name' => 'required|string|max:100',
            'duration_months' => 'required|integer|in:1,3,6,12',
            'original_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'max_screens' => 'required|integer|min:1',
            'quality' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string|max:255',
            'stock_available' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'is_active' => 'nullable|boolean',
        ]);

        // Calculate discount if not provided
        if (empty($validated['discount_percentage']) && $validated['original_price'] > $validated['selling_price']) {
            $validated['discount_percentage'] = (($validated['original_price'] - $validated['selling_price']) / $validated['original_price']) * 100;
        }

        // Filter out empty features
        if (isset($validated['features'])) {
            $validated['features'] = array_filter($validated['features'], function ($value) {
                return !empty(trim($value));
            });
            $validated['features'] = array_values($validated['features']); // Re-index array
        }

        // Ensure is_active is set
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        SubscriptionPlan::create($validated);

        return redirect()
            ->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->load('platform');
        return view('admin.subscription-plans.show', ['plan' => $subscriptionPlan]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        $platforms = Platform::active()->ordered()->get();
        return view('admin.subscription-plans.edit', [
            'plan' => $subscriptionPlan,
            'platforms' => $platforms
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $validated = $request->validate([
            'platform_id' => 'required|exists:platforms,id',
            'name' => 'required|string|max:100',
            'duration_months' => 'required|integer|in:1,3,6,12',
            'original_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'max_screens' => 'required|integer|min:1',
            'quality' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string|max:255',
            'stock_available' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'is_active' => 'nullable|boolean',
        ]);

        // Calculate discount if not provided
        if (empty($validated['discount_percentage']) && $validated['original_price'] > $validated['selling_price']) {
            $validated['discount_percentage'] = (($validated['original_price'] - $validated['selling_price']) / $validated['original_price']) * 100;
        }

        // Filter out empty features
        if (isset($validated['features'])) {
            $validated['features'] = array_filter($validated['features'], function ($value) {
                return !empty(trim($value));
            });
            $validated['features'] = array_values($validated['features']); // Re-index array
        }

        // Ensure is_active is set
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $subscriptionPlan->update($validated);

        return redirect()
            ->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        try {
            $subscriptionPlan->delete();

            return redirect()
                ->route('admin.subscription-plans.index')
                ->with('success', 'Subscription plan deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.subscription-plans.index')
                ->with('error', 'Error deleting plan. It may be associated with orders.');
        }
    }

    /**
     * Toggle plan active status
     */
    public function toggleStatus(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->update([
            'is_active' => !$subscriptionPlan->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Plan status updated successfully!',
            'is_active' => $subscriptionPlan->is_active
        ]);
    }

    /**
     * Update stock
     */
    public function updateStock(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $validated = $request->validate([
            'stock_available' => 'required|integer|min:0'
        ]);

        $subscriptionPlan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully!',
            'stock_available' => $subscriptionPlan->stock_available
        ]);
    }
}