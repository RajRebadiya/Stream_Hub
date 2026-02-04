<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('profile');

        // Filter by status
        if ($request->filled('status')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Filter by state
        if ($request->filled('state')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('state', 'like', '%' . $request->state . '%');
            });
        }

        // Search by name, email, or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhereHas('profile', function ($q2) use ($search) {
                        $q2->where('phone', 'like', '%' . $search . '%');
                    });
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.user-profiles.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user-profiles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => ['required', 'confirmed', Password::min(8)],
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'status' => 'required|in:active,inactive',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(), // Auto-verify for admin created users
        ]);

        // Create profile
        $user->profile()->create([
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'pincode' => $validated['pincode'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.user-profiles.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $userProfile)
    {
        $userProfile->load('profile');
        return view('admin.user-profiles.show', ['user' => $userProfile]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $userProfile)
    {
        $userProfile->load('profile');
        return view('admin.user-profiles.edit', ['user' => $userProfile]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $userProfile)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $userProfile->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'status' => 'required|in:active,inactive',
        ]);

        // Update user
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $userProfile->update($userData);

        // Update or create profile
        $userProfile->profile()->updateOrCreate(
            ['user_id' => $userProfile->id],
            [
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'pincode' => $validated['pincode'] ?? null,
                'status' => $validated['status'],
            ]
        );

        return redirect()
            ->route('admin.user-profiles.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $userProfile)
    {
        try {
            // Profile will be automatically deleted due to cascade
            $userProfile->delete();

            return redirect()
                ->route('admin.user-profiles.index')
                ->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.user-profiles.index')
                ->with('error', 'Error deleting user. They may have associated orders or subscriptions.');
        }
    }

    /**
     * Toggle user profile status
     */
    public function toggleStatus(User $userProfile)
    {
        if ($userProfile->profile) {
            $newStatus = $userProfile->profile->status === 'active' ? 'inactive' : 'active';
            $userProfile->profile()->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'User status updated successfully!',
                'status' => $newStatus
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User profile not found!'
        ], 404);
    }

    /**
     * Verify user email
     */
    public function verifyEmail(User $userProfile)
    {
        if (!$userProfile->email_verified_at) {
            $userProfile->update([
                'email_verified_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email already verified!'
        ], 400);
    }

    /**
     * Get user statistics
     */
    public function getStatistics(User $userProfile)
    {
        $stats = [
            'total_orders' => 0, // Add actual count if Order model exists
            'active_subscriptions' => 0, // Add actual count if Subscription model exists
            'total_spent' => 0, // Add actual sum if Order model exists
            'profile_completion' => $this->calculateProfileCompletion($userProfile),
        ];

        return response()->json($stats);
    }

    /**
     * Calculate profile completion percentage
     */
    private function calculateProfileCompletion(User $user)
    {
        if (!$user->profile) {
            return 0;
        }

        $fields = ['phone', 'address', 'city', 'state', 'pincode'];
        $completed = 0;

        foreach ($fields as $field) {
            if (!empty($user->profile->$field)) {
                $completed++;
            }
        }

        return ($completed / count($fields)) * 100;
    }
}