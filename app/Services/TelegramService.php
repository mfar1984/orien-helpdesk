<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    /**
     * Send a message to the configured Telegram channel.
     */
    public static function send(string $message, string $parseMode = 'Markdown'): bool
    {
        $settings = Setting::getByGroup('integration_telegram');
        $telegramSettings = $settings['telegram_settings'] ?? [];

        // Check if Telegram is enabled and configured
        if (empty($telegramSettings['enabled']) || !$telegramSettings['enabled']) {
            return false;
        }

        if (empty($telegramSettings['bot_token']) || empty($telegramSettings['channel_id'])) {
            return false;
        }

        try {
            $url = "https://api.telegram.org/bot{$telegramSettings['bot_token']}/sendMessage";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'chat_id' => $telegramSettings['channel_id'],
                'text' => $message,
                'parse_mode' => $parseMode,
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                Log::warning('Telegram notification failed', [
                    'http_code' => $httpCode,
                    'response' => $response
                ]);
                return false;
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Telegram notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send activity log notification to Telegram.
     */
    public static function sendActivityNotification(string $action, string $module, string $description, ?array $context = null): bool
    {
        $user = auth()->user();
        $userName = $user ? $user->name : 'System';
        $userType = $user ? ucfirst($user->user_type ?? 'Unknown') : 'System';
        
        // Get action emoji
        $emoji = match($action) {
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

        // Build message
        $message = "{$emoji} *" . company_name() . " Activity*\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━\n";
        $message .= "📌 *Action:* " . ucfirst(str_replace('_', ' ', $action)) . "\n";
        $message .= "📁 *Module:* " . ucfirst(str_replace('_', ' ', $module)) . "\n";
        $message .= "━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "📝 {$description}\n\n";
        
        if ($context) {
            if (isset($context['ticket_number'])) {
                $message .= "🎫 Ticket: #{$context['ticket_number']}\n";
            }
            if (isset($context['subject'])) {
                $message .= "📋 Subject: {$context['subject']}\n";
            }
            if (isset($context['status'])) {
                $message .= "📊 Status: {$context['status']}\n";
            }
            if (isset($context['priority'])) {
                $message .= "⚡ Priority: {$context['priority']}\n";
            }
            $message .= "\n";
        }
        
        $message .= "👤 *By:* {$userName} ({$userType})\n";
        $message .= "⏰ *Time:* " . now()->format('d M Y, H:i:s') . "\n";

        return self::send($message);
    }

    /**
     * Send ticket notification.
     */
    public static function sendTicketNotification(string $type, array $ticketData): bool
    {
        $emoji = match($type) {
            'created' => '🎫',
            'replied' => '💬',
            'closed' => '✅',
            'assigned' => '👤',
            'status_changed' => '🔄',
            default => '📋'
        };

        $title = match($type) {
            'created' => 'New Ticket Created',
            'replied' => 'New Reply',
            'closed' => 'Ticket Closed',
            'assigned' => 'Ticket Assigned',
            'status_changed' => 'Status Changed',
            default => 'Ticket Update'
        };

        $message = "{$emoji} *" . company_name() . " - {$title}*\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━\n";
        $message .= "🎫 *Ticket:* #{$ticketData['ticket_number']}\n";
        $message .= "📋 *Subject:* {$ticketData['subject']}\n";
        
        if (isset($ticketData['status'])) {
            $message .= "📊 *Status:* {$ticketData['status']}\n";
        }
        if (isset($ticketData['priority'])) {
            $message .= "⚡ *Priority:* {$ticketData['priority']}\n";
        }
        if (isset($ticketData['category'])) {
            $message .= "📁 *Category:* {$ticketData['category']}\n";
        }
        
        $message .= "━━━━━━━━━━━━━━━━━━\n\n";
        
        if (isset($ticketData['message'])) {
            // Truncate long messages
            $content = strip_tags($ticketData['message']);
            if (strlen($content) > 200) {
                $content = substr($content, 0, 200) . '...';
            }
            $message .= "💬 {$content}\n\n";
        }
        
        $message .= "👤 *By:* {$ticketData['user_name']}\n";
        $message .= "⏰ *Time:* " . now()->format('d M Y, H:i:s') . "\n";
        
        if (isset($ticketData['url'])) {
            $message .= "\n🔗 [View Ticket]({$ticketData['url']})";
        }

        return self::send($message);
    }
}
