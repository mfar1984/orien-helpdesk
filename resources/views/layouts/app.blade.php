@php
    $appSettings = \App\Models\Setting::whereIn('key', ['favicon', 'system_name', 'company_short_name'])
        ->pluck('value', 'key');
    $appFavicon = $appSettings['favicon'] ?? null;
    $appSystemName = $appSettings['system_name'] ?? $appSettings['company_short_name'] ?? 'Orien';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Dashboard') - {{ $appSystemName }}</title>
        
        @if($appFavicon)
            <link rel="icon" type="image/png" href="{{ asset('storage/' . $appFavicon) }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <style>
            /* Base Styles */
            body {
                font-family: 'Poppins', sans-serif;
                font-size: 14px;
                line-height: 1.5;
            }

            [x-cloak] { display: none !important; }

            /* Sidebar Styles */
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100%;
                background-color: white;
                color: #1f2937;
                transition: all 0.3s ease;
                z-index: 30;
                border-right: 1px solid #e5e7eb;
            }

            .sidebar-expanded { width: 256px; }
            .sidebar-collapsed { width: 64px; }

            .sidebar-collapsed .sidebar-nav-item {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }

            .sidebar-collapsed .sidebar-nav-icon {
                margin-left: auto;
                margin-right: auto;
            }

            .sidebar-collapsed .submenu-dropdown {
                position: absolute;
                left: 64px;
                top: 0;
                background-color: white;
                border: 1px solid #e5e7eb;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                border-radius: 4px;
                min-width: 200px;
                max-height: 80vh;
                overflow-y: auto;
                z-index: 40;
            }

            .sidebar-header {
                padding: 1.5rem 1rem;
                border-bottom: 1px solid #e5e7eb;
                height: 82px;
                box-sizing: border-box;
            }

            .sidebar-toggle {
                padding: 0.5rem;
                border-radius: 4px;
                color: #6b7280;
                width: 32px;
                height: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s ease;
            }

            .sidebar-toggle:hover {
                color: #1f2937;
                background-color: #f3f4f6;
            }

            .sidebar-nav { padding: 0; }

            .sidebar-nav-item {
                display: flex;
                align-items: center;
                padding: 0.5rem 1rem;
                font-size: 12px;
                font-weight: 500;
                transition: all 0.15s ease;
                border-radius: 0;
                margin: 0;
            }

            .sidebar-nav-item-active {
                color: #1f2937;
                background-color: #dbeafe !important;
                position: relative;
            }

            .sidebar-nav-item-active::after {
                content: '';
                position: absolute;
                right: 0;
                top: 0;
                bottom: 0;
                width: 3px;
                background-color: #3b82f6;
            }

            .sidebar-nav-item-inactive {
                color: #4b5563;
                position: relative;
                transition: all 0.2s ease;
            }

            .sidebar-nav-item-inactive:hover {
                background-color: #f0f9ff !important;
                color: #3b82f6 !important;
            }

            .sidebar-nav-item-inactive:hover .sidebar-nav-icon {
                color: #3b82f6 !important;
            }

            .sidebar-nav-item-inactive:hover::after {
                content: '';
                position: absolute;
                right: 0;
                top: 0;
                bottom: 0;
                width: 3px;
                background-color: #93c5fd;
            }

            .sidebar-nav-item-parent-active {
                color: #1f2937;
                background-color: transparent !important;
                position: relative;
            }

            .sidebar-nav-item-parent-active:hover {
                background-color: #f0f9ff !important;
                color: #3b82f6 !important;
            }

            .sidebar-nav-icon {
                margin-right: 0.75rem;
                width: 20px;
                height: 20px;
                flex-shrink: 0;
            }

            /* Submenu tree lines */
            .submenu-container {
                position: relative;
                margin-left: 24px;
                margin-top: 4px;
            }

            .submenu-container::before {
                content: '';
                position: absolute;
                left: -12px;
                top: 0;
                bottom: 0;
                width: 1px;
                background-color: #d1d5db;
            }

            .submenu-item {
                position: relative;
                padding-left: 20px;
                z-index: 1;
                font-size: 11px !important;
            }

            .submenu-item::before {
                content: '';
                position: absolute;
                left: -12px;
                top: 50%;
                width: 18px;
                height: 1px;
                background-color: #d1d5db;
            }

            .submenu-item::after {
                content: '';
                position: absolute;
                left: 0;
                top: 50%;
                transform: translateY(-50%);
                width: 6px;
                height: 6px;
                background-color: #9ca3af;
                border-radius: 50%;
            }

            .submenu-item.sidebar-nav-item-inactive:hover::after,
            .submenu-item.sidebar-nav-item-active::after {
                display: none;
            }

            /* Header Styles */
            .header {
                background-color: white;
                border-bottom: 1px solid #e5e7eb;
                position: sticky;
                top: 0;
                z-index: 1000;
            }

            .topbar {
                background-color: #f9fafb;
                border-bottom: 1px solid #e5e7eb;
                position: sticky;
                top: 0;
                z-index: 40;
            }

            .topbar-container {
                padding: 0 1.5rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                height: 48px;
            }

            .topbar-left {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: #4b5563;
                font-size: 11px;
            }

            .welcome-text { font-weight: 500; color: #374151; }
            .topbar-separator { color: #9ca3af; }
            .topbar-right { display: flex; align-items: center; gap: 0.75rem; }

            .topbar-user-btn {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                font-size: 11px;
                color: #374151;
                padding: 0.25rem 0.5rem;
                border-radius: 4px;
                transition: color 0.2s ease;
            }

            .topbar-user-btn:hover { color: #3b82f6; }

            .user-avatar {
                width: 24px;
                height: 24px;
                background-color: #3b82f6;
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .topbar-dropdown {
                position: absolute;
                right: 0;
                margin-top: 0.5rem;
                width: 192px;
                background-color: white;
                border-radius: 6px;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                border: 1px solid #e5e7eb;
                padding: 0.25rem 0;
                z-index: 50;
            }

            .topbar-dropdown-item {
                display: flex;
                align-items: center;
                padding: 0.5rem 0.75rem;
                font-size: 11px;
                color: #374151;
                transition: background-color 0.2s ease;
                gap: 8px;
            }

            .topbar-dropdown-item:hover { background-color: #f9fafb; }
            .topbar-dropdown-item svg { margin-right: 12px; }

            /* Breadcrumb Styles */
            .breadcrumb-bar { background-color: white; }

            .breadcrumb-container {
                padding: 0 1.5rem;
                display: flex;
                align-items: center;
                height: 32px;
            }

            /* Footer Styles */
            .footer {
                background-color: white;
                border-top: 1px solid #e5e7eb;
                margin-top: auto;
            }

            .footer-container { padding: 0 1.5rem; }

            .footer-content {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0.5rem 0;
                min-height: 32px;
            }

            .footer-copyright { color: #4b5563; font-size: 11px; }
            .footer-links { display: flex; align-items: center; gap: 0.5rem; }

            .footer-link {
                color: #4b5563;
                font-size: 11px;
                text-decoration: none;
                transition: color 0.2s ease;
            }

            .footer-link:hover { color: #3b82f6; }
            .footer-separator { color: #9ca3af; font-size: 11px; margin: 0 4px; }

            /* Main Content */
            .main-content { transition: margin-left 0.3s ease; }
            .main-content-expanded { margin-left: 256px; }
            .main-content-collapsed { margin-left: 64px; }

            /* Separator */
            .sidebar-separator { margin: 0.75rem; padding: 0 0.75rem; }
            .sidebar-separator-line { border: 0; border-top: 1px solid #e5e7eb; height: 1px; }

            /* Mobile Styles */
            @media (max-width: 768px) {
                .sidebar {
                    transform: translateX(-100%);
                    width: 280px !important;
                    z-index: 9001 !important;
                }
                .sidebar.sidebar-mobile-open { transform: translateX(0) !important; }
                .sidebar-expanded, .sidebar-collapsed { width: 280px !important; }
                .sidebar-toggle { display: none !important; }
                .main-content-expanded, .main-content-collapsed { margin-left: 0; }
                .sidebar-overlay {
                    position: fixed;
                    inset: 0;
                    background-color: rgba(75, 85, 99, 0.75);
                    z-index: 9000 !important;
                }
                .welcome-text, .topbar-separator { display: none !important; }
                .topbar-container { padding-left: 0.75rem; padding-right: 0.75rem; }
                .current-date { font-size: 10px !important; }
                .topbar-user-btn .user-email { display: none !important; }
            }
        </style>
    </head>
    <body class="font-poppins antialiased bg-gray-50"
          x-data="{ sidebarCollapsed: false }"
          @sidebar-toggled.window="sidebarCollapsed = $event.detail.collapsed">
        <div class="min-h-screen flex flex-col">
            <!-- Sidebar -->
            <x-sidebar />
            
            <!-- Mobile sidebar overlay -->
            <div x-data="{ showOverlay: false }"
                 x-show="showOverlay" 
                 @toggle-mobile-menu.window="showOverlay = !showOverlay"
                 @close-mobile-menu.window="showOverlay = false"
                 @click="$dispatch('close-mobile-menu')"
                 class="sidebar-overlay md:hidden"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
            </div>

            <!-- Main content -->
            <div class="main-content flex flex-col flex-1"
                 :class="sidebarCollapsed ? 'main-content-collapsed' : 'main-content-expanded'">
                <!-- Header with Page Title -->
                <x-header :title="View::getSection('page-title', 'Dashboard')" />

                <!-- Page Content -->
                <main class="p-6 flex-1">
                    @yield('content')
                </main>

                <!-- Footer -->
                <x-footer />
            </div>
        </div>

        <!-- Global Scripts for Dropdown Action Buttons -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Close all dropdowns when clicking outside
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.dropdown-container')) {
                        document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
                            menu.classList.add('hidden');
                        });
                    }
                });

                // Toggle dropdown on trigger click
                document.querySelectorAll('.dropdown-trigger').forEach(function(trigger) {
                    trigger.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const container = this.closest('.dropdown-container');
                        const menu = container.querySelector('.dropdown-menu');
                        
                        // Close other dropdowns
                        document.querySelectorAll('.dropdown-menu').forEach(function(m) {
                            if (m !== menu) m.classList.add('hidden');
                        });
                        
                        // Position and toggle this dropdown
                        if (menu.classList.contains('hidden')) {
                            // Get the pill container (parent of dropdown-container)
                            const pillContainer = container.closest('.inline-flex');
                            const pillRect = pillContainer ? pillContainer.getBoundingClientRect() : trigger.getBoundingClientRect();
                            
                            // Position below the pill, aligned to the left
                            menu.style.top = (pillRect.bottom + 4) + 'px';
                            menu.style.left = pillRect.left + 'px';
                            menu.classList.remove('hidden');
                        } else {
                            menu.classList.add('hidden');
                        }
                    });
                });
            });
        </script>

        <!-- Page-specific Scripts -->
        @stack('scripts')
    </body>
</html>
