@extends('layouts.app')

@section('title', 'User Management')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Platform Settings'],
        ['label' => 'Users', 'active' => true]
    ]" />
@endsection

@section('content')
<div class="bg-white border border-gray-200 settings-container">
    <!-- Page Header -->
    <div class="px-6 py-4 flex items-center justify-between settings-page-header">
        <div>
            <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">User Management</h2>
            <p class="text-xs text-gray-500 mt-0.5">Manage system users and access</p>
        </div>
        <div class="flex items-center gap-2">
            @if($canCreate ?? false)
            <a href="{{ route('settings.users.create', ['tab' => $currentTab]) }}" 
               class="inline-flex items-center gap-2 px-3 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition"
               style="min-height: 32px;">
                <span class="material-symbols-outlined" style="font-size: 14px;">person_add</span>
                USER
            </a>
            @endif
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="border-t border-gray-200">
        <nav class="flex px-6 settings-tabs-nav" aria-label="Tabs">
            @foreach($tabs as $key => $label)
                <a href="{{ route('settings.users', ['tab' => $key]) }}"
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

        @if(session('error'))
        <div class="px-6 pt-4">
            <div class="px-4 py-3 bg-red-50 border border-red-200 rounded">
                <p class="text-xs text-red-800" style="font-family: Poppins, sans-serif;">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <!-- Filter Form -->
        <div class="px-6 py-3 settings-filter-form">
            <form action="{{ route('settings.users') }}" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="tab" value="{{ $currentTab }}">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search name or email" 
                           class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                           style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                </div>
                <select name="status" class="px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 min-w-[120px]" style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <div class="filter-buttons">
                    <button type="submit" class="inline-flex items-center gap-2 px-3 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition" style="min-height: 32px;">
                        <span class="material-symbols-outlined" style="font-size: 14px;">search</span>
                        SEARCH
                    </button>
                    <button type="button" onclick="window.location.href='{{ route('settings.users', ['tab' => $currentTab]) }}'" class="inline-flex items-center gap-2 px-3 text-white text-xs font-medium rounded transition" style="min-height: 32px; background-color: #dc2626;">
                        <span class="material-symbols-outlined" style="font-size: 14px;">refresh</span>
                        RESET
                    </button>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="px-6 settings-table-container">
            <x-ui.data-table
                :headers="[
                    ['label' => '', 'align' => 'text-center', 'width' => 'w-16'],
                    ['label' => 'Name', 'align' => 'text-left'],
                    ['label' => 'Email', 'align' => 'text-left'],
                    ['label' => 'Status', 'align' => 'text-center'],
                    ['label' => 'Last Login', 'align' => 'text-center'],
                ]"
                :actions="true"
                empty-message="No users found."
            >
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-medium mx-auto">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900" style="font-family: Poppins, sans-serif; font-size: 12px;">{{ $user->full_name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @php
                            $effectiveStatus = $user->effective_status;
                            $statusColors = [
                                'active' => 'bg-green-100 text-green-600',
                                'locked' => 'bg-orange-100 text-orange-600',
                                'suspended' => 'bg-red-100 text-red-600',
                                'inactive' => 'bg-gray-100 text-gray-600',
                            ];
                        @endphp
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded {{ $statusColors[$effectiveStatus] ?? 'bg-gray-100 text-gray-600' }}" style="font-size: 10px;">
                            {{ ucfirst($effectiveStatus) }}
                        </span>
                        @if($user->isLocked() && !$user->isSuspended())
                            @php
                                $lockoutDuration = (int) setting('lockout_duration', 15);
                                $lockExpiry = $user->locked_at->copy()->addMinutes($lockoutDuration);
                                $remainingSeconds = now()->diffInSeconds($lockExpiry, false);
                            @endphp
                            <div class="text-xs text-orange-500 mt-1 lockout-countdown" 
                                 data-remaining="{{ max(0, $remainingSeconds) }}"
                                 data-user-id="{{ $user->id }}">
                                <span class="countdown-text">{{ $user->getLockoutRemainingMinutes() }}m left</span>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        @php
                            $moreActions = [];
                            
                            // Lock/Unlock (only show if has manage permission and not suspended)
                            if (($canManage ?? false) && !$user->isSuspended()) {
                                if ($user->isLocked()) {
                                    $moreActions[] = [
                                        'label' => 'Unlock Account',
                                        'icon' => 'lock_open',
                                        'onclick' => "showUnlockModal('" . route('settings.users.unlock', $user) . "')"
                                    ];
                                } else {
                                    $moreActions[] = [
                                        'label' => 'Lock Account',
                                        'icon' => 'lock',
                                        'onclick' => "showLockModal('" . route('settings.users.lock', $user) . "')"
                                    ];
                                }
                            }
                            
                            // Suspend/Unsuspend (only show if has manage permission)
                            if ($canManage ?? false) {
                                if ($user->isSuspended()) {
                                    $moreActions[] = [
                                        'label' => 'Unsuspend Account',
                                        'icon' => 'check_circle',
                                        'onclick' => "showUnsuspendModal('" . route('settings.users.unsuspend', $user) . "')"
                                    ];
                                } else {
                                    $moreActions[] = [
                                        'label' => 'Suspend Account',
                                        'icon' => 'block',
                                        'onclick' => "showSuspendModal('" . route('settings.users.suspend', $user) . "')"
                                    ];
                                }
                            }
                        @endphp
                        <x-ui.action-buttons
                            :edit-url="($canEdit ?? false) ? route('settings.users.edit', $user) : null"
                            :show-url="route('users.show', $user)"
                            :delete-onclick="($canDelete ?? false) ? 'showDeleteModal(\'' . route('settings.users.destroy', $user) . '\')' : null"
                            :more-actions="$moreActions"
                        />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-sm">
                        No {{ strtolower($tabs[$currentTab]) }} found.
                    </td>
                </tr>
                @endforelse
            </x-ui.data-table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-3">
            <x-ui.custom-pagination :paginator="$users" record-label="users" :tab-param="$currentTab" />
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<x-modals.delete-confirmation 
    id="delete-modal"
    title="Delete User"
    message="Are you sure you want to delete this user? This action cannot be undone and all associated data will be permanently removed."
/>

<!-- Lock Confirmation Modal -->
<div id="lock-modal" class="fixed inset-0 hidden overflow-y-auto" style="z-index: 100000;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="hideLockModal()"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-orange-600" style="font-size: 20px;">lock</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Lock Account</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4" style="font-family: Poppins, sans-serif;">
                Are you sure you want to lock this account? The user will not be able to login for {{ setting('lockout_duration', 15) }} minutes.
            </p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="hideLockModal()" class="px-4 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200" style="font-family: Poppins, sans-serif;">
                    Cancel
                </button>
                <form id="lock-form" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-xs font-medium text-white bg-orange-600 rounded hover:bg-orange-700" style="font-family: Poppins, sans-serif;">
                        Lock Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Unlock Confirmation Modal -->
<div id="unlock-modal" class="fixed inset-0 hidden overflow-y-auto" style="z-index: 100000;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="hideUnlockModal()"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600" style="font-size: 20px;">lock_open</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Unlock Account</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4" style="font-family: Poppins, sans-serif;">
                Are you sure you want to unlock this account? The user will be able to login immediately.
            </p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="hideUnlockModal()" class="px-4 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200" style="font-family: Poppins, sans-serif;">
                    Cancel
                </button>
                <form id="unlock-form" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700" style="font-family: Poppins, sans-serif;">
                        Unlock Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Suspend Confirmation Modal -->
<div id="suspend-modal" class="fixed inset-0 hidden overflow-y-auto" style="z-index: 100000;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="hideSuspendModal()"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-600" style="font-size: 20px;">block</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Suspend Account</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4" style="font-family: Poppins, sans-serif;">
                Are you sure you want to suspend this account? The user will not be able to login until unsuspended.
            </p>
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-700 mb-1" style="font-family: Poppins, sans-serif;">Reason (optional)</label>
                <input type="text" id="suspend-reason" placeholder="Enter suspension reason..." 
                    class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:border-red-500" style="font-family: Poppins, sans-serif;">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="hideSuspendModal()" class="px-4 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200" style="font-family: Poppins, sans-serif;">
                    Cancel
                </button>
                <form id="suspend-form" method="POST">
                    @csrf
                    <input type="hidden" name="reason" id="suspend-reason-input">
                    <button type="submit" onclick="document.getElementById('suspend-reason-input').value = document.getElementById('suspend-reason').value" 
                        class="px-4 py-2 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700" style="font-family: Poppins, sans-serif;">
                        Suspend Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Unsuspend Confirmation Modal -->
<div id="unsuspend-modal" class="fixed inset-0 hidden overflow-y-auto" style="z-index: 100000;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="hideUnsuspendModal()"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600" style="font-size: 20px;">check_circle</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Unsuspend Account</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4" style="font-family: Poppins, sans-serif;">
                Are you sure you want to unsuspend this account? The user will be able to login immediately.
            </p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="hideUnsuspendModal()" class="px-4 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200" style="font-family: Poppins, sans-serif;">
                    Cancel
                </button>
                <form id="unsuspend-form" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700" style="font-family: Poppins, sans-serif;">
                        Unsuspend Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Lock Modal
    function showLockModal(url) {
        document.getElementById('lock-form').action = url;
        document.getElementById('lock-modal').classList.remove('hidden');
    }
    function hideLockModal() {
        document.getElementById('lock-modal').classList.add('hidden');
    }
    
    // Unlock Modal
    function showUnlockModal(url) {
        document.getElementById('unlock-form').action = url;
        document.getElementById('unlock-modal').classList.remove('hidden');
    }
    function hideUnlockModal() {
        document.getElementById('unlock-modal').classList.add('hidden');
    }
    
    // Suspend Modal
    function showSuspendModal(url) {
        document.getElementById('suspend-form').action = url;
        document.getElementById('suspend-reason').value = '';
        document.getElementById('suspend-modal').classList.remove('hidden');
    }
    function hideSuspendModal() {
        document.getElementById('suspend-modal').classList.add('hidden');
    }
    
    // Unsuspend Modal
    function showUnsuspendModal(url) {
        document.getElementById('unsuspend-form').action = url;
        document.getElementById('unsuspend-modal').classList.remove('hidden');
    }
    function hideUnsuspendModal() {
        document.getElementById('unsuspend-modal').classList.add('hidden');
    }
    
    // Live Countdown for Lockout
    document.addEventListener('DOMContentLoaded', function() {
        const countdowns = document.querySelectorAll('.lockout-countdown');
        
        countdowns.forEach(function(el) {
            let remaining = parseInt(el.dataset.remaining);
            const textEl = el.querySelector('.countdown-text');
            
            if (remaining <= 0) {
                textEl.textContent = 'Unlocking...';
                setTimeout(() => location.reload(), 1000);
                return;
            }
            
            const interval = setInterval(function() {
                remaining--;
                
                if (remaining <= 0) {
                    clearInterval(interval);
                    textEl.textContent = 'Unlocked!';
                    el.classList.remove('text-orange-500');
                    el.classList.add('text-green-500');
                    setTimeout(() => location.reload(), 1500);
                    return;
                }
                
                const minutes = Math.floor(remaining / 60);
                const seconds = remaining % 60;
                
                if (minutes > 0) {
                    textEl.textContent = `${minutes}m ${seconds}s left`;
                } else {
                    textEl.textContent = `${seconds}s left`;
                }
            }, 1000);
        });
    });
</script>
@endsection
