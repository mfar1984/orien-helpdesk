<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $resetUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $resetUrl)
    {
        $this->user = $user;
        $this->resetUrl = $resetUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Reset Your Password - ' . company_name())
            ->view('emails.template')
            ->with([
                'badge' => 'Password Reset',
                'badgeColor' => '#ef4444',
                'title' => 'Reset Your Password',
                'greeting' => 'Dear <strong>' . $this->user->name . '</strong>, you have requested to reset your password.',
                'content' => '
                    <p style="color: #6b7280; margin-bottom: 20px;">Click the button below to reset your password. This link will expire in <strong>60 minutes</strong>.</p>
                    <p style="color: #6b7280; margin-bottom: 15px;"><strong>For security reasons:</strong></p>
                    <ul style="color: #6b7280; margin: 0 0 20px 0; padding-left: 20px;">
                        <li style="margin-bottom: 8px;">🔒 Never share your password with anyone</li>
                        <li style="margin-bottom: 8px;">🔑 Use a strong, unique password</li>
                        <li style="margin-bottom: 8px;">🛡️ Enable two-factor authentication if available</li>
                    </ul>
                    <p style="color: #9ca3af; font-size: 12px;">If you did not request a password reset, no further action is required.</p>
                ',
                'actionUrl' => $this->resetUrl,
                'actionText' => 'Reset Password',
            ]);
    }
}
