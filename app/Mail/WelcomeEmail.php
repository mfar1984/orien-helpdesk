<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Welcome to ' . company_name())
            ->view('emails.template')
            ->with([
                'badge' => 'Welcome',
                'badgeColor' => '#10b981',
                'title' => 'Welcome to ' . company_name() . '!',
                'greeting' => 'Dear <strong>' . $this->user->name . '</strong>, your account has been created successfully.',
                'content' => '
                    <p style="color: #374151; margin-bottom: 15px;">You can now:</p>
                    <ul style="color: #6b7280; margin: 0 0 20px 0; padding-left: 20px;">
                        <li style="margin-bottom: 8px;">📝 Submit support tickets</li>
                        <li style="margin-bottom: 8px;">📊 Track your ticket status</li>
                        <li style="margin-bottom: 8px;">📚 Browse our Knowledge Base</li>
                        <li style="margin-bottom: 8px;">📋 View your ticket history</li>
                    </ul>
                ',
                'infoItems' => [
                    'Your Email' => $this->user->email,
                ],
                'actionUrl' => route('login'),
                'actionText' => 'Login to Your Account',
            ]);
    }
}
