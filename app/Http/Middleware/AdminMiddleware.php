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

        // Add your admin check logic here
        // For example, check if user has admin role
        // if (!auth()->user()->is_admin) {
        //     abort(403, 'Unauthorized access');
        // }

        return $next($request);
    }
}