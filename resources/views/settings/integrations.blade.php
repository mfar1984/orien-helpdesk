@extends('layouts.app')

@section('title', 'Integrations')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Platform Settings'],
        ['label' => 'Integrations', 'active' => true]
    ]" />
@endsection

@section('content')
<div class="bg-white border border-gray-200 rounded-lg settings-container">
    <!-- Page Header -->
    <div class="px-6 py-4 flex items-center justify-between settings-page-header">
        <div>
            <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Integrations</h2>
            <p class="text-xs text-gray-500 mt-0.5" style="font-family: Poppins, sans-serif;">Manage third-party integrations and API connections</p>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="border-t border-gray-200">
        <nav class="flex px-6 settings-tabs-nav" aria-label="Tabs">
            @php
                $tabIcons = [
                    'email' => 'mail',
                    'telegram' => 'send',
                    'weather' => 'cloud',
                    'api' => 'api',
                    'spam' => 'shield',
                    'recycle-bin' => 'delete'
                ];
            @endphp
            @foreach($tabs as $key => $label)
                <a href="{{ route('settings.integrations', ['tab' => $key]) }}"
                   class="flex items-center gap-1.5 px-4 py-3 text-xs font-medium border-b-2 transition-colors {{ $currentTab === $key ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                   style="font-family: Poppins, sans-serif;">
                    <span class="material-symbols-outlined" style="font-size: 16px;">{{ $tabIcons[$key] ?? 'settings' }}</span>
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="px-6 py-6 border-t border-gray-200 settings-tab-content">
        @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-xs flex items-center gap-2" style="font-family: Poppins, sans-serif;">
                <span class="material-symbols-outlined" style="font-size: 16px;">check_circle</span>
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-xs flex items-center gap-2" style="font-family: Poppins, sans-serif;">
                <span class="material-symbols-outlined" style="font-size: 16px;">error</span>
                {{ session('error') }}
            </div>
        @endif

        @if($currentTab === 'email')
            @include('settings.integrations.partials.email')
        @elseif($currentTab === 'telegram')
            @include('settings.integrations.partials.telegram')
        @elseif($currentTab === 'weather')
            @include('settings.integrations.partials.weather')
        @elseif($currentTab === 'api')
            @include('settings.integrations.partials.api')
        @elseif($currentTab === 'spam')
            @include('settings.integrations.partials.spam')
        @elseif($currentTab === 'recycle-bin')
            @include('settings.integrations.partials.recycle_bin')
        @endif
    </div>
</div>
@endsection
