<?php

namespace App\Http\Middleware;

use App\Models\BannedEmail;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBannedEmail
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->input('email');

        if ($email && BannedEmail::isBanned($email)) {
            $reason = BannedEmail::getBanReason($email);
            return back()->withErrors([
                'email' => 'This email address has been banned' . ($reason ? ': ' . $reason : '.')
            ])->onlyInput('email');
        }

        return $next($request);
    }
}
