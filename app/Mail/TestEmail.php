<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Test Email - ' . company_name())
            ->view('emails.template')
            ->with([
                'badge' => 'Test Email',
                'badgeColor' => '#10b981',
                'title' => 'Email Configuration Test',
                'greeting' => 'Hello! This is a test email from <strong>' . company_name() . '</strong>.',
                'content' => '
                    <p style="color: #10b981; font-weight: 600; margin-bottom: 15px;">✅ Your email configuration is working correctly!</p>
                    <p style="color: #6b7280; margin-bottom: 15px;">If you received this email, it means your SMTP settings are properly configured and emails can be sent from your helpdesk system.</p>
                    <p style="color: #9ca3af; font-size: 12px;">This is an automated test message. No action is required.</p>
                ',
                'infoItems' => [
                    'Sent At' => now()->format('d M Y, H:i:s'),
                    'Server' => config('mail.mailers.smtp.host', 'N/A'),
                ],
            ]);
    }
}
