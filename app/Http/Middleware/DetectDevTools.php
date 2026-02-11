<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DetectDevTools
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip in development mode
        if (config('app.debug')) {
            return $next($request);
        }

        // Check if request came from DevTools detection
        if (
            $request->has('devtools_detected') ||
            $request->input('reason') === 'devtools_detected'
        ) {

            // Log the incident
            Log::warning('DevTools detected', [
                'user_id' => Auth::id(),
                'email' => Auth::user()?->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()
            ]);

            // Logout user
            if (Auth::check()) {
                // Get user before logout for logging
                $user = Auth::user();

                // Invalidate session
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Logout
                Auth::logout();

                // Optional: Block user temporarily
                // $user->update(['blocked_until' => now()->addMinutes(30)]);

                // Optional: Send notification to admin
                // $this->notifyAdmin($user);
            }

            // Clear all cookies
            foreach ($request->cookies as $cookie => $value) {
                cookie()->queue(cookie()->forget($cookie));
            }

            return response()->json([
                'status' => false,
                'message' => 'Security violation detected. You have been logged out.'
            ], 403);
        }

        // Add security headers
        $response = $next($request);

        if (method_exists($response, 'header')) {
            // Prevent caching
            $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->header('Pragma', 'no-cache');
            $response->header('Expires', '0');

            // Content Security Policy
            $response->header('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'");

            // Prevent clickjacking
            $response->header('X-Frame-Options', 'DENY');

            // Prevent MIME sniffing
            $response->header('X-Content-Type-Options', 'nosniff');
        }

        return $response;
    }

    /**
     * Notify admin about security violation
     */
    protected function notifyAdmin($user)
    {
        // Implement admin notification logic
        // Mail::to(config('mail.admin'))->send(new SecurityViolationMail($user));
    }
}