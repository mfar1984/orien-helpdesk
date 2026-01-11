@extends('layouts.app')

@section('title', 'Edit Email Template')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Platform Settings'],
        ['label' => 'Categories', 'url' => route('settings.categories', ['tab' => 'email-templates'])],
        ['label' => 'Edit: ' . $template->name, 'active' => true]
    ]" />
@endsection

@section('content')
<form action="{{ route('settings.categories.email-template.update', $template) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="bg-white border border-gray-200">
        <!-- Page Header -->
        <div class="px-6 py-4 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Edit Email Template</h2>
                <p class="text-xs text-gray-500 mt-0.5">Update email template content and settings</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('settings.categories', ['tab' => 'email-templates']) }}" 
                   class="inline-flex items-center gap-2 px-3 text-xs font-medium rounded transition"
                   style="min-height: 32px; background-color: #f3f4f6; color: #374151;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">close</span>
                    CANCEL
                </a>
                <button type="submit" 
                   class="inline-flex items-center gap-2 px-3 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition"
                   style="min-height: 32px;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">save</span>
                    SAVE
                </button>
            </div>
        </div>

        <!-- Content - Full Width -->
        <div class="border-t border-gray-200 p-6" style="width: 100%;">
            <div style="width: 100%;">
                @if($errors->any())
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded">
                    <ul class="text-xs text-red-800" style="font-family: Poppins, sans-serif;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                            Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $template->name) }}" required
                               style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                            Type <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="type" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; background-color: #ffffff; outline: none; cursor: pointer; box-sizing: border-box;">
                            @foreach($types as $value => $label)
                            <option value="{{ $value }}" {{ old('type', $template->type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                            Subject <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="subject" value="{{ old('subject', $template->subject) }}" required
                               style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                            Status <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="status" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; background-color: #ffffff; outline: none; cursor: pointer; box-sizing: border-box;">
                            <option value="active" {{ old('status', $template->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $template->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Description
                    </label>
                    <textarea name="description" rows="2"
                              style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; resize: vertical; outline: none; box-sizing: border-box;">{{ old('description', $template->description) }}</textarea>
                </div>

                <div class="mb-4">
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Email Body <span style="color: #ef4444;">*</span>
                    </label>
                    <textarea id="email-body" name="body">{{ old('body', $template->body) }}</textarea>
                </div>

            <!-- Available Variables -->
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h4 class="text-xs font-semibold text-blue-800 mb-2" style="font-family: Poppins, sans-serif;">Available Variables (click to insert)</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach(['{{ticket_id}}', '{{ticket_subject}}', '{{ticket_status}}', '{{ticket_priority}}', '{{customer_name}}', '{{customer_email}}', '{{agent_name}}', '{{time_elapsed}}'] as $var)
                    <button type="button" onclick="insertVariable('{{ $var }}')"
                            class="px-2 py-1 bg-white border border-blue-200 rounded text-xs text-blue-700 hover:bg-blue-100 transition cursor-pointer" 
                            style="font-family: monospace;">{{ $var }}</button>
                    @endforeach
                </div>
            </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/{{ env('TINYMCE_API_KEY', 'no-api-key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
let editor = null;

tinymce.init({
    selector: '#email-body',
    height: 400,
    menubar: false,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code | help',
    content_style: 'body { font-family: Poppins, sans-serif; font-size: 12px; }',
    setup: function(ed) {
        editor = ed;
    }
});

function insertVariable(variable) {
    if (editor) {
        editor.insertContent(variable);
    }
}
</script>
@endpush
@endsection
