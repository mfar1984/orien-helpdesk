<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Permission Action Labels
    |--------------------------------------------------------------------------
    |
    | Define labels for permission actions (columns in matrix)
    |
    */

    'labels' => [
        'view' => 'View',
        'create' => 'Create',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'export' => 'Export',
        'manage' => 'Manage',
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Matrix
    |--------------------------------------------------------------------------
    |
    | Define which actions are available for each module
    | true = action is available, false = not available
    |
    */

    'matrix' => [
        // Dashboard / Overview
        'dashboard' => [
            'view' => true,
            'create' => false,
            'edit' => false,
            'delete' => false,
            'export' => false,
            'manage' => false,
        ],
        
        // Tickets
        'tickets' => [
            'view' => true,
            'create' => true,
            'edit' => true,
            'delete' => true,
            'export' => false, // Export is in Reports page
            'manage' => true, // Assign, Status change
        ],
        
        // Knowledge Base > View
        'knowledgebase_view' => [
            'view' => true,
            'create' => false,
            'edit' => false,
            'delete' => false,
            'export' => false,
            'manage' => false,
        ],

        // Knowledge Base > Settings > Articles
        'knowledgebase_articles' => [
            'view' => true,
            'create' => true,
            'edit' => true,
            'delete' => true,
            'export' => false,
            'manage' => false,
        ],

        // Knowledge Base > Settings > Categories
        'knowledgebase_categories' => [
            'view' => true,
            'create' => true,
            'edit' => true,
            'delete' => true,
            'export' => false,
            'manage' => false,
        ],
        
        // Reports
        'reports' => [
            'view' => true,
            'create' => false,
            'edit' => false,
            'delete' => false,
            'export' => true,
            'manage' => false,
        ],

        // Tools > Ban Emails
        'tools_ban_emails' => [
            'view' => true,
            'create' => true,
            'edit' => true,
            'delete' => true,
            'export' => true,
            'manage' => false,
        ],

        // Tools > Ban IPs
        'tools_ban_ips' => [
            'view' => true,
            'create' => true,
            'edit' => true,
            'delete' => true,
            'export' => true,
            'manage' => false,
        ],

        // Tools > Whitelist IPs
        'tools_whitelist_ips' => [
            'view' => true,
            'create' => true,
            'edit' => true,
            'delete' => true,
            'export' => true,
            'manage' => false,
        ],

        // Tools > Whitelist Emails
        'tools_whitelist_emails' => [
            'view' => true,
            'create' => true,
            'edit' => true,
            'delete' => true,
            'export' => true,
            'manage' => false,
        ],

        // Tools > Bad Words
        'tools_bad_words' => [
            'view' => true,
            'create' => true,
            'edit' => true,
            'delete' => true,
            'export' => true,
            'manage' => false,
        ],

        // Tools > Bad Websites
        'tools_bad_websites' => [
            'view' => true,
            'create' => true,
            'edit' => true,
            'delete' => true,
            'export' => true,
            'manage' => false,
        ],
        
        // Platform Settings > General Config
        'settings_general' => [
            'view' => true,
            'create' => false,
            'edit' => true,
            'delete' => false,
            'export' => false,
            'manage' => false,
        ],

        // Platform Settings > Integrations > Email Gateway
        'settings_integrations_email' => [
            'view' => true,
            'create' => false,
            'edit' => true,
            'delete' => false,
            'export' => false,
            'manage' => false,
        ],
        
        // Platform Settings > Integrations > Telegram Gateway
        'settings_integrations_telegram' => [
            'view' => true,
            'create' => false,
            'edit' => true,
            'delete' => false,
            'export' => false,
            'manage' => false,
        ],
        
        // Platform Settings > Integrations > Weather
        'settings_integrations_weather' => [
            'view' => true,
            'create' => false,
            'edit' => true,
            'delete' => false,
            'export' => false,
            'manage' => false,
        ],
        
        // Platform Settings > Integrations > API
        'settings_integrations_api' => [
            'view' => true,
            'create' => false,
            'edit' => true,
            'delete' => false,
            'export' => false,
            'manage' => false,
        ],
        
        // Platform Settings > Integrations > Spam & Security
        'settings_integrations_spam' => [
            'view' => true,
            'create' => false,
            'edit' => true,
            'delete' => false,
            'export' => false,
            'manage' => false,
        ],

        // Platform Settings > Integrations > Recycle Bin
        'settings_integrations_recycle' => [
            'view' => true,
            'create' => false,
            'edit' => false,
            'delete' => true,
            'export' => false,
            'manage' => true, // Restore
        ],

        // Platform Settings > Categories > Ticket Categories
        'settings_ticket_categories' => [
            'view' => true,
            'create' => true,
            'edit' => true,
            'delete' => true,
            'export' => false,
            'manage' => false,
        ],

        // Platform Settings > Categories > Priorities
        'settings_priorities' => [
            'view' => true,
            'create' => true,
            'edit' => true,
            'delete' => true,
            'export' => false,
            'manage' => false,
        ],

        // Platform Settings > Categories > Status
        'settings_status' => [
            'view' => true,
            'create' => true,
            'edit' => true,
            'delete' => true,
            'export' => false,
            'manage' => false,
        ],

        // Platform Settings > Categories > SLA
        'settings_sla' => [
            'view' => true,
            'create' => true,
            'edit' => true,
            'delete' => true,
            'export' => false,
            'manage' => false,
        ],

        // Platform Settings > Categories > Email Templates
        'settings_email_templates' => [
            'view' => true,
            'create' => true,
            'edit' => true,
            'delete' => true,
            'export' => false,
            'manage' => false,
        ],
        
        // Platform Settings > Roles
        'settings_roles' => [
            'view' => true,
            'create' => true,
            'edit' => true,
            'delete' => true,
            'export' => false,
            'manage' => false,
        ],

        // Platform Settings > Users > Administrators
        'settings_users_admin' => [
            'view' => true,
            'create' => true,
            'edit' => true,
            'delete' => true,
            'export' => false,
            'manage' => true, // Lock, Suspend
        ],

        // Platform Settings > Users > Agents
        'settings_users_agents' => [
            'view' => true,
            'create' => true,
            'edit' => true,
            'delete' => true,
            'export' => false,
            'manage' => true, // Lock, Suspend
        ],

        // Platform Settings > Users > Customers
        'settings_users_customers' => [
            'view' => true,
            'create' => true,
            'edit' => true,
            'delete' => true,
            'export' => false,
            'manage' => true, // Lock, Suspend
        ],
        
        // Platform Settings > Activity Logs
        'settings_activity_logs' => [
            'view' => true,
            'create' => false,
            'edit' => false,
            'delete' => true,
            'export' => true,
            'manage' => false,
        ],

        // Platform Settings > Audit Logs
        'settings_audit_logs' => [
            'view' => true,
            'create' => false,
            'edit' => false,
            'delete' => true,
            'export' => true,
            'manage' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Labels
    |--------------------------------------------------------------------------
    |
    | Human-readable names for modules (rows in matrix)
    |
    */

    'modules' => [
        'dashboard' => 'Overview',
        'tickets' => 'Tickets',
        'knowledgebase_view' => 'Knowledgebase › View',
        'knowledgebase_articles' => 'Knowledgebase › Settings › Articles',
        'knowledgebase_categories' => 'Knowledgebase › Settings › Categories',
        'reports' => 'Reports',
        'tools_ban_emails' => 'Tools › Ban Emails',
        'tools_ban_ips' => 'Tools › Ban IPs',
        'tools_whitelist_ips' => 'Tools › Whitelist IPs',
        'tools_whitelist_emails' => 'Tools › Whitelist Emails',
        'tools_bad_words' => 'Tools › Bad Words',
        'tools_bad_websites' => 'Tools › Bad Websites',
        'settings_general' => 'Platform Settings › General Config',
        'settings_integrations_email' => 'Platform Settings › Integrations › Email Gateway',
        'settings_integrations_telegram' => 'Platform Settings › Integrations › Telegram Gateway',
        'settings_integrations_weather' => 'Platform Settings › Integrations › Weather',
        'settings_integrations_api' => 'Platform Settings › Integrations › API',
        'settings_integrations_spam' => 'Platform Settings › Integrations › Spam & Security',
        'settings_integrations_recycle' => 'Platform Settings › Integrations › Recycle Bin',
        'settings_ticket_categories' => 'Platform Settings › Categories › Ticket Categories',
        'settings_priorities' => 'Platform Settings › Categories › Priorities',
        'settings_status' => 'Platform Settings › Categories › Status',
        'settings_sla' => 'Platform Settings › Categories › SLA',
        'settings_email_templates' => 'Platform Settings › Categories › Email Templates',
        'settings_roles' => 'Platform Settings › Roles',
        'settings_users_admin' => 'Platform Settings › Users › Administrators',
        'settings_users_agents' => 'Platform Settings › Users › Agents',
        'settings_users_customers' => 'Platform Settings › Users › Customers',
        'settings_activity_logs' => 'Platform Settings › Activity Logs',
        'settings_audit_logs' => 'Platform Settings › Audit Logs',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Groups (for visual grouping in matrix)
    |--------------------------------------------------------------------------
    */

    'groups' => [
        'Main' => [
            'dashboard',
            'tickets',
        ],
        'Knowledgebase' => [
            'knowledgebase_view',
            'knowledgebase_articles',
            'knowledgebase_categories',
        ],
        'Reports' => [
            'reports',
        ],
        'Tools' => [
            'tools_ban_emails',
            'tools_ban_ips',
            'tools_whitelist_ips',
            'tools_whitelist_emails',
            'tools_bad_words',
            'tools_bad_websites',
        ],
        'Platform Settings' => [
            'settings_general',
            'settings_integrations_email',
            'settings_integrations_telegram',
            'settings_integrations_weather',
            'settings_integrations_api',
            'settings_integrations_spam',
            'settings_integrations_recycle',
            'settings_ticket_categories',
            'settings_priorities',
            'settings_status',
            'settings_sla',
            'settings_email_templates',
            'settings_roles',
            'settings_users_admin',
            'settings_users_agents',
            'settings_users_customers',
            'settings_activity_logs',
            'settings_audit_logs',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Role Presets
    |--------------------------------------------------------------------------
    |
    | Default permission sets for each role.
    |
    */

    'role_presets' => [
        'administrator' => [
            // Full Access - All permissions
            'overview',
            'tickets_view',
            'tickets_create',
            'tickets_edit',
            'tickets_delete',
            'tickets_assign',
            'tickets_status',
            'knowledgebase_view',
            'knowledgebase_manage',
            'reports_view',
            'reports_export',
            'tools_manage',
            'settings_general',
            'settings_integrations',
            'settings_categories',
            'settings_roles',
            'settings_users',
            'settings_activity_logs',
        ],

        'agent' => [
            // Agent Access
            'overview',
            'tickets_view',
            'tickets_edit',
            'tickets_status',
            'knowledgebase_view',
            'reports_view',
        ],

        'customer' => [
            // Customer Access
            'overview',
            'tickets_view',
            'tickets_create',
            'knowledgebase_view',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Permissions
    |--------------------------------------------------------------------------
    |
    | Map menu items to required permissions.
    |
    */

    'menu_permissions' => [
        'overview' => ['overview'],
        'tickets' => ['tickets_view'],
        'knowledgebase' => ['knowledgebase_view'],
        'reports' => ['reports_view'],
        'tools' => ['tools_manage'],
        'settings' => ['settings_general', 'settings_integrations', 'settings_categories', 'settings_roles', 'settings_users', 'settings_activity_logs'],
    ],
];
