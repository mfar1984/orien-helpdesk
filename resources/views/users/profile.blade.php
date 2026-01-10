@extends('layouts.app')

@section('title', 'User Profile')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Profile', 'active' => true]
    ]" />
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <!-- Left Column - Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-white border border-gray-200">
            <div class="px-6 py-6 text-center border-b border-gray-200">
                <div class="w-24 h-24 bg-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-semibold mx-auto mb-4">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2 class="text-lg font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">{{ $user->name }}</h2>
                <p class="text-xs text-gray-500 mt-1">{{ $user->email }}</p>
                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full {{ ($user->status ?? 'active') === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} mt-3">
                    {{ ucfirst($user->status ?? 'Active') }}
                </span>
            </div>
            <div class="px-6 py-4">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-400" style="font-size: 18px;">badge</span>
                        <div>
                            <p class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">Role</p>
                            <p class="text-sm text-gray-900" style="font-family: Poppins, sans-serif;">{{ $user->role?->name ?? 'No Role' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-400" style="font-size: 18px;">calendar_today</span>
                        <div>
                            <p class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">Member Since</p>
                            <p class="text-sm text-gray-900" style="font-family: Poppins, sans-serif;">{{ format_date($user->created_at) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-400" style="font-size: 18px;">schedule</span>
                        <div>
                            <p class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">Last Login</p>
                            <p class="text-sm text-gray-900" style="font-family: Poppins, sans-serif;">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Profile Details -->
    <div class="lg:col-span-2 space-y-4">
        <!-- Account Information -->
        <div class="bg-white border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600" style="font-size: 18px;">account_circle</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Account Information</h3>
                        <p class="text-xs text-gray-500">Your account details</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #6b7280; margin-bottom: 4px; font-family: Poppins, sans-serif;">Full Name</label>
                        <p class="text-sm text-gray-900" style="font-family: Poppins, sans-serif;">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #6b7280; margin-bottom: 4px; font-family: Poppins, sans-serif;">Email Address</label>
                        <p class="text-sm text-gray-900" style="font-family: Poppins, sans-serif;">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #6b7280; margin-bottom: 4px; font-family: Poppins, sans-serif;">Hash Link</label>
                        <p class="text-sm text-gray-900 font-mono text-xs break-all">{{ $user->hash_link }}</p>
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #6b7280; margin-bottom: 4px; font-family: Poppins, sans-serif;">Account Status</label>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded {{ ($user->status ?? 'active') === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">{{ ucfirst($user->status ?? 'Active') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Summary -->
        <div class="bg-white border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-600" style="font-size: 18px;">analytics</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Activity Summary</h3>
                        <p class="text-xs text-gray-500">Your recent activity statistics</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-semibold text-blue-600" style="font-family: Poppins, sans-serif;">12</p>
                        <p class="text-xs text-gray-500 mt-1" style="font-family: Poppins, sans-serif;">Tickets Created</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-semibold text-green-600" style="font-family: Poppins, sans-serif;">8</p>
                        <p class="text-xs text-gray-500 mt-1" style="font-family: Poppins, sans-serif;">Tickets Resolved</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-semibold text-purple-600" style="font-family: Poppins, sans-serif;">45</p>
                        <p class="text-xs text-gray-500 mt-1" style="font-family: Poppins, sans-serif;">Replies Sent</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-semibold text-orange-600" style="font-family: Poppins, sans-serif;">3</p>
                        <p class="text-xs text-gray-500 mt-1" style="font-family: Poppins, sans-serif;">Articles Written</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="bg-white border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-red-600" style="font-size: 18px;">security</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Security Settings</h3>
                        <p class="text-xs text-gray-500">Manage your security preferences</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-500" style="font-size: 20px;">lock</span>
                            <div>
                                <p class="text-sm font-medium text-gray-900" style="font-family: Poppins, sans-serif;">Password</p>
                                <p class="text-xs text-gray-500">Last changed 30 days ago</p>
                            </div>
                        </div>
                        <button type="button" class="inline-flex items-center gap-2 px-3 py-2 text-xs font-medium text-blue-600 bg-blue-50 rounded hover:bg-blue-100 transition">
                            <span class="material-symbols-outlined" style="font-size: 14px;">edit</span>
                            Change
                        </button>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-500" style="font-size: 20px;">smartphone</span>
                            <div>
                                <p class="text-sm font-medium text-gray-900" style="font-family: Poppins, sans-serif;">Two-Factor Authentication</p>
                                <p class="text-xs {{ $user->twoFactorEnabled() ? 'text-green-600' : 'text-gray-500' }}">
                                    {{ $user->twoFactorEnabled() ? 'Enabled' : 'Not enabled' }}
                                </p>
                            </div>
                        </div>
                        @if($user->twoFactorEnabled())
                            <a href="{{ route('two-factor.show') }}" class="inline-flex items-center gap-2 px-3 py-2 text-xs font-medium text-blue-600 bg-blue-50 rounded hover:bg-blue-100 transition">
                                <span class="material-symbols-outlined" style="font-size: 14px;">settings</span>
                                Manage
                            </a>
                        @else
                            <a href="{{ route('two-factor.show') }}" class="inline-flex items-center gap-2 px-3 py-2 text-xs font-medium text-green-600 bg-green-50 rounded hover:bg-green-100 transition">
                                <span class="material-symbols-outlined" style="font-size: 14px;">add</span>
                                Enable
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
