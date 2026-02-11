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

        // Only get platforms that the user actually has subscriptions for
        $platforms = Platform::whereHas('subscriptionPlans', function ($q) use ($user) {
            $q->whereHas('userSubscriptions', function ($q2) use ($user) {
                $q2->where('user_id', $user->id);
            });
        })
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.platforms.access', compact('tokens', 'platforms', 'user'));
    }


    /**
     * Generate encrypted token that cannot be decrypted/tampered
     */
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

        // 🔐 Generate random salt for additional security
        $salt = bin2hex(random_bytes(16));

        // Store cookie data separately (this will be returned after verification)
        $cookieData = $platform->token; // Original cookie string from platform

        $rawData = $platform->id . '|' . $user->id . '|' . $email . '|' . $ip . '|' . $time . '|' . $salt;

        // 🔐 ENCRYPTED TOKEN using AES-256-CBC (cannot be decrypted without key)
        $encryptionKey = config('app.key');
        $iv = random_bytes(16); // Initialization vector

        $encryptedData = openssl_encrypt(
            $rawData,
            'AES-256-CBC',
            $encryptionKey,
            0,
            $iv
        );

        // Combine IV with encrypted data and encode in base64
        $token = base64_encode($iv . '::' . $encryptedData);

        // Generate HMAC signature to prevent tampering
        $signature = hash_hmac('sha256', $token, $encryptionKey);

        // Final token format: signature.token
        $finalToken = $signature . '.' . $token;

        // 📌 Mark previous tokens as inactive
        UserToken::forUserAndPlatform($user->id, $platform->id)
            ->active()
            ->update(['status' => 'inactive']);

        // ✨ Create new token record in database
        $userToken = UserToken::create([
            'user_id' => $user->id,
            'platform_id' => $platform->id,
            'token' => $finalToken,
            'cookie_data' => $cookieData, // 🆕 Store cookie data separately
            'ip_address' => $ip,
            'status' => 'active',
            'use_status' => 0, // 0 = not used, 1 = already used
            'expires_at' => now()->addDays(30), // Token expires in 30 days
        ]);

        // Update user stats
        $user->user_token = $finalToken;
        $user->token_generate_count += 1;
        $user->token_generate_date = $today;
        $user->save();

        return response()->json([
            'status' => true,
            'token' => $finalToken,
            'time' => $time,
            'remaining' => 3 - $user->token_generate_count,
            'token_id' => $userToken->id,
            'expires_at' => $userToken->expires_at
        ]);
    }

    /**
     * Verify encrypted token with tamper detection
     * 🆕 Returns cookie data after successful verification
     */
    public function verifyToken(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'platform_id' => 'required|integer|exists:platforms,id'
        ]);

        $receivedToken = $validated['token'];

        // 🔐 Verify token format (signature.token)
        if (substr_count($receivedToken, '.') !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token format'
            ], 401);
        }

        // Split signature and token
        list($receivedSignature, $tokenData) = explode('.', $receivedToken, 2);

        // 🔐 Verify HMAC signature to detect tampering
        $encryptionKey = config('app.key');
        $expectedSignature = hash_hmac('sha256', $tokenData, $encryptionKey);

        if (!hash_equals($expectedSignature, $receivedSignature)) {
            return response()->json([
                'status' => false,
                'message' => 'Token has been tampered with'
            ], 401);
        }

        // Find the token in database
        $userToken = UserToken::where('token', $receivedToken)
            ->where('platform_id', $validated['platform_id'])
            ->first();

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

        // 🔐 Optional: Decrypt and verify token data integrity
        try {
            $decodedToken = base64_decode($tokenData);

            if (substr_count($decodedToken, '::') !== 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token structure invalid'
                ], 401);
            }

            list($iv, $encryptedData) = explode('::', $decodedToken, 2);

            $decryptedData = openssl_decrypt(
                $encryptedData,
                'AES-256-CBC',
                $encryptionKey,
                0,
                $iv
            );

            if ($decryptedData === false) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token decryption failed'
                ], 401);
            }

            // Verify decrypted data structure
            $parts = explode('|', $decryptedData);
            if (count($parts) !== 6) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token data corrupted'
                ], 401);
            }

            // Verify platform_id and user_id match
            if ($parts[0] != $validated['platform_id'] || $parts[1] != $userToken->user_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token validation failed'
                ], 401);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Token verification error'
            ], 401);
        }

        // ✨ Mark token as used (use_status = 1)
        $userToken->update(['use_status' => 1]);

        // 🆕 Return cookie data for client-side cookie setting
        return response()->json([
            'status' => true,
            'message' => 'Token verified successfully',
            'user_id' => $userToken->user_id,
            'platform_id' => $userToken->platform_id,
            'cookie_data' => $userToken->cookie_data, // 🆕 Send cookie data back
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
