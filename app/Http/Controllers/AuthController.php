<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\RegistrationSubscriptionService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $subscriptionService;

    public function __construct(RegistrationSubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Register User with optional immediate platform subscription
     * 
     * @param Request $request
     * - name: User full name
     * - email: Valid email
     * - mobile: 10-digit mobile number
     * - password: Minimum 8 characters
     * - subscription_plan_ids: (Optional) Array of subscription plan IDs to subscribe [1, 2, 3]
     * 
     * Response includes order details if subscriptions selected
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|max:10|min:10',
            'password' => 'required|string|min:8',
            'subscription_plan_ids' => 'nullable|array',
            'subscription_plan_ids.*' => 'integer|exists:subscription_plans,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
            ]);

            Auth::login($user);

            $response = [
                'success' => true,
                'message' => 'Registration successful!',
                'user' => $user,
                'redirect' => route('dashboard')
            ];

            // Create order with selected subscriptions if provided
            $subscriptionPlanIds = $request->input('subscription_plan_ids', []);
            if (!empty($subscriptionPlanIds)) {
                $order = $this->subscriptionService->createRegistrationOrder(
                    $user,
                    $subscriptionPlanIds,
                    'free' // Free registration - no payment required
                );

                if ($order) {
                    $response['message'] = 'Registration successful! Subscriptions activated.';
                    $response['order'] = [
                        'order_number' => $order->order_number,
                        'total_amount' => $order->total_amount,
                        'discount_amount' => $order->discount_amount,
                        'final_amount' => $order->final_amount,
                        'status' => $order->status,
                        'payment_status' => $order->payment_status,
                        'items_count' => $order->orderItems->count(),
                    ];
                }
            }

            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. ' . $e->getMessage()
            ], 500);
        }
    }

    // Login User
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $remember = $request->remember ?? false;

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => 'Login successful!',
                'redirect' => route('dashboard')  // Changed from route('home')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'The provided credentials do not match our records.'
        ], 401);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');  // Changed from route('home')
    }

    /**
     * Get available subscription plans for registration page
     * Shows active plans grouped by platform
     */
    public function getAvailablePlans()
    {
        try {
            $platforms = \App\Models\Platform::where('is_active', true)
                ->where('status', 'active')
                ->with([
                    'activeSubscriptionPlans' => function ($query) {
                        $query->select('id', 'platform_id', 'name', 'duration_months', 'original_price', 'selling_price', 'discount_percentage', 'max_screens', 'quality', 'features');
                    }
                ])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $platforms->map(function ($platform) {
                    // dd(asset('storage/' . $platform->logo));
                    return [
                        'platform_id' => $platform->id,
                        'platform_name' => $platform->name,

                        'platform_logo' => asset('storage/' . $platform->logo),
                        'plans' => $platform->activeSubscriptionPlans->map(function ($plan) {
                            return [
                                'id' => $plan->id,
                                'name' => $plan->name,
                                'duration_months' => $plan->duration_months,
                                'original_price' => $plan->original_price,
                                'selling_price' => $plan->selling_price,
                                'discount_percentage' => round($plan->discount_percentage, 2),
                                'max_screens' => $plan->max_screens,
                                'quality' => $plan->quality,
                                'features' => $plan->features,
                            ];
                        })
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch available plans'
            ], 500);
        }
    }
}