@php
    $user = auth()->user();
    $brandSettings = \App\Models\Setting::whereIn('key', ['logo', 'favicon', 'company_short_name', 'system_name'])
        ->pluck('value', 'key');
    $sidebarLogo = $brandSettings['logo'] ?? null;
    $sidebarFavicon = $brandSettings['favicon'] ?? null;
    $companyShortName = $brandSettings['company_short_name'] ?? 'ORIEN';
    
    // Check permissions based on new matrix structure
    $canViewDashboard = $user->hasPermission('dashboard.view') || $user->hasPermission('overview');
    $canViewTickets = $user->hasPermission('tickets.view') || $user->hasPermission('tickets_view');
    $canViewKnowledgebase = $user->hasPermission('knowledgebase_view.view') || $user->hasPermission('knowledgebase_view');
    $canManageKnowledgebase = $user->hasAnyPermissionMatching(['knowledgebase_articles.', 'knowledgebase_categories.']) || $user->hasPermission('knowledgebase_manage');
    $canViewReports = $user->hasPermission('reports.view') || $user->hasPermission('reports_view');
    $canViewTools = $user->hasAnyPermissionMatching(['tools_ban_emails.', 'tools_ban_ips.', 'tools_bad_words.', 'tools_bad_websites.']) || $user->hasPermission('tools_manage');
    
    // Platform Settings permissions
    $canViewGeneralConfig = $user->hasPermission('settings_general.view') || $user->hasPermission('settings_general');
    $canViewIntegrations = $user->hasAnyPermissionMatching(['settings_integrations_email.', 'settings_integrations_spam.', 'settings_integrations_recycle.']) || $user->hasPermission('settings_integrations');
    $canViewCategories = $user->hasAnyPermissionMatching(['settings_ticket_categories.', 'settings_priorities.', 'settings_status.', 'settings_sla.', 'settings_email_templates.']) || $user->hasPermission('settings_categories');
    $canViewRoles = $user->hasPermission('settings_roles.view') || $user->hasPermission('settings_roles');
    $canViewUsers = $user->hasAnyPermissionMatching(['settings_users_admin.', 'settings_users_agents.', 'settings_users_customers.']) || $user->hasPermission('settings_users');
    $canViewActivityLogs = $user->hasPermission('settings_activity_logs.view') || $user->hasPermission('settings_activity_logs');
    
    $canViewAnySettings = $canViewGeneralConfig || $canViewIntegrations || $canViewCategories || $canViewRoles || $canViewUsers || $canViewActivityLogs;
    
    $menuItems = [
        // Main Section
        [
            'label' => 'Overview',
            'icon' => 'dashboard',
            'route' => 'dashboard',
            'active' => 'dashboard*',
            'visible' => $canViewDashboard,
        ],
        [
            'label' => 'Tickets',
            'icon' => 'confirmation_number',
            'route' => 'tickets.index',
            'active' => 'tickets*',
            'visible' => $canViewTickets,
        ],
        [
            'label' => 'Knowledgebase',
            'icon' => 'menu_book',
            'route' => null,
            'active' => 'knowledgebase*',
            'visible' => $canViewKnowledgebase || $canManageKnowledgebase,
            'children' => [
                [
                    'label' => 'View',
                    'route' => 'knowledgebase.index',
                    'active' => 'knowledgebase.index',
                    'visible' => $canViewKnowledgebase,
                ],
                [
                    'label' => 'Settings',
                    'route' => 'knowledgebase.settings',
                    'active' => 'knowledgebase.settings*',
                    'visible' => $canManageKnowledgebase,
                ],
            ],
        ],
        [
            'label' => 'Reports',
            'icon' => 'bar_chart',
            'route' => 'reports.index',
            'active' => 'reports*',
            'visible' => $canViewReports,
        ],
        
        // Divider
        ['type' => 'divider', 'visible' => $canViewTools],
        
        // Tools Section
        [
            'label' => 'Tools',
            'icon' => 'build',
            'route' => 'tools.index',
            'active' => 'tools*',
            'visible' => $canViewTools,
        ],
        
        // Divider
        ['type' => 'divider', 'visible' => $canViewAnySettings],
        
        // Platform Settings Section
        [
            'label' => 'Platform Settings',
            'icon' => 'settings',
            'route' => null,
            'active' => 'settings*',
            'visible' => $canViewAnySettings,
            'children' => [
                [
                    'label' => 'General Config',
                    'route' => 'settings.general',
                    'active' => 'settings.general*',
                    'visible' => $canViewGeneralConfig,
                ],
                [
                    'label' => 'Integrations',
                    'route' => 'settings.integrations',
                    'active' => 'settings.integrations*',
                    'visible' => $canViewIntegrations,
                ],
                [
                    'label' => 'Categories',
                    'route' => 'settings.categories',
                    'active' => 'settings.categories*',
                    'visible' => $canViewCategories,
                ],
                [
                    'label' => 'Roles',
                    'route' => 'settings.roles',
                    'active' => 'settings.roles*',
                    'visible' => $canViewRoles,
                ],
                [
                    'label' => 'Users',
                    'route' => 'settings.users',
                    'active' => 'settings.users*',
                    'visible' => $canViewUsers,
                ],
                [
                    'label' => 'Activity Logs',
                    'route' => 'settings.activity-logs',
                    'active' => 'settings.activity-logs*',
                    'visible' => $canViewActivityLogs,
                ],
            ],
        ],
    ];
