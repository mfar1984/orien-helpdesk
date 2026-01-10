<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'ORIEN Helpdesk' }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #e5e7eb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                    <!-- Dark Header -->
                    <tr>
                        <td style="background-color: #1f2937; padding: 25px 30px;">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <span style="color: #ffffff; font-size: 18px; font-weight: 600;">{{ company_name() ?? 'ORIEN' }}</span>
                                        <span style="color: #60a5fa; font-size: 18px;"> Helpdesk</span>
                                    </td>
                                    <td align="right">
                                        @if(isset($badge))
                                        <span style="background-color: {{ $badgeColor ?? '#3b82f6' }}; color: #ffffff; padding: 6px 12px; border-radius: 20px; font-size: 12px;">{{ $badge }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Ticket Badge (if applicable) -->
                    @if(isset($ticketNumber))
                    <tr>
                        <td style="padding: 25px 30px 0 30px;">
                            <span style="background-color: #dbeafe; color: #1e40af; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                🎫 {{ $ticketNumber }}
                            </span>
                        </td>
                    </tr>
                    @endif
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 25px 30px;">
                            @if(isset($title))
                            <h2 style="margin: 0 0 15px 0; color: #1f2937; font-size: 20px;">{{ $title }}</h2>
                            @endif
                            
                            <p style="margin: 0 0 20px 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                {!! $greeting ?? '' !!}
                            </p>
                            
                            <!-- Message Box -->
                            @if(isset($messageContent))
                            <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 8px; padding: 20px; border-left: 4px solid #0ea5e9; margin-bottom: 20px;">
                                <p style="margin: 0; color: #0c4a6e; font-size: 14px; line-height: 1.6;">
                                    {!! $messageContent !!}
                                </p>
                            </div>
                            @endif
                            
                            <!-- Info Box -->
                            @if(isset($infoItems) && count($infoItems) > 0)
                            <div style="background-color: #f9fafb; border-radius: 8px; padding: 20px; margin-bottom: 20px; border-left: 4px solid #3b82f6;">
                                @foreach($infoItems as $label => $value)
                                <p style="margin: 0 0 10px 0; font-size: 12px; color: #9ca3af; text-transform: uppercase;">{{ $label }}</p>
                                <p style="margin: 0 0 15px 0; font-size: 14px; color: #1f2937; font-weight: 500;">{{ $value }}</p>
                                @endforeach
                            </div>
                            @endif
                            
                            <!-- Additional Content -->
                            @if(isset($content))
                            <div style="margin-bottom: 20px; color: #374151; font-size: 14px; line-height: 1.6;">
                                {!! $content !!}
                            </div>
                            @endif
                        </td>
                    </tr>
                    
                    <!-- CTA Button -->
                    @if(isset($actionUrl) && isset($actionText))
                    <tr>
                        <td style="padding: 0 30px 30px 30px;">
                            <a href="{{ $actionUrl }}" style="display: block; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #ffffff; padding: 16px; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; text-align: center;">
                                {{ $actionText }}
                            </a>
                        </td>
                    </tr>
                    @endif
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 20px 30px; border-top: 1px solid #e5e7eb;">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <p style="margin: 0; color: #6b7280; font-size: 12px;">Best regards,</p>
                                        <p style="margin: 5px 0 0 0; color: #374151; font-size: 13px; font-weight: 600;">{{ company_name() ?? 'ORIEN NET SOLUTIONS SDN BHD' }}</p>
                                    </td>
                                    <td align="right" style="color: #9ca3af; font-size: 11px;">
                                        Support Team
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                
                <!-- Footer Links -->
                <table width="600" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
                    <tr>
                        <td align="center" style="color: #9ca3af; font-size: 11px;">
                            <p style="margin: 0;">This is an automated message from {{ company_name() ?? 'ORIEN Helpdesk' }}</p>
                            <p style="margin: 5px 0 0 0;">Please do not reply directly to this email.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
