<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Check if user is suspended (highest priority)
            if ($user->isSuspended()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account has been suspended. Please contact administrator.']);
            }

            // Check if user is locked
            if ($user->isLocked()) {
                $remainingMinutes = $user->getLockoutRemainingMinutes();
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => "Your account is temporarily locked. Please try again in {$remainingMinutes} minute(s)."]);
            }

            // Check if user status is inactive
            if ($user->status === 'inactive') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account has been deactivated. Please contact administrator.']);
            }

            // Check if user has no role
            if (!$user->role_id) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account is not properly configured. Please contact administrator.']);
            }
        }

        return $next($request);
    }
}
