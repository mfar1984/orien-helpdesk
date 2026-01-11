@extends('layouts.app')

@section('title', 'Categories Settings')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Platform Settings'],
        ['label' => 'Categories', 'active' => true]
    ]" />
@endsection

@section('content')
<div class="bg-white border border-gray-200 settings-container">
    <!-- Page Header -->
    <div class="px-6 py-4 flex items-center justify-between settings-page-header">
        <div>
            <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Categories Settings</h2>
            <p class="text-xs text-gray-500 mt-0.5">Manage categories, priorities, status, SLA rules and email templates</p>
        </div>
        <div class="flex items-center gap-2">
            @php
                $buttonConfig = [
                    'categories' => ['icon' => 'category', 'label' => 'CATEGORY', 'onclick' => 'showCreateModal()'],
                    'priorities' => ['icon' => 'flag', 'label' => 'PRIORITY', 'onclick' => 'showCreateModal()'],
                    'status' => ['icon' => 'toggle_on', 'label' => 'STATUS', 'onclick' => 'showCreateModal()'],
                    'sla-rules' => ['icon' => 'schedule', 'label' => 'SLA RULE', 'onclick' => 'showCreateModal()'],
                    'email-templates' => null,
                ];
                $btn = $buttonConfig[$currentTab] ?? null;
            @endphp
            @if($btn && ($canCreate ?? false))
            <button type="button" onclick="{{ $btn['onclick'] }}"
               class="inline-flex items-center gap-2 px-3 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition"
               style="min-height: 32px;">
                <span class="material-symbols-outlined" style="font-size: 14px;">{{ $btn['icon'] }}</span>
                {{ $btn['label'] }}
            </button>
            @endif
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="border-t border-gray-200">
        <nav class="flex px-6 settings-tabs-nav" aria-label="Tabs">
            @foreach($tabs as $key => $label)
                <a href="{{ route('settings.categories', ['tab' => $key]) }}"
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
            <form action="{{ route('settings.categories') }}" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="tab" value="{{ $currentTab }}">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search {{ strtolower($tabs[$currentTab]) }}..." 
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
                    <button type="button" onclick="window.location.href='{{ route('settings.categories', ['tab' => $currentTab]) }}'" class="inline-flex items-center gap-2 px-3 text-white text-xs font-medium rounded transition" style="min-height: 32px; background-color: #dc2626;">
                        <span class="material-symbols-outlined" style="font-size: 14px;">refresh</span>
                        RESET
                    </button>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="px-6 settings-table-container">
            @include('settings.categories.partials.' . str_replace('-', '_', $currentTab) . '_table')
        </div>

        <!-- Pagination -->
        <div class="px-6 py-3">
            <x-ui.custom-pagination :paginator="$items" :record-label="strtolower($tabs[$currentTab])" :tab-param="$currentTab" />
        </div>
    </div>
</div>

<!-- Include Modals based on current tab -->
@if($currentTab !== 'email-templates')
    @include('settings.categories.partials.' . str_replace('-', '_', $currentTab) . '_modal')
@endif

<!-- Delete Confirmation Modal -->
<x-modals.delete-confirmation 
    id="delete-modal"
    title="Delete {{ ucfirst(str_replace('-', ' ', rtrim($currentTab, 's'))) }}"
    message="Are you sure you want to delete this item? This action cannot be undone."
/>
@endsection
