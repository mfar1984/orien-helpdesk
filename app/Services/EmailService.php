<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Configure mail settings from database.
     */
    public static function configure(): void
    {
        $settings = Setting::getByGroup('integration_email');
        
        if (empty($settings)) {
            return;
        }

        $emailSettings = $settings['email_settings'] ?? [];
        
        if (empty($emailSettings)) {
            return;
        }

        $provider = $emailSettings['provider'] ?? 'smtp';

        if ($provider === 'smtp') {
            self::configureSMTP($emailSettings);
        } elseif ($provider === 'gmail') {
            self::configureGmail($emailSettings);
        }

        // Set from address
        Config::set('mail.from.address', $emailSettings['from_address'] ?? config('mail.from.address'));
        Config::set('mail.from.name', $emailSettings['from_name'] ?? config('mail.from.name'));
    }

    /**
     * Configure SMTP settings.
     */
    private static function configureSMTP(array $settings): void
    {
        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => $settings['host'] ?? '',
            'port' => $settings['port'] ?? 587,
            'encryption' => $settings['encryption'] ?? 'tls',
            'username' => $settings['username'] ?? '',
            'password' => $settings['password'] ?? '',
            'timeout' => null,
        ]);
    }

    /**
     * Configure Gmail settings.
     */
    private static function configureGmail(array $settings): void
    {
        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'encryption' => 'tls',
            'username' => $settings['gmail_client_id'] ?? '',
            'password' => $settings['gmail_client_secret'] ?? '',
            'timeout' => null,
        ]);
    }

    /**
     * Check if email is configured.
     */
    public static function isConfigured(): bool
    {
        $settings = Setting::getByGroup('integration_email');
        return !empty($settings['email_settings']);
    }

    /**
     * Send email with automatic configuration.
     */
    public static function send($mailable, $recipient)
    {
        if (!self::isConfigured()) {
            \Log::warning('Email not configured. Skipping email send.');
            return false;
        }

        try {
            self::configure();
            Mail::to($recipient)->send($mailable);
            return true;
        } catch (\Exception $e) {
            \Log::error('Email send failed: ' . $e->getMessage());
            return false;
        }
    }
}
