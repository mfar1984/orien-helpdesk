<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #22c55e; color: white; padding: 20px; text-align: center; }
        .content { background-color: #f9fafb; padding: 30px; }
        .feature-list { background-color: white; padding: 20px; margin: 20px 0; border-radius: 4px; }
        .feature-list li { margin: 10px 0; }
        .button { display: inline-block; padding: 12px 24px; background-color: #22c55e; color: white; text-decoration: none; border-radius: 4px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to {{ company_name() }}!</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            
            <p>Welcome to {{ company_name() }} Support! Your account has been created successfully.</p>
            
            <div class="feature-list">
                <p><strong>You can now:</strong></p>
                <ul>
                    <li>✓ Submit support tickets</li>
                    <li>✓ Track your ticket status</li>
                    <li>✓ Browse our Knowledge Base</li>
                    <li>✓ View your ticket history</li>
                </ul>
            </div>
            
            <p><strong>Your Login Details:</strong></p>
            <p style="background-color: white; padding: 15px; border-radius: 4px;">
                <strong>Email:</strong> {{ $user->email }}<br>
                <strong>Login URL:</strong> <a href="{{ route('login') }}">{{ route('login') }}</a>
            </p>
            
            <p>If you have any questions, feel free to create a support ticket.</p>
            
            <a href="{{ route('login') }}" class="button">Login Now</a>
            
            <p>Best regards,<br>{{ company_name() }} Support Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply directly to this message.</p>
            <p>&copy; {{ date('Y') }} {{ company_name() }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
