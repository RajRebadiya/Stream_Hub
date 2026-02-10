<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Redirect non-admin users to the admin 'access' page
        if (!auth()->user()->is_admin) {
            return redirect()->route('admin.access');
        }

        return $next($request);
    }
}