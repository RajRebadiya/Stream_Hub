<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Platform;
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
        $platforms = Platform::orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.platforms.access', compact('platforms'));
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
        // dd($platform , $user);
        $email = $user->email;
        $ip = $request->ip();

        // dd($email, $ip);
        $time = now()->timestamp;

        $rawData = $platform->token . '|' . $email . '|' . $ip . '|' . $time;


        // 🔐 DECRYPTABLE TOKEN
        $token = $rawData;
        // dd($token);

        // 🔐 NON-DECRYPTABLE TOKEN
        // $token = hash_hmac('sha256', $rawData, config('app.key'));

        $user->user_token = $token;
        // ➕ Increase count
        $user->token_generate_count += 1;
        $user->token_generate_date = $today;
        $user->save();
        $user->save();

        return response()->json([
            'status' => true,
            'token' => $token,
            'time' => $time,
            'remaining' => 3 - $user->token_generate_count
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
