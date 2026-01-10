<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use App\Models\SlaRule;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use Illuminate\Database\Seeder;

class TicketSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categories
        $categories = [
            ['name' => 'General', 'color' => '#3b82f6', 'icon' => 'category', 'sort_order' => 1],
            ['name' => 'Technical', 'color' => '#8b5cf6', 'icon' => 'build', 'sort_order' => 2],
            ['name' => 'Billing', 'color' => '#10b981', 'icon' => 'payments', 'sort_order' => 3],
            ['name' => 'Sales', 'color' => '#f59e0b', 'icon' => 'storefront', 'sort_order' => 4],
            ['name' => 'Support', 'color' => '#ef4444', 'icon' => 'support_agent', 'sort_order' => 5],
        ];

        foreach ($categories as $category) {
            TicketCategory::firstOrCreate(['name' => $category['name']], $category);
        }

        // Priorities
        $priorities = [
            ['name' => 'Low', 'color' => '#22c55e', 'icon' => 'arrow_downward', 'sort_order' => 1],
            ['name' => 'Medium', 'color' => '#f59e0b', 'icon' => 'remove', 'sort_order' => 2],
            ['name' => 'High', 'color' => '#f97316', 'icon' => 'arrow_upward', 'sort_order' => 3],
            ['name' => 'Critical', 'color' => '#ef4444', 'icon' => 'priority_high', 'sort_order' => 4],
        ];

        foreach ($priorities as $priority) {
            TicketPriority::firstOrCreate(['name' => $priority['name']], $priority);
        }

        // Statuses
        $statuses = [
            ['name' => 'Open', 'color' => '#3b82f6', 'icon' => 'radio_button_unchecked', 'sort_order' => 1, 'is_default' => true, 'is_closed' => false],
            ['name' => 'In Progress', 'color' => '#f59e0b', 'icon' => 'pending', 'sort_order' => 2, 'is_default' => false, 'is_closed' => false],
            ['name' => 'Waiting', 'color' => '#8b5cf6', 'icon' => 'hourglass_empty', 'sort_order' => 3, 'is_default' => false, 'is_closed' => false],
            ['name' => 'Resolved', 'color' => '#10b981', 'icon' => 'check_circle', 'sort_order' => 4, 'is_default' => false, 'is_closed' => true],
            ['name' => 'Closed', 'color' => '#6b7280', 'icon' => 'cancel', 'sort_order' => 5, 'is_default' => false, 'is_closed' => true],
        ];

        foreach ($statuses as $status) {
            TicketStatus::firstOrCreate(['name' => $status['name']], $status);
        }

        // SLA Rules - Get priority IDs
        $lowPriority = TicketPriority::where('name', 'Low')->first();
        $mediumPriority = TicketPriority::where('name', 'Medium')->first();
        $highPriority = TicketPriority::where('name', 'High')->first();
        $criticalPriority = TicketPriority::where('name', 'Critical')->first();
        
        // Get category IDs
        $technicalCategory = TicketCategory::where('name', 'Technical')->first();
        $billingCategory = TicketCategory::where('name', 'Billing')->first();
        
        $slaRules = [
            // General SLA for all priorities/categories
            ['name' => 'Default SLA', 'response_time' => 480, 'resolution_time' => 2880, 'priority_id' => null, 'category_id' => null, 'sort_order' => 10],
            
            // Priority-based SLA rules (apply to all categories)
            ['name' => 'Low Priority SLA', 'response_time' => 480, 'resolution_time' => 2880, 'priority_id' => $lowPriority?->id, 'category_id' => null, 'sort_order' => 9],
            ['name' => 'Medium Priority SLA', 'response_time' => 240, 'resolution_time' => 1440, 'priority_id' => $mediumPriority?->id, 'category_id' => null, 'sort_order' => 8],
            ['name' => 'High Priority SLA', 'response_time' => 60, 'resolution_time' => 480, 'priority_id' => $highPriority?->id, 'category_id' => null, 'sort_order' => 7],
            ['name' => 'Critical Priority SLA', 'response_time' => 15, 'resolution_time' => 120, 'priority_id' => $criticalPriority?->id, 'category_id' => null, 'sort_order' => 6],
            
            // Specific Priority + Category combinations (most specific, highest priority)
            ['name' => 'Critical Technical Issues', 'response_time' => 10, 'resolution_time' => 60, 'priority_id' => $criticalPriority?->id, 'category_id' => $technicalCategory?->id, 'sort_order' => 1],
            ['name' => 'High Technical Issues', 'response_time' => 30, 'resolution_time' => 240, 'priority_id' => $highPriority?->id, 'category_id' => $technicalCategory?->id, 'sort_order' => 2],
            ['name' => 'Critical Billing Issues', 'response_time' => 15, 'resolution_time' => 120, 'priority_id' => $criticalPriority?->id, 'category_id' => $billingCategory?->id, 'sort_order' => 3],
            ['name' => 'High Billing Issues', 'response_time' => 60, 'resolution_time' => 360, 'priority_id' => $highPriority?->id, 'category_id' => $billingCategory?->id, 'sort_order' => 4],
        ];

        foreach ($slaRules as $rule) {
            SlaRule::firstOrCreate(
                ['name' => $rule['name']], 
                $rule
            );
        }

        // Email Templates
        $templates = [
            // Ticket Notifications
            [
                'name' => 'Ticket Created',
                'slug' => 'ticket-created-confirmation',
                'subject' => 'Your ticket #{{ticket_id}} has been created',
                'type' => 'notification',
                'description' => 'Sent to customer when a new ticket is created',
                'body' => '<p>Dear {{customer_name}},</p><p>Thank you for contacting us. Your ticket <strong>#{{ticket_id}}</strong> has been created successfully.</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Category:</strong> {{ticket_category}}</p><p><strong>Priority:</strong> {{ticket_priority}}</p><p>Our team will review your request and respond as soon as possible.</p><p>You can track your ticket status by logging into your account.</p><p>Best regards,<br>{{company_name}} Support Team</p>',
            ],
            [
                'name' => 'Ticket Status Update',
                'slug' => 'ticket-status-update',
                'subject' => 'Your ticket #{{ticket_id}} status has been updated',
                'type' => 'notification',
                'description' => 'Sent to customer when ticket status changes',
                'body' => '<p>Dear {{customer_name}},</p><p>Your ticket <strong>#{{ticket_id}}</strong> status has been updated.</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Previous Status:</strong> {{old_status}}</p><p><strong>New Status:</strong> {{new_status}}</p><p>Please login to view the latest updates and any responses from our team.</p><p>Best regards,<br>{{company_name}} Support Team</p>',
            ],
            [
                'name' => 'Ticket Resolved',
                'slug' => 'ticket-resolved',
                'subject' => 'Your ticket #{{ticket_id}} has been resolved',
                'type' => 'notification',
                'description' => 'Sent to customer when ticket is marked as resolved',
                'body' => '<p>Dear {{customer_name}},</p><p>Great news! Your ticket <strong>#{{ticket_id}}</strong> has been resolved.</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Resolution:</strong> {{resolution_notes}}</p><p>If you have any further questions or if the issue persists, please feel free to reopen the ticket or create a new one.</p><p>We would appreciate if you could take a moment to rate our support.</p><p>Best regards,<br>{{company_name}} Support Team</p>',
            ],
            [
                'name' => 'Ticket Closed',
                'slug' => 'ticket-closed',
                'subject' => 'Your ticket #{{ticket_id}} has been closed',
                'type' => 'notification',
                'description' => 'Sent to customer when ticket is closed',
                'body' => '<p>Dear {{customer_name}},</p><p>Your ticket <strong>#{{ticket_id}}</strong> has been closed.</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p>Thank you for using our support services. If you need further assistance, please don\'t hesitate to create a new ticket.</p><p>Best regards,<br>{{company_name}} Support Team</p>',
            ],
            [
                'name' => 'Ticket Reply Notification',
                'slug' => 'ticket-reply-notification',
                'subject' => 'New reply on your ticket #{{ticket_id}}',
                'type' => 'notification',
                'description' => 'Sent when ticket receives a reply',
                'body' => '<p>Dear {{customer_name}},</p><p>You have received a new reply on your ticket <strong>#{{ticket_id}}</strong>.</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Reply from:</strong> {{reply_author}}</p><hr><p>{{reply_content}}</p><hr><p>Please login to your account to view the full conversation and respond.</p><p>Best regards,<br>{{company_name}} Support Team</p>',
            ],
            [
                'name' => 'New Reply from Customer',
                'slug' => 'ticket-reply-from-customer',
                'subject' => '[Ticket #{{ticket_id}}] New reply from {{customer_name}}',
                'type' => 'notification',
                'description' => 'Sent to assigned agent when customer replies',
                'body' => '<p>Hello {{agent_name}},</p><p>Customer <strong>{{customer_name}}</strong> has replied to ticket <strong>#{{ticket_id}}</strong>.</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Priority:</strong> {{ticket_priority}}</p><hr><p>{{reply_content}}</p><hr><p>Please login to the helpdesk to respond.</p>',
            ],
            // Auto-Reply Templates
            [
                'name' => 'Auto Reply',
                'slug' => 'auto-reply',
                'subject' => 'We received your message - Ticket #{{ticket_id}}',
                'type' => 'auto-reply',
                'description' => 'Automatic response when ticket is created via email',
                'body' => '<p>Dear {{customer_name}},</p><p>Thank you for your email. This is an automated response to confirm that we have received your message.</p><p>Your ticket number is <strong>#{{ticket_id}}</strong>. Please use this number for any future correspondence.</p><p>Our support team will respond within {{sla_response_time}}.</p><p>In the meantime, you may find answers to common questions in our <a href="{{knowledgebase_url}}">Knowledge Base</a>.</p><p>Best regards,<br>{{company_name}} Support Team</p>',
            ],
            [
                'name' => 'Out of Office Auto Reply',
                'slug' => 'out-of-office-auto-reply',
                'subject' => 'We received your message - Currently Out of Office',
                'type' => 'auto-reply',
                'description' => 'Auto response during non-business hours',
                'body' => '<p>Dear {{customer_name}},</p><p>Thank you for contacting us. Our office is currently closed.</p><p>Your ticket <strong>#{{ticket_id}}</strong> has been created and will be reviewed when we return.</p><p><strong>Business Hours:</strong> {{business_hours}}</p><p>For urgent matters, please call our emergency line: {{emergency_phone}}</p><p>Best regards,<br>{{company_name}} Support Team</p>',
            ],
            // Escalation Templates
            [
                'name' => 'SLA Escalation - First Response',
                'slug' => 'sla-escalation-first-response',
                'subject' => '[URGENT] Ticket #{{ticket_id}} - First Response SLA Breach',
                'type' => 'escalation',
                'description' => 'Sent when first response SLA is breached',
                'body' => '<p><strong style="color: #dc2626;">⚠️ SLA BREACH ALERT</strong></p><p>Ticket <strong>#{{ticket_id}}</strong> has breached its first response SLA and requires immediate attention.</p><p><strong>Customer:</strong> {{customer_name}}</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Priority:</strong> {{ticket_priority}}</p><p><strong>Category:</strong> {{ticket_category}}</p><p><strong>Created:</strong> {{ticket_created_at}}</p><p><strong>Time Elapsed:</strong> {{time_elapsed}}</p><p><strong>SLA Target:</strong> {{sla_response_time}}</p><p>Please take action immediately.</p>',
            ],
            [
                'name' => 'SLA Escalation - Resolution',
                'slug' => 'sla-escalation-resolution',
                'subject' => '[CRITICAL] Ticket #{{ticket_id}} - Resolution SLA Breach',
                'type' => 'escalation',
                'description' => 'Sent when resolution SLA is breached',
                'body' => '<p><strong style="color: #dc2626;">🚨 CRITICAL SLA BREACH</strong></p><p>Ticket <strong>#{{ticket_id}}</strong> has breached its resolution SLA.</p><p><strong>Customer:</strong> {{customer_name}}</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Priority:</strong> {{ticket_priority}}</p><p><strong>Assigned To:</strong> {{assigned_agent}}</p><p><strong>Created:</strong> {{ticket_created_at}}</p><p><strong>Time Elapsed:</strong> {{time_elapsed}}</p><p><strong>SLA Target:</strong> {{sla_resolution_time}}</p><p>This ticket requires immediate escalation and resolution.</p>',
            ],
            [
                'name' => 'SLA Warning - Approaching Deadline',
                'slug' => 'sla-warning-approaching-deadline',
                'subject' => '[WARNING] Ticket #{{ticket_id}} - SLA Deadline Approaching',
                'type' => 'escalation',
                'description' => 'Sent when SLA deadline is approaching',
                'body' => '<p><strong style="color: #f59e0b;">⏰ SLA WARNING</strong></p><p>Ticket <strong>#{{ticket_id}}</strong> is approaching its SLA deadline.</p><p><strong>Customer:</strong> {{customer_name}}</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Priority:</strong> {{ticket_priority}}</p><p><strong>Time Remaining:</strong> {{time_remaining}}</p><p>Please ensure this ticket is addressed before the deadline.</p>',
            ],
            // Reminder Templates
            [
                'name' => 'Ticket Reminder - Awaiting Response',
                'slug' => 'ticket-reminder-awaiting-response',
                'subject' => 'Reminder: Your ticket #{{ticket_id}} is awaiting your response',
                'type' => 'reminder',
                'description' => 'Sent to customer when waiting for their response',
                'body' => '<p>Dear {{customer_name}},</p><p>This is a friendly reminder that your ticket <strong>#{{ticket_id}}</strong> is awaiting your response.</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Last Updated:</strong> {{last_updated}}</p><p>If you have resolved the issue or no longer need assistance, please let us know so we can close the ticket.</p><p>If we don\'t hear from you within {{auto_close_days}} days, the ticket will be automatically closed.</p><p>Best regards,<br>{{company_name}} Support Team</p>',
            ],
            [
                'name' => 'Ticket Auto-Close Warning',
                'slug' => 'ticket-auto-close-warning',
                'subject' => 'Your ticket #{{ticket_id}} will be closed soon',
                'type' => 'reminder',
                'description' => 'Sent before auto-closing inactive ticket',
                'body' => '<p>Dear {{customer_name}},</p><p>Your ticket <strong>#{{ticket_id}}</strong> has been inactive and will be automatically closed in {{days_until_close}} days.</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p>If you still need assistance, please reply to this email or login to update your ticket.</p><p>Best regards,<br>{{company_name}} Support Team</p>',
            ],
            // Welcome Template
            [
                'name' => 'Welcome Email',
                'slug' => 'welcome-email',
                'subject' => 'Welcome to {{company_name}} Support',
                'type' => 'welcome',
                'description' => 'Sent to new users when they register',
                'body' => '<p>Dear {{customer_name}},</p><p>Welcome to {{company_name}} Support!</p><p>Your account has been created successfully. You can now:</p><ul><li>Submit support tickets</li><li>Track your ticket status</li><li>Browse our Knowledge Base</li><li>View your ticket history</li></ul><p><strong>Your Login Details:</strong></p><p>Email: {{customer_email}}</p><p>Login URL: {{login_url}}</p><p>If you have any questions, feel free to create a support ticket.</p><p>Best regards,<br>{{company_name}} Support Team</p>',
            ],
            // Password Reset Template
            [
                'name' => 'Password Reset',
                'slug' => 'password-reset',
                'subject' => 'Reset Your Password - {{company_name}}',
                'type' => 'notification',
                'description' => 'Sent to users when they request a password reset',
                'body' => '<p>Dear {{customer_name}},</p><p>You are receiving this email because we received a password reset request for your account.</p><p style="margin: 30px 0;"><a href="{{reset_url}}" style="background-color: #3b82f6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;">Reset Password</a></p><p>This password reset link will expire in {{expiry_time}}.</p><p>If you did not request a password reset, no further action is required. Your password will remain unchanged.</p><p><strong>For security reasons:</strong></p><ul><li>Never share your password with anyone</li><li>Use a strong, unique password</li><li>Enable two-factor authentication if available</li></ul><p>If you\'re having trouble clicking the button, copy and paste the URL below into your web browser:</p><p style="color: #6b7280; word-break: break-all;">{{reset_url}}</p><p>Best regards,<br>{{company_name}} Support Team</p>',
            ],
            // Agent Assignment
            [
                'name' => 'Ticket Assigned to Agent',
                'slug' => 'ticket-assigned-to-agent',
                'subject' => 'New ticket assigned: #{{ticket_id}}',
                'type' => 'notification',
                'description' => 'Sent to agent when ticket is assigned to them',
                'body' => '<p>Hello {{agent_name}},</p><p>A new ticket has been assigned to you.</p><p><strong>Ticket ID:</strong> #{{ticket_id}}</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Customer:</strong> {{customer_name}}</p><p><strong>Priority:</strong> {{ticket_priority}}</p><p><strong>Category:</strong> {{ticket_category}}</p><p><strong>SLA Response Time:</strong> {{sla_response_time}}</p><hr><p>{{ticket_content}}</p><hr><p>Please login to the helpdesk to respond.</p>',
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::firstOrCreate(['name' => $template['name']], $template);
        }
    }
}
