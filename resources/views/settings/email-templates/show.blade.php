@extends('layouts.app')

@section('title', 'View Email Template')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Platform Settings'],
        ['label' => 'Categories', 'url' => route('settings.categories', ['tab' => 'email-templates'])],
        ['label' => $template->name, 'active' => true]
    ]" />
@endsection

@section('content')
<div class="bg-white border border-gray-200">
    <!-- Page Header -->
    <div class="px-6 py-4 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">{{ $template->name }}</h2>
            <p class="text-xs text-gray-500 mt-0.5">View email template details</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('settings.categories', ['tab' => 'email-templates']) }}" 
               class="inline-flex items-center gap-2 px-3 text-xs font-medium rounded transition"
               style="min-height: 32px; background-color: #f3f4f6; color: #374151;">
                <span class="material-symbols-outlined" style="font-size: 14px;">arrow_back</span>
                BACK
            </a>
            <a href="{{ route('settings.categories.email-template.edit', $template) }}" 
               class="inline-flex items-center gap-2 px-3 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition"
               style="min-height: 32px;">
                <span class="material-symbols-outlined" style="font-size: 14px;">edit</span>
                EDIT
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="border-t border-gray-200 p-6">
        <div class="max-w-4xl">
            <!-- Template Info -->
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1" style="font-family: Poppins, sans-serif;">Name</label>
                    <p class="text-sm text-gray-900" style="font-family: Poppins, sans-serif;">{{ $template->name }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1" style="font-family: Poppins, sans-serif;">Type</label>
                    @php
                        $typeColors = [
                            'notification' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
                            'auto-reply' => ['bg' => 'bg-green-100', 'text' => 'text-green-600'],
                            'escalation' => ['bg' => 'bg-red-100', 'text' => 'text-red-600'],
                            'reminder' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600'],
                            'welcome' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
                        ];
                        $typeColor = $typeColors[$template->type] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'];
                    @endphp
                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded {{ $typeColor['bg'] }} {{ $typeColor['text'] }}">
                        {{ ucfirst(str_replace('-', ' ', $template->type)) }}
                    </span>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1" style="font-family: Poppins, sans-serif;">Subject</label>
                    <p class="text-sm text-gray-900" style="font-family: Poppins, sans-serif;">{{ $template->subject }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1" style="font-family: Poppins, sans-serif;">Status</label>
                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded {{ $template->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($template->status) }}
                    </span>
                </div>
            </div>

            @if($template->description)
            <div class="mb-6">
                <label class="block text-xs font-medium text-gray-500 mb-1" style="font-family: Poppins, sans-serif;">Description</label>
                <p class="text-sm text-gray-900" style="font-family: Poppins, sans-serif;">{{ $template->description }}</p>
            </div>
            @endif

            <!-- Email Body Preview -->
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-2" style="font-family: Poppins, sans-serif;">Email Body</label>
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <div class="prose prose-sm max-w-none" style="font-family: Poppins, sans-serif;">
                        {!! $template->body !!}
                    </div>
                </div>
            </div>

            <!-- Available Variables -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h4 class="text-xs font-semibold text-blue-800 mb-2" style="font-family: Poppins, sans-serif;">Available Variables</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach(['{{ticket_id}}', '{{ticket_subject}}', '{{ticket_status}}', '{{ticket_priority}}', '{{customer_name}}', '{{customer_email}}', '{{agent_name}}', '{{time_elapsed}}'] as $var)
                    <code class="px-2 py-1 bg-white border border-blue-200 rounded text-xs text-blue-700" style="font-family: monospace;">{{ $var }}</code>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
