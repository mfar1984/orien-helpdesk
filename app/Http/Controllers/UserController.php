<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display the specified user profile.
     * Uses hash_link for route model binding.
     */
    public function show(User $user): View
    {
        $user->load('role');
        return view('users.profile', compact('user'));
    }
}
