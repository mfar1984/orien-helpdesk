<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f9fafb; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 40px 30px; }
        .success-badge { display: inline-block; background-color: #22c55e; color: white; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 600; margin: 20px 0; }
        .info-box { background-color: #f0f9ff; border-left: 4px solid #3b82f6; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .footer { text-align: center; padding: 20px; background-color: #f9fafb; color: #6b7280; font-size: 12px; }
        .button { display: inline-block; padding: 12px 24px; background-color: #3b82f6; color: white; text-decoration: none; border-radius: 4px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ Email Test Successful</h1>
        </div>
        
        <div class="content">
            <div style="text-align: center;">
                <span class="success-badge">✓ Configuration Working</span>
            </div>
            
            <p>Congratulations! Your email configuration is working correctly.</p>
            
            <div class="info-box">
                <p style="margin: 0;"><strong>Test Details:</strong></p>
                <p style="margin: 5px 0 0 0;">
                    <strong>Date:</strong> {{ now()->format('d M Y, H:i:s') }}<br>
                    <strong>From:</strong> {{ company_name() }}<br>
                    <strong>Status:</strong> Successfully delivered
                </p>
            </div>
            
            <p>This is a test email sent from your {{ company_name() }} helpdesk system to verify that your email gateway is properly configured and functioning.</p>
            
            <p><strong>What's working:</strong></p>
            <ul>
                <li>✓ SMTP/Gmail connection established</li>
                <li>✓ Email authentication successful</li>
                <li>✓ Message delivery confirmed</li>
                <li>✓ Email formatting working</li>
            </ul>
            
            <p style="margin-top: 30px;">You can now use this email configuration to send notifications, password resets, and other automated emails.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated test email from {{ company_name() }}</p>
            <p>&copy; {{ date('Y') }} {{ company_name() }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
