<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\SpamCheckService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        // Check IP against spam APIs before authentication if enabled
        if (SpamCheckService::checkOnLogin()) {
            $ipCheck = SpamCheckService::checkIP($request->ip());
            if ($ipCheck['is_spam']) {
                return back()->withErrors([
                    'email' => 'Login from your IP address is not allowed.',
                ])->onlyInput('email');
            }
        }

        // Find user by email first to check status
        $user = User::where('email', $credentials['email'])->first();
        
        if ($user) {
            // Check if user is suspended (highest priority)
            if ($user->isSuspended()) {
                return back()->withErrors([
                    'email' => 'Your account has been suspended. Please contact administrator.',
                ])->onlyInput('email');
            }
            
            // Check if user is locked
            if ($user->isLocked()) {
                $remainingMinutes = $user->getLockoutRemainingMinutes();
                return back()->withErrors([
                    'email' => "Your account is temporarily locked due to too many failed login attempts. Please try again in {$remainingMinutes} minute(s).",
                ])->onlyInput('email');
            }
        }

        // Attempt authentication
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Check user status
            if ($user->status === 'inactive') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated. Please contact administrator.',
                ])->onlyInput('email');
            }

            // Check if user has a role
            if (!$user->role_id) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is not properly configured. Please contact administrator.',
                ])->onlyInput('email');
            }

            // Check if 2FA is enabled
            if ($user->twoFactorEnabled()) {
                // Logout temporarily and store user ID for 2FA verification
                Auth::logout();
                session(['2fa:user:id' => $user->id]);
                return redirect()->route('two-factor.verify');
            }

            // Reset login attempts on successful login
            $user->resetLoginAttempts();

            // Update last login
            $user->update(['last_login_at' => now()]);

            // Log successful login
            ActivityLogService::logLogin();

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        // Failed login - increment attempts if user exists
        if ($user) {
            $remainingAttempts = $user->incrementLoginAttempts();
            $maxAttempts = setting('max_login_attempts', 5);
            
            // Log failed login attempt
            ActivityLogService::logFailedLogin($credentials['email']);
            
            if ($remainingAttempts <= 0) {
                $lockoutDuration = setting('lockout_duration', 15);
                return back()->withErrors([
                    'email' => "Too many failed login attempts. Your account has been locked for {$lockoutDuration} minutes.",
                ])->onlyInput('email');
            }
            
            return back()->withErrors([
                'email' => "Invalid credentials. You have {$remainingAttempts} attempt(s) remaining before your account is locked.",
            ])->onlyInput('email');
        }

        // Log failed login attempt for non-existent user
        ActivityLogService::logFailedLogin($credentials['email']);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        // Log logout before destroying session
        ActivityLogService::logLogout();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
