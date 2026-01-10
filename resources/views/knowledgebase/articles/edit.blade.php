@extends('layouts.app')

@section('title', 'Edit Article')

@section('breadcrumb')
    <x-breadcrumb />
@endsection

@section('content')
<div class="bg-white border border-gray-200 rounded-lg">
    <!-- Page Header -->
    <div class="px-6 py-4 flex items-center justify-between border-b border-gray-200">
        <div>
            <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Edit Article</h2>
            <p class="text-xs text-gray-500 mt-0.5" style="font-family: Poppins, sans-serif;">Update article content and settings</p>
        </div>
        <a href="{{ route('knowledgebase.settings', ['tab' => 'articles']) }}" 
           class="inline-flex items-center gap-2 px-3 border border-gray-300 text-gray-700 text-xs font-medium rounded hover:bg-gray-50 transition"
           style="min-height: 32px; font-family: Poppins, sans-serif;">
            <span class="material-symbols-outlined" style="font-size: 14px;">arrow_back</span>
            Back
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('knowledgebase.articles.update', $article) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="p-6 space-y-6">
            @if($errors->any())
            <div class="px-4 py-3 bg-red-50 border border-red-200 rounded">
                <ul class="text-xs text-red-800" style="font-family: Poppins, sans-serif;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Title -->
            <div>
                <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                    Title <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title', $article->title) }}" required
                       style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                       placeholder="Article title">
            </div>

            <!-- Category & Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Category <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="category_id" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; background-color: #ffffff; outline: none; cursor: pointer; box-sizing: border-box;">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Status <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="status" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; background-color: #ffffff; outline: none; cursor: pointer; box-sizing: border-box;">
                        <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
            </div>

            <!-- Excerpt -->
            <div>
                <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                    Excerpt
                </label>
                <textarea name="excerpt" rows="2"
                          style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; resize: vertical; outline: none; box-sizing: border-box;"
                          placeholder="Brief summary of the article">{{ old('excerpt', $article->excerpt) }}</textarea>
            </div>

            <!-- Content -->
            <div>
                <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                    Content <span style="color: #ef4444;">*</span>
                </label>
                <textarea id="article-content" name="content">{{ old('content', $article->content) }}</textarea>
            </div>

            <!-- Article Info -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-xs font-medium text-gray-700 mb-3" style="font-family: Poppins, sans-serif;">Article Information</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">Views</p>
                        <p class="text-sm font-medium text-gray-900" style="font-family: Poppins, sans-serif;">{{ number_format($article->views) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">Read Time</p>
                        <p class="text-sm font-medium text-gray-900" style="font-family: Poppins, sans-serif;">{{ $article->read_time }} min</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">Created</p>
                        <p class="text-sm font-medium text-gray-900" style="font-family: Poppins, sans-serif;">{{ format_date($article->created_at) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">Last Updated</p>
                        <p class="text-sm font-medium text-gray-900" style="font-family: Poppins, sans-serif;">{{ format_datetime($article->updated_at) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3">
            <a href="{{ route('knowledgebase.settings', ['tab' => 'articles']) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-xs font-medium rounded hover:bg-gray-50 transition"
               style="font-family: Poppins, sans-serif;">
                Cancel
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition"
                    style="font-family: Poppins, sans-serif;">
                <span class="material-symbols-outlined" style="font-size: 16px;">save</span>
                Update Article
            </button>
        </div>
    </form>
</div>

@push('scripts')
<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/g6yrur70ud67rx6rqxxpsl2ppkodlugcrkp46jker7cbacvu/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    tinymce.init({
        selector: '#article-content',
        height: 400,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code | help',
        content_style: 'body { font-family: Poppins, sans-serif; font-size: 14px; line-height: 1.6; }'
    });
});
</script>
@endpush
@endsection
