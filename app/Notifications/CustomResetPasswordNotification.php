<?php

namespace App\Notifications;

use App\Models\EmailTemplate;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordNotification extends Notification
{
    public $token;
    public $email;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $template = EmailTemplate::where('slug', 'password-reset')->first();

        if ($template) {
            $subject = $this->replacePlaceholders($template->subject, $notifiable, $resetUrl);
            $body = $this->replacePlaceholders($template->body, $notifiable, $resetUrl);

            return (new MailMessage)
                ->subject($subject)
                ->html($body);
        }

        // Fallback to default Laravel email
        return (new MailMessage)
            ->subject('Reset Your Password - ' . company_name())
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $resetUrl)
            ->line('This password reset link will expire in 60 minutes.')
            ->line('If you did not request a password reset, no further action is required.');
    }

    /**
     * Replace placeholders in template.
     */
    private function replacePlaceholders($content, $user, $resetUrl)
    {
        $replacements = [
            '{{customer_name}}' => $user->name,
            '{{customer_email}}' => $user->email,
            '{{reset_url}}' => $resetUrl,
            '{{company_name}}' => company_name(),
            '{{expiry_time}}' => '60 minutes',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }
}
