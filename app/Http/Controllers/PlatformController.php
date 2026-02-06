<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Platform;
use App\Models\UserToken;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PlatformController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $platforms = Platform::orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.platforms.index', compact('platforms'));
    }
    public function access()
    {
        $user = Auth::user();
        
        // Fetch all tokens for the logged-in user with platform details
        $tokens = UserToken::where('user_id', $user->id)
            ->with('platform')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Also get platforms for generate token functionality
        $platforms = Platform::orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.platforms.access', compact('tokens', 'platforms', 'user'));
    }

    public function generateToken(Request $request, $id)
    {
        $platform = Platform::findOrFail($id);

        $user = Auth::user();
        $today = now()->toDateString();

        // 🔁 Reset count if new day
        if ($user->token_generate_date !== $today) {
            $user->token_generate_count = 0;
            $user->token_generate_date = $today;
        }

        // 🚫 Limit check
        if ($user->token_generate_count >= 3) {
            return response()->json([
                'status' => false,
                'message' => 'Daily token generation limit reached (3 per day)'
            ], 429);
        }

        $email = $user->email;
        $ip = $request->ip();
        $time = now()->timestamp;

        $rawData = $platform->token . '|' . $email . '|' . $ip . '|' . $time;

        // 🔐 DECRYPTABLE TOKEN
        $token = $rawData;
        // 🔐 NON-DECRYPTABLE TOKEN (commented option)
        // $token = hash_hmac('sha256', $rawData, config('app.key'));

        // 📌 Mark previous tokens as inactive
        UserToken::forUserAndPlatform($user->id, $platform->id)
            ->active()
            ->update(['status' => 'inactive']);

        // ✨ Create new token record in database
        $userToken = UserToken::create([
            'user_id' => $user->id,
            'platform_id' => $platform->id,
            'token' => $token,
            'ip_address' => $ip,
            'status' => 'active',
            'use_status' => 0, // 0 = not used, 1 = already used
            'expires_at' => now()->addDays(30), // Token expires in 30 days
        ]);

        // Update user stats
        $user->user_token = $token;
        $user->token_generate_count += 1;
        $user->token_generate_date = $today;
        $user->save();

        return response()->json([
            'status' => true,
            'token' => $token,
            'time' => $time,
            'remaining' => 3 - $user->token_generate_count,
            'token_id' => $userToken->id,
            'expires_at' => $userToken->expires_at
        ]);
    }

    /**
     * Verify token and mark it as used
     */
    public function verifyToken(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'platform_id' => 'required|integer|exists:platforms,id'
        ]);

        // Find the token
        $userToken = UserToken::where('token', $validated['token'])
            ->where('platform_id', $validated['platform_id'])
            ->first();

            // dd($userToken);

        // Check if token exists
        if (!$userToken) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token'
            ], 401);
        }

        // Check if token has expired
        if ($userToken->expires_at && now()->isAfter($userToken->expires_at)) {
            return response()->json([
                'status' => false,
                'message' => 'Token has expired'
            ], 401);
        }

        // Check if token status is active
        if ($userToken->status !== 'active') {
            return response()->json([
                'status' => false,
                'message' => 'Token is not active'
            ], 401);
        }

        // Check if token is already used
        if ($userToken->use_status === 1) {
            return response()->json([
                'status' => false,
                'message' => 'Token is already in use'
            ], 403);
        }

        // ✨ Check if user is already logged in (user_status must be 1)
        // $user = $userToken->user;

        // if ($user->status !== 1) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'User already logged in'
        //     ], 403);
        // }

        // ✨ Mark token as used (use_status = 1)
        $userToken->update(['use_status' => 1]);

        return response()->json([
            'status' => true,
            'message' => 'Token verified successfully',
            'user_id' => $userToken->user_id,
            'platform_id' => $userToken->platform_id,
            'verified_at' => now(),
        ]);
    }


    public function revokeToken($tokenId)
    {
        $token = UserToken::findOrFail($tokenId);

        // Check if token belongs to current user
        if ($token->user_id !== Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Mark token as inactive
        $token->update(['status' => 'inactive']);

        return response()->json([
            'status' => true,
            'message' => 'Token has been revoked successfully'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.platforms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:platforms,slug',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('platforms', 'public');
        }

        // Ensure is_active is set (checkbox)
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        // Set default sort_order if not provided
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Platform::create($validated);

        return redirect()
            ->route('admin.platforms.index')
            ->with('success', 'Platform created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Platform $platform)
    {
        return view('admin.platforms.show', compact('platform'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Platform $platform)
    {
        return view('admin.platforms.edit', compact('platform'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Platform $platform)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:platforms,slug,' . $platform->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'is_active' => 'nullable|boolean',
            'token' => 'nullable|string',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($platform->logo && Storage::disk('public')->exists($platform->logo)) {
                Storage::disk('public')->delete($platform->logo);
            }
            $validated['logo'] = $request->file('logo')->store('platforms', 'public');
        }

        // Ensure is_active is set (checkbox)
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        // Set default sort_order if not provided
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $platform->update($validated);

        return redirect()
            ->route('admin.platforms.index')
            ->with('success', 'Platform updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Platform $platform)
    {
        try {
            // Delete logo if exists
            if ($platform->logo && Storage::disk('public')->exists($platform->logo)) {
                Storage::disk('public')->delete($platform->logo);
            }

            $platform->delete();

            return redirect()
                ->route('admin.platforms.index')
                ->with('success', 'Platform deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.platforms.index')
                ->with('error', 'Error deleting platform. It may be associated with other records.');
        }
    }

    /**
     * Toggle platform active status
     */
    public function toggleStatus(Platform $platform)
    {
        $platform->update([
            'is_active' => !$platform->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Platform status updated successfully!',
            'is_active' => $platform->is_active
        ]);
    }
}
