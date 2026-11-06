<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Bot API network options
    |--------------------------------------------------------------------------
    |
    | These control how the application talks to api.telegram.org. They live in
    | config (not read through env() at runtime) so they keep working once
    | `php artisan config:cache` has been run on the server.
    |
    */

    // Seconds allowed for the TCP/TLS handshake. Kept short because every
    // activity log waits for this call before the response is returned.
    'connect_timeout' => (int) env('TELEGRAM_CONNECT_TIMEOUT', 4),

    // Total seconds allowed for the whole request.
    'timeout' => (int) env('TELEGRAM_TIMEOUT', 8),

    // Verify the Bot API TLS certificate. Leave this on: the bot token travels
    // inside the request, so an unverified connection means the token can be
    // captured by anything sitting in the middle.
    'verify_ssl' => (bool) env('TELEGRAM_VERIFY_SSL', true),

    // Pin outbound requests to IPv4. A host with a broken IPv6 route fails in
    // exactly the same way as a firewall block, so this removes one variable.
    'force_ipv4' => (bool) env('TELEGRAM_FORCE_IPV4', true),

    // Optional outbound proxy, e.g. "http://user:pass@host:port". Needed when
    // the hosting provider blocks direct access to api.telegram.org.
    'proxy' => env('TELEGRAM_PROXY', ''),

    /*
    |--------------------------------------------------------------------------
    | Failure back-off
    |--------------------------------------------------------------------------
    |
    | Notifications are sent inline during the request. When the API cannot be
    | reached, every logged action would otherwise pay the full timeout. After
    | a transport failure, sending is skipped for this many seconds.
    |
    */

    'backoff_seconds' => (int) env('TELEGRAM_BACKOFF_SECONDS', 300),

];
