@extends('layouts.app')

@section('title', 'Knowledgebase Settings')

@section('breadcrumb')
    <x-breadcrumb />
@endsection

@section('content')
<div class="kb-settings-container bg-white border border-gray-200">
    <!-- Page Header -->
    <div class="kb-settings-header px-6 py-4 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Knowledgebase Settings</h2>
            <p class="text-xs text-gray-500 mt-0.5">Manage articles and categories</p>
        </div>
        <div class="flex items-center gap-2">
            @if($currentTab === 'articles')
                @if(auth()->user()->hasPermission('knowledgebase_articles.create'))
                <button type="button" onclick="showCreateArticleModal()"
                   class="inline-flex items-center gap-2 px-3 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition"
                   style="min-height: 32px;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">article</span>
                    ARTICLE
                </button>
                @endif
            @else
                @if(auth()->user()->hasPermission('knowledgebase_categories.create'))
                <button type="button" onclick="showCreateCategoryModal()"
                   class="inline-flex items-center gap-2 px-3 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition"
                   style="min-height: 32px;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">folder</span>
                    CATEGORY
                </button>
                @endif
            @endif
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="border-t border-gray-200">
        <nav class="kb-settings-tabs flex px-6" aria-label="Tabs">
            @foreach($tabs as $key => $label)
                <a href="{{ route('knowledgebase.settings', ['tab' => $key]) }}"
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
        <div class="px-6 py-3">
            <form action="{{ route('knowledgebase.settings') }}" method="GET" class="kb-settings-filter flex items-center gap-2">
                <input type="hidden" name="tab" value="{{ $currentTab }}">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search {{ $currentTab }}..." 
                           class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                           style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                </div>
                <select name="status" class="px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 min-w-[120px]" style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                    <option value="">All Status</option>
                    @if($currentTab === 'articles')
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    @else
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    @endif
                </select>
                <div class="filter-buttons flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-3 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition" style="min-height: 32px;">
                        <span class="material-symbols-outlined" style="font-size: 14px;">search</span>
                        SEARCH
                    </button>
                    <button type="button" onclick="window.location.href='{{ route('knowledgebase.settings', ['tab' => $currentTab]) }}'" class="inline-flex items-center gap-2 px-3 text-white text-xs font-medium rounded transition" style="min-height: 32px; background-color: #dc2626;">
                        <span class="material-symbols-outlined" style="font-size: 14px;">refresh</span>
                        RESET
                    </button>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="kb-settings-table px-6">
            @if($currentTab === 'articles')
            @include('knowledgebase.partials.articles_table')
            @else
            @include('knowledgebase.partials.categories_table')
            @endif
        </div>

        <!-- Pagination -->
        <div class="px-6 py-3">
            <x-ui.custom-pagination :paginator="$items" :record-label="$currentTab" :tab-param="$currentTab" />
        </div>
    </div>
</div>

<!-- Create Article Modal -->
@include('knowledgebase.partials.article_modal')

<!-- Create Category Modal -->
@include('knowledgebase.partials.category_modal')

<!-- Delete Confirmation Modal -->
<x-modals.delete-confirmation 
    id="delete-modal"
    title="Delete {{ $currentTab === 'articles' ? 'Article' : 'Category' }}"
    message="Are you sure you want to delete this item? This action cannot be undone."
/>
@endsection
