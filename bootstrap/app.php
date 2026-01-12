<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust Cloudflare proxies
        $middleware->trustProxies(at: '*');
        
        // Register middleware aliases
        $middleware->alias([
            'check.banned.email' => \App\Http\Middleware\CheckBannedEmail::class,
            'check.banned.ip' => \App\Http\Middleware\CheckBannedIp::class,
            'check.user.status' => \App\Http\Middleware\CheckUserStatus::class,
            'session.timeout' => \App\Http\Middleware\SessionTimeout::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'audit.log' => \App\Http\Middleware\AuditLogMiddleware::class,
        ]);
        
        // Append audit log middleware to web group
        $middleware->appendToGroup('web', \App\Http\Middleware\AuditLogMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
