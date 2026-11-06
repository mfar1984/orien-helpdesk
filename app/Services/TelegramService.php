<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class TelegramService
{
    /**
     * Telegram rejects any single message longer than this.
     */
    private const MAX_LENGTH = 4096;

    /**
     * Cache key remembering that the Bot API could not be reached.
     */
    private const BLOCK_KEY = 'telegram:delivery-block';

    private const MAX_BACKOFF_SECONDS = 3600;

    /**
     * Send a message to the configured Telegram channel.
     */
    public static function send(string $message, string $parseMode = 'HTML'): bool
    {
        return static::deliver($message, $parseMode)['success'];
    }

    /**
     * Send a message and return the full outcome so callers can surface the
     * real reason a delivery failed.
     *
     * @return array{success: bool, message: string, data: array, parse_error: bool}
     */
    public static function deliver(string $message, string $parseMode = 'HTML', bool $force = false): array
    {
        try {
            return static::deliverSafely($message, $parseMode, $force);
        } catch (Throwable $exception) {
            // Do not include exception text here. A transport exception may
            // contain request details, and the bot token must never reach logs.
            Log::warning('Telegram notification failed unexpectedly', [
                'exception' => $exception::class,
                'code' => $exception->getCode(),
            ]);

            return static::result(false, 'Telegram notification failed unexpectedly.');
        }
    }

    private static function deliverSafely(string $message, string $parseMode, bool $force): array
    {
        $config = static::config();

        if (! $config['enabled'] && ! $force) {
            return static::result(false, 'Telegram notifications are disabled.');
        }

        if ($config['bot_token'] === '') {
            return static::result(false, 'Bot API Token not configured. Please save your Telegram settings first.');
        }

        if ($config['channel_id'] === '') {
            return static::result(false, 'Channel ID not configured. Please save your Telegram settings first.');
        }

        // Skip known failures during their bounded cooldown. Forced connection
        // tests bypass this block and always report the current API response.
        $block = Cache::get(self::BLOCK_KEY);
        if (! $force && $block) {
            $message = is_array($block) && isset($block['message'])
                ? (string) $block['message']
                : 'Skipped: the Telegram API was unreachable recently.';

            return static::result(false, $message);
        }

        $result = static::post($config, [
            'chat_id' => $config['channel_id'],
            'text' => static::clamp($message),
            'parse_mode' => $parseMode,
            'disable_web_page_preview' => true,
        ]);

        // Telegram discards the entire message when it cannot parse the markup,
        // which happens whenever user content contains formatting characters.
        // Retry once as plain text so the notification still gets through.
        if (! $result['success'] && $result['parse_error']) {
            Log::warning('Telegram rejected message markup, retrying as plain text', [
                'reason' => $result['message'],
            ]);

            $result = static::post($config, [
                'chat_id' => $config['channel_id'],
                'text' => static::clamp(static::stripMarkup($message)),
                'disable_web_page_preview' => true,
            ]);
        }

        return $result;
    }

    /**
     * Send a connection test message.
     *
     * Bypasses both the enabled flag and the unreachable back-off so an
     * administrator always gets a truthful answer from the test button.
     *
     * @return array{success: bool, message: string, data: array, parse_error: bool}
     */
    public static function sendTestMessage(string $note): array
    {
        $message = '🔔 <b>'.static::esc(company_name()).' Helpdesk</b>'."\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━\n";
        $message .= "📋 <b>Test Connection</b>\n";
        $message .= "━━━━━━━━━━━━━━━━━━\n\n";
        $message .= '📝 '.static::esc($note)."\n\n";
        $message .= '⏰ '.now()->format('d M Y, H:i:s')."\n";
        $message .= '👤 Sent by: '.static::esc(auth()->user()->name ?? 'System')."\n\n";
        $message .= '✅ <b>Connection Successful!</b>';

        return static::deliver($message, 'HTML', force: true);
    }

    /**
     * Send activity log notification to Telegram.
     */
    public static function sendActivityNotification(string $action, string $module, string $description, ?array $context = null): bool
    {
        $user = auth()->user();
        $userName = $user ? $user->name : 'System';
        $userType = $user ? ucfirst($user->user_type ?? 'Unknown') : 'System';

        $emoji = match ($action) {
            'create' => '🆕',
            'update' => '✏️',
            'delete' => '🗑️',
            'force_delete' => '💥',
            'login' => '🔐',
            'logout' => '🚪',
            'failed_login' => '⚠️',
            'password_change' => '🔑',
            'reply' => '💬',
            'assign' => '👤',
            'status_change' => '🔄',
            'restore' => '♻️',
            'export' => '📤',
            'view' => '👁️',
            'lock' => '🔒',
            'unlock' => '🔓',
            'suspend' => '🚫',
            'unsuspend' => '✅',
            default => '📋'
        };

        $message = "{$emoji} <b>".static::esc(company_name()).' Activity</b>'."\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━\n";
        $message .= '📌 <b>Action:</b> '.static::esc(ucfirst(str_replace('_', ' ', $action)))."\n";
        $message .= '📁 <b>Module:</b> '.static::esc(ucfirst(str_replace('_', ' ', $module)))."\n";
        $message .= "━━━━━━━━━━━━━━━━━━\n\n";
        $message .= '📝 '.static::esc($description)."\n\n";

        if ($context) {
            if (isset($context['ticket_number'])) {
                $message .= '🎫 Ticket: #'.static::esc($context['ticket_number'])."\n";
            }
            if (isset($context['subject'])) {
                $message .= '📋 Subject: '.static::esc($context['subject'])."\n";
            }
            if (isset($context['status'])) {
                $message .= '📊 Status: '.static::esc($context['status'])."\n";
            }
            if (isset($context['priority'])) {
                $message .= '⚡ Priority: '.static::esc($context['priority'])."\n";
            }
            $message .= "\n";
        }

        $message .= '👤 <b>By:</b> '.static::esc($userName).' ('.static::esc($userType).")\n";
        $message .= '⏰ <b>Time:</b> '.now()->format('d M Y, H:i:s')."\n";

        return static::send($message);
    }

    /**
     * Send ticket notification.
     */
    public static function sendTicketNotification(string $type, array $ticketData): bool
    {
        $emoji = match ($type) {
            'created' => '🎫',
            'replied' => '💬',
            'closed' => '✅',
            'assigned' => '👤',
            'status_changed' => '🔄',
            default => '📋'
        };

        $title = match ($type) {
            'created' => 'New Ticket Created',
            'replied' => 'New Reply',
            'closed' => 'Ticket Closed',
            'assigned' => 'Ticket Assigned',
            'status_changed' => 'Status Changed',
            default => 'Ticket Update'
        };

        $message = "{$emoji} <b>".static::esc(company_name()).' - '.static::esc($title).'</b>'."\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━\n";
        $message .= '🎫 <b>Ticket:</b> #'.static::esc($ticketData['ticket_number'] ?? '-')."\n";
        $message .= '📋 <b>Subject:</b> '.static::esc($ticketData['subject'] ?? '-')."\n";

        if (isset($ticketData['status'])) {
            $message .= '📊 <b>Status:</b> '.static::esc($ticketData['status'])."\n";
        }
        if (isset($ticketData['priority'])) {
            $message .= '⚡ <b>Priority:</b> '.static::esc($ticketData['priority'])."\n";
        }
        if (isset($ticketData['category'])) {
            $message .= '📁 <b>Category:</b> '.static::esc($ticketData['category'])."\n";
        }

        $message .= "━━━━━━━━━━━━━━━━━━\n\n";

        if (isset($ticketData['message'])) {
            // mb_* so a multi-byte character is never cut in half; broken UTF-8
            // makes Telegram reject the request outright.
            $content = trim(strip_tags($ticketData['message']));

            if (mb_strlen($content, 'UTF-8') > 200) {
                $content = mb_substr($content, 0, 200, 'UTF-8').'...';
            }

            $message .= '💬 '.static::esc($content)."\n\n";
        }

        $message .= '👤 <b>By:</b> '.static::esc($ticketData['user_name'] ?? 'System')."\n";
        $message .= '⏰ <b>Time:</b> '.now()->format('d M Y, H:i:s')."\n";

        if (! empty($ticketData['url'])) {
            $message .= "\n".'🔗 <a href="'.static::esc($ticketData['url']).'">View Ticket</a>';
        }

        return static::send($message);
    }

    /**
     * Perform the HTTP call to the Bot API.
     *
     * @return array{success: bool, message: string, data: array, parse_error: bool}
     */
    private static function post(array $config, array $payload): array
    {
        $ch = curl_init();

        if ($ch === false) {
            static::rememberBlock(
                'transport',
                'Skipped: Telegram transport initialization failed recently.',
                $config['backoff']
            );
            Log::warning('Telegram cURL initialization failed');

            return static::result(false, 'Connection failed: unable to initialize the HTTP client.');
        }

        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.telegram.org/bot'.$config['bot_token'].'/sendMessage',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $config['connect_timeout'],
            CURLOPT_TIMEOUT => $config['timeout'],
            CURLOPT_SSL_VERIFYPEER => $config['verify_ssl'],
            CURLOPT_SSL_VERIFYHOST => $config['verify_ssl'] ? 2 : 0,
        ]);

        if ($config['force_ipv4']) {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }

        if ($config['proxy'] !== '') {
            curl_setopt($ch, CURLOPT_PROXY, $config['proxy']);
        }

        $body = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errorNo = curl_errno($ch);
        $error = static::redact(curl_error($ch), $config['bot_token']);

        // No curl_close() here on purpose: it is a no-op since PHP 8.0 and
        // deprecated in PHP 8.5. The handle is released when $ch goes out
        // of scope.
        unset($ch);

        // Transport level failure: DNS, routing, TLS or timeout. The old code
        // dropped this detail and only logged http_code 0, which is why these
        // failures were impossible to diagnose from the log.
        if ($errorNo !== 0) {
            static::rememberBlock(
                'transport',
                'Skipped: the Telegram API was unreachable recently.',
                $config['backoff']
            );

            Log::warning('Telegram transport failure', [
                'curl_errno' => $errorNo,
                'curl_error' => $error,
            ]);

            return static::result(false, 'Connection failed: '.static::explain($errorNo, $error));
        }

        $decoded = json_decode((string) $body, true);

        if ($status !== 200 || ! ($decoded['ok'] ?? false)) {
            $description = static::redact(
                (string) ($decoded['description'] ?? 'Unknown error occurred'),
                $config['bot_token']
            );
            $parseError = static::isParseError($description);

            if ($status === 429) {
                $retryAfter = (int) ($decoded['parameters']['retry_after'] ?? $config['backoff']);
                $retryAfter = max(30, min(self::MAX_BACKOFF_SECONDS, $retryAfter));
                static::rememberBlock(
                    'rate_limit',
                    'Skipped: Telegram rate limiting is active. Try again later or use Test Connection.',
                    $retryAfter
                );
            } elseif ($status >= 500 && $status <= 599) {
                static::rememberBlock(
                    'server',
                    'Skipped: Telegram reported a temporary server error recently.',
                    $config['backoff']
                );
            } elseif ($status >= 400 && $status <= 499 && ! $parseError) {
                static::rememberBlock(
                    'configuration',
                    'Blocked: Telegram rejected the saved configuration. Use Test Connection after correcting it.',
                    self::MAX_BACKOFF_SECONDS
                );
            } elseif ($status === 0) {
                static::rememberBlock(
                    'server',
                    'Skipped: Telegram returned an invalid response recently.',
                    $config['backoff']
                );
            }

            Log::warning('Telegram API rejected the request', [
                'http_code' => $status,
                'description' => $description,
            ]);

            return static::result(
                false,
                'Telegram API Error: '.$description,
                [],
                $parseError
            );
        }

        Cache::forget(self::BLOCK_KEY);

        $chat = $decoded['result']['chat'] ?? [];

        return static::result(true, 'Message sent successfully to Telegram channel!', [
            'message_id' => $decoded['result']['message_id'] ?? null,
            'chat_id' => $chat['id'] ?? null,
            'chat_title' => $chat['title'] ?? ($chat['username'] ?? 'Private Chat'),
        ]);
    }

    /**
     * Resolve the effective configuration.
     */
    private static function config(): array
    {
        $settings = Setting::getByGroup('integration_telegram')['telegram_settings'] ?? [];

        $connectTimeout = static::boundedInteger(config('telegram.connect_timeout'), 4, 1, 15);
        $timeout = static::boundedInteger(config('telegram.timeout'), 8, 1, 30);

        return [
            'enabled' => (bool) ($settings['enabled'] ?? false),
            'bot_token' => trim((string) ($settings['bot_token'] ?? '')),
            'channel_id' => trim((string) ($settings['channel_id'] ?? '')),
            'connect_timeout' => $connectTimeout,
            'timeout' => max($connectTimeout, $timeout),
            'verify_ssl' => (bool) config('telegram.verify_ssl', true),
            'force_ipv4' => (bool) config('telegram.force_ipv4', true),
            'proxy' => trim((string) config('telegram.proxy', '')),
            'backoff' => static::boundedInteger(config('telegram.backoff_seconds'), 300, 30, self::MAX_BACKOFF_SECONDS),
        ];
    }

    private static function boundedInteger($value, int $default, int $minimum, int $maximum): int
    {
        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            $value = $default;
        }

        return max($minimum, min($maximum, (int) $value));
    }

    private static function rememberBlock(string $kind, string $message, int $seconds): void
    {
        Cache::put(self::BLOCK_KEY, [
            'kind' => $kind,
            'message' => $message,
        ], max(30, min(self::MAX_BACKOFF_SECONDS, $seconds)));
    }

    private static function redact(string $value, string $botToken): string
    {
        return $botToken === '' ? $value : str_replace($botToken, '[redacted]', $value);
    }

    /**
     * Turn a cURL error number into something the operator can act on.
     */
    private static function explain(int $errorNo, string $error): string
    {
        $unreachable = [
            CURLE_COULDNT_RESOLVE_HOST,
            CURLE_COULDNT_CONNECT,
            CURLE_OPERATION_TIMEOUTED,
        ];

        if (in_array($errorNo, $unreachable, true)) {
            return $error.'. This server cannot reach api.telegram.org, which usually means the'
                .' hosting provider blocks it. Ask them to allow outbound HTTPS to api.telegram.org,'
                .' or route the call through a proxy by setting TELEGRAM_PROXY in .env.';
        }

        if (in_array($errorNo, [CURLE_SSL_PEER_CERTIFICATE, CURLE_SSL_CACERT], true)) {
            return $error.'. The CA bundle on this server appears to be out of date.';
        }

        return $error;
    }

    /**
     * Escape a value for Telegram's HTML parse mode.
     *
     * Only these three characters are special, and escaping just them keeps the
     * plain-text fallback readable.
     */
    private static function esc($value): string
    {
        return str_replace(['&', '<', '>'], ['&amp;', '&lt;', '&gt;'], (string) $value);
    }

    /**
     * Keep the payload inside Telegram's length limit.
     */
    private static function clamp(string $message): string
    {
        if (mb_strlen($message, 'UTF-8') <= self::MAX_LENGTH) {
            return $message;
        }

        return mb_substr($message, 0, self::MAX_LENGTH - 1, 'UTF-8').'…';
    }

    /**
     * Reduce a formatted message to plain text for the retry attempt.
     */
    private static function stripMarkup(string $message): string
    {
        return html_entity_decode(strip_tags($message), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Did Telegram reject the message because of its markup?
     */
    private static function isParseError(string $description): bool
    {
        $description = strtolower($description);

        return str_contains($description, "can't parse entities")
            || str_contains($description, 'can\'t parse')
            || str_contains($description, 'unsupported start tag')
            || str_contains($description, 'unclosed start tag')
            || str_contains($description, 'parse_mode');
    }

    /**
     * @return array{success: bool, message: string, data: array, parse_error: bool}
     */
    private static function result(bool $success, string $message, array $data = [], bool $parseError = false): array
    {
        return [
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'parse_error' => $parseError,
        ];
    }
}
