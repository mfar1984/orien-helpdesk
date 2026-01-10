<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $timeout = setting('session_timeout', 120) * 60; // Convert to seconds
            $lastActivity = session('last_activity_time');
            
            if ($lastActivity && (time() - $lastActivity) > $timeout) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')
                    ->with('error', 'Your session has expired due to inactivity. Please login again.');
            }
            
            // Update last activity time
            session(['last_activity_time' => time()]);
        }

        return $next($request);
    }
}
