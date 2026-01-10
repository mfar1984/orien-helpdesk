@extends('layouts.app')

@section('title', 'Knowledgebase')

@section('breadcrumb')
    <x-breadcrumb />
@endsection

@section('content')
<div class="flex gap-6">
    <!-- Sidebar - Categories -->
    <div class="w-64 flex-shrink-0">
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <!-- Search -->
            <div class="p-3 border-b border-gray-200">
                <form action="{{ route('knowledgebase.index') }}" method="GET">
                    @if($selectedCategory)
                    <input type="hidden" name="category" value="{{ $selectedCategory->slug }}">
                    @endif
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search ?? '' }}" 
                               placeholder="Search articles..." 
                               class="w-full pl-8 pr-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                               style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;">
                        <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400" style="font-size: 14px;">search</span>
                    </div>
                </form>
            </div>

            <!-- Categories List -->
            <div class="p-2">
                <a href="{{ route('knowledgebase.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-md transition {{ !$selectedCategory ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}"
                   style="font-family: Poppins, sans-serif;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">home</span>
                    <span class="text-xs font-medium">All Categories</span>
                </a>
                
                <div class="mt-2 space-y-0.5">
                    @foreach($categories as $category)
                    <a href="{{ route('knowledgebase.index', ['category' => $category->slug]) }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-md transition {{ $selectedCategory && $selectedCategory->id === $category->id ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}"
                       style="font-family: Poppins, sans-serif;">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined" style="font-size: 18px; color: {{ $category->color }};">{{ $category->icon }}</span>
                            <span class="text-xs font-medium">{{ $category->name }}</span>
                        </div>
                        <span class="text-xs text-gray-400">{{ $category->articles_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Settings Link (only show if user has manage permissions) -->
            @php
                $canManageKB = auth()->user()->hasAnyPermissionMatching(['knowledgebase_articles.', 'knowledgebase_categories.']) 
                    || auth()->user()->hasPermission('knowledgebase_manage');
            @endphp
            @if($canManageKB)
            <div class="p-2 border-t border-gray-200">
                <a href="{{ route('knowledgebase.settings') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-md text-gray-500 hover:bg-gray-50 transition"
                   style="font-family: Poppins, sans-serif;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">settings</span>
                    <span class="text-xs font-medium">Settings</span>
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 min-w-0 overflow-hidden">
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 overflow-hidden">
                @if($selectedCategory)
                <div class="flex items-start gap-3 mb-2">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: {{ $selectedCategory->color }}20;">
                        <span class="material-symbols-outlined" style="font-size: 20px; color: {{ $selectedCategory->color }};">{{ $selectedCategory->icon }}</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-base font-semibold text-gray-900 truncate" style="font-family: Poppins, sans-serif;">{{ $selectedCategory->name }}</h2>
                        <p class="text-xs text-gray-500 line-clamp-2" style="font-family: Poppins, sans-serif;">{{ $selectedCategory->description }}</p>
                    </div>
                </div>
                @elseif($search)
                <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Search Results</h2>
                <p class="text-xs text-gray-500 mt-0.5" style="font-family: Poppins, sans-serif;">Showing results for "{{ $search }}"</p>
                @else
                <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Knowledgebase</h2>
                <p class="text-xs text-gray-500 mt-0.5" style="font-family: Poppins, sans-serif;">Browse help articles and documentation</p>
                @endif
            </div>

            <!-- Content -->
            <div class="p-6">
                @if($selectedCategory || $search)
                    <!-- Articles List -->
                    @if($articles->count() > 0)
                    <div class="space-y-2">
                        @foreach($articles as $article)
                        <a href="{{ route('knowledgebase.article', $article) }}" 
                           class="flex items-start gap-4 p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all group">
                            <span class="material-symbols-outlined text-gray-400 group-hover:text-blue-500 flex-shrink-0 mt-0.5" style="font-size: 20px;">article</span>
                            <div class="flex-1 min-w-0 overflow-hidden">
                                <p class="text-sm font-medium text-gray-900 group-hover:text-blue-600 truncate" style="font-family: Poppins, sans-serif;">{{ $article->title }}</p>
                                @if($article->excerpt)
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-2" style="font-family: Poppins, sans-serif;">{{ Str::limit($article->excerpt, 150) }}</p>
                                @endif
                                <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                                    @if(!$selectedCategory)
                                    <span class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">{{ $article->category->name }}</span>
                                    <span class="text-gray-300">•</span>
                                    @endif
                                    <span class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">{{ $article->read_time }} min read</span>
                                    <span class="text-gray-300">•</span>
                                    <span class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">{{ number_format($article->views) }} views</span>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-gray-300 group-hover:text-blue-400 flex-shrink-0" style="font-size: 18px;">chevron_right</span>
                        </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($articles->hasPages())
                    <div class="mt-6">
                        <x-ui.custom-pagination :paginator="$articles" record-label="articles" />
                    </div>
                    @endif
                    @else
                    <div class="text-center py-12">
                        <span class="material-symbols-outlined text-gray-300" style="font-size: 48px;">search_off</span>
                        <p class="text-sm text-gray-500 mt-2" style="font-family: Poppins, sans-serif;">No articles found</p>
                        @if($search)
                        <p class="text-xs text-gray-400 mt-1" style="font-family: Poppins, sans-serif;">Try different keywords or browse categories</p>
                        @endif
                    </div>
                    @endif
                @else
                    <!-- Categories Overview (when no category selected) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        @foreach($categories as $category)
                        <a href="{{ route('knowledgebase.index', ['category' => $category->slug]) }}" 
                           class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-sm transition-all group">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: {{ $category->color }}20;">
                                    <span class="material-symbols-outlined" style="font-size: 20px; color: {{ $category->color }};">{{ $category->icon }}</span>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900 group-hover:text-blue-600 transition-colors" style="font-size: 13px; font-family: Poppins, sans-serif;">{{ $category->name }}</h3>
                                    <p class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">{{ $category->articles_count }} articles</p>
                                </div>
                            </div>
                            @if($category->description)
                            <p class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ Str::limit($category->description, 80) }}</p>
                            @endif
                        </a>
                        @endforeach
                    </div>

                    <!-- Popular Articles -->
                    @if($popularArticles->count() > 0)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-4" style="font-family: Poppins, sans-serif;">Popular Articles</h3>
                        <div class="space-y-2">
                            @foreach($popularArticles as $article)
                            <a href="{{ route('knowledgebase.article', $article) }}" 
                               class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all group">
                                <span class="material-symbols-outlined text-gray-400 group-hover:text-blue-500" style="font-size: 18px;">article</span>
                                <div class="flex-1">
                                    <p class="text-xs font-medium text-gray-900 group-hover:text-blue-600" style="font-family: Poppins, sans-serif;">{{ $article->title }}</p>
                                    <p class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">{{ $article->category->name }} • {{ $article->read_time }} min read</p>
                                </div>
                                <span class="material-symbols-outlined text-gray-300 group-hover:text-blue-400" style="font-size: 16px;">chevron_right</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
