@extends('layouts.app')

@section('title', 'Activity Logs')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Platform Settings'],
        ['label' => 'Activity Logs', 'active' => true]
    ]" />
@endsection

@section('content')
<div class="bg-white border border-gray-200 settings-container">
    <!-- Page Header -->
    <div class="px-6 py-4 flex items-center justify-between settings-page-header">
        <div>
            <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Activity Logs</h2>
            <p class="text-xs text-gray-500 mt-0.5">Monitor system activities and user actions</p>
        </div>
        <div class="flex items-center gap-2">
            @if($currentTab === 'activity')
                @if($canExport ?? false)
                <form action="{{ route('settings.activity-logs.export') }}" method="GET" class="inline">
                    <input type="hidden" name="tab" value="{{ $currentTab }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                    <input type="hidden" name="action" value="{{ request('action') }}">
                    <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                    <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                    <button type="submit" class="inline-flex items-center gap-2 px-3 text-white text-xs font-medium rounded transition" style="min-height: 32px; background-color: #10b981;">
                        <span class="material-symbols-outlined" style="font-size: 14px;">download</span>
                        EXPORT
                    </button>
                </form>
                @endif
                @if($canDelete ?? false)
                <button type="button" onclick="showClearModal()" class="inline-flex items-center gap-2 px-3 text-white text-xs font-medium rounded transition" style="min-height: 32px; background-color: #dc2626;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">delete_sweep</span>
                    CLEAR ALL
                </button>
                @endif
            @elseif($currentTab === 'audit')
                @if($canExportAudit ?? false)
                <button type="button" onclick="exportAuditLogs()" class="inline-flex items-center gap-2 px-3 text-white text-xs font-medium rounded transition" style="min-height: 32px; background-color: #10b981;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">download</span>
                    EXPORT PDF
                </button>
                @endif
                @if($canDeleteAudit ?? false)
                <button type="button" onclick="showClearAuditModal()" class="inline-flex items-center gap-2 px-3 text-white text-xs font-medium rounded transition" style="min-height: 32px; background-color: #dc2626;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">delete_sweep</span>
                    CLEAR ALL
                </button>
                @endif
            @endif
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="border-t border-gray-200">
        <nav class="flex px-6 settings-tabs-nav" aria-label="Tabs">
            @foreach($tabs as $key => $label)
                <a href="{{ route('settings.activity-logs', ['tab' => $key]) }}"
                   class="px-4 py-3 text-xs font-medium border-b-2 {{ $currentTab === $key ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                   style="font-family: Poppins, sans-serif;">
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="px-6 py-4 pb-6 border-t border-gray-200 settings-tab-content">
        @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded text-xs flex items-center gap-2" style="font-family: Poppins, sans-serif;">
                <span class="material-symbols-outlined" style="font-size: 16px;">check_circle</span>
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded text-xs flex items-center gap-2" style="font-family: Poppins, sans-serif;">
                <span class="material-symbols-outlined" style="font-size: 16px;">error</span>
                {{ session('error') }}
            </div>
        @endif

        @if($currentTab === 'activity')
            <!-- Activity Stats Cards -->
            <div class="grid grid-cols-4 gap-4 mb-4 settings-stats-grid">
                <div class="rounded-lg p-4 border-l-4 border-blue-500" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="material-symbols-outlined text-blue-600" style="font-size: 18px;">history</span>
                        <span class="text-xs font-medium text-blue-600" style="font-family: Poppins, sans-serif;">Total Logs</span>
                    </div>
                    <p class="text-xl font-bold text-blue-700" style="font-family: Poppins, sans-serif;">{{ number_format($activityStats['total_logs'] ?? 0) }}</p>
                </div>
                <div class="rounded-lg p-4 border-l-4 border-green-500" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="material-symbols-outlined text-green-600" style="font-size: 18px;">storage</span>
                        <span class="text-xs font-medium text-green-600" style="font-family: Poppins, sans-serif;">Storage Used</span>
                    </div>
                    <p class="text-xl font-bold text-green-700" style="font-family: Poppins, sans-serif;">{{ $activityStats['table_size'] ?? '0 KB' }}</p>
                </div>
                <div class="rounded-lg p-4 border-l-4 border-amber-500" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="material-symbols-outlined text-amber-600" style="font-size: 18px;">hard_drive</span>
                        <span class="text-xs font-medium text-amber-600" style="font-family: Poppins, sans-serif;">Max Storage</span>
                    </div>
                    <p class="text-xl font-bold text-amber-700" style="font-family: Poppins, sans-serif;">{{ $activityStats['max_size'] ?? '3 MB' }}</p>
                </div>
                <div class="rounded-lg p-4 border-l-4 border-purple-500" style="background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="material-symbols-outlined text-purple-600" style="font-size: 18px;">schedule</span>
                        <span class="text-xs font-medium text-purple-600" style="font-family: Poppins, sans-serif;">Oldest Log</span>
                    </div>
                    <p class="text-xl font-bold text-purple-700" style="font-family: Poppins, sans-serif;">{{ $activityStats['oldest_log'] ? $activityStats['oldest_log']->diffForHumans() : 'N/A' }}</p>
                </div>
            </div>

            <!-- Activity Log Filter Form -->
            <div class="mb-4 settings-filter-form">
                <form action="{{ route('settings.activity-logs') }}" method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="tab" value="{{ $currentTab }}">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search activity..." 
                           class="flex-1 px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                           style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                    <select name="user_id" class="px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500" style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                        <option value="">All Users</option>
                        @foreach($users ?? [] as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <select name="action" class="px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500" style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                        <option value="">All Actions</option>
                        @foreach($actions ?? [] as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $action)) }}</option>
                        @endforeach
                    </select>
                    <select name="module" class="px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500" style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                        <option value="">All Modules</option>
                        @foreach($modules ?? [] as $module)
                            <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>{{ ucfirst($module) }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                           style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                           style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                    <div class="filter-buttons">
                        <button type="submit" class="shrink-0 inline-flex items-center gap-2 px-3 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition" style="min-height: 32px;">
                            <span class="material-symbols-outlined" style="font-size: 14px;">search</span>
                            SEARCH
                        </button>
                        <button type="button" onclick="window.location.href='{{ route('settings.activity-logs', ['tab' => $currentTab]) }}'" class="shrink-0 inline-flex items-center gap-2 px-3 text-white text-xs font-medium rounded transition" style="min-height: 32px; background-color: #dc2626;">
                            <span class="material-symbols-outlined" style="font-size: 14px;">refresh</span>
                            RESET
                        </button>
                    </div>
                </form>
            </div>
            <!-- Activity Log Table -->
            <x-ui.data-table
                :headers="[
                    ['label' => 'User', 'align' => 'text-left', 'width' => 'w-40'],
                    ['label' => 'Action', 'align' => 'text-center', 'width' => 'w-28'],
                    ['label' => 'Module', 'align' => 'text-center', 'width' => 'w-28'],
                    ['label' => 'Description', 'align' => 'text-left'],
                    ['label' => 'IP Address', 'align' => 'text-center', 'width' => 'w-32'],
                    ['label' => 'Date/Time', 'align' => 'text-center', 'width' => 'w-40'],
                ]"
                :actions="$canDelete ?? false"
                empty-message="No activity logs found."
            >
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            @if($log->user)
                            <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-medium flex-shrink-0">
                                {{ strtoupper(substr($log->user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-medium text-gray-900 truncate" style="font-family: Poppins, sans-serif;">{{ $log->user->name }}</div>
                                <div class="text-xs text-gray-400 truncate" style="font-size: 10px;">{{ $log->user->email }}</div>
                            </div>
                            @else
                            <div class="w-7 h-7 bg-gray-400 rounded-full flex items-center justify-center text-white text-xs font-medium flex-shrink-0">
                                <span class="material-symbols-outlined" style="font-size: 14px;">computer</span>
                            </div>
                            <span class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">System</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded {{ $log->action_color }}" style="font-size: 10px;">
                            <span class="material-symbols-outlined" style="font-size: 12px;">{{ $log->action_icon }}</span>
                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="text-xs text-gray-600" style="font-family: Poppins, sans-serif;">{{ ucfirst($log->module) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs text-gray-700" style="font-family: Poppins, sans-serif;">{{ Str::limit($log->description, 80) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="text-xs text-gray-500 font-mono" style="font-size: 10px;">{{ $log->ip_address ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ format_date($log->created_at) }}</div>
                        <div class="text-xs text-gray-400" style="font-size: 10px;">{{ format_time($log->created_at) }}</div>
                    </td>
                    @if($canDelete ?? false)
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <x-ui.action-buttons
                            :delete-onclick="'showDeleteModal(\'' . route('settings.activity-logs.destroy', $log) . '\')'"
                        />
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ ($canDelete ?? false) ? 7 : 6 }}" class="px-6 py-8 text-center text-gray-500 text-sm">
                        <div class="flex flex-col items-center gap-2">
                            <span class="material-symbols-outlined" style="font-size: 48px; color: #d1d5db;">history</span>
                            <p style="font-family: Poppins, sans-serif;">No activity logs found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </x-ui.data-table>
        @elseif($currentTab === 'audit')
            <!-- Audit Log Stats -->
            @if(isset($auditStats))
            <div class="mb-6 grid grid-cols-2 md:grid-cols-5 gap-3 settings-stats-grid">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-3 border border-blue-200">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600" style="font-size: 18px;">visibility</span>
                        <span class="text-xs text-blue-600 font-medium" style="font-family: Poppins, sans-serif;">Total Views</span>
                    </div>
                    <p class="text-lg font-bold text-blue-800 mt-1" style="font-family: Poppins, sans-serif;">{{ number_format($auditStats['total_logs']) }}</p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-3 border border-green-200">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-600" style="font-size: 18px;">storage</span>
                        <span class="text-xs text-green-600 font-medium" style="font-family: Poppins, sans-serif;">Storage Used</span>
                    </div>
                    <p class="text-lg font-bold text-green-800 mt-1" style="font-family: Poppins, sans-serif;">{{ $auditStats['table_size'] }}</p>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-3 border border-purple-200">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-600" style="font-size: 18px;">database</span>
                        <span class="text-xs text-purple-600 font-medium" style="font-family: Poppins, sans-serif;">Max Storage</span>
                    </div>
                    <p class="text-lg font-bold text-purple-800 mt-1" style="font-family: Poppins, sans-serif;">{{ $auditStats['max_size'] }}</p>
                </div>
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-3 border border-orange-200">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-orange-600" style="font-size: 18px;">schedule</span>
                        <span class="text-xs text-orange-600 font-medium" style="font-family: Poppins, sans-serif;">Retention</span>
                    </div>
                    <p class="text-lg font-bold text-orange-800 mt-1" style="font-family: Poppins, sans-serif;">{{ $auditStats['max_age_days'] }} Days</p>
                </div>
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-3 border border-gray-200">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-600" style="font-size: 18px;">history</span>
                        <span class="text-xs text-gray-600 font-medium" style="font-family: Poppins, sans-serif;">Oldest Log</span>
                    </div>
                    <p class="text-sm font-bold text-gray-800 mt-1" style="font-family: Poppins, sans-serif;">
                        {{ $auditStats['oldest_log'] ? $auditStats['oldest_log']->diffForHumans() : 'N/A' }}
                    </p>
                </div>
            </div>
            @endif

            <!-- Audit Log Filter -->
            <div class="mb-4 settings-filter-form">
                <form action="{{ route('settings.activity-logs') }}" method="GET" class="flex items-center gap-2" id="audit-filter-form">
                    <input type="hidden" name="tab" value="audit">
                    <select name="user_id" class="px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500" style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px; min-width: 150px;">
                        <option value="">All Users</option>
                        @foreach($users ?? [] as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <input type="datetime-local" name="datetime_from" value="{{ request('datetime_from') }}" 
                           class="px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                           style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;"
                           title="Start Date & Time">
                    <input type="datetime-local" name="datetime_to" value="{{ request('datetime_to') }}" 
                           class="px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                           style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;"
                           title="End Date & Time">
                    <div class="filter-buttons">
                        <button type="submit" class="shrink-0 inline-flex items-center gap-2 px-3 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition" style="min-height: 32px;">
                            <span class="material-symbols-outlined" style="font-size: 14px;">search</span>
                            SEARCH
                        </button>
                        <button type="button" onclick="window.location.href='{{ route('settings.activity-logs', ['tab' => 'audit']) }}'" class="shrink-0 inline-flex items-center gap-2 px-3 text-white text-xs font-medium rounded transition" style="min-height: 32px; background-color: #dc2626;">
                            <span class="material-symbols-outlined" style="font-size: 14px;">refresh</span>
                            RESET
                        </button>
                    </div>
                </form>
            </div>

            <!-- Audit Log Timeline -->
            @if(!($hasFilter ?? false))
            <!-- No filter applied - show instruction -->
            <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                <span class="material-symbols-outlined" style="font-size: 64px; color: #9ca3af;">filter_alt</span>
                <h3 class="mt-4 text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Select Filter to View Audit Logs</h3>
                <p class="mt-2 text-xs text-gray-500" style="font-family: Poppins, sans-serif;">
                    Please select a user or date range and click SEARCH to view audit logs.
                </p>
            </div>
            @elseif(isset($groupedLogs) && $groupedLogs->count() > 0)
            <div class="relative">
                @foreach($groupedLogs as $date => $logsForDate)
                    @php
                        $dateObj = \Carbon\Carbon::parse($date);
                        $isToday = $dateObj->isToday();
                        $isYesterday = $dateObj->isYesterday();
                        $dateLabel = $isToday ? 'TODAY' : ($isYesterday ? 'YESTERDAY' : $dateObj->format('d M Y'));
                    @endphp
                    
                    <!-- Date Header -->
                    <div class="flex items-center gap-3 mb-4 {{ !$loop->first ? 'mt-8' : '' }}">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $isToday ? 'bg-blue-600' : ($isYesterday ? 'bg-purple-600' : 'bg-gray-500') }} text-white">
                            <span class="material-symbols-outlined" style="font-size: 16px;">calendar_today</span>
                        </div>
                        <h3 class="text-sm font-bold {{ $isToday ? 'text-blue-600' : ($isYesterday ? 'text-purple-600' : 'text-gray-600') }}" style="font-family: Poppins, sans-serif;">
                            {{ $dateLabel }}
                        </h3>
                        <span class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">{{ $logsForDate->count() }} activities</span>
                    </div>

                    <!-- Timeline Container -->
                    <div class="relative ml-4 pl-8 border-l-2 border-gray-200">
                        @foreach($logsForDate as $log)
                        <div class="relative mb-4 last:mb-0">
                            <!-- Timeline Dot -->
                            <div class="absolute -left-[25px] top-3 w-3 h-3 rounded-full {{ $log->action_color }} ring-4 ring-white"></div>
                            
                            <!-- Timeline Connector -->
                            <div class="absolute -left-[20px] top-6 w-0.5 h-full bg-gray-200 {{ $loop->last ? 'hidden' : '' }}"></div>
                            
                            <!-- Card -->
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                                <!-- Card Header -->
                                <div class="px-4 py-3 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <!-- Action Icon -->
                                        <div class="flex items-center justify-center w-8 h-8 rounded-lg {{ $log->action_color }}">
                                            <span class="material-symbols-outlined text-white" style="font-size: 16px;">{{ $log->action_icon }}</span>
                                        </div>
                                        <!-- Action & Module -->
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold text-gray-900 uppercase" style="font-family: Poppins, sans-serif;">{{ strtoupper($log->action) }}</span>
                                                <span class="text-gray-300">•</span>
                                                <span class="inline-flex items-center gap-1 text-xs text-gray-600" style="font-family: Poppins, sans-serif;">
                                                    <span class="material-symbols-outlined" style="font-size: 12px;">{{ $log->module_icon }}</span>
                                                    {{ ucfirst($log->module) }}
                                                </span>
                                            </div>
                                            @if($log->subject_name)
                                            <p class="text-xs text-gray-500 mt-0.5" style="font-family: Poppins, sans-serif;">
                                                {{ $log->subject_name }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- User Badge -->
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 text-xs font-medium rounded {{ $log->user_type_badge }}" style="font-family: Poppins, sans-serif;">
                                            {{ ucfirst($log->user_type ?? 'unknown') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Card Body -->
                                <div class="px-4 py-3">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <!-- User -->
                                        <div>
                                            <p class="text-xs text-gray-400 mb-1" style="font-family: Poppins, sans-serif;">User</p>
                                            <div class="flex items-center gap-2">
                                                @if($log->user)
                                                <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-medium flex-shrink-0">
                                                    {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                                </div>
                                                <span class="text-xs font-medium text-gray-900 truncate" style="font-family: Poppins, sans-serif;">{{ $log->user->name }}</span>
                                                @else
                                                <span class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">System</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Time -->
                                        <div>
                                            <p class="text-xs text-gray-400 mb-1" style="font-family: Poppins, sans-serif;">Time</p>
                                            <p class="text-xs font-medium text-gray-900" style="font-family: Poppins, sans-serif;">{{ $log->created_at->format('H:i:s') }}</p>
                                        </div>
                                        
                                        <!-- IP Address -->
                                        <div>
                                            <p class="text-xs text-gray-400 mb-1" style="font-family: Poppins, sans-serif;">IP Address</p>
                                            <p class="text-xs font-mono text-gray-700" style="font-size: 10px;">{{ $log->ip_address ?? '-' }}</p>
                                        </div>
                                        
                                        <!-- Response Time -->
                                        <div>
                                            <p class="text-xs text-gray-400 mb-1" style="font-family: Poppins, sans-serif;">Response</p>
                                            <p class="text-xs font-medium {{ $log->response_time_ms > 1000 ? 'text-red-600' : ($log->response_time_ms > 500 ? 'text-yellow-600' : 'text-green-600') }}" style="font-family: Poppins, sans-serif;">
                                                {{ $log->response_time_ms ?? 0 }}ms
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- URL -->
                                    <div class="mt-3 pt-3 border-t border-gray-100">
                                        <p class="text-xs text-gray-400 mb-1" style="font-family: Poppins, sans-serif;">URL</p>
                                        <p class="text-xs font-mono text-gray-600 truncate" style="font-size: 10px;" title="{{ $log->url }}">{{ $log->url }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if(isset($auditLogs) && $auditLogs->hasPages())
            <div class="mt-6">
                <x-ui.custom-pagination :paginator="$auditLogs" record-label="audit logs" tab-param="audit" />
            </div>
            @endif
            @else
            <!-- Filter applied but no results -->
            <div class="text-center py-12">
                <span class="material-symbols-outlined" style="font-size: 64px; color: #d1d5db;">search_off</span>
                <h3 class="mt-4 text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">No Results Found</h3>
                <p class="mt-2 text-xs text-gray-500" style="font-family: Poppins, sans-serif;">
                    No audit logs found for the selected filter. Try adjusting your search criteria.
                </p>
            </div>
            @endif
        @endif

        <!-- Pagination for Activity Log -->
        @if($currentTab === 'activity' && isset($logs))
        <div class="mt-4">
            <x-ui.custom-pagination :paginator="$logs" record-label="activity logs" :tab-param="$currentTab" />
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<x-modals.delete-confirmation 
    id="delete-modal"
    title="Delete Activity Log"
    message="Are you sure you want to delete this activity log entry? This action cannot be undone."
/>

<!-- Clear All Activity Modal -->
<div id="clear-modal" class="fixed inset-0 hidden overflow-y-auto" style="z-index: 100000;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="hideClearModal()"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-600" style="font-size: 20px;">delete_sweep</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Clear All Activity Logs</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4" style="font-family: Poppins, sans-serif;">
                Are you sure you want to clear ALL activity logs? This action cannot be undone and all log entries will be permanently deleted.
            </p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="hideClearModal()" class="px-4 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200" style="font-family: Poppins, sans-serif;">
                    Cancel
                </button>
                <form action="{{ route('settings.activity-logs.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="tab" value="{{ $currentTab }}">
                    <button type="submit" class="px-4 py-2 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700" style="font-family: Poppins, sans-serif;">
                        Clear All Logs
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Clear All Audit Modal -->
<div id="clear-audit-modal" class="fixed inset-0 hidden overflow-y-auto" style="z-index: 100000;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="hideClearAuditModal()"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-600" style="font-size: 20px;">delete_sweep</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Clear All Audit Logs</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4" style="font-family: Poppins, sans-serif;">
                Are you sure you want to clear ALL audit logs? This action cannot be undone and all audit entries will be permanently deleted.
            </p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="hideClearAuditModal()" class="px-4 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200" style="font-family: Poppins, sans-serif;">
                    Cancel
                </button>
                <form action="{{ route('settings.audit-logs.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700" style="font-family: Poppins, sans-serif;">
                        Clear All Logs
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showClearModal() {
    document.getElementById('clear-modal').classList.remove('hidden');
}
function hideClearModal() {
    document.getElementById('clear-modal').classList.add('hidden');
}
function showClearAuditModal() {
    document.getElementById('clear-audit-modal').classList.remove('hidden');
}
function hideClearAuditModal() {
    document.getElementById('clear-audit-modal').classList.add('hidden');
}

function exportAuditLogs() {
    // Get current filter values from the form
    const form = document.getElementById('audit-filter-form');
    const userId = form.querySelector('[name="user_id"]').value;
    const datetimeFrom = form.querySelector('[name="datetime_from"]').value;
    const datetimeTo = form.querySelector('[name="datetime_to"]').value;
    
    // Build export URL with filters
    let url = '{{ route("settings.audit-logs.export") }}?';
    const params = new URLSearchParams();
    
    if (userId) params.append('user_id', userId);
    if (datetimeFrom) params.append('datetime_from', datetimeFrom);
    if (datetimeTo) params.append('datetime_to', datetimeTo);
    
    url += params.toString();
    
    // Trigger download
    window.location.href = url;
}
</script>
@endpush
@endsection
