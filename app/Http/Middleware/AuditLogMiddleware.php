<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditLogMiddleware
{
    /**
     * Routes/patterns to track for audit logging (GET requests - page views).
     */
    protected array $viewRoutes = [
        // Dashboard
        'dashboard' => 'dashboard',
        'dashboard.index' => 'dashboard',
        
        // Tickets
        'tickets.index' => 'tickets',
        'tickets.show' => 'tickets',
        'tickets.create' => 'tickets',
        'tickets.edit' => 'tickets',
        
        // Knowledgebase
        'knowledgebase.index' => 'knowledgebase',
        'knowledgebase.article' => 'knowledgebase',
        'knowledgebase.settings' => 'knowledgebase',
        'knowledgebase.articles.edit' => 'knowledgebase',
        
        // Reports
        'reports.index' => 'reports',
        
        // Tools
        'tools.index' => 'tools',
        
        // Settings
        'settings.general' => 'settings',
        'settings.integrations' => 'integrations',
        'settings.categories' => 'categories',
        'settings.roles' => 'roles',
        'settings.roles.show' => 'roles',
        'settings.roles.edit' => 'roles',
        'settings.roles.create' => 'roles',
        'settings.users' => 'users',
        'settings.users.edit' => 'users',
        'settings.users.create' => 'users',
        'settings.activity-logs' => 'activity_logs',
        'settings.categories.email-template.show' => 'categories',
        'settings.categories.email-template.edit' => 'categories',
        
        // User Profile
        'users.show' => 'users',
        
        // Two-Factor
        'two-factor.show' => 'two_factor',
    ];

    /**
     * Routes/patterns to track for audit logging (POST/PUT/DELETE requests - actions).
     */
    protected array $actionRoutes = [
        // Tickets
        'tickets.store' => ['module' => 'tickets', 'action' => 'create'],
        'tickets.update' => ['module' => 'tickets', 'action' => 'update'],
        'tickets.destroy' => ['module' => 'tickets', 'action' => 'delete'],
        'tickets.reply' => ['module' => 'tickets', 'action' => 'reply'],
        'tickets.assign' => ['module' => 'tickets', 'action' => 'assign'],
        'tickets.updateStatus' => ['module' => 'tickets', 'action' => 'status_change'],
        'tickets.updatePriority' => ['module' => 'tickets', 'action' => 'priority_change'],
        'tickets.restore' => ['module' => 'tickets', 'action' => 'restore'],
        'tickets.forceDelete' => ['module' => 'tickets', 'action' => 'force_delete'],
        
        // Knowledgebase
        'knowledgebase.articles.store' => ['module' => 'knowledgebase', 'action' => 'create'],
        'knowledgebase.articles.update' => ['module' => 'knowledgebase', 'action' => 'update'],
        'knowledgebase.articles.destroy' => ['module' => 'knowledgebase', 'action' => 'delete'],
        'knowledgebase.categories.store' => ['module' => 'knowledgebase', 'action' => 'create'],
        'knowledgebase.categories.update' => ['module' => 'knowledgebase', 'action' => 'update'],
        'knowledgebase.categories.destroy' => ['module' => 'knowledgebase', 'action' => 'delete'],
        
        // Reports
        'reports.export' => ['module' => 'reports', 'action' => 'export'],
        
        // Tools
        'tools.ban-email.store' => ['module' => 'tools', 'action' => 'create'],
        'tools.ban-email.update' => ['module' => 'tools', 'action' => 'update'],
        'tools.ban-email.destroy' => ['module' => 'tools', 'action' => 'delete'],
        'tools.ban-ip.store' => ['module' => 'tools', 'action' => 'create'],
        'tools.ban-ip.update' => ['module' => 'tools', 'action' => 'update'],
        'tools.ban-ip.destroy' => ['module' => 'tools', 'action' => 'delete'],
        'tools.whitelist-email.store' => ['module' => 'tools', 'action' => 'create'],
        'tools.whitelist-email.update' => ['module' => 'tools', 'action' => 'update'],
        'tools.whitelist-email.destroy' => ['module' => 'tools', 'action' => 'delete'],
        'tools.whitelist-ip.store' => ['module' => 'tools', 'action' => 'create'],
        'tools.whitelist-ip.update' => ['module' => 'tools', 'action' => 'update'],
        'tools.whitelist-ip.destroy' => ['module' => 'tools', 'action' => 'delete'],
        'tools.bad-word.store' => ['module' => 'tools', 'action' => 'create'],
        'tools.bad-word.update' => ['module' => 'tools', 'action' => 'update'],
        'tools.bad-word.destroy' => ['module' => 'tools', 'action' => 'delete'],
        'tools.bad-website.store' => ['module' => 'tools', 'action' => 'create'],
        'tools.bad-website.update' => ['module' => 'tools', 'action' => 'update'],
        'tools.bad-website.destroy' => ['module' => 'tools', 'action' => 'delete'],
        'tools.export' => ['module' => 'tools', 'action' => 'export'],
        
        // Settings - General
        'settings.general.save' => ['module' => 'settings', 'action' => 'update'],
        
        // Settings - Integrations
        'settings.integrations.email.save' => ['module' => 'integrations', 'action' => 'update'],
        'settings.integrations.weather.save' => ['module' => 'integrations', 'action' => 'update'],
        'settings.integrations.api.save' => ['module' => 'integrations', 'action' => 'update'],
        'settings.integrations.spam.save' => ['module' => 'integrations', 'action' => 'update'],
        'settings.integrations.email.test' => ['module' => 'integrations', 'action' => 'test'],
        'settings.integrations.weather.test' => ['module' => 'integrations', 'action' => 'test'],
        'settings.integrations.spam.test' => ['module' => 'integrations', 'action' => 'test'],
        
        // Settings - Categories
        'settings.categories.category.store' => ['module' => 'categories', 'action' => 'create'],
        'settings.categories.category.update' => ['module' => 'categories', 'action' => 'update'],
        'settings.categories.category.destroy' => ['module' => 'categories', 'action' => 'delete'],
        'settings.categories.priority.store' => ['module' => 'categories', 'action' => 'create'],
        'settings.categories.priority.update' => ['module' => 'categories', 'action' => 'update'],
        'settings.categories.priority.destroy' => ['module' => 'categories', 'action' => 'delete'],
        'settings.categories.status.store' => ['module' => 'categories', 'action' => 'create'],
        'settings.categories.status.update' => ['module' => 'categories', 'action' => 'update'],
        'settings.categories.status.destroy' => ['module' => 'categories', 'action' => 'delete'],
        'settings.categories.sla-rule.store' => ['module' => 'categories', 'action' => 'create'],
        'settings.categories.sla-rule.update' => ['module' => 'categories', 'action' => 'update'],
        'settings.categories.sla-rule.destroy' => ['module' => 'categories', 'action' => 'delete'],
        'settings.categories.email-template.update' => ['module' => 'categories', 'action' => 'update'],
        
        // Settings - Roles
        'settings.roles.store' => ['module' => 'roles', 'action' => 'create'],
        'settings.roles.update' => ['module' => 'roles', 'action' => 'update'],
        'settings.roles.destroy' => ['module' => 'roles', 'action' => 'delete'],
        
        // Settings - Users
        'settings.users.store' => ['module' => 'users', 'action' => 'create'],
        'settings.users.update' => ['module' => 'users', 'action' => 'update'],
        'settings.users.destroy' => ['module' => 'users', 'action' => 'delete'],
        'settings.users.lock' => ['module' => 'users', 'action' => 'lock'],
        'settings.users.unlock' => ['module' => 'users', 'action' => 'unlock'],
        'settings.users.suspend' => ['module' => 'users', 'action' => 'suspend'],
        'settings.users.unsuspend' => ['module' => 'users', 'action' => 'unsuspend'],
        
        // Settings - Activity Logs
        'settings.activity-logs.destroy' => ['module' => 'activity_logs', 'action' => 'delete'],
        'settings.activity-logs.clear' => ['module' => 'activity_logs', 'action' => 'clear'],
        'settings.activity-logs.export' => ['module' => 'activity_logs', 'action' => 'export'],
        
        // Recycle Bin
        'recycle-bin.restore' => ['module' => 'recycle_bin', 'action' => 'restore'],
        'recycle-bin.force-delete' => ['module' => 'recycle_bin', 'action' => 'force_delete'],
        'recycle-bin.empty-all' => ['module' => 'recycle_bin', 'action' => 'empty'],
        
        // Two-Factor
        'two-factor.enable' => ['module' => 'two_factor', 'action' => 'enable'],
        'two-factor.disable' => ['module' => 'two_factor', 'action' => 'disable'],
        'two-factor.regenerate-codes' => ['module' => 'two_factor', 'action' => 'regenerate'],
        
        // Auth
        'logout' => ['module' => 'auth', 'action' => 'logout'],
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        $response = $next($request);
        
        // Log request if user is authenticated
        if (auth()->check()) {
            $this->logRequest($request, $response, $startTime);
        }
        
        return $response;
    }

    /**
     * Log the request to audit log.
     */
    protected function logRequest(Request $request, Response $response, float $startTime): void
    {
        $routeName = $request->route()?->getName();
        
        if (!$routeName) {
            return;
        }

        $user = auth()->user();
        $responseTime = (int) ((microtime(true) - $startTime) * 1000);
        $method = $request->method();

        // Determine action and module based on request type
        $action = 'view';
        $module = null;

        if ($method === 'GET' && isset($this->viewRoutes[$routeName])) {
            $action = 'view';
            $module = $this->viewRoutes[$routeName];
        } elseif (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE']) && isset($this->actionRoutes[$routeName])) {
            $action = $this->actionRoutes[$routeName]['action'];
            $module = $this->actionRoutes[$routeName]['module'];
        } else {
            // Route not tracked
            return;
        }

        // Extract subject info from route parameters
        $subjectType = null;
        $subjectId = null;
        $subjectName = null;

        $routeParams = $request->route()->parameters();
        
        if (!empty($routeParams)) {
            $firstParam = reset($routeParams);
            
            if (is_object($firstParam) && method_exists($firstParam, 'getKey')) {
                $subjectType = get_class($firstParam);
                $subjectId = $firstParam->getKey();
                $subjectName = $firstParam->name ?? $firstParam->title ?? $firstParam->subject ?? $firstParam->email ?? $firstParam->ticket_number ?? "#{$subjectId}";
            } elseif (is_numeric($firstParam)) {
                $subjectId = (int) $firstParam;
            }
        }

        // Get request data (exclude sensitive fields)
        $requestData = $request->except(['_token', '_method', 'password', 'password_confirmation', 'current_password', 'secret', 'recovery_code']);
        
        try {
            AuditLog::create([
                'user_id' => $user->id,
                'user_type' => $user->user_type,
                'action' => $action,
                'module' => $module,
                'route_name' => $routeName,
                'url' => $request->fullUrl(),
                'method' => $method,
                'subject_type' => $subjectType,
                'subject_id' => $subjectId,
                'subject_name' => $subjectName,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'request_data' => !empty($requestData) ? $requestData : null,
                'response_code' => $response->getStatusCode(),
                'response_time_ms' => $responseTime,
                'created_at' => now(),
            ]);

            // Run cleanup occasionally (1% chance per request)
            if (rand(1, 100) === 1) {
                AuditLog::cleanup();
            }
        } catch (\Exception $e) {
            // Silently fail - don't break the application
            \Log::error('Audit log failed: ' . $e->getMessage());
        }
    }
}
