<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionCredential;
use App\Models\SubscriptionPlan;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionCredentialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SubscriptionCredential::with(['subscriptionPlan.platform', 'assignedToUser']);

        // Filter by platform
        if ($request->filled('platform_id')) {
            $query->whereHas('subscriptionPlan', function ($q) use ($request) {
                $q->where('platform_id', $request->platform_id);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', '%' . $search . '%')
                    ->orWhere('profile_name', 'like', '%' . $search . '%');
            });
        }

        $credentials = $query->orderBy('created_at', 'desc')->paginate(20);
        $platforms = Platform::active()->ordered()->get();

        // Calculate statistics
        $statistics = [
            'total' => SubscriptionCredential::count(),
            'available' => SubscriptionCredential::where('status', 'available')->count(),
            'assigned' => SubscriptionCredential::where('status', 'assigned')->count(),
            'expired' => SubscriptionCredential::where('status', 'expired')->count(),
            'blocked' => SubscriptionCredential::where('status', 'blocked')->count(),
        ];

        return view('admin.credentials.index', compact('credentials', 'platforms', 'statistics'));
    }

    /**
     * Show available credentials.
     */
    public function available(Request $request)
    {
        $query = SubscriptionCredential::with(['subscriptionPlan.platform'])->available();

        if ($request->filled('platform_id')) {
            $query->whereHas('subscriptionPlan', function ($q) use ($request) {
                $q->where('platform_id', $request->platform_id);
            });
        }

        $credentials = $query->orderBy('created_at', 'desc')->paginate(20);
        $platforms = Platform::active()->ordered()->get();

        $statistics = [
            'total' => SubscriptionCredential::count(),
            'available' => SubscriptionCredential::where('status', 'available')->count(),
            'assigned' => SubscriptionCredential::where('status', 'assigned')->count(),
            'blocked' => SubscriptionCredential::where('status', 'blocked')->count(),
        ];

        return view('admin.credentials.available', compact('credentials', 'platforms', 'statistics'));
    }

    /**
     * Show assigned credentials.
     */
    public function assigned(Request $request)
    {
        $query = SubscriptionCredential::with(['subscriptionPlan.platform', 'assignedToUser'])->assigned();

        if ($request->filled('platform_id')) {
            $query->whereHas('subscriptionPlan', function ($q) use ($request) {
                $q->where('platform_id', $request->platform_id);
            });
        }

        $credentials = $query->orderBy('assigned_at', 'desc')->paginate(20);
        $platforms = Platform::active()->ordered()->get();

        $statistics = [
            'total' => SubscriptionCredential::count(),
            'available' => SubscriptionCredential::where('status', 'available')->count(),
            'assigned' => SubscriptionCredential::where('status', 'assigned')->count(),
            'blocked' => SubscriptionCredential::where('status', 'blocked')->count(),
        ];

        return view('admin.credentials.assigned', compact('credentials', 'platforms', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plans = SubscriptionPlan::with('platform')->active()->get();
        $users = User::orderBy('name')->get();

        return view('admin.credentials.create', compact('plans', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'email' => 'nullable|email|max:255',
            'password' => 'nullable|string|max:255',
            'profile_name' => 'nullable|string|max:100',
            'pin' => 'nullable|string|max:10',
            'status' => 'required|in:available,assigned,expired,blocked',
            'notes' => 'nullable|string',
            'assigned_to_user_id' => 'nullable|exists:users,id',
        ]);

        // If assigned, set assigned_at
        if ($validated['status'] === 'assigned' && !empty($validated['assigned_to_user_id'])) {
            $validated['assigned_at'] = now();
        }

        SubscriptionCredential::create($validated);

        return redirect()
            ->route('admin.credentials.index')
            ->with('success', 'Credential created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubscriptionCredential $credential)
    {
        $credential->load(['subscriptionPlan.platform', 'assignedToUser.profile']);
        return view('admin.credentials.show', compact('credential'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubscriptionCredential $credential)
    {
        $credential->load(['subscriptionPlan.platform']);
        $plans = SubscriptionPlan::with('platform')->active()->get();
        $users = User::orderBy('name')->get();

        return view('admin.credentials.edit', compact('credential', 'plans', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubscriptionCredential $credential)
    {
        $validated = $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'email' => 'nullable|email|max:255',
            'password' => 'nullable|string|max:255',
            'profile_name' => 'nullable|string|max:100',
            'pin' => 'nullable|string|max:10',
            'status' => 'required|in:available,assigned,expired,blocked',
            'notes' => 'nullable|string',
            'assigned_to_user_id' => 'nullable|exists:users,id',
        ]);

        // Handle password update (only if provided)
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        // Handle pin update (only if provided)
        if (empty($validated['pin'])) {
            unset($validated['pin']);
        }

        // If status changed to assigned and user provided
        if ($validated['status'] === 'assigned' && !empty($validated['assigned_to_user_id'])) {
            if (!$credential->isAssigned()) {
                $validated['assigned_at'] = now();
            }
        }

        // If status changed from assigned to something else
        if ($validated['status'] !== 'assigned') {
            $validated['assigned_to_user_id'] = null;
            $validated['assigned_at'] = null;
        }

        $credential->update($validated);

        return redirect()
            ->route('admin.credentials.show', $credential->id)
            ->with('success', 'Credential updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubscriptionCredential $credential)
    {
        try {
            $credential->delete();

            return redirect()
                ->route('admin.credentials.index')
                ->with('success', 'Credential deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.credentials.index')
                ->with('error', 'Error deleting credential.');
        }
    }

    /**
     * Assign credential to user.
     */
    public function assign(Request $request, SubscriptionCredential $credential)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $credential->assignTo($request->user_id);

        return response()->json([
            'success' => true,
            'message' => 'Credential assigned successfully!',
        ]);
    }

    /**
     * Unassign credential from user.
     */
    public function unassign(SubscriptionCredential $credential)
    {
        $credential->unassign();

        return response()->json([
            'success' => true,
            'message' => 'Credential unassigned successfully!',
        ]);
    }

    /**
     * Block credential.
     */
    public function block(SubscriptionCredential $credential)
    {
        $credential->block();

        return response()->json([
            'success' => true,
            'message' => 'Credential blocked successfully!',
        ]);
    }

    /**
     * Make credential available.
     */
    public function makeAvailable(SubscriptionCredential $credential)
    {
        $credential->makeAvailable();

        return response()->json([
            'success' => true,
            'message' => 'Credential is now available!',
        ]);
    }

    /**
     * Reveal password (for authorized viewing).
     */
    public function revealPassword(SubscriptionCredential $credential)
    {
        return response()->json([
            'success' => true,
            'password' => $credential->password,
        ]);
    }

    /**
     * Reveal pin (for authorized viewing).
     */
    public function revealPin(SubscriptionCredential $credential)
    {
        return response()->json([
            'success' => true,
            'pin' => $credential->pin,
        ]);
    }
}