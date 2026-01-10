<?php

namespace App\Http\Middleware;

use App\Services\ContentFilterService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBannedIp
{
    /**
     * Handle an incoming request.
     * Supports wildcard patterns like 192.168.1.* or 10.*.*.*
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        $result = ContentFilterService::isIpBanned($ip);
        
        if ($result['banned']) {
            $reason = $result['reason'] ?? null;
            abort(403, 'Your IP address has been banned' . ($reason ? ': ' . $reason : '.'));
        }

        return $next($request);
    }
}