@endphp

<div class="sidebar sidebar-expanded" 
     x-data="{ collapsed: false, openMenus: {} }"
     :class="{ 'sidebar-collapsed': collapsed, 'sidebar-expanded': !collapsed, 'sidebar-mobile-open': $store.mobileMenu?.open }"
     @toggle-sidebar.window="collapsed = !collapsed">

    <!-- Sidebar Header -->
    <div class="sidebar-header" style="position: relative;">
        <div class="flex items-center justify-center relative">
            <div x-show="!collapsed" class="flex items-center space-x-3">
                @if($sidebarLogo)
                    <img src="{{ asset('storage/' . $sidebarLogo) }}" alt="{{ $companyShortName }}" style="height: 40px; object-fit: contain;">
                @else
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-white" style="font-size: 24px;">deployed_code</span>
                    </div>
                    <span class="text-lg font-bold text-gray-800">{{ $companyShortName }}</span>
                @endif
            </div>
            <div x-show="collapsed">
                @if($sidebarFavicon)
                    <img src="{{ asset('storage/' . $sidebarFavicon) }}" alt="{{ $companyShortName }}" class="w-10 h-10 object-contain">
                @elseif($sidebarLogo)
                    <img src="{{ asset('storage/' . $sidebarLogo) }}" alt="{{ $companyShortName }}" class="w-10 h-10 object-contain">
                @else
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-white" style="font-size: 24px;">deployed_code</span>
                    </div>
                @endif
            </div>
            <button @click="collapsed = !collapsed; $dispatch('sidebar-toggled', { collapsed: collapsed })"
                    class="sidebar-toggle absolute right-0 top-1/2 -translate-y-1/2" x-show="!collapsed" type="button">
                <span class="material-icons-outlined" style="font-size: 20px;">widgets</span>
            </button>
            <button @click="collapsed = !collapsed; $dispatch('sidebar-toggled', { collapsed: collapsed })"
                    class="sidebar-toggle" x-show="collapsed" type="button">
                <span class="material-symbols-outlined" style="font-size: 20px;">arrow_circle_right</span>
            </button>
        </div>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="sidebar-nav mt-4 overflow-y-auto" style="max-height: calc(100vh - 100px);">
        <div class="space-y-1">
            @foreach($menuItems as $item)
                @if(isset($item['type']) && $item['type'] === 'divider')
                    @if($item['visible'] ?? true)
                    <div class="sidebar-separator"><hr class="sidebar-separator-line"></div>
                    @endif
                @elseif(isset($item['children']))
                    @php
                        $isAnyChildActive = false;
                        $visibleChildren = [];
                        
                        // Filter children based on visibility
                        foreach ($item['children'] as $child) {
                            if ($child['visible'] ?? true) {
                                $visibleChildren[] = $child;
                                if (request()->routeIs($child['active'] ?? '')) {
                                    $isAnyChildActive = true;
                                }
                            }
                        }
                        $shouldExpand = $isAnyChildActive;
                    @endphp
                    @if(($item['visible'] ?? true) && count($visibleChildren) > 0)
                    <div x-data="{ open: {{ $shouldExpand ? 'true' : 'false' }}, hovered: false }"
                         class="relative" @mouseenter="hovered = collapsed" @mouseleave="hovered = false">
                        <button @click="if(!collapsed) open = !open"
                                class="sidebar-nav-item {{ $shouldExpand ? 'sidebar-nav-item-parent-active' : 'sidebar-nav-item-inactive' }} w-full text-left">
                            <span class="material-symbols-outlined sidebar-nav-icon">{{ $item['icon'] }}</span>
                            <span x-show="!collapsed" class="flex-1">{{ $item['label'] }}</span>
                            <span x-show="!collapsed && !open" class="material-symbols-outlined ml-auto" style="font-size: 16px;">chevron_right</span>
                            <span x-show="!collapsed && open" class="material-symbols-outlined ml-auto" style="font-size: 16px;">expand_more</span>
                        </button>
                        <div x-show="(collapsed && hovered) || (!collapsed && open)" x-transition
                             :class="collapsed ? 'submenu-dropdown' : 'submenu-container space-y-1'">
                            @foreach($visibleChildren as $child)
                                @php 
                                    $childUrl = '#';
                                    try { $childUrl = route($child['route']); } catch (\Exception $e) {}
                                @endphp
                                <a href="{{ $childUrl }}"
                                   class="submenu-item sidebar-nav-item {{ request()->routeIs($child['active'] ?? '') ? 'sidebar-nav-item-active' : 'sidebar-nav-item-inactive' }} text-sm">
                                    {{ $child['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @else
                    @if($item['visible'] ?? true)
                        @php 
                            $url = '#'; 
                            try { $url = route($item['route']); } catch (\Exception $e) {}
                        @endphp
                        <a href="{{ $url }}"
                           class="sidebar-nav-item {{ request()->routeIs($item['active'] ?? '') ? 'sidebar-nav-item-active' : 'sidebar-nav-item-inactive' }}">
                            <span class="material-symbols-outlined sidebar-nav-icon">{{ $item['icon'] }}</span>
                            <span x-show="!collapsed">{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endif
            @endforeach
        </div>
    </nav>
</div>
