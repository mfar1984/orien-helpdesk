@extends('layouts.app')

@section('title', $article->title)

@section('breadcrumb')
    <x-breadcrumb />
@endsection

@section('content')
<div class="kb-article-container flex gap-6">
    <!-- Main Content -->
    <div class="kb-article-main flex-1">
        <div class="bg-white border border-gray-200 rounded-lg">
            <!-- Article Header -->
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium" style="background-color: {{ $article->category->color }}20; color: {{ $article->category->color }}; font-family: Poppins, sans-serif;">
                        <span class="material-symbols-outlined mr-1" style="font-size: 12px;">{{ $article->category->icon }}</span>
                        {{ $article->category->name }}
                    </span>
                </div>
                <h1 class="text-xl font-semibold text-gray-900 mb-2" style="font-family: Poppins, sans-serif;">{{ $article->title }}</h1>
                <div class="flex items-center gap-4 text-xs text-gray-400" style="font-family: Poppins, sans-serif;">
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined" style="font-size: 14px;">schedule</span>
                        {{ $article->read_time }} min read
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined" style="font-size: 14px;">visibility</span>
                        {{ number_format($article->views) }} views
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined" style="font-size: 14px;">calendar_today</span>
                        {{ format_date($article->published_at ?? $article->created_at) }}
                    </span>
                </div>
            </div>

            <!-- Article Content -->
            <div class="px-6 py-6">
                <div class="prose prose-sm max-w-none" style="font-family: Poppins, sans-serif; font-size: 13px; line-height: 1.7; color: #374151;">
                    {!! $article->content !!}
                </div>
            </div>

            <!-- Article Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">
                        Was this article helpful?
                    </p>
                    <div class="flex items-center gap-2">
                        <button class="inline-flex items-center gap-1 px-3 py-1.5 border border-gray-300 rounded text-xs text-gray-600 hover:bg-gray-100 transition" style="font-family: Poppins, sans-serif;">
                            <span class="material-symbols-outlined" style="font-size: 14px;">thumb_up</span>
                            Yes
                        </button>
                        <button class="inline-flex items-center gap-1 px-3 py-1.5 border border-gray-300 rounded text-xs text-gray-600 hover:bg-gray-100 transition" style="font-family: Poppins, sans-serif;">
                            <span class="material-symbols-outlined" style="font-size: 14px;">thumb_down</span>
                            No
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="kb-article-sidebar w-72 flex-shrink-0">
        <!-- Related Articles -->
        @if($relatedArticles->count() > 0)
        <div class="bg-white border border-gray-200 rounded-lg p-4 mb-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3" style="font-family: Poppins, sans-serif;">Related Articles</h3>
            <div class="space-y-2">
                @foreach($relatedArticles as $related)
                <a href="{{ route('knowledgebase.article', $related) }}" class="block p-2 rounded hover:bg-gray-50 transition group">
                    <p class="text-xs font-medium text-gray-700 group-hover:text-blue-600" style="font-family: Poppins, sans-serif;">{{ $related->title }}</p>
                    <p class="text-xs text-gray-400 mt-0.5" style="font-family: Poppins, sans-serif;">{{ $related->read_time }} min read</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Back to Category -->
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <a href="{{ route('knowledgebase.index', ['category' => $article->category->slug]) }}" 
               class="flex items-center gap-2 text-xs text-blue-600 hover:text-blue-700" style="font-family: Poppins, sans-serif;">
                <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span>
                Back to {{ $article->category->name }}
            </a>
        </div>
    </div>
</div>

<style>
.prose h2 { font-size: 16px; font-weight: 600; margin-top: 24px; margin-bottom: 12px; color: #111827; }
.prose h3 { font-size: 14px; font-weight: 600; margin-top: 20px; margin-bottom: 8px; color: #374151; }
.prose p { margin-bottom: 12px; }
.prose ul, .prose ol { margin-bottom: 12px; padding-left: 20px; }
.prose li { margin-bottom: 4px; }
.prose code { background-color: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-size: 12px; }
.prose pre { background-color: #1f2937; color: #f9fafb; padding: 16px; border-radius: 6px; overflow-x: auto; margin-bottom: 16px; }
.prose pre code { background: none; padding: 0; }
</style>
@endsection
