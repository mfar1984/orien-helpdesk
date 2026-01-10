<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #3b82f6; color: white; padding: 20px; text-align: center; }
        .content { background-color: #f9fafb; padding: 30px; }
        .ticket-info { background-color: white; padding: 15px; margin: 20px 0; border-left: 4px solid #3b82f6; }
        .button { display: inline-block; padding: 12px 24px; background-color: #3b82f6; color: white; text-decoration: none; border-radius: 4px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Ticket Created Successfully</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $ticket->creator->name }},</p>
            
            <p>Thank you for contacting us. Your support ticket has been created successfully.</p>
            
            <div class="ticket-info">
                <strong>Ticket ID:</strong> #{{ $ticket->ticket_number }}<br>
                <strong>Subject:</strong> {{ $ticket->subject }}<br>
                <strong>Priority:</strong> {{ $ticket->priority->name }}<br>
                <strong>Status:</strong> {{ $ticket->status->name }}<br>
                <strong>Created:</strong> {{ $ticket->created_at->format('d M Y, H:i') }}
            </div>
            
            <p><strong>Your message:</strong></p>
            <p style="background-color: white; padding: 15px; border-radius: 4px;">{{ $ticket->description }}</p>
            
            <p>We will respond to your ticket as soon as possible. You can track the status of your ticket by clicking the button below:</p>
            
            <a href="{{ route('tickets.show', $ticket->id) }}" class="button">View Ticket</a>
            
            <p>Best regards,<br>{{ config('app.name') }} Support Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply directly to this message.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
