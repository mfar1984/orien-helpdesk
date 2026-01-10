@extends('layouts.app')

@section('title', 'Tools')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Tools', 'active' => true]
    ]" />
@endsection

@section('content')
<div class="bg-white border border-gray-200">
    <!-- Page Header -->
    <div class="px-6 py-4 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Tools</h2>
            <p class="text-xs text-gray-500 mt-0.5">Manage email and IP bans, whitelists</p>
        </div>
        <div class="flex items-center gap-2">
            @php
                $tabPermMap = [
                    'ban-email' => 'tools_ban_emails',
                    'ban-ip' => 'tools_ban_ips',
                    'whitelist-ip' => 'tools_whitelist_ips',
                    'whitelist-email' => 'tools_whitelist_emails',
                    'bad-word' => 'tools_bad_words',
                    'bad-website' => 'tools_bad_websites',
                ];
                $buttonLabels = [
                    'ban-email' => ['icon' => 'block', 'label' => 'BAN EMAIL'],
                    'ban-ip' => ['icon' => 'block', 'label' => 'BAN IP'],
                    'whitelist-ip' => ['icon' => 'verified_user', 'label' => 'WHITELIST IP'],
                    'whitelist-email' => ['icon' => 'verified_user', 'label' => 'WHITELIST EMAIL'],
                    'bad-word' => ['icon' => 'warning', 'label' => 'ADD BAD WORD'],
                    'bad-website' => ['icon' => 'dangerous', 'label' => 'ADD BAD WEBSITE'],
                ];
                $currentPerm = $tabPermMap[$currentTab] ?? 'tools_ban_emails';
                $btn = $buttonLabels[$currentTab] ?? ['icon' => 'add', 'label' => 'ENTRY'];
                $canCreate = auth()->user()->hasPermission($currentPerm . '.create');
                $canExport = auth()->user()->hasPermission($currentPerm . '.export');
            @endphp
            @if($canExport)
            <button type="button" onclick="exportData('{{ $currentTab }}')"
               class="inline-flex items-center gap-2 px-3 text-white text-xs font-medium rounded transition"
               style="min-height: 32px; background-color: #059669;">
                <span class="material-symbols-outlined" style="font-size: 14px;">download</span>
                EXPORT
            </button>
            @endif
            @if($canCreate)
            <button type="button" onclick="showCreateModal('{{ $currentTab }}')"
               class="inline-flex items-center gap-2 px-3 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition"
               style="min-height: 32px;">
                <span class="material-symbols-outlined" style="font-size: 14px;">{{ $btn['icon'] }}</span>
                {{ $btn['label'] }}
            </button>
            @endif
        </div>
    </div>

    <!-- Tabs Navigation (filtered by permission in controller) -->
    <div class="border-t border-gray-200">
        <nav class="flex px-6" aria-label="Tabs">
            @foreach($tabs as $key => $label)
                <a href="{{ route('tools.index', ['tab' => $key]) }}"
                   class="px-4 py-3 text-xs font-medium border-b-2 {{ $currentTab === $key ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                   style="font-family: Poppins, sans-serif;">
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="border-t border-gray-200">
        @if(session('success'))
        <div class="px-6 pt-4">
            <div class="px-4 py-3 bg-green-50 border border-green-200 rounded">
                <p class="text-xs text-green-800" style="font-family: Poppins, sans-serif;">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Filter Form -->
        <div class="px-6 py-3">
            <form action="{{ route('tools.index') }}" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="tab" value="{{ $currentTab }}">
                <div class="flex-1">
                    @php
                        $placeholder = 'Search ';
                        if (str_contains($currentTab, 'email')) {
                            $placeholder .= 'email address...';
                        } elseif ($currentTab === 'bad-word') {
                            $placeholder .= 'bad word...';
                        } elseif ($currentTab === 'bad-website') {
                            $placeholder .= 'website URL...';
                        } else {
                            $placeholder .= 'IP address...';
                        }
                    @endphp
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ $placeholder }}" 
                           class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                           style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                </div>
                <button type="submit" class="inline-flex items-center gap-2 px-3 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition" style="min-height: 32px;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">search</span>
                    SEARCH
                </button>
                <button type="button" onclick="window.location.href='{{ route('tools.index', ['tab' => $currentTab]) }}'" class="inline-flex items-center gap-2 px-3 text-white text-xs font-medium rounded transition" style="min-height: 32px; background-color: #dc2626;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">refresh</span>
                    RESET
                </button>
            </form>
        </div>

        <!-- Data Table -->
        <div class="px-6">
            <div class="overflow-x-auto shadow border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: Poppins, sans-serif; font-size: 11px;">
                                @if($currentTab === 'bad-word')
                                    Word / Pattern
                                @elseif($currentTab === 'bad-website')
                                    URL / Pattern
                                @elseif(str_contains($currentTab, 'email'))
                                    Email / Pattern
                                @else
                                    IP / Pattern
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: Poppins, sans-serif; font-size: 11px;">
                                Reason
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: Poppins, sans-serif; font-size: 11px;">
                                Added By
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: Poppins, sans-serif; font-size: 11px;">
                                Date Added
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: Poppins, sans-serif; font-size: 11px;">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if($currentTab === 'ban-email')
                            @forelse($data as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-900" style="font-family: monospace; font-size: 12px;">{{ $item->email }}</span>
                                        @if(str_contains($item->email, '*') || str_contains($item->email, '?'))
                                            <span class="inline-flex px-1.5 py-0.5 text-xs font-medium rounded bg-purple-100 text-purple-700">Wildcard</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ $item->reason }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs text-gray-500">{{ $item->addedBy->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs text-gray-500">{{ format_date($item->created_at) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <x-ui.action-buttons 
                                        :edit-onclick="auth()->user()->hasPermission('tools_ban_emails.edit') ? 'showEditModal(\'ban-email\', ' . $item->id . ', \'' . addslashes($item->email) . '\', \'' . addslashes($item->reason) . '\')' : null" 
                                        :delete-onclick="auth()->user()->hasPermission('tools_ban_emails.delete') ? 'showDeleteModal(\'' . route('tools.ban-email.destroy', $item->id) . '\')' : null" 
                                    />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No banned emails found.</td>
                            </tr>
                            @endforelse
                        @elseif($currentTab === 'ban-ip')
                            @forelse($data as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-900" style="font-family: monospace; font-size: 12px;">{{ $item->ip_address }}</span>
                                        @if(str_contains($item->ip_address, '*') || str_contains($item->ip_address, '?'))
                                            <span class="inline-flex px-1.5 py-0.5 text-xs font-medium rounded bg-purple-100 text-purple-700">Wildcard</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ $item->reason }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs text-gray-500">{{ $item->addedBy->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs text-gray-500">{{ format_date($item->created_at) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <x-ui.action-buttons 
                                        :edit-onclick="auth()->user()->hasPermission('tools_ban_ips.edit') ? 'showEditModal(\'ban-ip\', ' . $item->id . ', \'' . addslashes($item->ip_address) . '\', \'' . addslashes($item->reason) . '\')' : null" 
                                        :delete-onclick="auth()->user()->hasPermission('tools_ban_ips.delete') ? 'showDeleteModal(\'' . route('tools.ban-ip.destroy', $item->id) . '\')' : null" 
                                    />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No banned IPs found.</td>
                            </tr>
                            @endforelse
                        @elseif($currentTab === 'whitelist-ip')
                            @forelse($data as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-900" style="font-family: monospace; font-size: 12px;">{{ $item->ip_address }}</span>
                                        @if(str_contains($item->ip_address, '*') || str_contains($item->ip_address, '?'))
                                            <span class="inline-flex px-1.5 py-0.5 text-xs font-medium rounded bg-purple-100 text-purple-700">Wildcard</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ $item->reason }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs text-gray-500">{{ $item->addedBy->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs text-gray-500">{{ format_date($item->created_at) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <x-ui.action-buttons 
                                        :edit-onclick="auth()->user()->hasPermission('tools_whitelist_ips.edit') ? 'showEditModal(\'whitelist-ip\', ' . $item->id . ', \'' . addslashes($item->ip_address) . '\', \'' . addslashes($item->reason) . '\')' : null" 
                                        :delete-onclick="auth()->user()->hasPermission('tools_whitelist_ips.delete') ? 'showDeleteModal(\'' . route('tools.whitelist-ip.destroy', $item->id) . '\')' : null" 
                                    />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No whitelisted IPs found.</td>
                            </tr>
                            @endforelse
                        @elseif($currentTab === 'whitelist-email')
                            @forelse($data as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-900" style="font-family: monospace; font-size: 12px;">{{ $item->email }}</span>
                                        @if(str_contains($item->email, '*') || str_contains($item->email, '?'))
                                            <span class="inline-flex px-1.5 py-0.5 text-xs font-medium rounded bg-purple-100 text-purple-700">Wildcard</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ $item->reason }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs text-gray-500">{{ $item->addedBy->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs text-gray-500">{{ format_date($item->created_at) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <x-ui.action-buttons 
                                        :edit-onclick="auth()->user()->hasPermission('tools_whitelist_emails.edit') ? 'showEditModal(\'whitelist-email\', ' . $item->id . ', \'' . addslashes($item->email) . '\', \'' . addslashes($item->reason) . '\')' : null" 
                                        :delete-onclick="auth()->user()->hasPermission('tools_whitelist_emails.delete') ? 'showDeleteModal(\'' . route('tools.whitelist-email.destroy', $item->id) . '\')' : null" 
                                    />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No whitelisted emails found.</td>
                            </tr>
                            @endforelse
                        @elseif($currentTab === 'bad-word')
                            @forelse($data as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-900" style="font-family: monospace; font-size: 12px;">{{ $item->word }}</span>
                                        @if(str_contains($item->word, '*') || str_contains($item->word, '?'))
                                            <span class="inline-flex px-1.5 py-0.5 text-xs font-medium rounded bg-purple-100 text-purple-700">Wildcard</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ $item->reason ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs text-gray-500">{{ $item->addedBy->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex px-2 text-xs font-semibold rounded-full 
                                        @if($item->severity === 'high') bg-red-100 text-red-800
                                        @elseif($item->severity === 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst($item->severity) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <x-ui.action-buttons 
                                        :edit-onclick="auth()->user()->hasPermission('tools_bad_words.edit') ? 'showEditModal(\'bad-word\', ' . $item->id . ', \'' . addslashes($item->word) . '\', \'' . addslashes($item->reason ?? '') . '\', \'' . $item->severity . '\')' : null" 
                                        :delete-onclick="auth()->user()->hasPermission('tools_bad_words.delete') ? 'showDeleteModal(\'' . route('tools.bad-word.destroy', $item->id) . '\')' : null" 
                                    />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No bad words found.</td>
                            </tr>
                            @endforelse
                        @elseif($currentTab === 'bad-website')
                            @forelse($data as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-900" style="font-family: monospace; font-size: 12px;">{{ $item->url }}</span>
                                        @if(str_contains($item->url, '*') || str_contains($item->url, '?'))
                                            <span class="inline-flex px-1.5 py-0.5 text-xs font-medium rounded bg-purple-100 text-purple-700">Wildcard</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ $item->reason ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs text-gray-500">{{ $item->addedBy->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex px-2 text-xs font-semibold rounded-full 
                                        @if($item->severity === 'high') bg-red-100 text-red-800
                                        @elseif($item->severity === 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst($item->severity) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <x-ui.action-buttons 
                                        :edit-onclick="auth()->user()->hasPermission('tools_bad_websites.edit') ? 'showEditModal(\'bad-website\', ' . $item->id . ', \'' . addslashes($item->url) . '\', \'' . addslashes($item->reason ?? '') . '\', \'' . $item->severity . '\')' : null" 
                                        :delete-onclick="auth()->user()->hasPermission('tools_bad_websites.delete') ? 'showDeleteModal(\'' . route('tools.bad-website.destroy', $item->id) . '\')' : null" 
                                    />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No bad websites found.</td>
                            </tr>
                            @endforelse
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-3">
            <div class="flex items-center justify-between">
                <p class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">
                    @if($data->count() > 0)
                        Showing 1 to {{ $data->count() }} of {{ $data->count() }} entries
                    @else
                        No entries found
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Include Bad Word & Bad Website Modals -->
@include('tools.partials.bad-word-modal')
@include('tools.partials.bad-website-modal')

<!-- Ban Email Modal -->
<div id="ban-email-modal" class="fixed inset-0 flex items-center justify-center" style="background-color: rgba(0,0,0,0.5) !important; z-index: 9999 !important; display: none;">
    <div style="background-color: #ffffff !important; border-radius: 12px !important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important; width: 100% !important; max-width: 520px !important; margin: 16px !important; overflow: hidden !important;">
        <div style="padding: 16px 20px !important; border-bottom: 1px solid #e5e7eb !important; display: flex !important; align-items: center !important; justify-content: space-between !important; background-color: #f9fafb !important;">
            <div style="display: flex !important; align-items: center !important; gap: 10px !important;">
                <div style="width: 36px !important; height: 36px !important; border-radius: 8px !important; background-color: #dc2626 !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                    <span class="material-symbols-outlined" style="font-size: 20px !important; color: #ffffff !important;">block</span>
                </div>
                <h3 id="ban-email-modal-title" style="font-size: 14px !important; font-weight: 600 !important; color: #111827 !important; font-family: Poppins, sans-serif !important; margin: 0 !important;">Ban Email Address</h3>
            </div>
            <button type="button" onclick="closeModal('ban-email-modal')" style="width: 32px !important; height: 32px !important; border-radius: 6px !important; border: none !important; background-color: transparent !important; cursor: pointer !important; display: flex !important; align-items: center !important; justify-content: center !important;" onmouseover="this.style.backgroundColor='#e5e7eb'" onmouseout="this.style.backgroundColor='transparent'">
                <span class="material-symbols-outlined" style="font-size: 20px !important; color: #6b7280 !important;">close</span>
            </button>
        </div>
        <!-- Modal Tabs -->
        <div style="display: flex !important; border-bottom: 1px solid #e5e7eb !important;" id="ban-email-tabs">
            <button type="button" onclick="switchModalTab('ban-email', 'single')" id="ban-email-tab-single" style="flex: 1 !important; padding: 12px !important; font-size: 12px !important; font-weight: 500 !important; color: #3b82f6 !important; background-color: #ffffff !important; border: none !important; border-bottom: 2px solid #3b82f6 !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;">
                <span class="material-symbols-outlined" style="font-size: 16px !important; vertical-align: middle !important; margin-right: 4px !important;">person</span>
                Single
            </button>
            <button type="button" onclick="switchModalTab('ban-email', 'bulk')" id="ban-email-tab-bulk" style="flex: 1 !important; padding: 12px !important; font-size: 12px !important; font-weight: 500 !important; color: #6b7280 !important; background-color: #f9fafb !important; border: none !important; border-bottom: 2px solid transparent !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;">
                <span class="material-symbols-outlined" style="font-size: 16px !important; vertical-align: middle !important; margin-right: 4px !important;">group_add</span>
                Bulk Import
            </button>
        </div>
        <!-- Single Form -->
        <form id="ban-email-form" action="{{ route('tools.ban-email.store') }}" method="POST">
            @csrf
            <div id="ban-email-single" style="padding: 20px !important;">
                <div style="display: flex !important; flex-direction: column !important; gap: 16px !important;">
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Email Address <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <input type="email" name="email" required
                               style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important;"
                               placeholder="email@example.com"
                               onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Reason <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <textarea name="reason" required rows="3"
                                  style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; resize: vertical !important; outline: none !important; box-sizing: border-box !important;"
                                  placeholder="Reason for banning this email"
                                  onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'"></textarea>
                    </div>
                </div>
            </div>
            <div style="padding: 16px 20px !important; border-top: 1px solid #e5e7eb !important; display: flex !important; justify-content: flex-end !important; gap: 10px !important; background-color: #f9fafb !important;" id="ban-email-single-footer">
                <button type="button" onclick="closeModal('ban-email-modal')" 
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #374151 !important; background-color: #ffffff !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;"
                        onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#ffffff'">
                    Cancel
                </button>
                <button type="submit"
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #ffffff !important; background-color: #dc2626 !important; border: none !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important; display: flex !important; align-items: center !important; gap: 6px !important;"
                        onmouseover="this.style.backgroundColor='#b91c1c'" onmouseout="this.style.backgroundColor='#dc2626'">
                    <span class="material-symbols-outlined" style="font-size: 16px !important;">block</span>
                    Ban Email
                </button>
            </div>
        </form>
        <!-- Bulk Form -->
        <form id="ban-email-bulk-form" action="{{ route('tools.ban-email.bulk') }}" method="POST" style="display: none;">
            @csrf
            <div id="ban-email-bulk" style="padding: 20px !important;">
                <div style="display: flex !important; flex-direction: column !important; gap: 16px !important;">
                    <div style="padding: 12px !important; background-color: #eff6ff !important; border: 1px solid #bfdbfe !important; border-radius: 6px !important;">
                        <p style="font-size: 11px !important; color: #1e40af !important; margin: 0 !important; font-family: Poppins, sans-serif !important;">
                            <span class="material-symbols-outlined" style="font-size: 14px !important; vertical-align: middle !important; margin-right: 4px !important;">info</span>
                            Enter one email per line. All emails will use the same reason.
                        </p>
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Email Addresses <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <textarea name="emails" required rows="8"
                                  style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: monospace !important; font-size: 12px !important; color: #1f2937 !important; resize: vertical !important; outline: none !important; box-sizing: border-box !important;"
                                  placeholder="spam1@example.com&#10;spam2@example.com&#10;spam3@example.com"
                                  onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'"></textarea>
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Reason (applies to all) <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <input type="text" name="reason" required
                               style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important;"
                               placeholder="Bulk import - spam emails"
                               onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                    </div>
                </div>
            </div>
            <div style="padding: 16px 20px !important; border-top: 1px solid #e5e7eb !important; display: flex !important; justify-content: flex-end !important; gap: 10px !important; background-color: #f9fafb !important;" id="ban-email-bulk-footer">
                <button type="button" onclick="closeModal('ban-email-modal')" 
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #374151 !important; background-color: #ffffff !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;"
                        onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#ffffff'">
                    Cancel
                </button>
                <button type="submit"
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #ffffff !important; background-color: #dc2626 !important; border: none !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important; display: flex !important; align-items: center !important; gap: 6px !important;"
                        onmouseover="this.style.backgroundColor='#b91c1c'" onmouseout="this.style.backgroundColor='#dc2626'">
                    <span class="material-symbols-outlined" style="font-size: 16px !important;">group_add</span>
                    Ban All Emails
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Ban IP Modal -->
<div id="ban-ip-modal" class="fixed inset-0 flex items-center justify-center" style="background-color: rgba(0,0,0,0.5) !important; z-index: 9999 !important; display: none;">
    <div style="background-color: #ffffff !important; border-radius: 12px !important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important; width: 100% !important; max-width: 520px !important; margin: 16px !important; overflow: hidden !important;">
        <div style="padding: 16px 20px !important; border-bottom: 1px solid #e5e7eb !important; display: flex !important; align-items: center !important; justify-content: space-between !important; background-color: #f9fafb !important;">
            <div style="display: flex !important; align-items: center !important; gap: 10px !important;">
                <div style="width: 36px !important; height: 36px !important; border-radius: 8px !important; background-color: #dc2626 !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                    <span class="material-symbols-outlined" style="font-size: 20px !important; color: #ffffff !important;">block</span>
                </div>
                <h3 id="ban-ip-modal-title" style="font-size: 14px !important; font-weight: 600 !important; color: #111827 !important; font-family: Poppins, sans-serif !important; margin: 0 !important;">Ban IP Address</h3>
            </div>
            <button type="button" onclick="closeModal('ban-ip-modal')" style="width: 32px !important; height: 32px !important; border-radius: 6px !important; border: none !important; background-color: transparent !important; cursor: pointer !important; display: flex !important; align-items: center !important; justify-content: center !important;" onmouseover="this.style.backgroundColor='#e5e7eb'" onmouseout="this.style.backgroundColor='transparent'">
                <span class="material-symbols-outlined" style="font-size: 20px !important; color: #6b7280 !important;">close</span>
            </button>
        </div>
        <!-- Modal Tabs -->
        <div style="display: flex !important; border-bottom: 1px solid #e5e7eb !important;">
            <button type="button" onclick="switchModalTab('ban-ip', 'single')" id="ban-ip-tab-single" style="flex: 1 !important; padding: 12px !important; font-size: 12px !important; font-weight: 500 !important; color: #3b82f6 !important; background-color: #ffffff !important; border: none !important; border-bottom: 2px solid #3b82f6 !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;">
                <span class="material-symbols-outlined" style="font-size: 16px !important; vertical-align: middle !important; margin-right: 4px !important;">person</span>
                Single
            </button>
            <button type="button" onclick="switchModalTab('ban-ip', 'bulk')" id="ban-ip-tab-bulk" style="flex: 1 !important; padding: 12px !important; font-size: 12px !important; font-weight: 500 !important; color: #6b7280 !important; background-color: #f9fafb !important; border: none !important; border-bottom: 2px solid transparent !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;">
                <span class="material-symbols-outlined" style="font-size: 16px !important; vertical-align: middle !important; margin-right: 4px !important;">group_add</span>
                Bulk Import
            </button>
        </div>
        <!-- Single Form -->
        <form id="ban-ip-form" action="{{ route('tools.ban-ip.store') }}" method="POST">
            @csrf
            <div id="ban-ip-single" style="padding: 20px !important;">
                <div style="display: flex !important; flex-direction: column !important; gap: 16px !important;">
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            IP Address <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <input type="text" name="ip_address" required
                               style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important;"
                               placeholder="192.168.1.1"
                               onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Reason <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <textarea name="reason" required rows="3"
                                  style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; resize: vertical !important; outline: none !important; box-sizing: border-box !important;"
                                  placeholder="Reason for banning this IP"
                                  onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'"></textarea>
                    </div>
                </div>
            </div>
            <div style="padding: 16px 20px !important; border-top: 1px solid #e5e7eb !important; display: flex !important; justify-content: flex-end !important; gap: 10px !important; background-color: #f9fafb !important;" id="ban-ip-single-footer">
                <button type="button" onclick="closeModal('ban-ip-modal')" 
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #374151 !important; background-color: #ffffff !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;"
                        onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#ffffff'">
                    Cancel
                </button>
                <button type="submit"
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #ffffff !important; background-color: #dc2626 !important; border: none !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important; display: flex !important; align-items: center !important; gap: 6px !important;"
                        onmouseover="this.style.backgroundColor='#b91c1c'" onmouseout="this.style.backgroundColor='#dc2626'">
                    <span class="material-symbols-outlined" style="font-size: 16px !important;">block</span>
                    Ban IP
                </button>
            </div>
        </form>
        <!-- Bulk Form -->
        <form id="ban-ip-bulk-form" action="{{ route('tools.ban-ip.bulk') }}" method="POST" style="display: none;">
            @csrf
            <div id="ban-ip-bulk" style="padding: 20px !important;">
                <div style="display: flex !important; flex-direction: column !important; gap: 16px !important;">
                    <div style="padding: 12px !important; background-color: #eff6ff !important; border: 1px solid #bfdbfe !important; border-radius: 6px !important;">
                        <p style="font-size: 11px !important; color: #1e40af !important; margin: 0 !important; font-family: Poppins, sans-serif !important;">
                            <span class="material-symbols-outlined" style="font-size: 14px !important; vertical-align: middle !important; margin-right: 4px !important;">info</span>
                            Enter one IP address per line. All IPs will use the same reason.
                        </p>
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            IP Addresses <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <textarea name="ip_addresses" required rows="8"
                                  style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: monospace !important; font-size: 12px !important; color: #1f2937 !important; resize: vertical !important; outline: none !important; box-sizing: border-box !important;"
                                  placeholder="192.168.1.1&#10;10.0.0.1&#10;172.16.0.1"
                                  onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'"></textarea>
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Reason (applies to all) <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <input type="text" name="reason" required
                               style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important;"
                               placeholder="Bulk import - malicious IPs"
                               onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                    </div>
                </div>
            </div>
            <div style="padding: 16px 20px !important; border-top: 1px solid #e5e7eb !important; display: flex !important; justify-content: flex-end !important; gap: 10px !important; background-color: #f9fafb !important;" id="ban-ip-bulk-footer">
                <button type="button" onclick="closeModal('ban-ip-modal')" 
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #374151 !important; background-color: #ffffff !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;"
                        onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#ffffff'">
                    Cancel
                </button>
                <button type="submit"
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #ffffff !important; background-color: #dc2626 !important; border: none !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important; display: flex !important; align-items: center !important; gap: 6px !important;"
                        onmouseover="this.style.backgroundColor='#b91c1c'" onmouseout="this.style.backgroundColor='#dc2626'">
                    <span class="material-symbols-outlined" style="font-size: 16px !important;">group_add</span>
                    Ban All IPs
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Whitelist IP Modal -->
<div id="whitelist-ip-modal" class="fixed inset-0 flex items-center justify-center" style="background-color: rgba(0,0,0,0.5) !important; z-index: 9999 !important; display: none;">
    <div style="background-color: #ffffff !important; border-radius: 12px !important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important; width: 100% !important; max-width: 520px !important; margin: 16px !important; overflow: hidden !important;">
        <div style="padding: 16px 20px !important; border-bottom: 1px solid #e5e7eb !important; display: flex !important; align-items: center !important; justify-content: space-between !important; background-color: #f9fafb !important;">
            <div style="display: flex !important; align-items: center !important; gap: 10px !important;">
                <div style="width: 36px !important; height: 36px !important; border-radius: 8px !important; background-color: #16a34a !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                    <span class="material-symbols-outlined" style="font-size: 20px !important; color: #ffffff !important;">verified_user</span>
                </div>
                <h3 id="whitelist-ip-modal-title" style="font-size: 14px !important; font-weight: 600 !important; color: #111827 !important; font-family: Poppins, sans-serif !important; margin: 0 !important;">Whitelist IP Address</h3>
            </div>
            <button type="button" onclick="closeModal('whitelist-ip-modal')" style="width: 32px !important; height: 32px !important; border-radius: 6px !important; border: none !important; background-color: transparent !important; cursor: pointer !important; display: flex !important; align-items: center !important; justify-content: center !important;" onmouseover="this.style.backgroundColor='#e5e7eb'" onmouseout="this.style.backgroundColor='transparent'">
                <span class="material-symbols-outlined" style="font-size: 20px !important; color: #6b7280 !important;">close</span>
            </button>
        </div>
        <!-- Modal Tabs -->
        <div style="display: flex !important; border-bottom: 1px solid #e5e7eb !important;">
            <button type="button" onclick="switchModalTab('whitelist-ip', 'single')" id="whitelist-ip-tab-single" style="flex: 1 !important; padding: 12px !important; font-size: 12px !important; font-weight: 500 !important; color: #3b82f6 !important; background-color: #ffffff !important; border: none !important; border-bottom: 2px solid #3b82f6 !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;">
                <span class="material-symbols-outlined" style="font-size: 16px !important; vertical-align: middle !important; margin-right: 4px !important;">person</span>
                Single
            </button>
            <button type="button" onclick="switchModalTab('whitelist-ip', 'bulk')" id="whitelist-ip-tab-bulk" style="flex: 1 !important; padding: 12px !important; font-size: 12px !important; font-weight: 500 !important; color: #6b7280 !important; background-color: #f9fafb !important; border: none !important; border-bottom: 2px solid transparent !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;">
                <span class="material-symbols-outlined" style="font-size: 16px !important; vertical-align: middle !important; margin-right: 4px !important;">group_add</span>
                Bulk Import
            </button>
        </div>
        <!-- Single Form -->
        <form id="whitelist-ip-form" action="{{ route('tools.whitelist-ip.store') }}" method="POST">
            @csrf
            <div id="whitelist-ip-single" style="padding: 20px !important;">
                <div style="display: flex !important; flex-direction: column !important; gap: 16px !important;">
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            IP Address <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <input type="text" name="ip_address" required
                               style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important;"
                               placeholder="10.0.0.1"
                               onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Reason <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <textarea name="reason" required rows="3"
                                  style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; resize: vertical !important; outline: none !important; box-sizing: border-box !important;"
                                  placeholder="Reason for whitelisting this IP"
                                  onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'"></textarea>
                    </div>
                </div>
            </div>
            <div style="padding: 16px 20px !important; border-top: 1px solid #e5e7eb !important; display: flex !important; justify-content: flex-end !important; gap: 10px !important; background-color: #f9fafb !important;" id="whitelist-ip-single-footer">
                <button type="button" onclick="closeModal('whitelist-ip-modal')" 
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #374151 !important; background-color: #ffffff !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;"
                        onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#ffffff'">
                    Cancel
                </button>
                <button type="submit"
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #ffffff !important; background-color: #16a34a !important; border: none !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important; display: flex !important; align-items: center !important; gap: 6px !important;"
                        onmouseover="this.style.backgroundColor='#15803d'" onmouseout="this.style.backgroundColor='#16a34a'">
                    <span class="material-symbols-outlined" style="font-size: 16px !important;">verified_user</span>
                    Whitelist IP
                </button>
            </div>
        </form>
        <!-- Bulk Form -->
        <form id="whitelist-ip-bulk-form" action="{{ route('tools.whitelist-ip.bulk') }}" method="POST" style="display: none;">
            @csrf
            <div id="whitelist-ip-bulk" style="padding: 20px !important;">
                <div style="display: flex !important; flex-direction: column !important; gap: 16px !important;">
                    <div style="padding: 12px !important; background-color: #f0fdf4 !important; border: 1px solid #bbf7d0 !important; border-radius: 6px !important;">
                        <p style="font-size: 11px !important; color: #166534 !important; margin: 0 !important; font-family: Poppins, sans-serif !important;">
                            <span class="material-symbols-outlined" style="font-size: 14px !important; vertical-align: middle !important; margin-right: 4px !important;">info</span>
                            Enter one IP address per line. All IPs will use the same reason.
                        </p>
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            IP Addresses <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <textarea name="ip_addresses" required rows="8"
                                  style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: monospace !important; font-size: 12px !important; color: #1f2937 !important; resize: vertical !important; outline: none !important; box-sizing: border-box !important;"
                                  placeholder="10.0.0.1&#10;192.168.1.100&#10;172.16.0.1"
                                  onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'"></textarea>
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Reason (applies to all) <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <input type="text" name="reason" required
                               style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important;"
                               placeholder="Bulk import - trusted IPs"
                               onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                    </div>
                </div>
            </div>
            <div style="padding: 16px 20px !important; border-top: 1px solid #e5e7eb !important; display: flex !important; justify-content: flex-end !important; gap: 10px !important; background-color: #f9fafb !important;" id="whitelist-ip-bulk-footer">
                <button type="button" onclick="closeModal('whitelist-ip-modal')" 
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #374151 !important; background-color: #ffffff !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;"
                        onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#ffffff'">
                    Cancel
                </button>
                <button type="submit"
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #ffffff !important; background-color: #16a34a !important; border: none !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important; display: flex !important; align-items: center !important; gap: 6px !important;"
                        onmouseover="this.style.backgroundColor='#15803d'" onmouseout="this.style.backgroundColor='#16a34a'">
                    <span class="material-symbols-outlined" style="font-size: 16px !important;">group_add</span>
                    Whitelist All IPs
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Whitelist Email Modal -->
<div id="whitelist-email-modal" class="fixed inset-0 flex items-center justify-center" style="background-color: rgba(0,0,0,0.5) !important; z-index: 9999 !important; display: none;">
    <div style="background-color: #ffffff !important; border-radius: 12px !important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important; width: 100% !important; max-width: 520px !important; margin: 16px !important; overflow: hidden !important;">
        <div style="padding: 16px 20px !important; border-bottom: 1px solid #e5e7eb !important; display: flex !important; align-items: center !important; justify-content: space-between !important; background-color: #f9fafb !important;">
            <div style="display: flex !important; align-items: center !important; gap: 10px !important;">
                <div style="width: 36px !important; height: 36px !important; border-radius: 8px !important; background-color: #16a34a !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                    <span class="material-symbols-outlined" style="font-size: 20px !important; color: #ffffff !important;">verified_user</span>
                </div>
                <h3 id="whitelist-email-modal-title" style="font-size: 14px !important; font-weight: 600 !important; color: #111827 !important; font-family: Poppins, sans-serif !important; margin: 0 !important;">Whitelist Email Address</h3>
            </div>
            <button type="button" onclick="closeModal('whitelist-email-modal')" style="width: 32px !important; height: 32px !important; border-radius: 6px !important; border: none !important; background-color: transparent !important; cursor: pointer !important; display: flex !important; align-items: center !important; justify-content: center !important;" onmouseover="this.style.backgroundColor='#e5e7eb'" onmouseout="this.style.backgroundColor='transparent'">
                <span class="material-symbols-outlined" style="font-size: 20px !important; color: #6b7280 !important;">close</span>
            </button>
        </div>
        <!-- Modal Tabs -->
        <div style="display: flex !important; border-bottom: 1px solid #e5e7eb !important;">
            <button type="button" onclick="switchModalTab('whitelist-email', 'single')" id="whitelist-email-tab-single" style="flex: 1 !important; padding: 12px !important; font-size: 12px !important; font-weight: 500 !important; color: #3b82f6 !important; background-color: #ffffff !important; border: none !important; border-bottom: 2px solid #3b82f6 !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;">
                <span class="material-symbols-outlined" style="font-size: 16px !important; vertical-align: middle !important; margin-right: 4px !important;">person</span>
                Single
            </button>
            <button type="button" onclick="switchModalTab('whitelist-email', 'bulk')" id="whitelist-email-tab-bulk" style="flex: 1 !important; padding: 12px !important; font-size: 12px !important; font-weight: 500 !important; color: #6b7280 !important; background-color: #f9fafb !important; border: none !important; border-bottom: 2px solid transparent !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;">
                <span class="material-symbols-outlined" style="font-size: 16px !important; vertical-align: middle !important; margin-right: 4px !important;">group_add</span>
                Bulk Import
            </button>
        </div>
        <!-- Single Form -->
        <form id="whitelist-email-form" action="{{ route('tools.whitelist-email.store') }}" method="POST">
            @csrf
            <div id="whitelist-email-single" style="padding: 20px !important;">
                <div style="display: flex !important; flex-direction: column !important; gap: 16px !important;">
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Email Address <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <input type="email" name="email" required
                               style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important;"
                               placeholder="trusted@company.com"
                               onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Reason <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <textarea name="reason" required rows="3"
                                  style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; resize: vertical !important; outline: none !important; box-sizing: border-box !important;"
                                  placeholder="Reason for whitelisting this email"
                                  onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'"></textarea>
                    </div>
                </div>
            </div>
            <div style="padding: 16px 20px !important; border-top: 1px solid #e5e7eb !important; display: flex !important; justify-content: flex-end !important; gap: 10px !important; background-color: #f9fafb !important;" id="whitelist-email-single-footer">
                <button type="button" onclick="closeModal('whitelist-email-modal')" 
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #374151 !important; background-color: #ffffff !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;"
                        onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#ffffff'">
                    Cancel
                </button>
                <button type="submit"
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #ffffff !important; background-color: #16a34a !important; border: none !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important; display: flex !important; align-items: center !important; gap: 6px !important;"
                        onmouseover="this.style.backgroundColor='#15803d'" onmouseout="this.style.backgroundColor='#16a34a'">
                    <span class="material-symbols-outlined" style="font-size: 16px !important;">verified_user</span>
                    Whitelist Email
                </button>
            </div>
        </form>
        <!-- Bulk Form -->
        <form id="whitelist-email-bulk-form" action="{{ route('tools.whitelist-email.bulk') }}" method="POST" style="display: none;">
            @csrf
            <div id="whitelist-email-bulk" style="padding: 20px !important;">
                <div style="display: flex !important; flex-direction: column !important; gap: 16px !important;">
                    <div style="padding: 12px !important; background-color: #f0fdf4 !important; border: 1px solid #bbf7d0 !important; border-radius: 6px !important;">
                        <p style="font-size: 11px !important; color: #166534 !important; margin: 0 !important; font-family: Poppins, sans-serif !important;">
                            <span class="material-symbols-outlined" style="font-size: 14px !important; vertical-align: middle !important; margin-right: 4px !important;">info</span>
                            Enter one email per line. All emails will use the same reason.
                        </p>
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Email Addresses <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <textarea name="emails" required rows="8"
                                  style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: monospace !important; font-size: 12px !important; color: #1f2937 !important; resize: vertical !important; outline: none !important; box-sizing: border-box !important;"
                                  placeholder="trusted1@company.com&#10;trusted2@company.com&#10;partner@business.com"
                                  onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'"></textarea>
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Reason (applies to all) <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <input type="text" name="reason" required
                               style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important;"
                               placeholder="Bulk import - trusted emails"
                               onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                    </div>
                </div>
            </div>
            <div style="padding: 16px 20px !important; border-top: 1px solid #e5e7eb !important; display: flex !important; justify-content: flex-end !important; gap: 10px !important; background-color: #f9fafb !important;" id="whitelist-email-bulk-footer">
                <button type="button" onclick="closeModal('whitelist-email-modal')" 
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #374151 !important; background-color: #ffffff !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;"
                        onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#ffffff'">
                    Cancel
                </button>
                <button type="submit"
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #ffffff !important; background-color: #16a34a !important; border: none !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important; display: flex !important; align-items: center !important; gap: 6px !important;"
                        onmouseover="this.style.backgroundColor='#15803d'" onmouseout="this.style.backgroundColor='#16a34a'">
                    <span class="material-symbols-outlined" style="font-size: 16px !important;">group_add</span>
                    Whitelist All Emails
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<x-modals.delete-confirmation 
    id="delete-modal"
    title="Delete Entry"
    message="Are you sure you want to delete this entry? This action cannot be undone."
/>

@push('scripts')
<script>
let currentEditId = null;
let currentEditTab = null;

function exportData(tab) {
    window.location.href = '{{ route('tools.export') }}?tab=' + tab;
}

function switchModalTab(modalType, tabType) {
    const singleTab = document.getElementById(modalType + '-tab-single');
    const bulkTab = document.getElementById(modalType + '-tab-bulk');
    const singleForm = document.getElementById(modalType + '-form');
    const bulkForm = document.getElementById(modalType + '-bulk-form');
    
    if (tabType === 'single') {
        // Activate single tab
        singleTab.style.color = '#3b82f6';
        singleTab.style.backgroundColor = '#ffffff';
        singleTab.style.borderBottom = '2px solid #3b82f6';
        bulkTab.style.color = '#6b7280';
        bulkTab.style.backgroundColor = '#f9fafb';
        bulkTab.style.borderBottom = '2px solid transparent';
        
        // Show single form, hide bulk form
        singleForm.style.display = 'block';
        bulkForm.style.display = 'none';
    } else {
        // Activate bulk tab
        bulkTab.style.color = '#3b82f6';
        bulkTab.style.backgroundColor = '#ffffff';
        bulkTab.style.borderBottom = '2px solid #3b82f6';
        singleTab.style.color = '#6b7280';
        singleTab.style.backgroundColor = '#f9fafb';
        singleTab.style.borderBottom = '2px solid transparent';
        
        // Show bulk form, hide single form
        bulkForm.style.display = 'block';
        singleForm.style.display = 'none';
    }
}

function showCreateModal(tab) {
    currentEditId = null;
    currentEditTab = tab;
    
    // Reset to single tab
    switchModalTab(tab, 'single');
    
    document.getElementById(tab + '-modal').style.display = 'flex';
    
    // Update modal title for create
    const modal = document.getElementById(tab + '-modal');
    const title = document.getElementById(tab + '-modal-title');
    if (title) {
        if (tab === 'ban-email') title.textContent = 'Ban Email Address';
        else if (tab === 'ban-ip') title.textContent = 'Ban IP Address';
        else if (tab === 'whitelist-ip') title.textContent = 'Whitelist IP Address';
        else if (tab === 'whitelist-email') title.textContent = 'Whitelist Email Address';
        else if (tab === 'bad-word') title.textContent = 'Add Bad Word';
        else if (tab === 'bad-website') title.textContent = 'Add Bad Website';
    }
    
    // Clear forms
    const singleForm = modal.querySelector('#' + tab + '-form');
    const bulkForm = modal.querySelector('#' + tab + '-bulk-form');
    if (singleForm) singleForm.reset();
    if (bulkForm) bulkForm.reset();
}

function showEditModal(tab, id, value, reason, severity = null) {
    currentEditId = id;
    currentEditTab = tab;
    
    const modal = document.getElementById(tab + '-modal');
    modal.style.display = 'flex';
    
    // Update modal title for edit
    const title = modal.querySelector('h3');
    if (tab === 'ban-email') title.textContent = 'Edit Banned Email';
    else if (tab === 'ban-ip') title.textContent = 'Edit Banned IP';
    else if (tab === 'whitelist-ip') title.textContent = 'Edit Whitelisted IP';
    else if (tab === 'whitelist-email') title.textContent = 'Edit Whitelisted Email';
    else if (tab === 'bad-word') title.textContent = 'Edit Bad Word';
    else if (tab === 'bad-website') title.textContent = 'Edit Bad Website';
    
    // Fill form with existing data
    const form = modal.querySelector('form');
    if (tab.includes('email')) {
        form.querySelector('input[name="email"]').value = value;
    } else if (tab === 'bad-word') {
        form.querySelector('input[name="word"]').value = value;
        if (severity) form.querySelector('select[name="severity"]').value = severity;
    } else if (tab === 'bad-website') {
        form.querySelector('input[name="url"]').value = value;
        if (severity) form.querySelector('select[name="severity"]').value = severity;
    } else {
        form.querySelector('input[name="ip_address"]').value = value;
    }
    if (form.querySelector('textarea[name="reason"]')) {
        form.querySelector('textarea[name="reason"]').value = reason || '';
    }
    
    // Update form action for edit
    let baseAction = form.getAttribute('action').replace(/\/\d+$/, '');
    form.setAttribute('action', baseAction + '/' + id);
    
    // Add PUT method
    let methodInput = form.querySelector('input[name="_method"]');
    if (!methodInput) {
        form.insertAdjacentHTML('afterbegin', '<input type="hidden" name="_method" value="PUT">');
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.querySelector('#' + modalId + ' form').reset();
    currentEditId = null;
    currentEditTab = null;
}

// Close modals on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal('ban-email-modal');
        closeModal('ban-ip-modal');
        closeModal('whitelist-ip-modal');
        closeModal('whitelist-email-modal');
    }
});

// Close modals on backdrop click
['ban-email-modal', 'ban-ip-modal', 'whitelist-ip-modal', 'whitelist-email-modal', 'bad-word-modal', 'bad-website-modal'].forEach(function(modalId) {
    document.getElementById(modalId).addEventListener('click', function(e) {
        if (e.target === this) closeModal(modalId);
    });
});
</script>
@endpush
@endsection
