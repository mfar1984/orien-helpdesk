<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Template Preview</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #1f2937; min-height: 100vh; padding: 40px 20px; }
        .container { max-width: 1400px; margin: 0 auto; }
        h1 { color: #fff; text-align: center; margin-bottom: 10px; font-size: 28px; }
        .subtitle { color: #9ca3af; text-align: center; margin-bottom: 40px; font-size: 14px; }
        .designs { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
        .design-card { background: #374151; border-radius: 12px; overflow: hidden; }
        .design-header { padding: 20px; border-bottom: 1px solid #4b5563; display: flex; justify-content: space-between; align-items: center; }
        .design-title { color: #fff; font-size: 16px; font-weight: 600; }
        .design-badge { background: #3b82f6; color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 12px; }
        .design-preview { padding: 20px; background: #e5e7eb; }
        .email-frame { border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
        .select-btn { display: block; margin: 20px; padding: 14px; background: #10b981; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; text-align: center; text-decoration: none; }
        .select-btn:hover { background: #059669; }
        @media (max-width: 1200px) { .designs { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Email Template Preview</h1>
        <p class="subtitle">Pilih design yang anda suka untuk semua email notifications</p>
        
        <div class="designs">
            <!-- Design 1 -->
            <div class="design-card">
                <div class="design-header">
                    <span class="design-title">Design 1: Modern Card</span>
                    <span class="design-badge">Gradient Header</span>
                </div>
                <div class="design-preview">
                    <div class="email-frame">
                        <!-- Design 1 Email -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f3f4f6; padding: 20px;">
                            <tr>
                                <td align="center">
                                    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); overflow: hidden;">
                                        <tr>
                                            <td style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); padding: 25px; text-align: center;">
                                                <h1 style="color: #ffffff; margin: 0; font-size: 20px; font-family: 'Segoe UI', sans-serif;">📩 New Reply</h1>
                                                <p style="color: #bfdbfe; margin: 8px 0 0 0; font-size: 13px; font-family: 'Segoe UI', sans-serif;">Ticket #TKT-2026-0001</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 25px;">
                                                <p style="color: #374151; font-size: 14px; margin: 0 0 15px 0; font-family: 'Segoe UI', sans-serif;">Dear <strong>Faizan Rahman</strong>,</p>
                                                <p style="color: #6b7280; font-size: 13px; line-height: 1.6; margin: 0 0 20px 0; font-family: 'Segoe UI', sans-serif;">
                                                    You have received a new reply on your support ticket.
                                                </p>
                                                <div style="background-color: #f9fafb; border-radius: 8px; padding: 15px; margin-bottom: 20px; border-left: 4px solid #3b82f6;">
                                                    <p style="margin: 0 0 8px 0; font-size: 11px; color: #9ca3af; text-transform: uppercase; font-family: 'Segoe UI', sans-serif;">Subject</p>
                                                    <p style="margin: 0 0 12px 0; font-size: 14px; color: #1f2937; font-weight: 600; font-family: 'Segoe UI', sans-serif;">Nice Logo</p>
                                                    <p style="margin: 0 0 8px 0; font-size: 11px; color: #9ca3af; text-transform: uppercase; font-family: 'Segoe UI', sans-serif;">Reply From</p>
                                                    <p style="margin: 0; font-size: 13px; color: #374151; font-family: 'Segoe UI', sans-serif;">👤 Administrator</p>
                                                </div>
                                                <div style="background-color: #eff6ff; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                                                    <p style="margin: 0; color: #1e40af; font-size: 13px; line-height: 1.6; font-family: 'Segoe UI', sans-serif;">
                                                        "notification email"
                                                    </p>
                                                </div>
                                                <div style="text-align: center;">
                                                    <a href="#" style="background-color: #3b82f6; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 13px; display: inline-block; font-family: 'Segoe UI', sans-serif;">
                                                        View Full Conversation
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: #f9fafb; padding: 20px; text-align: center; border-top: 1px solid #e5e7eb;">
                                                <p style="margin: 0; color: #6b7280; font-size: 11px; font-family: 'Segoe UI', sans-serif;">Best regards,</p>
                                                <p style="margin: 5px 0 0 0; color: #374151; font-size: 13px; font-weight: 600; font-family: 'Segoe UI', sans-serif;">ORIEN NET SOLUTIONS SDN BHD</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <a href="{{ route('email.preview.select', ['design' => 1]) }}" class="select-btn">✓ Pilih Design Ini</a>
            </div>

            <!-- Design 2 -->
            <div class="design-card">
                <div class="design-header">
                    <span class="design-title">Design 2: Minimal Clean</span>
                    <span class="design-badge">Simple & Light</span>
                </div>
                <div class="design-preview">
                    <div class="email-frame">
                        <!-- Design 2 Email -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #ffffff; padding: 25px;">
                            <tr>
                                <td style="padding-bottom: 20px; border-bottom: 2px solid #3b82f6;">
                                    <h2 style="margin: 0; color: #1f2937; font-size: 18px; font-family: 'Segoe UI', sans-serif;">🎫 ORIEN Helpdesk</h2>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 25px 0;">
                                    <p style="color: #374151; font-size: 14px; margin: 0 0 15px 0; font-family: 'Segoe UI', sans-serif;">Hi Faizan,</p>
                                    <p style="color: #6b7280; font-size: 13px; line-height: 1.7; margin: 0 0 20px 0; font-family: 'Segoe UI', sans-serif;">
                                        There's a new reply on your ticket <strong style="color: #3b82f6;">#TKT-2026-0001</strong>
                                    </p>
                                    <table width="100%" style="background-color: #f8fafc; border-radius: 6px; margin-bottom: 20px;">
                                        <tr>
                                            <td style="padding: 15px;">
                                                <table width="100%">
                                                    <tr>
                                                        <td style="padding: 6px 0;">
                                                            <span style="color: #9ca3af; font-size: 11px; font-family: 'Segoe UI', sans-serif;">SUBJECT</span><br>
                                                            <span style="color: #1f2937; font-size: 13px; font-weight: 500; font-family: 'Segoe UI', sans-serif;">Nice Logo</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 6px 0;">
                                                            <span style="color: #9ca3af; font-size: 11px; font-family: 'Segoe UI', sans-serif;">FROM</span><br>
                                                            <span style="color: #1f2937; font-size: 13px; font-family: 'Segoe UI', sans-serif;">Administrator</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 6px 0;">
                                                            <span style="color: #9ca3af; font-size: 11px; font-family: 'Segoe UI', sans-serif;">MESSAGE</span><br>
                                                            <span style="color: #374151; font-size: 13px; font-style: italic; font-family: 'Segoe UI', sans-serif;">"notification email"</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <a href="#" style="display: inline-block; background-color: #3b82f6; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 500; font-family: 'Segoe UI', sans-serif;">
                                        View Ticket →
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 20px; border-top: 1px solid #e5e7eb;">
                                    <p style="margin: 0; color: #9ca3af; font-size: 11px; font-family: 'Segoe UI', sans-serif;">
                                        ORIEN NET SOLUTIONS SDN BHD<br>
                                        <span style="color: #d1d5db;">Support Team</span>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <a href="{{ route('email.preview.select', ['design' => 2]) }}" class="select-btn">✓ Pilih Design Ini</a>
            </div>

            <!-- Design 3 -->
            <div class="design-card">
                <div class="design-header">
                    <span class="design-title">Design 3: Dark Header</span>
                    <span class="design-badge">Professional</span>
                </div>
                <div class="design-preview">
                    <div class="email-frame">
                        <!-- Design 3 Email -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #e5e7eb; padding: 20px;">
                            <tr>
                                <td align="center">
                                    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                                        <tr>
                                            <td style="background-color: #1f2937; padding: 18px 20px;">
                                                <table width="100%">
                                                    <tr>
                                                        <td>
                                                            <span style="color: #ffffff; font-size: 16px; font-weight: 600; font-family: 'Segoe UI', sans-serif;">ORIEN</span>
                                                            <span style="color: #60a5fa; font-size: 16px; font-family: 'Segoe UI', sans-serif;"> Helpdesk</span>
                                                        </td>
                                                        <td align="right">
                                                            <span style="background-color: #3b82f6; color: #ffffff; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-family: 'Segoe UI', sans-serif;">New Reply</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 20px 20px 0 20px;">
                                                <span style="background-color: #dbeafe; color: #1e40af; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; font-family: 'Segoe UI', sans-serif;">
                                                    🎫 TKT-2026-0001
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 20px;">
                                                <h2 style="margin: 0 0 12px 0; color: #1f2937; font-size: 17px; font-family: 'Segoe UI', sans-serif;">Nice Logo</h2>
                                                <p style="margin: 0 0 15px 0; color: #6b7280; font-size: 13px; line-height: 1.6; font-family: 'Segoe UI', sans-serif;">
                                                    Dear Faizan Rahman, you have a new reply from <strong style="color: #374151;">Administrator</strong>
                                                </p>
                                                <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 8px; padding: 15px; border-left: 4px solid #0ea5e9;">
                                                    <p style="margin: 0; color: #0c4a6e; font-size: 13px; line-height: 1.6; font-family: 'Segoe UI', sans-serif;">
                                                        notification email
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 0 20px 20px 20px;">
                                                <a href="#" style="display: block; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #ffffff; padding: 12px; text-decoration: none; border-radius: 8px; font-size: 13px; font-weight: 600; text-align: center; font-family: 'Segoe UI', sans-serif;">
                                                    View Full Conversation
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: #f9fafb; padding: 15px 20px; border-top: 1px solid #e5e7eb;">
                                                <table width="100%">
                                                    <tr>
                                                        <td>
                                                            <p style="margin: 0; color: #6b7280; font-size: 11px; font-family: 'Segoe UI', sans-serif;">Best regards,</p>
                                                            <p style="margin: 3px 0 0 0; color: #374151; font-size: 12px; font-weight: 600; font-family: 'Segoe UI', sans-serif;">ORIEN NET SOLUTIONS SDN BHD</p>
                                                        </td>
                                                        <td align="right" style="color: #9ca3af; font-size: 10px; font-family: 'Segoe UI', sans-serif;">
                                                            Support Team
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <a href="{{ route('email.preview.select', ['design' => 3]) }}" class="select-btn">✓ Pilih Design Ini</a>
            </div>
        </div>
    </div>
</body>
</html>
