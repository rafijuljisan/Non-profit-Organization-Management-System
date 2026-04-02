<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPanelMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // If not logged in, redirect to login
        if (!$user) {
            return redirect()->route('login');
        }

        // If user has NO roles assigned, block admin access
        if ($user->roles->isEmpty()) {
            abort(403, 'তুমি এই পেজে প্রবেশ করতে পারবে না।');
        }

        return $next($request);
    }
}