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
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('mobileMenu', {
                    open: false,
                    toggle() {
                        this.open = !this.open;
                    },
                    close() {
                        this.open = false;
                    }
                });
            });
        </script>

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

            /* ============================================
               MOBILE STYLES - Separated from Desktop
               ============================================ */
            @media (max-width: 768px) {
                /* ----- MOBILE SIDEBAR ----- */
                .sidebar {
                    position: fixed !important;
                    left: 0 !important;
                    top: 0 !important;
                    height: 100% !important;
                    transform: translateX(-100%);
                    width: 280px !important;
                    z-index: 9001 !important;
                    transition: transform 0.3s ease !important;
                    background-color: white !important;
                    border-right: 1px solid #e5e7eb !important;
                }
                .sidebar.sidebar-mobile-open { 
                    transform: translateX(0) !important; 
                }
                .sidebar-expanded, .sidebar-collapsed { 
                    width: 280px !important; 
                }
                .sidebar-toggle { 
                    display: none !important; 
                }
                .main-content-expanded, .main-content-collapsed { 
                    margin-left: 0 !important; 
                }
                .sidebar-overlay {
                    position: fixed;
                    inset: 0;
                    background-color: rgba(75, 85, 99, 0.75);
                    z-index: 9000 !important;
                }
                
                /* Mobile sidebar navigation */
                .sidebar .sidebar-nav {
                    padding: 0 !important;
                    overflow-y: auto !important;
                    max-height: calc(100vh - 100px) !important;
                }
                
                .sidebar .sidebar-nav-item {
                    padding: 0.75rem 1rem !important;
                    font-size: 13px !important;
                    justify-content: flex-start !important;
                }
                
                .sidebar .sidebar-nav-icon {
                    margin-right: 0.75rem !important;
                    margin-left: 0 !important;
                }
                
                .sidebar .submenu-container {
                    margin-left: 24px !important;
                }
                
                .sidebar .submenu-item {
                    padding: 0.6rem 1rem 0.6rem 20px !important;
                    font-size: 12px !important;
                }
                
                /* Mobile sidebar header */
                .sidebar .sidebar-header {
                    padding: 1rem !important;
                    height: auto !important;
                    min-height: 70px !important;
                }
                
                /* Collapsed state should not apply on mobile */
                .sidebar-collapsed .sidebar-nav-item {
                    justify-content: flex-start !important;
                    padding-left: 1rem !important;
                    padding-right: 1rem !important;
                }
                
                .sidebar-collapsed .sidebar-nav-icon {
                    margin-left: 0 !important;
                    margin-right: 0.75rem !important;
                }

                /* ----- MOBILE TOPBAR ----- */
                .topbar {
                    position: sticky !important;
                    top: 0 !important;
                    z-index: 40 !important;
                }
                
                .topbar-container { 
                    padding: 0 0.75rem !important;
                    height: 44px !important;
                }
                
                .topbar-left {
                    flex: 1 !important;
                    min-width: 0 !important;
                }
                
                /* Hide welcome text and separator on mobile */
                .welcome-text, 
                .topbar-separator { 
                    display: none !important; 
                }
                
                /* Mobile date - shorter format */
                .current-date { 
                    font-size: 10px !important;
                    white-space: nowrap !important;
                    overflow: hidden !important;
                    text-overflow: ellipsis !important;
                    max-width: 180px !important;
                }
                
                .topbar-right {
                    flex-shrink: 0 !important;
                }
                
                /* Hide email on mobile, show only avatar */
                .topbar-user-btn {
                    padding: 0.25rem !important;
                    gap: 0.5rem !important;
                }
                
                .topbar-user-btn .user-email { 
                    display: none !important; 
                }
                
                .topbar-user-btn svg.h-4 {
                    display: none !important;
                }
                
                .user-avatar {
                    width: 28px !important;
                    height: 28px !important;
                }
                
                /* Mobile dropdown positioning */
                .topbar-dropdown {
                    position: fixed !important;
                    right: 0.75rem !important;
                    top: auto !important;
                    margin-top: 0.25rem !important;
                    width: 180px !important;
                    z-index: 9999 !important;
                }

                /* ----- MOBILE BREADCRUMB ----- */
                .breadcrumb-bar {
                    border-bottom: 1px solid #e5e7eb !important;
                }
                
                .breadcrumb-container {
                    padding: 0 0.5rem !important;
                    height: 44px !important;
                    gap: 0.5rem !important;
                }
                
                /* Mobile menu button */
                .breadcrumb-container button[type="button"] {
                    flex-shrink: 0 !important;
                }
                
                /* Breadcrumb nav on mobile */
                .breadcrumb-container nav {
                    flex: 1 !important;
                    min-width: 0 !important;
                    overflow-x: auto !important;
                    scrollbar-width: none !important;
                    -ms-overflow-style: none !important;
                }
                
                .breadcrumb-container nav::-webkit-scrollbar {
                    display: none !important;
                }
                
                .breadcrumb-container nav span,
                .breadcrumb-container nav a {
                    font-size: 10px !important;
                    white-space: nowrap !important;
                }

                /* ----- MOBILE FOOTER ----- */
                .footer {
                    border-top: 1px solid #e5e7eb !important;
                }
                
                .footer-container {
                    padding: 0 0.75rem !important;
                }
                
                .footer-content {
                    flex-direction: column !important;
                    align-items: center !important;
                    gap: 0.5rem !important;
                    padding: 0.75rem 0 !important;
                    min-height: auto !important;
                }
                
                .footer-copyright {
                    font-size: 10px !important;
                    text-align: center !important;
                    order: 2 !important;
                }
                
                .footer-links {
                    order: 1 !important;
                    flex-wrap: wrap !important;
                    justify-content: center !important;
                    gap: 0.25rem !important;
                }
                
                .footer-link {
                    font-size: 10px !important;
                    padding: 0.25rem 0.5rem !important;
                }
                
                .footer-separator {
                    font-size: 10px !important;
                    margin: 0 2px !important;
                }

                /* ----- MOBILE MAIN CONTENT ----- */
                main.p-6 {
                    padding: 1rem !important;
                }
                
                /* Mobile page headers */
                .bg-white.border.border-gray-200 > .px-6.py-4 {
                    padding: 1rem !important;
                }
                
                .bg-white.border.border-gray-200 > .px-6.py-4 h2 {
                    font-size: 14px !important;
                }
                
                .bg-white.border.border-gray-200 > .px-6.py-4 p {
                    font-size: 10px !important;
                }
                
                /* Mobile buttons in header */
                .bg-white.border.border-gray-200 > .px-6.py-4 .flex.items-center.gap-2 button,
                .bg-white.border.border-gray-200 > .px-6.py-4 .flex.items-center.gap-2 a {
                    padding: 0 0.5rem !important;
                    font-size: 10px !important;
                    min-height: 28px !important;
                }
                
                .bg-white.border.border-gray-200 > .px-6.py-4 .flex.items-center.gap-2 .material-symbols-outlined {
                    font-size: 12px !important;
                }
                
                /* Mobile tabs navigation */
                .border-t.border-gray-200 nav.flex {
                    overflow-x: auto !important;
                    scrollbar-width: none !important;
                    -ms-overflow-style: none !important;
                    padding: 0 0.5rem !important;
                }
                
                .border-t.border-gray-200 nav.flex::-webkit-scrollbar {
                    display: none !important;
                }
                
                .border-t.border-gray-200 nav.flex a {
                    font-size: 10px !important;
                    padding: 0.75rem 0.5rem !important;
                    white-space: nowrap !important;
                }
                
                /* Mobile filter forms */
                .px-6.py-3 form.flex {
                    flex-wrap: wrap !important;
                    gap: 0.5rem !important;
                    padding: 0.75rem !important;
                }
                
                .px-6.py-3 form.flex .flex-1 {
                    flex: 1 1 100% !important;
                    min-width: 0 !important;
                }
                
                .px-6.py-3 form.flex select {
                    flex: 1 1 auto !important;
                    min-width: 100px !important;
                }
                
                .px-6.py-3 form.flex button {
                    flex: 1 1 auto !important;
                }
                
                /* Mobile data tables */
                .px-6 .overflow-x-auto {
                    margin: 0 -0.75rem !important;
                    padding: 0 0.75rem !important;
                }
                
                .px-6 table {
                    font-size: 11px !important;
                }
                
                .px-6 table th,
                .px-6 table td {
                    padding: 0.5rem 0.75rem !important;
                }
            }
            
            /* ============================================
               SMALL MOBILE (< 480px) - Extra adjustments
               ============================================ */
            @media (max-width: 480px) {
                .current-date {
                    max-width: 120px !important;
                }
                
                .breadcrumb-container nav span:not(:last-child),
                .breadcrumb-container nav a:not(:last-child) {
                    display: none !important;
                }
                
                /* Show only last 2 breadcrumb items on very small screens */
                .breadcrumb-container nav > *:nth-last-child(-n+3) {
                    display: inline-flex !important;
                }
                
                .footer-content {
                    padding: 0.5rem 0 !important;
                }
                
                .footer-links {
                    gap: 0.125rem !important;
                }
            }

            /* ============================================
               MOBILE TICKETS PAGE STYLES
               ============================================ */
            @media (max-width: 768px) {
                /* ----- MOBILE TICKETS PAGE HEADER ----- */
                .tickets-page-header {
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    gap: 0.75rem !important;
                    padding: 1rem !important;
                }
                
                .tickets-page-header h2 {
                    font-size: 14px !important;
                }
                
                .tickets-page-header p {
                    font-size: 10px !important;
                }
                
                .tickets-page-header .flex.items-center.gap-2 {
                    width: 100% !important;
                }
                
                .tickets-page-header .flex.items-center.gap-2 a {
                    flex: 1 !important;
                    justify-content: center !important;
                }

                /* ----- MOBILE TICKETS TABS ----- */
                .tickets-tabs-nav {
                    overflow-x: auto !important;
                    scrollbar-width: none !important;
                    -ms-overflow-style: none !important;
                    padding: 0 0.5rem !important;
                    gap: 0 !important;
                }
                
                .tickets-tabs-nav::-webkit-scrollbar {
                    display: none !important;
                }
                
                .tickets-tabs-nav a {
                    padding: 0.75rem 0.5rem !important;
                    font-size: 10px !important;
                    white-space: nowrap !important;
                    flex-shrink: 0 !important;
                }
                
                .tickets-tabs-nav a span.inline-flex {
                    gap: 0.25rem !important;
                }
                
                .tickets-tabs-nav a span span {
                    font-size: 8px !important;
                    min-width: 14px !important;
                    padding: 0.125rem 0.25rem !important;
                }

                /* ----- MOBILE TICKETS FILTER FORM ----- */
                .tickets-filter-form {
                    flex-direction: column !important;
                    gap: 0.5rem !important;
                    padding: 0.75rem 1rem !important;
                }
                
                .tickets-filter-form .flex-1 {
                    width: 100% !important;
                }
                
                .tickets-filter-form input[type="text"] {
                    width: 100% !important;
                    font-size: 11px !important;
                }
                
                .tickets-filter-form select {
                    width: 100% !important;
                    min-width: 100% !important;
                    font-size: 11px !important;
                }
                
                .tickets-filter-form .filter-buttons {
                    display: flex !important;
                    gap: 0.5rem !important;
                    width: 100% !important;
                }
                
                .tickets-filter-form button {
                    flex: 1 !important;
                    justify-content: center !important;
                    font-size: 10px !important;
                    padding: 0 0.75rem !important;
                }

                /* ----- MOBILE TICKETS DATA TABLE - CARD LAYOUT ----- */
                .tickets-table-container {
                    padding: 0 0.5rem !important;
                }
                
                .tickets-table-container .overflow-x-auto {
                    overflow-x: visible !important;
                    border: none !important;
                    box-shadow: none !important;
                }
                
                /* Hide table header on mobile */
                .tickets-table-container table thead {
                    display: none !important;
                }
                
                .tickets-table-container table {
                    display: block !important;
                    border: none !important;
                }
                
                .tickets-table-container table tbody {
                    display: flex !important;
                    flex-direction: column !important;
                    gap: 0.75rem !important;
                }
                
                /* Each row becomes a card */
                .tickets-table-container table tbody tr {
                    display: flex !important;
                    flex-direction: column !important;
                    background: white !important;
                    border: 1px solid #e5e7eb !important;
                    border-radius: 8px !important;
                    padding: 0.75rem !important;
                    gap: 0.5rem !important;
                }
                
                .tickets-table-container table tbody tr:hover {
                    background: #f9fafb !important;
                }
                
                /* Each cell becomes a row with label */
                .tickets-table-container table tbody td {
                    display: flex !important;
                    justify-content: space-between !important;
                    align-items: center !important;
                    padding: 0.25rem 0 !important;
                    border: none !important;
                    white-space: normal !important;
                }
                
                /* Add labels before each cell */
                .tickets-table-container table tbody td:nth-child(1)::before { content: 'ID:'; }
                .tickets-table-container table tbody td:nth-child(2)::before { content: 'Subject:'; }
                .tickets-table-container table tbody td:nth-child(3)::before { content: 'Requester:'; }
                .tickets-table-container table tbody td:nth-child(4)::before { content: 'Status:'; }
                .tickets-table-container table tbody td:nth-child(5)::before { content: 'Priority:'; }
                .tickets-table-container table tbody td:nth-child(6)::before { content: 'Created:'; }
                .tickets-table-container table tbody td:nth-child(7)::before { content: ''; }
                
                .tickets-table-container table tbody td::before {
                    font-weight: 600 !important;
                    font-size: 10px !important;
                    color: #6b7280 !important;
                    font-family: Poppins, sans-serif !important;
                    min-width: 70px !important;
                    flex-shrink: 0 !important;
                }
                
                /* Show ALL columns on mobile - no hiding */
                .tickets-table-container table tbody td {
                    display: flex !important;
                }
                
                /* Actions row - full width at bottom */
                .tickets-table-container table tbody td:last-child {
                    border-top: 1px solid #e5e7eb !important;
                    padding-top: 0.75rem !important;
                    margin-top: 0.25rem !important;
                    justify-content: flex-end !important;
                }
                
                .tickets-table-container table tbody td:last-child::before {
                    display: none !important;
                }
                
                /* Cell content styling */
                .tickets-table-container table tbody td a {
                    font-size: 11px !important;
                }
                
                .tickets-table-container table tbody td .text-sm {
                    font-size: 11px !important;
                    text-align: right !important;
                }
                
                /* Status and Priority badges */
                .tickets-table-container table tbody td span.inline-flex {
                    font-size: 9px !important;
                    padding: 0.25rem 0.5rem !important;
                }
                
                .tickets-table-container table tbody td span.inline-flex .material-symbols-outlined {
                    font-size: 11px !important;
                }
                
                /* Empty state */
                .tickets-table-container table tbody tr td[colspan] {
                    display: block !important;
                    text-align: center !important;
                    padding: 2rem 1rem !important;
                }
                
                .tickets-table-container table tbody tr td[colspan]::before {
                    display: none !important;
                }

                /* ----- MOBILE TICKETS PAGINATION ----- */
                .tickets-pagination {
                    flex-direction: column !important;
                    gap: 0.75rem !important;
                    padding: 0.75rem 1rem !important;
                }
                
                .tickets-pagination p {
                    font-size: 10px !important;
                    text-align: center !important;
                }

                /* ----- MOBILE TICKETS EDIT MODAL ----- */
                #edit-ticket-modal > div {
                    padding: 0.5rem !important;
                    align-items: flex-start !important;
                    padding-top: 2rem !important;
                }
                
                #edit-ticket-modal > div > div {
                    max-width: 100% !important;
                    width: 100% !important;
                    margin: 0 !important;
                    border-radius: 8px !important;
                    max-height: calc(100vh - 3rem) !important;
                }
                
                #edit-ticket-modal h3 {
                    font-size: 14px !important;
                }
                
                #edit-ticket-modal form {
                    padding: 1rem !important;
                }
                
                #edit-ticket-modal label {
                    font-size: 10px !important;
                }
                
                #edit-ticket-modal input,
                #edit-ticket-modal textarea {
                    font-size: 11px !important;
                    padding: 8px 10px !important;
                }
                
                #edit-ticket-modal textarea {
                    min-height: 120px !important;
                }
                
                #edit-ticket-modal form > div > div:last-child {
                    flex-direction: column !important;
                    gap: 0.5rem !important;
                }
                
                #edit-ticket-modal form button {
                    width: 100% !important;
                    justify-content: center !important;
                    font-size: 10px !important;
                    padding: 10px 16px !important;
                }
            }
            
            /* ============================================
               SMALL MOBILE TICKETS (< 480px)
               ============================================ */
            @media (max-width: 480px) {
                .tickets-tabs-nav a {
                    padding: 0.5rem 0.375rem !important;
                    font-size: 9px !important;
                }
                
                .tickets-filter-form button span.material-symbols-outlined {
                    display: none !important;
                }
                
                /* Even smaller card text */
                .tickets-table-container table tbody td::before {
                    font-size: 9px !important;
                    min-width: 60px !important;
                }
                
                .tickets-table-container table tbody td .text-sm {
                    font-size: 10px !important;
                }
            }

            /* ============================================
               MOBILE TICKETS CREATE PAGE STYLES
               ============================================ */
            @media (max-width: 768px) {
                /* ----- MOBILE TICKETS CREATE HEADER ----- */
                .ticket-create-header {
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    gap: 0.75rem !important;
                    padding: 1rem !important;
                }
                
                .ticket-create-header > div:first-child {
                    display: flex !important;
                    align-items: center !important;
                    gap: 0.75rem !important;
                }
                
                .ticket-create-header h2 {
                    font-size: 14px !important;
                }
                
                .ticket-create-header p {
                    font-size: 10px !important;
                }
                
                .ticket-create-header > a {
                    width: 100% !important;
                    justify-content: center !important;
                }

                /* ----- MOBILE TICKETS CREATE 2-COLUMN LAYOUT ----- */
                .ticket-create-content {
                    grid-template-columns: 1fr !important;
                    padding: 1rem !important;
                    gap: 1rem !important;
                }
                
                /* Form section */
                .ticket-create-content > div:first-child {
                    order: 1 !important;
                }
                
                /* Info panels section */
                .ticket-create-content > div:last-child {
                    order: 2 !important;
                }
                
                .ticket-create-content form {
                    padding: 1rem !important;
                }
                
                .ticket-create-content label {
                    font-size: 10px !important;
                }
                
                .ticket-create-content input,
                .ticket-create-content textarea,
                .ticket-create-content select {
                    font-size: 11px !important;
                    padding: 8px 10px !important;
                }
                
                /* Priority & Category row - stack on mobile */
                .ticket-create-content form > div > div:nth-child(2) {
                    grid-template-columns: 1fr !important;
                    gap: 12px !important;
                }
                
                /* Action buttons */
                .ticket-create-content form > div > div:last-child {
                    flex-direction: column !important;
                    gap: 0.5rem !important;
                }
                
                .ticket-create-content form > div > div:last-child button,
                .ticket-create-content form > div > div:last-child a {
                    width: 100% !important;
                    justify-content: center !important;
                    font-size: 10px !important;
                }
                
                /* Info panels */
                .ticket-create-content > div:last-child > div {
                    margin-bottom: 0 !important;
                }
                
                .ticket-create-content > div:last-child h4 {
                    font-size: 11px !important;
                }
                
                .ticket-create-content > div:last-child p,
                .ticket-create-content > div:last-child span,
                .ticket-create-content > div:last-child li {
                    font-size: 10px !important;
                }
            }

            /* ============================================
               MOBILE TICKETS SHOW PAGE STYLES
               ============================================ */
            @media (max-width: 768px) {
                /* ----- MOBILE TICKETS SHOW HEADER ----- */
                .ticket-show-header {
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    gap: 0.75rem !important;
                    padding: 1rem !important;
                }
                
                .ticket-show-header h2 {
                    font-size: 14px !important;
                }
                
                .ticket-show-header p {
                    font-size: 10px !important;
                }
                
                .ticket-show-header > a {
                    width: 100% !important;
                    justify-content: center !important;
                }

                /* ----- MOBILE TICKETS SHOW CONTENT ----- */
                .ticket-show-content {
                    padding: 1rem !important;
                }
                
                /* Info cards grid - stack on mobile */
                .ticket-show-content > div:first-child {
                    grid-template-columns: 1fr !important;
                    gap: 1rem !important;
                    margin-bottom: 1rem !important;
                }
                
                .ticket-show-content > div:first-child > div h4 {
                    font-size: 11px !important;
                }
                
                .ticket-show-content > div:first-child > div p {
                    font-size: 10px !important;
                }
                
                /* Info card headers */
                .ticket-show-content > div:first-child > div > div:first-child {
                    padding: 10px 12px !important;
                }
                
                .ticket-show-content > div:first-child > div > div:first-child > div:first-child {
                    width: 28px !important;
                    height: 28px !important;
                }
                
                .ticket-show-content > div:first-child > div > div:first-child > div:first-child .material-symbols-outlined {
                    font-size: 16px !important;
                }
                
                /* Info card inner grid - 2 columns on mobile */
                .ticket-show-content > div:first-child > div > div:last-child {
                    grid-template-columns: 1fr 1fr !important;
                    gap: 12px !important;
                    padding: 12px !important;
                }
                
                /* Info card items */
                .ticket-show-content > div:first-child > div > div:last-child > div {
                    gap: 8px !important;
                }
                
                .ticket-show-content > div:first-child > div > div:last-child > div > div:first-child {
                    width: 26px !important;
                    height: 26px !important;
                }
                
                .ticket-show-content > div:first-child > div > div:last-child > div > div:first-child .material-symbols-outlined {
                    font-size: 14px !important;
                }
                
                /* Ticket info card - 2 columns */
                .ticket-show-content > div:first-child > div:last-child > div:last-child {
                    grid-template-columns: 1fr 1fr !important;
                }
                
                /* Status and Priority badges in info cards */
                .ticket-show-content > div:first-child span[style*="inline-flex"] {
                    font-size: 9px !important;
                    padding: 2px 6px !important;
                }

                /* ----- MOBILE CHAT & SIDEBAR LAYOUT ----- */
                .ticket-show-content > div:last-child {
                    grid-template-columns: 1fr !important;
                    gap: 1rem !important;
                }
                
                /* Chat column */
                .ticket-show-content > div:last-child > div:first-child {
                    order: 1 !important;
                }
                
                /* Sidebar column */
                .ticket-show-content > div:last-child > div.sidebar-scroll {
                    order: 2 !important;
                    position: relative !important;
                    top: 0 !important;
                }

                /* ----- MOBILE CHAT MESSAGES ----- */
                #chat-messages {
                    padding: 12px !important;
                    gap: 12px !important;
                }
                
                #chat-messages > div {
                    border-radius: 6px !important;
                }
                
                /* Chat message header */
                #chat-messages > div > div:first-child {
                    padding: 10px 12px !important;
                    flex-direction: row !important;
                    flex-wrap: wrap !important;
                    align-items: center !important;
                    gap: 8px !important;
                }
                
                #chat-messages > div > div:first-child > div:first-child {
                    display: flex !important;
                    align-items: center !important;
                    gap: 8px !important;
                    flex: 1 !important;
                    min-width: 0 !important;
                }
                
                #chat-messages > div > div:first-child > div:first-child > div:first-child {
                    width: 28px !important;
                    height: 28px !important;
                    flex-shrink: 0 !important;
                }
                
                #chat-messages > div > div:first-child > div:first-child > div:first-child .material-symbols-outlined {
                    font-size: 14px !important;
                }
                
                #chat-messages > div > div:first-child > div:first-child > div:last-child p:first-child {
                    font-size: 11px !important;
                }
                
                #chat-messages > div > div:first-child > div:first-child > div:last-child p:last-child {
                    font-size: 9px !important;
                }
                
                /* Message type badge */
                #chat-messages > div > div:first-child > span {
                    font-size: 8px !important;
                    padding: 2px 6px !important;
                    flex-shrink: 0 !important;
                }
                
                /* Chat message body */
                #chat-messages > div > div:nth-child(2) {
                    padding: 12px !important;
                }
                
                #chat-messages > div > div:nth-child(2) p {
                    font-size: 11px !important;
                    line-height: 1.5 !important;
                }
                
                /* Attachments section */
                #chat-messages > div > div:last-child {
                    padding: 10px 12px !important;
                }
                
                #chat-messages > div > div:last-child p {
                    font-size: 10px !important;
                }
                
                #chat-messages > div > div:last-child a {
                    padding: 4px 8px !important;
                    margin-right: 6px !important;
                    margin-bottom: 6px !important;
                }
                
                #chat-messages > div > div:last-child a span {
                    font-size: 10px !important;
                }
                
                #chat-messages > div > div:last-child a .material-symbols-outlined {
                    font-size: 14px !important;
                }

                /* ----- MOBILE REPLY FORM ----- */
                .ticket-reply-form {
                    padding: 12px !important;
                }
                
                .ticket-reply-form textarea {
                    font-size: 11px !important;
                    padding: 8px 10px !important;
                    min-height: 80px !important;
                }
                
                .ticket-reply-form > div > div:last-child {
                    flex-direction: column !important;
                    gap: 10px !important;
                }
                
                .ticket-reply-form > div > div:last-child > div:first-child {
                    flex-direction: column !important;
                    align-items: stretch !important;
                    gap: 8px !important;
                    width: 100% !important;
                }
                
                /* Attach files button */
                .ticket-reply-form > div > div:last-child > div:first-child > div label {
                    width: 100% !important;
                    justify-content: center !important;
                    font-size: 10px !important;
                }
                
                /* Internal note checkbox */
                .ticket-reply-form > div > div:last-child > div:first-child > label {
                    font-size: 11px !important;
                }
                
                .ticket-reply-form > div > div:last-child > div:last-child {
                    flex-direction: column !important;
                    width: 100% !important;
                    gap: 8px !important;
                }
                
                /* Working time input */
                .ticket-reply-form > div > div:last-child > div:last-child > div:first-child {
                    width: 100% !important;
                    justify-content: center !important;
                }
                
                .ticket-reply-form > div > div:last-child > div:last-child > div:first-child input {
                    flex: 1 !important;
                    width: 100% !important;
                }
                
                .ticket-reply-form > div > div:last-child > div:last-child > div:first-child span {
                    font-size: 10px !important;
                }
                
                .ticket-reply-form button[type="submit"] {
                    width: 100% !important;
                    justify-content: center !important;
                    font-size: 10px !important;
                }

                /* ----- MOBILE SIDEBAR ACTIONS ----- */
                .sidebar-scroll {
                    gap: 12px !important;
                }
                
                .sidebar-scroll > div {
                    border-radius: 6px !important;
                }
                
                /* Sidebar card headers */
                .sidebar-scroll > div > div:first-child {
                    padding: 10px 12px !important;
                }
                
                .sidebar-scroll > div > div:first-child > div:first-child {
                    width: 28px !important;
                    height: 28px !important;
                }
                
                .sidebar-scroll > div > div:first-child > div:first-child .material-symbols-outlined {
                    font-size: 16px !important;
                }
                
                .sidebar-scroll > div > div:first-child h4 {
                    font-size: 11px !important;
                }
                
                /* Sidebar card content */
                .sidebar-scroll > div > div:last-child {
                    padding: 12px !important;
                }
                
                .sidebar-scroll > div label {
                    font-size: 10px !important;
                    margin-bottom: 4px !important;
                }
                
                .sidebar-scroll > div select,
                .sidebar-scroll > div input {
                    font-size: 10px !important;
                    padding: 6px 8px !important;
                }
                
                .sidebar-scroll > div button {
                    font-size: 10px !important;
                    padding: 8px 12px !important;
                }
                
                .sidebar-scroll > div button .material-symbols-outlined {
                    font-size: 14px !important;
                }
                
                /* Assigned to list */
                .assigned-to-list {
                    max-height: 80px !important;
                }
                
                .assigned-to-list > div {
                    padding: 4px 6px !important;
                }
                
                .assigned-to-list > div > div:first-child {
                    width: 20px !important;
                    height: 20px !important;
                }
                
                .assigned-to-list > div > div:first-child .material-symbols-outlined {
                    font-size: 10px !important;
                }
                
                .assigned-to-list > div span {
                    font-size: 10px !important;
                }
                
                /* Assign checkbox list */
                .assign-checkbox-list {
                    max-height: 80px !important;
                    padding: 4px !important;
                }
                
                .assign-checkbox-list label {
                    padding: 6px 4px !important;
                }
                
                .assign-checkbox-list label input {
                    width: 14px !important;
                    height: 14px !important;
                }
                
                .assign-checkbox-list label > div span:first-child {
                    font-size: 10px !important;
                }
                
                .assign-checkbox-list label > div span:last-child {
                    font-size: 8px !important;
                }
                
                /* Closed ticket message */
                .ticket-show-content > div:last-child > div:first-child > div:last-child {
                    padding: 16px !important;
                }
                
                .ticket-show-content > div:last-child > div:first-child > div:last-child .material-symbols-outlined {
                    font-size: 28px !important;
                }
                
                .ticket-show-content > div:last-child > div:first-child > div:last-child p {
                    font-size: 11px !important;
                }
            }
            
            /* ============================================
               SMALL MOBILE TICKETS SHOW (< 480px)
               ============================================ */
            @media (max-width: 480px) {
                /* Stack info card items vertically */
                .ticket-show-content > div:first-child > div > div:last-child {
                    grid-template-columns: 1fr !important;
                }
                
                .ticket-show-content > div:first-child > div:last-child > div:last-child {
                    grid-template-columns: 1fr !important;
                }
                
                #chat-messages > div > div:first-child {
                    padding: 8px 10px !important;
                }
                
                /* Hide working time label on very small screens */
                .ticket-reply-form > div > div:last-child > div:last-child > div:first-child > span:last-child {
                    display: none !important;
                }
            }

            /* ============================================
               MOBILE KNOWLEDGEBASE INDEX PAGE STYLES
               ============================================ */
            @media (max-width: 768px) {
                /* ----- MOBILE KB INDEX LAYOUT ----- */
                .kb-index-container {
                    flex-direction: column !important;
                    gap: 1rem !important;
                }
                
                /* KB Sidebar - full width on mobile */
                .kb-sidebar {
                    width: 100% !important;
                    order: 2 !important;
                }
                
                .kb-sidebar > div {
                    border-radius: 6px !important;
                }
                
                /* KB Sidebar search */
                .kb-sidebar .p-3 input {
                    font-size: 11px !important;
                }
                
                /* KB Sidebar categories */
                .kb-sidebar .p-2 a {
                    padding: 0.5rem 0.75rem !important;
                }
                
                .kb-sidebar .p-2 a span {
                    font-size: 11px !important;
                }
                
                .kb-sidebar .p-2 a .material-symbols-outlined {
                    font-size: 16px !important;
                }
                
                /* KB Main content - full width */
                .kb-main-content {
                    width: 100% !important;
                    order: 1 !important;
                }
                
                .kb-main-content > div {
                    border-radius: 6px !important;
                }
                
                /* KB Header */
                .kb-main-content .px-6.py-4 {
                    padding: 1rem !important;
                }
                
                .kb-main-content .px-6.py-4 h2 {
                    font-size: 14px !important;
                }
                
                .kb-main-content .px-6.py-4 p {
                    font-size: 10px !important;
                }
                
                .kb-main-content .px-6.py-4 .w-10 {
                    width: 32px !important;
                    height: 32px !important;
                }
                
                .kb-main-content .px-6.py-4 .w-10 .material-symbols-outlined {
                    font-size: 16px !important;
                }
                
                /* KB Content area */
                .kb-main-content .p-6 {
                    padding: 1rem !important;
                }
                
                /* KB Categories grid */
                .kb-main-content .grid {
                    grid-template-columns: 1fr !important;
                    gap: 0.75rem !important;
                }
                
                .kb-main-content .grid > a {
                    padding: 0.75rem !important;
                }
                
                .kb-main-content .grid > a .w-10 {
                    width: 32px !important;
                    height: 32px !important;
                }
                
                .kb-main-content .grid > a h3 {
                    font-size: 12px !important;
                }
                
                .kb-main-content .grid > a p {
                    font-size: 10px !important;
                }
                
                /* KB Articles list */
                .kb-main-content .space-y-2 > a {
                    padding: 0.75rem !important;
                    gap: 0.75rem !important;
                }
                
                .kb-main-content .space-y-2 > a > span:first-child {
                    font-size: 18px !important;
                }
                
                .kb-main-content .space-y-2 > a p {
                    font-size: 11px !important;
                }
                
                .kb-main-content .space-y-2 > a .flex.items-center.gap-3 {
                    gap: 0.25rem !important;
                    flex-wrap: wrap !important;
                }
                
                .kb-main-content .space-y-2 > a .flex.items-center.gap-3 span {
                    font-size: 9px !important;
                }
                
                /* Popular articles section */
                .kb-main-content h3 {
                    font-size: 12px !important;
                }
                
                /* Empty state */
                .kb-main-content .text-center .material-symbols-outlined {
                    font-size: 40px !important;
                }
                
                .kb-main-content .text-center p {
                    font-size: 11px !important;
                }
            }

            /* ============================================
               MOBILE KNOWLEDGEBASE ARTICLE PAGE STYLES
               ============================================ */
            @media (max-width: 768px) {
                /* ----- MOBILE KB ARTICLE LAYOUT ----- */
                .kb-article-container {
                    flex-direction: column !important;
                    gap: 1rem !important;
                }
                
                /* KB Article main - full width */
                .kb-article-main {
                    width: 100% !important;
                    order: 1 !important;
                }
                
                .kb-article-main > div {
                    border-radius: 6px !important;
                }
                
                /* Article header */
                .kb-article-main .px-6.py-5 {
                    padding: 1rem !important;
                }
                
                .kb-article-main .px-6.py-5 span.inline-flex {
                    font-size: 10px !important;
                    padding: 2px 6px !important;
                }
                
                .kb-article-main .px-6.py-5 h1 {
                    font-size: 16px !important;
                    margin-bottom: 0.5rem !important;
                }
                
                .kb-article-main .px-6.py-5 .flex.items-center.gap-4 {
                    flex-wrap: wrap !important;
                    gap: 0.5rem !important;
                }
                
                .kb-article-main .px-6.py-5 .flex.items-center.gap-4 span {
                    font-size: 10px !important;
                }
                
                /* Article content */
                .kb-article-main .px-6.py-6 {
                    padding: 1rem !important;
                }
                
                .kb-article-main .prose {
                    font-size: 12px !important;
                }
                
                .kb-article-main .prose h2 {
                    font-size: 14px !important;
                }
                
                .kb-article-main .prose h3 {
                    font-size: 13px !important;
                }
                
                /* Article footer */
                .kb-article-main .px-6.py-4.border-t {
                    padding: 0.75rem 1rem !important;
                    flex-direction: column !important;
                    gap: 0.75rem !important;
                }
                
                .kb-article-main .px-6.py-4.border-t > div {
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    gap: 0.5rem !important;
                    width: 100% !important;
                }
                
                .kb-article-main .px-6.py-4.border-t p {
                    font-size: 10px !important;
                }
                
                .kb-article-main .px-6.py-4.border-t .flex.items-center.gap-2 {
                    width: 100% !important;
                }
                
                .kb-article-main .px-6.py-4.border-t button {
                    flex: 1 !important;
                    justify-content: center !important;
                    font-size: 10px !important;
                }
                
                /* KB Article sidebar - full width */
                .kb-article-sidebar {
                    width: 100% !important;
                    order: 2 !important;
                }
                
                .kb-article-sidebar > div {
                    border-radius: 6px !important;
                    margin-bottom: 0.75rem !important;
                }
                
                .kb-article-sidebar h3 {
                    font-size: 12px !important;
                }
                
                .kb-article-sidebar .space-y-2 > a {
                    padding: 0.5rem !important;
                }
                
                .kb-article-sidebar .space-y-2 > a p {
                    font-size: 10px !important;
                }
                
                .kb-article-sidebar a.flex.items-center.gap-2 {
                    font-size: 10px !important;
                }
            }

            /* ============================================
               MOBILE KNOWLEDGEBASE SETTINGS PAGE STYLES
               ============================================ */
            @media (max-width: 768px) {
                /* ----- MOBILE KB SETTINGS HEADER ----- */
                .kb-settings-header {
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    gap: 0.75rem !important;
                    padding: 1rem !important;
                }
                
                .kb-settings-header h2 {
                    font-size: 14px !important;
                }
                
                .kb-settings-header p {
                    font-size: 10px !important;
                }
                
                .kb-settings-header .flex.items-center.gap-2 {
                    width: 100% !important;
                }
                
                .kb-settings-header .flex.items-center.gap-2 button {
                    flex: 1 !important;
                    justify-content: center !important;
                }

                /* ----- MOBILE KB SETTINGS TABS ----- */
                .kb-settings-tabs {
                    overflow-x: auto !important;
                    scrollbar-width: none !important;
                    -ms-overflow-style: none !important;
                    padding: 0 0.5rem !important;
                }
                
                .kb-settings-tabs::-webkit-scrollbar {
                    display: none !important;
                }
                
                .kb-settings-tabs a {
                    padding: 0.75rem 0.5rem !important;
                    font-size: 10px !important;
                    white-space: nowrap !important;
                    flex-shrink: 0 !important;
                }

                /* ----- MOBILE KB SETTINGS FILTER ----- */
                .kb-settings-filter {
                    flex-direction: column !important;
                    gap: 0.5rem !important;
                }
                
                .kb-settings-filter .flex-1 {
                    width: 100% !important;
                }
                
                .kb-settings-filter input[type="text"] {
                    width: 100% !important;
                    font-size: 11px !important;
                }
                
                .kb-settings-filter select {
                    width: 100% !important;
                    min-width: 100% !important;
                    font-size: 11px !important;
                }
                
                /* Filter buttons wrapper - same row */
                .kb-settings-filter .filter-buttons {
                    display: flex !important;
                    gap: 0.5rem !important;
                    width: 100% !important;
                }
                
                .kb-settings-filter .filter-buttons button {
                    flex: 1 !important;
                    justify-content: center !important;
                    font-size: 10px !important;
                    padding: 0 0.75rem !important;
                }

                /* ----- MOBILE KB SETTINGS TABLE - CARD LAYOUT ----- */
                .kb-settings-table {
                    padding: 0 0.5rem !important;
                }
                
                .kb-settings-table .overflow-x-auto {
                    overflow-x: visible !important;
                    border: none !important;
                    box-shadow: none !important;
                }
                
                /* Hide table header on mobile */
                .kb-settings-table table thead {
                    display: none !important;
                }
                
                .kb-settings-table table {
                    display: block !important;
                    border: none !important;
                }
                
                .kb-settings-table table tbody {
                    display: flex !important;
                    flex-direction: column !important;
                    gap: 0.75rem !important;
                }
                
                /* Each row becomes a card */
                .kb-settings-table table tbody tr {
                    display: flex !important;
                    flex-direction: column !important;
                    background: white !important;
                    border: 1px solid #e5e7eb !important;
                    border-radius: 8px !important;
                    padding: 0.75rem !important;
                    gap: 0.5rem !important;
                }
                
                .kb-settings-table table tbody tr:hover {
                    background: #f9fafb !important;
                }
                
                /* Each cell becomes a row with label */
                .kb-settings-table table tbody td {
                    display: flex !important;
                    justify-content: space-between !important;
                    align-items: center !important;
                    padding: 0.25rem 0 !important;
                    border: none !important;
                    white-space: normal !important;
                }
                
                .kb-settings-table table tbody td::before {
                    font-weight: 600 !important;
                    font-size: 10px !important;
                    color: #6b7280 !important;
                    font-family: Poppins, sans-serif !important;
                    min-width: 70px !important;
                    flex-shrink: 0 !important;
                }
                
                /* Show ALL columns on mobile - no hiding */
                .kb-settings-table table tbody td {
                    display: flex !important;
                }
                
                /* Cell content styling */
                .kb-settings-table table tbody td .text-sm,
                .kb-settings-table table tbody td .text-xs {
                    font-size: 11px !important;
                    text-align: right !important;
                }
                
                .kb-settings-table table tbody td .max-w-xs {
                    max-width: none !important;
                }
                
                /* Category icon in table */
                .kb-settings-table table tbody td .w-8 {
                    width: 24px !important;
                    height: 24px !important;
                }
                
                .kb-settings-table table tbody td .w-8 .material-symbols-outlined {
                    font-size: 12px !important;
                }
                
                .kb-settings-table table tbody td .flex.items-center.gap-3 {
                    gap: 0.5rem !important;
                }
                
                /* Status badges */
                .kb-settings-table table tbody td span.inline-flex {
                    font-size: 9px !important;
                    padding: 0.25rem 0.5rem !important;
                }
                
                /* Actions row - full width at bottom */
                .kb-settings-table table tbody td:last-child {
                    border-top: 1px solid #e5e7eb !important;
                    padding-top: 0.75rem !important;
                    margin-top: 0.25rem !important;
                    justify-content: flex-end !important;
                }
                
                .kb-settings-table table tbody td:last-child::before {
                    display: none !important;
                }
                
                /* Empty state */
                .kb-settings-table table tbody tr td[colspan] {
                    display: block !important;
                    text-align: center !important;
                    padding: 2rem 1rem !important;
                }
                
                .kb-settings-table table tbody tr td[colspan]::before {
                    display: none !important;
                }
                
                .kb-settings-table table tbody tr td[colspan] .material-symbols-outlined {
                    font-size: 36px !important;
                }
                
                .kb-settings-table table tbody tr td[colspan] p {
                    font-size: 11px !important;
                }
            }
            
            /* ============================================
               SMALL MOBILE KNOWLEDGEBASE (< 480px)
               ============================================ */
            @media (max-width: 480px) {
                .kb-settings-tabs a {
                    padding: 0.5rem 0.375rem !important;
                    font-size: 9px !important;
                }
                
                .kb-settings-filter button span.material-symbols-outlined {
                    display: none !important;
                }
                
                /* KB Article - smaller title */
                .kb-article-main .px-6.py-5 h1 {
                    font-size: 14px !important;
                }
                
                /* Even smaller card labels */
                .kb-settings-table table tbody td::before {
                    font-size: 9px !important;
                    min-width: 60px !important;
                }
                
                .kb-settings-table table tbody td .w-8 {
                    width: 20px !important;
                    height: 20px !important;
                }
                
                .kb-settings-table table td .w-8 .material-symbols-outlined {
                    font-size: 10px !important;
                }
            }

            /* ============================================
               MOBILE TOOLS PAGE STYLES
               ============================================ */
            @media (max-width: 768px) {
                /* ----- MOBILE TOOLS CONTAINER ----- */
                .tools-container {
                    border-radius: 6px !important;
                }

                /* ----- MOBILE TOOLS PAGE HEADER ----- */
                .tools-page-header {
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    gap: 0.75rem !important;
                    padding: 1rem !important;
                }
                
                .tools-page-header h2 {
                    font-size: 14px !important;
                }
                
                .tools-page-header p {
                    font-size: 10px !important;
                }
                
                .tools-page-header > div:last-child {
                    width: 100% !important;
                    display: flex !important;
                }
                
                .tools-page-header > div:last-child button {
                    flex: 1 !important;
                    justify-content: center !important;
                    font-size: 10px !important;
                    padding: 0 0.5rem !important;
                }

                /* ----- MOBILE TOOLS TABS ----- */
                .tools-tabs-nav {
                    overflow-x: auto !important;
                    scrollbar-width: none !important;
                    -ms-overflow-style: none !important;
                    padding: 0 0.5rem !important;
                }
                
                .tools-tabs-nav::-webkit-scrollbar {
                    display: none !important;
                }
                
                .tools-tabs-nav a {
                    padding: 0.75rem 0.5rem !important;
                    font-size: 10px !important;
                    white-space: nowrap !important;
                    flex-shrink: 0 !important;
                }

                /* ----- MOBILE TOOLS FILTER FORM ----- */
                .tools-filter-form {
                    padding: 0.75rem 0.5rem !important;
                }
                
                .tools-filter-form form {
                    flex-direction: column !important;
                    gap: 0.5rem !important;
                }
                
                .tools-filter-form .flex-1 {
                    width: 100% !important;
                }
                
                .tools-filter-form input[type="text"] {
                    width: 100% !important;
                    font-size: 11px !important;
                }
                
                /* Filter buttons wrapper - same row */
                .tools-filter-form .filter-buttons {
                    display: flex !important;
                    gap: 0.5rem !important;
                    width: 100% !important;
                }
                
                .tools-filter-form .filter-buttons button {
                    flex: 1 !important;
                    justify-content: center !important;
                    font-size: 10px !important;
                    padding: 0 0.75rem !important;
                }

                /* ----- MOBILE TOOLS TABLE - CARD LAYOUT ----- */
                .tools-table-container {
                    padding: 0 0.5rem !important;
                }
                
                .tools-table-container .overflow-x-auto {
                    overflow-x: visible !important;
                    border: none !important;
                    box-shadow: none !important;
                }
                
                /* Hide table header on mobile */
                .tools-table-container table thead {
                    display: none !important;
                }
                
                .tools-table-container table {
                    display: block !important;
                    border: none !important;
                }
                
                .tools-table-container table tbody {
                    display: flex !important;
                    flex-direction: column !important;
                    gap: 0.75rem !important;
                }
                
                /* Each row becomes a card */
                .tools-table-container table tbody tr {
                    display: flex !important;
                    flex-direction: column !important;
                    background: white !important;
                    border: 1px solid #e5e7eb !important;
                    border-radius: 8px !important;
                    padding: 0.75rem !important;
                    gap: 0.5rem !important;
                }
                
                .tools-table-container table tbody tr:hover {
                    background: #f9fafb !important;
                }
                
                /* Each cell becomes a row with label */
                .tools-table-container table tbody td {
                    display: flex !important;
                    justify-content: space-between !important;
                    align-items: flex-start !important;
                    padding: 0.25rem 0 !important;
                    border: none !important;
                    white-space: normal !important;
                    max-width: none !important;
                    overflow: visible !important;
                }
                
                /* Add labels before each cell */
                .tools-table-container table tbody td:nth-child(1)::before { content: 'Pattern:'; }
                .tools-table-container table tbody td:nth-child(2)::before { content: 'Reason:'; }
                .tools-table-container table tbody td:nth-child(3)::before { content: 'Added By:'; }
                .tools-table-container table tbody td:nth-child(4)::before { content: 'Date:'; }
                .tools-table-container table tbody td:nth-child(5)::before { content: ''; }
                
                .tools-table-container table tbody td::before {
                    font-weight: 600 !important;
                    font-size: 10px !important;
                    color: #6b7280 !important;
                    font-family: Poppins, sans-serif !important;
                    min-width: 70px !important;
                    flex-shrink: 0 !important;
                }
                
                /* Show ALL columns on mobile - no hiding */
                .tools-table-container table tbody td {
                    display: flex !important;
                }
                
                /* Cell content styling */
                .tools-table-container table tbody td .text-sm,
                .tools-table-container table tbody td .text-xs {
                    font-size: 11px !important;
                    text-align: right !important;
                    word-break: break-all !important;
                }
                
                /* Wildcard badge */
                .tools-table-container table tbody td:first-child .inline-flex {
                    font-size: 9px !important;
                    padding: 0.125rem 0.375rem !important;
                }
                
                /* Actions row - full width at bottom */
                .tools-table-container table tbody td:last-child {
                    border-top: 1px solid #e5e7eb !important;
                    padding-top: 0.75rem !important;
                    margin-top: 0.25rem !important;
                    justify-content: flex-end !important;
                }
                
                .tools-table-container table tbody td:last-child::before {
                    display: none !important;
                }
                
                /* Empty state */
                .tools-table-container table tbody tr td[colspan] {
                    display: block !important;
                    text-align: center !important;
                    padding: 2rem 1rem !important;
                }
                
                .tools-table-container table tbody tr td[colspan]::before {
                    display: none !important;
                }

                /* ----- MOBILE TOOLS PAGINATION ----- */
                .tools-container > div:last-child > div:last-child {
                    padding: 0.5rem !important;
                }
                
                .tools-container > div:last-child > div:last-child p {
                    font-size: 9px !important;
                }

                /* ----- MOBILE TOOLS MODALS ----- */
                /* All tools modals - responsive width */
                #ban-email-modal > div,
                #ban-ip-modal > div,
                #whitelist-ip-modal > div,
                #whitelist-email-modal > div,
                #bad-word-modal > div,
                #bad-website-modal > div {
                    max-width: calc(100vw - 32px) !important;
                    margin: 16px !important;
                    max-height: calc(100vh - 32px) !important;
                    overflow-y: auto !important;
                }
                
                /* Modal header */
                #ban-email-modal > div > div:first-child,
                #ban-ip-modal > div > div:first-child,
                #whitelist-ip-modal > div > div:first-child,
                #whitelist-email-modal > div > div:first-child,
                #bad-word-modal > div > div:first-child,
                #bad-website-modal > div > div:first-child {
                    padding: 12px 16px !important;
                }
                
                #ban-email-modal > div > div:first-child h3,
                #ban-ip-modal > div > div:first-child h3,
                #whitelist-ip-modal > div > div:first-child h3,
                #whitelist-email-modal > div > div:first-child h3,
                #bad-word-modal > div > div:first-child h3,
                #bad-website-modal > div > div:first-child h3 {
                    font-size: 12px !important;
                }
                
                #ban-email-modal > div > div:first-child > div:first-child > div:first-child,
                #ban-ip-modal > div > div:first-child > div:first-child > div:first-child,
                #whitelist-ip-modal > div > div:first-child > div:first-child > div:first-child,
                #whitelist-email-modal > div > div:first-child > div:first-child > div:first-child,
                #bad-word-modal > div > div:first-child > div:first-child > div:first-child,
                #bad-website-modal > div > div:first-child > div:first-child > div:first-child {
                    width: 28px !important;
                    height: 28px !important;
                }
                
                #ban-email-modal > div > div:first-child > div:first-child > div:first-child .material-symbols-outlined,
                #ban-ip-modal > div > div:first-child > div:first-child > div:first-child .material-symbols-outlined,
                #whitelist-ip-modal > div > div:first-child > div:first-child > div:first-child .material-symbols-outlined,
                #whitelist-email-modal > div > div:first-child > div:first-child > div:first-child .material-symbols-outlined,
                #bad-word-modal > div > div:first-child > div:first-child > div:first-child .material-symbols-outlined,
                #bad-website-modal > div > div:first-child > div:first-child > div:first-child .material-symbols-outlined {
                    font-size: 16px !important;
                }
                
                /* Modal tabs */
                #ban-email-modal > div > div:nth-child(2) button,
                #ban-ip-modal > div > div:nth-child(2) button,
                #whitelist-ip-modal > div > div:nth-child(2) button,
                #whitelist-email-modal > div > div:nth-child(2) button {
                    padding: 10px !important;
                    font-size: 10px !important;
                }
                
                #ban-email-modal > div > div:nth-child(2) button .material-symbols-outlined,
                #ban-ip-modal > div > div:nth-child(2) button .material-symbols-outlined,
                #whitelist-ip-modal > div > div:nth-child(2) button .material-symbols-outlined,
                #whitelist-email-modal > div > div:nth-child(2) button .material-symbols-outlined {
                    font-size: 14px !important;
                }
                
                /* Modal form content */
                #ban-email-single, #ban-email-bulk,
                #ban-ip-single, #ban-ip-bulk,
                #whitelist-ip-single, #whitelist-ip-bulk,
                #whitelist-email-single, #whitelist-email-bulk {
                    padding: 16px !important;
                }
                
                /* Modal form labels */
                #ban-email-modal label,
                #ban-ip-modal label,
                #whitelist-ip-modal label,
                #whitelist-email-modal label,
                #bad-word-modal label,
                #bad-website-modal label {
                    font-size: 10px !important;
                }
                
                /* Modal form inputs */
                #ban-email-modal input,
                #ban-email-modal textarea,
                #ban-ip-modal input,
                #ban-ip-modal textarea,
                #whitelist-ip-modal input,
                #whitelist-ip-modal textarea,
                #whitelist-email-modal input,
                #whitelist-email-modal textarea,
                #bad-word-modal input,
                #bad-word-modal textarea,
                #bad-word-modal select,
                #bad-website-modal input,
                #bad-website-modal textarea,
                #bad-website-modal select {
                    font-size: 11px !important;
                    padding: 8px 10px !important;
                }
                
                /* Modal info box */
                #ban-email-bulk > div > div:first-child,
                #ban-ip-bulk > div > div:first-child,
                #whitelist-ip-bulk > div > div:first-child,
                #whitelist-email-bulk > div > div:first-child {
                    padding: 10px !important;
                }
                
                #ban-email-bulk > div > div:first-child p,
                #ban-ip-bulk > div > div:first-child p,
                #whitelist-ip-bulk > div > div:first-child p,
                #whitelist-email-bulk > div > div:first-child p {
                    font-size: 10px !important;
                }
                
                /* Modal footer */
                #ban-email-single-footer, #ban-email-bulk-footer,
                #ban-ip-single-footer, #ban-ip-bulk-footer,
                #whitelist-ip-single-footer, #whitelist-ip-bulk-footer,
                #whitelist-email-single-footer, #whitelist-email-bulk-footer,
                #bad-word-single-footer, #bad-word-bulk-footer,
                #bad-website-single-footer, #bad-website-bulk-footer {
                    padding: 12px 16px !important;
                    flex-direction: column !important;
                    gap: 8px !important;
                }
                
                #ban-email-single-footer button, #ban-email-bulk-footer button,
                #ban-ip-single-footer button, #ban-ip-bulk-footer button,
                #whitelist-ip-single-footer button, #whitelist-ip-bulk-footer button,
                #whitelist-email-single-footer button, #whitelist-email-bulk-footer button,
                #bad-word-single-footer button, #bad-word-bulk-footer button,
                #bad-website-single-footer button, #bad-website-bulk-footer button {
                    width: 100% !important;
                    justify-content: center !important;
                    padding: 10px 16px !important;
                    font-size: 11px !important;
                }
                
                /* Modal form content for bad-word and bad-website */
                #bad-word-single, #bad-word-bulk,
                #bad-website-single, #bad-website-bulk {
                    padding: 16px !important;
                }
                
                /* Modal tabs for bad-word and bad-website */
                #bad-word-modal > div > div:nth-child(2) button,
                #bad-website-modal > div > div:nth-child(2) button {
                    padding: 10px !important;
                    font-size: 10px !important;
                }
                
                #bad-word-modal > div > div:nth-child(2) button .material-symbols-outlined,
                #bad-website-modal > div > div:nth-child(2) button .material-symbols-outlined {
                    font-size: 14px !important;
                }
                
                /* Modal info box for bad-word and bad-website */
                #bad-word-bulk > div > div:first-child,
                #bad-website-bulk > div > div:first-child {
                    padding: 10px !important;
                }
                
                #bad-word-bulk > div > div:first-child p,
                #bad-website-bulk > div > div:first-child p {
                    font-size: 10px !important;
                }
            }
            
            /* ============================================
               SMALL MOBILE TOOLS PAGE (< 480px)
               ============================================ */
            @media (max-width: 480px) {
                /* Hide icons in filter buttons on very small screens */
                .tools-filter-form .filter-buttons button span.material-symbols-outlined {
                    display: none !important;
                }
                
                /* Even smaller tabs */
                .tools-tabs-nav a {
                    padding: 0.5rem 0.375rem !important;
                    font-size: 9px !important;
                }
                
                /* Smaller header buttons */
                .tools-page-header > div:last-child button {
                    font-size: 9px !important;
                }
                
                .tools-page-header > div:last-child button span.material-symbols-outlined {
                    font-size: 12px !important;
                }
                
                /* Even smaller card labels */
                .tools-table-container table tbody td::before {
                    font-size: 9px !important;
                    min-width: 60px !important;
                }
            }

            /* ============================================
               MOBILE SETTINGS PAGES STYLES
               ============================================ */
            @media (max-width: 768px) {
                /* ----- MOBILE SETTINGS CONTAINER ----- */
                .settings-container {
                    border-radius: 6px !important;
                }

                /* ----- MOBILE SETTINGS PAGE HEADER ----- */
                .settings-page-header {
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    gap: 0.75rem !important;
                    padding: 1rem !important;
                }
                
                .settings-page-header h2 {
                    font-size: 14px !important;
                }
                
                .settings-page-header p {
                    font-size: 10px !important;
                }
                
                .settings-page-header > div:last-child {
                    width: 100% !important;
                    display: flex !important;
                    flex-wrap: wrap !important;
                    gap: 0.5rem !important;
                }
                
                .settings-page-header > div:last-child a,
                .settings-page-header > div:last-child button,
                .settings-page-header > div:last-child form {
                    flex: 1 !important;
                }
                
                .settings-page-header > div:last-child a,
                .settings-page-header > div:last-child button {
                    justify-content: center !important;
                    font-size: 10px !important;
                    padding: 0 0.5rem !important;
                }
                
                .settings-page-header > div:last-child form button {
                    width: 100% !important;
                    justify-content: center !important;
                }

                /* ----- MOBILE SETTINGS TABS ----- */
                .settings-tabs-nav {
                    overflow-x: auto !important;
                    scrollbar-width: none !important;
                    -ms-overflow-style: none !important;
                    padding: 0 0.5rem !important;
                }
                
                .settings-tabs-nav::-webkit-scrollbar {
                    display: none !important;
                }
                
                .settings-tabs-nav a {
                    padding: 0.75rem 0.5rem !important;
                    font-size: 10px !important;
                    white-space: nowrap !important;
                    flex-shrink: 0 !important;
                }
                
                .settings-tabs-nav a .material-symbols-outlined {
                    font-size: 14px !important;
                }

                /* ----- MOBILE SETTINGS TAB CONTENT ----- */
                .settings-tab-content {
                    padding: 1rem !important;
                }

                /* ----- MOBILE SETTINGS FILTER FORM ----- */
                .settings-filter-form {
                    padding: 0.75rem 0.5rem !important;
                }
                
                .settings-filter-form form {
                    flex-direction: column !important;
                    gap: 0.5rem !important;
                }
                
                .settings-filter-form .flex-1,
                .settings-filter-form input[type="text"] {
                    width: 100% !important;
                }
                
                .settings-filter-form input,
                .settings-filter-form select {
                    width: 100% !important;
                    font-size: 11px !important;
                    min-width: 100% !important;
                }
                
                /* Filter buttons wrapper - same row */
                .settings-filter-form .filter-buttons {
                    display: flex !important;
                    gap: 0.5rem !important;
                    width: 100% !important;
                }
                
                .settings-filter-form .filter-buttons button {
                    flex: 1 !important;
                    justify-content: center !important;
                    font-size: 10px !important;
                    padding: 0 0.75rem !important;
                }

                /* ----- MOBILE SETTINGS TABLE - CARD LAYOUT ----- */
                .settings-table-container {
                    padding: 0 0.5rem !important;
                }
                
                .settings-table-container .overflow-x-auto {
                    overflow-x: visible !important;
                    border: none !important;
                    box-shadow: none !important;
                }
                
                /* Hide table header on mobile */
                .settings-table-container table thead {
                    display: none !important;
                }
                
                .settings-table-container table {
                    display: block !important;
                    border: none !important;
                }
                
                .settings-table-container table tbody {
                    display: flex !important;
                    flex-direction: column !important;
                    gap: 0.75rem !important;
                }
                
                /* Each row becomes a card */
                .settings-table-container table tbody tr {
                    display: flex !important;
                    flex-direction: column !important;
                    background: white !important;
                    border: 1px solid #e5e7eb !important;
                    border-radius: 8px !important;
                    padding: 0.75rem !important;
                    gap: 0.5rem !important;
                }
                
                .settings-table-container table tbody tr:hover {
                    background: #f9fafb !important;
                }
                
                /* Each cell becomes a row with label */
                .settings-table-container table tbody td {
                    display: flex !important;
                    justify-content: space-between !important;
                    align-items: center !important;
                    padding: 0.25rem 0 !important;
                    border: none !important;
                    white-space: normal !important;
                }
                
                .settings-table-container table tbody td::before {
                    font-weight: 600 !important;
                    font-size: 10px !important;
                    color: #6b7280 !important;
                    font-family: Poppins, sans-serif !important;
                    min-width: 80px !important;
                    flex-shrink: 0 !important;
                }
                
                /* Show ALL columns on mobile - no hiding */
                .settings-table-container table tbody td {
                    display: flex !important;
                }
                
                /* Cell content styling */
                .settings-table-container table tbody td .text-sm,
                .settings-table-container table tbody td .text-xs {
                    font-size: 11px !important;
                    text-align: right !important;
                }
                
                /* User avatar in table */
                .settings-table-container table tbody td .w-8 {
                    width: 24px !important;
                    height: 24px !important;
                }
                
                /* Status badges */
                .settings-table-container table tbody td span.inline-flex {
                    font-size: 9px !important;
                    padding: 0.25rem 0.5rem !important;
                }
                
                /* Actions row - full width at bottom */
                .settings-table-container table tbody td:last-child {
                    border-top: 1px solid #e5e7eb !important;
                    padding-top: 0.75rem !important;
                    margin-top: 0.25rem !important;
                    justify-content: flex-end !important;
                }
                
                .settings-table-container table tbody td:last-child::before {
                    display: none !important;
                }
                
                /* Empty state */
                .settings-table-container table tbody tr td[colspan] {
                    display: block !important;
                    text-align: center !important;
                    padding: 2rem 1rem !important;
                }
                
                .settings-table-container table tbody tr td[colspan]::before {
                    display: none !important;
                }

                /* ----- MOBILE SETTINGS STATS GRID ----- */
                .settings-stats-grid {
                    grid-template-columns: repeat(2, 1fr) !important;
                    gap: 0.5rem !important;
                }
                
                .settings-stats-grid > div {
                    padding: 0.75rem !important;
                }
                
                .settings-stats-grid > div .material-symbols-outlined {
                    font-size: 16px !important;
                }
                
                .settings-stats-grid > div .text-xs {
                    font-size: 9px !important;
                }
                
                .settings-stats-grid > div .text-xl,
                .settings-stats-grid > div .text-lg {
                    font-size: 14px !important;
                }
                
                .settings-stats-grid > div .text-sm {
                    font-size: 10px !important;
                }

                /* ----- MOBILE ACTIVITY LOG TABLE - CARD LAYOUT ----- */
                .settings-tab-content table thead {
                    display: none !important;
                }
                
                .settings-tab-content table {
                    display: block !important;
                    border: none !important;
                }
                
                .settings-tab-content table tbody {
                    display: flex !important;
                    flex-direction: column !important;
                    gap: 0.75rem !important;
                }
                
                .settings-tab-content table tbody tr {
                    display: flex !important;
                    flex-direction: column !important;
                    background: white !important;
                    border: 1px solid #e5e7eb !important;
                    border-radius: 8px !important;
                    padding: 0.75rem !important;
                    gap: 0.5rem !important;
                }
                
                .settings-tab-content table tbody td {
                    display: flex !important;
                    justify-content: space-between !important;
                    align-items: center !important;
                    padding: 0.25rem 0 !important;
                    border: none !important;
                }
                
                .settings-tab-content table tbody td::before {
                    font-weight: 600 !important;
                    font-size: 10px !important;
                    color: #6b7280 !important;
                    font-family: Poppins, sans-serif !important;
                    min-width: 70px !important;
                    flex-shrink: 0 !important;
                }
                
                .settings-tab-content table tbody td .w-7 {
                    width: 20px !important;
                    height: 20px !important;
                }
                
                .settings-tab-content table tbody td .w-7 .material-symbols-outlined {
                    font-size: 10px !important;
                }
                
                /* Actions row */
                .settings-tab-content table tbody td:last-child {
                    border-top: 1px solid #e5e7eb !important;
                    padding-top: 0.75rem !important;
                    margin-top: 0.25rem !important;
                    justify-content: flex-end !important;
                }
                
                .settings-tab-content table tbody td:last-child::before {
                    display: none !important;
                }
                
                /* Empty state */
                .settings-tab-content table tbody tr td[colspan] {
                    display: block !important;
                    text-align: center !important;
                }
                
                .settings-tab-content table tbody tr td[colspan]::before {
                    display: none !important;
                }

                /* ----- MOBILE AUDIT LOG TIMELINE ----- */
                .settings-tab-content .relative.ml-4.pl-8 {
                    margin-left: 0.5rem !important;
                    padding-left: 1.5rem !important;
                }
                
                .settings-tab-content .relative.ml-4.pl-8 > div > div {
                    padding: 0.75rem !important;
                }
                
                .settings-tab-content .relative.ml-4.pl-8 .grid {
                    grid-template-columns: repeat(2, 1fr) !important;
                    gap: 0.5rem !important;
                }
                
                .settings-tab-content .relative.ml-4.pl-8 .grid > div .text-xs {
                    font-size: 9px !important;
                }
                
                .settings-tab-content .relative.ml-4.pl-8 .w-6 {
                    width: 18px !important;
                    height: 18px !important;
                }
                
                .settings-tab-content .relative.ml-4.pl-8 .w-8 {
                    width: 24px !important;
                    height: 24px !important;
                }

                /* ----- MOBILE SETTINGS MODALS ----- */
                #lock-modal > div > div:last-child,
                #unlock-modal > div > div:last-child,
                #suspend-modal > div > div:last-child,
                #unsuspend-modal > div > div:last-child,
                #clear-modal > div > div:last-child,
                #clear-audit-modal > div > div:last-child {
                    max-width: calc(100vw - 32px) !important;
                    margin: 16px !important;
                    padding: 1rem !important;
                }
                
                #lock-modal h3,
                #unlock-modal h3,
                #suspend-modal h3,
                #unsuspend-modal h3,
                #clear-modal h3,
                #clear-audit-modal h3 {
                    font-size: 14px !important;
                }
                
                #lock-modal p,
                #unlock-modal p,
                #suspend-modal p,
                #unsuspend-modal p,
                #clear-modal p,
                #clear-audit-modal p {
                    font-size: 11px !important;
                }
                
                #lock-modal .w-10,
                #unlock-modal .w-10,
                #suspend-modal .w-10,
                #unsuspend-modal .w-10,
                #clear-modal .w-10,
                #clear-audit-modal .w-10 {
                    width: 32px !important;
                    height: 32px !important;
                }
                
                #lock-modal button,
                #unlock-modal button,
                #suspend-modal button,
                #unsuspend-modal button,
                #clear-modal button,
                #clear-audit-modal button {
                    font-size: 10px !important;
                    padding: 0.5rem 1rem !important;
                }
                
                #suspend-modal input {
                    font-size: 11px !important;
                }
            }
            
            /* ============================================
               SMALL MOBILE SETTINGS PAGES (< 480px)
               ============================================ */
            @media (max-width: 480px) {
                /* Hide icons in filter buttons on very small screens */
                .settings-filter-form .filter-buttons button span.material-symbols-outlined {
                    display: none !important;
                }
                
                /* Even smaller tabs */
                .settings-tabs-nav a {
                    padding: 0.5rem 0.375rem !important;
                    font-size: 9px !important;
                }
                
                .settings-tabs-nav a .material-symbols-outlined {
                    font-size: 12px !important;
                }
                
                /* Smaller header buttons */
                .settings-page-header > div:last-child a,
                .settings-page-header > div:last-child button {
                    font-size: 9px !important;
                }
                
                .settings-page-header > div:last-child .material-symbols-outlined {
                    font-size: 12px !important;
                }
                
                /* Stats grid - single column on very small screens */
                .settings-stats-grid {
                    grid-template-columns: 1fr !important;
                }
                
                /* Even smaller card labels */
                .settings-table-container table tbody td::before {
                    font-size: 9px !important;
                    min-width: 70px !important;
                }
                
                /* Audit timeline - even smaller */
                .settings-tab-content .relative.ml-4.pl-8 .grid {
                    grid-template-columns: 1fr !important;
                }
            }

            /* ============================================
               MOBILE INTEGRATIONS WEATHER PAGE STYLES
               ============================================ */
            @media (max-width: 768px) {
                /* ----- MOBILE WEATHER INTEGRATION CONTENT ----- */
                .weather-integration-content {
                    gap: 1rem !important;
                }
                
                /* Weather status card */
                .weather-status-card {
                    padding: 0.75rem !important;
                }
                
                .weather-status-card > div {
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    gap: 0.75rem !important;
                }
                
                .weather-status-card .w-10 {
                    width: 32px !important;
                    height: 32px !important;
                }
                
                .weather-status-card .w-10 .material-symbols-outlined {
                    font-size: 16px !important;
                }
                
                .weather-status-card h3 {
                    font-size: 12px !important;
                }
                
                .weather-status-card p {
                    font-size: 10px !important;
                }
                
                .weather-status-card span.inline-flex {
                    font-size: 10px !important;
                    padding: 0.25rem 0.5rem !important;
                }
                
                /* Weather info box */
                .weather-info-box {
                    padding: 0.75rem !important;
                }
                
                .weather-info-box .material-symbols-outlined {
                    font-size: 16px !important;
                }
                
                .weather-info-box p {
                    font-size: 10px !important;
                }
                
                /* Weather form */
                .weather-integration-content form label {
                    font-size: 10px !important;
                }
                
                .weather-integration-content form input,
                .weather-integration-content form select {
                    font-size: 11px !important;
                    min-height: 32px !important;
                }
                
                .weather-integration-content form p.text-xs {
                    font-size: 9px !important;
                }
                
                /* Weather form grid - stack on mobile */
                .weather-form-grid {
                    grid-template-columns: 1fr !important;
                    gap: 0.75rem !important;
                }
                
                /* Weather action buttons */
                .weather-action-buttons {
                    flex-direction: column !important;
                    gap: 0.5rem !important;
                    padding-top: 1rem !important;
                    margin-top: 1rem !important;
                }
                
                .weather-action-buttons button,
                .weather-action-buttons span {
                    width: 100% !important;
                    justify-content: center !important;
                    font-size: 11px !important;
                    min-height: 36px !important;
                }
                
                /* Weather test modal */
                #weatherTestModal > div {
                    width: calc(100% - 32px) !important;
                    max-width: 100% !important;
                    margin: 16px !important;
                    max-height: calc(100vh - 32px) !important;
                }
                
                #weatherTestModal .px-6.py-4 {
                    padding: 12px 16px !important;
                }
                
                #weatherTestModal h3 {
                    font-size: 12px !important;
                }
                
                #weatherTestModal .w-10 {
                    width: 28px !important;
                    height: 28px !important;
                }
                
                #weatherTestModal .w-10 .material-symbols-outlined {
                    font-size: 16px !important;
                }
                
                #weatherTestModal .p-6 {
                    padding: 1rem !important;
                }
                
                /* Weather test result */
                #weatherTestResult .text-6xl {
                    font-size: 48px !important;
                }
                
                #weatherTestResult .text-3xl {
                    font-size: 24px !important;
                }
                
                #weatherTestResult .text-lg {
                    font-size: 14px !important;
                }
                
                #weatherTestResult .grid {
                    grid-template-columns: 1fr 1fr !important;
                    gap: 0.5rem !important;
                }
                
                #weatherTestResult .grid > div {
                    padding: 0.75rem !important;
                }
                
                #weatherTestResult .grid > div .text-xs {
                    font-size: 9px !important;
                }
                
                #weatherTestResult .grid > div .text-lg {
                    font-size: 12px !important;
                }
                
                #weatherTestResult .grid > div .material-symbols-outlined {
                    font-size: 16px !important;
                }
            }
            
            /* ============================================
               SMALL MOBILE WEATHER (< 480px)
               ============================================ */
            @media (max-width: 480px) {
                #weatherTestResult .grid {
                    grid-template-columns: 1fr !important;
                }
                
                #weatherTestResult .text-6xl {
                    font-size: 36px !important;
                }
                
                #weatherTestResult .text-3xl {
                    font-size: 20px !important;
                }
            }

            /* ============================================
               MOBILE INTEGRATIONS SPAM PAGE STYLES
               ============================================ */
            @media (max-width: 768px) {
                /* ----- MOBILE SPAM INTEGRATION CONTENT ----- */
                .spam-integration-content {
                    gap: 1rem !important;
                }
                
                /* Spam service cards */
                .spam-service-card {
                    padding: 0.75rem !important;
                }
                
                /* Service card header */
                .spam-service-card > div:first-child {
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    gap: 0.75rem !important;
                }
                
                .spam-service-card > div:first-child > div:first-child {
                    display: flex !important;
                    align-items: center !important;
                    gap: 0.75rem !important;
                }
                
                .spam-service-card .w-10 {
                    width: 32px !important;
                    height: 32px !important;
                }
                
                .spam-service-card .w-10 .material-symbols-outlined {
                    font-size: 16px !important;
                }
                
                .spam-service-card h3 {
                    font-size: 12px !important;
                }
                
                .spam-service-card p {
                    font-size: 10px !important;
                }
                
                /* Toggle switch positioning */
                .spam-service-card > div:first-child > label {
                    align-self: flex-start !important;
                }
                
                /* Spam service grids - stack on mobile */
                .spam-service-grid {
                    grid-template-columns: 1fr !important;
                    gap: 0.75rem !important;
                }
                
                .spam-service-grid-3 {
                    grid-template-columns: 1fr !important;
                    gap: 0.75rem !important;
                }
                
                .spam-service-grid label,
                .spam-service-grid-3 label {
                    font-size: 10px !important;
                }
                
                .spam-service-grid input,
                .spam-service-grid-3 input {
                    font-size: 11px !important;
                    min-height: 32px !important;
                }
                
                .spam-service-grid p,
                .spam-service-grid-3 p {
                    font-size: 9px !important;
                }
                
                .spam-service-grid a,
                .spam-service-grid-3 a {
                    font-size: 10px !important;
                }
                
                .spam-service-grid button,
                .spam-service-grid-3 button {
                    width: 100% !important;
                    justify-content: center !important;
                    font-size: 10px !important;
                }
                
                /* Free service info box */
                .spam-service-card .bg-green-50 {
                    padding: 0.5rem !important;
                }
                
                .spam-service-card .bg-green-50 span {
                    font-size: 10px !important;
                }
                
                .spam-service-card .bg-green-50 .material-symbols-outlined {
                    font-size: 14px !important;
                }
                
                /* Auto-check settings grid */
                .spam-autocheck-grid {
                    grid-template-columns: 1fr !important;
                    gap: 0.5rem !important;
                }
                
                .spam-autocheck-grid label {
                    padding: 0.75rem !important;
                }
                
                .spam-autocheck-grid label span {
                    font-size: 10px !important;
                }
                
                .spam-autocheck-grid label p {
                    font-size: 9px !important;
                }
                
                .spam-autocheck-grid label input {
                    width: 16px !important;
                    height: 16px !important;
                }
                
                /* Spam info box */
                .spam-info-box {
                    padding: 0.75rem !important;
                }
                
                .spam-info-box .material-symbols-outlined {
                    font-size: 16px !important;
                }
                
                .spam-info-box h4 {
                    font-size: 11px !important;
                }
                
                .spam-info-box p,
                .spam-info-box li {
                    font-size: 10px !important;
                }
                
                /* Spam save button */
                .spam-save-button {
                    justify-content: stretch !important;
                }
                
                .spam-save-button button,
                .spam-save-button span {
                    width: 100% !important;
                    justify-content: center !important;
                    font-size: 11px !important;
                }
                
                /* Spam test modal */
                #spamTestModal > div {
                    width: calc(100% - 32px) !important;
                    max-width: 100% !important;
                    margin: 16px !important;
                    max-height: calc(100vh - 32px) !important;
                }
                
                #spamTestModal .px-6.py-4 {
                    padding: 12px 16px !important;
                }
                
                #spamTestModal h3 {
                    font-size: 12px !important;
                }
                
                #spamTestModal .p-6 {
                    padding: 1rem !important;
                }
                
                #spamTestModal label {
                    font-size: 10px !important;
                }
                
                #spamTestModal input {
                    font-size: 11px !important;
                }
                
                #spamTestModal p {
                    font-size: 9px !important;
                }
                
                #spamTestModal .px-6.py-4.border-t {
                    padding: 12px 16px !important;
                    flex-direction: column !important;
                    gap: 0.5rem !important;
                }
                
                #spamTestModal .px-6.py-4.border-t button {
                    width: 100% !important;
                    justify-content: center !important;
                }
                
                /* Test result section */
                #testResultSection .bg-gray-50 > div {
                    font-size: 10px !important;
                }
            }
            
            /* ============================================
               SMALL MOBILE SPAM (< 480px)
               ============================================ */
            @media (max-width: 480px) {
                .spam-service-card .w-10 {
                    width: 28px !important;
                    height: 28px !important;
                }
                
                .spam-service-card .w-10 .material-symbols-outlined {
                    font-size: 14px !important;
                }
                
                .spam-autocheck-grid label {
                    padding: 0.5rem !important;
                    gap: 0.5rem !important;
                }
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
            <div x-show="$store.mobileMenu?.open" 
                 @click="$store.mobileMenu.open = false"
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
