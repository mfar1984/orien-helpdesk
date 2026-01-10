<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'permissions',
        'status',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($role) {
            if (empty($role->slug)) {
                $role->slug = Str::slug($role->name);
            }
        });
    }

    /**
     * Get users with this role.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if role has a specific permission.
     * 
     * Supports both formats:
     * - New format: 'module.action' (e.g., 'tickets.view', 'knowledgebase_articles.create')
     * - Old format: 'module_action' (e.g., 'tickets_view', 'knowledgebase_view')
     */
    public function hasPermission(string $permission): bool
    {
        $permissions = $this->permissions ?? [];
        
        // Direct match (exact permission key)
        if (in_array($permission, $permissions)) {
            return true;
        }
        
        // Map old permission keys to new matrix format for backward compatibility
        $permissionMap = [
            // Dashboard
            'overview' => ['dashboard.view'],
            
            // Tickets
            'tickets_view' => ['tickets.view'],
            'tickets_create' => ['tickets.create'],
            'tickets_edit' => ['tickets.edit'],
            'tickets_delete' => ['tickets.delete'],
            'tickets_assign' => ['tickets.manage'],
            'tickets_status' => ['tickets.manage'],
            
            // Knowledgebase - Old format
            'knowledgebase_view' => ['knowledgebase_view.view'],
            'knowledgebase_manage' => [
                'knowledgebase_articles.view', 'knowledgebase_articles.create', 
                'knowledgebase_articles.edit', 'knowledgebase_articles.delete',
                'knowledgebase_categories.view', 'knowledgebase_categories.create',
                'knowledgebase_categories.edit', 'knowledgebase_categories.delete'
            ],
            
            // Reports
            'reports_view' => ['reports.view'],
            'reports_export' => ['reports.export'],
            
            // Tools - Old format
            'tools_manage' => [
                'tools_ban_emails.view', 'tools_ban_emails.create', 'tools_ban_emails.edit', 'tools_ban_emails.delete',
                'tools_ban_ips.view', 'tools_ban_ips.create', 'tools_ban_ips.edit', 'tools_ban_ips.delete',
                'tools_bad_words.view', 'tools_bad_words.create', 'tools_bad_words.edit', 'tools_bad_words.delete',
                'tools_bad_websites.view', 'tools_bad_websites.create', 'tools_bad_websites.edit', 'tools_bad_websites.delete',
            ],
            
            // Settings - Old format
            'settings_general' => ['settings_general.view', 'settings_general.edit'],
            'settings_integrations' => [
                'settings_integrations_email.view', 'settings_integrations_email.edit',
                'settings_integrations_spam.view', 'settings_integrations_spam.edit',
                'settings_integrations_recycle.view', 'settings_integrations_recycle.delete', 'settings_integrations_recycle.manage',
            ],
            'settings_categories' => [
                'settings_ticket_categories.view', 'settings_ticket_categories.create', 'settings_ticket_categories.edit', 'settings_ticket_categories.delete',
                'settings_priorities.view', 'settings_priorities.create', 'settings_priorities.edit', 'settings_priorities.delete',
                'settings_status.view', 'settings_status.create', 'settings_status.edit', 'settings_status.delete',
                'settings_sla.view', 'settings_sla.create', 'settings_sla.edit', 'settings_sla.delete',
                'settings_email_templates.view', 'settings_email_templates.create', 'settings_email_templates.edit', 'settings_email_templates.delete',
            ],
            'settings_roles' => ['settings_roles.view', 'settings_roles.create', 'settings_roles.edit', 'settings_roles.delete'],
            'settings_users' => [
                'settings_users_admin.view', 'settings_users_admin.create', 'settings_users_admin.edit', 'settings_users_admin.delete', 'settings_users_admin.manage',
                'settings_users_agents.view', 'settings_users_agents.create', 'settings_users_agents.edit', 'settings_users_agents.delete', 'settings_users_agents.manage',
                'settings_users_customers.view', 'settings_users_customers.create', 'settings_users_customers.edit', 'settings_users_customers.delete', 'settings_users_customers.manage',
            ],
            'settings_activity_logs' => ['settings_activity_logs.view', 'settings_activity_logs.delete', 'settings_activity_logs.export'],
        ];
        
        // Check if old format permission maps to any new format permissions
        if (isset($permissionMap[$permission])) {
            foreach ($permissionMap[$permission] as $newPermission) {
                if (in_array($newPermission, $permissions)) {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Check if role has ANY permission for a module (for sidebar visibility).
     */
    public function hasAnyPermissionFor(string $module): bool
    {
        $permissions = $this->permissions ?? [];
        
        foreach ($permissions as $permission) {
            if (str_starts_with($permission, $module . '.')) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if role has ANY permission matching a pattern (for sidebar groups).
     */
    public function hasAnyPermissionMatching(array $patterns): bool
    {
        $permissions = $this->permissions ?? [];
        
        foreach ($permissions as $permission) {
            foreach ($patterns as $pattern) {
                if (str_starts_with($permission, $pattern)) {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Get users count.
     */
    public function getUsersCountAttribute(): int
    {
        return $this->users()->count();
    }
}
