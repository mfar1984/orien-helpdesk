<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\NotificationService;
use App\Services\SpamCheckService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        $minPasswordLength = setting('password_min_length', 8);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:' . $minPasswordLength],
        ]);

        // Check email against spam APIs if enabled
        if (SpamCheckService::checkOnRegistration()) {
            $emailCheck = SpamCheckService::checkEmail($validated['email']);
            if ($emailCheck['is_spam']) {
                return back()->withErrors([
                    'email' => 'This email address has been flagged as spam. Please use a different email.'
                ])->withInput();
            }

            // Check IP
            $ipCheck = SpamCheckService::checkIP($request->ip());
            if ($ipCheck['is_spam']) {
                return back()->withErrors([
                    'email' => 'Registration from your IP address is not allowed.'
                ])->withInput();
            }
        }

        // Get default "Customer" role
        $customerRole = Role::where('slug', 'customer')->first();

        if (!$customerRole) {
            return back()->withErrors([
                'email' => 'System configuration error. Please contact administrator.'
            ])->withInput();
        }

        // Create user with customer role and active status
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $customerRole->id,
            'user_type' => 'customer', // Registered users are always customers
            'status' => 'active',
        ]);

        // Send welcome email
        NotificationService::sendWelcomeEmail($user);

        Auth::login($user);
        
        // Log registration
        ActivityLogService::log('register', 'auth', "New user registered: {$user->email}", $user);

        return redirect()->route('dashboard');
    }
}
