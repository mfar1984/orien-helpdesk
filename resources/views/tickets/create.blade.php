@extends('layouts.app')

@section('title', 'Create Ticket')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Tickets', 'url' => route('tickets.index')],
        ['label' => 'Create Ticket', 'active' => true]
    ]" />
@endsection

@section('content')
<div class="bg-white border border-gray-200">
    <!-- Page Header -->
    <div class="ticket-create-header px-6 py-4 flex items-center justify-between border-b border-gray-200">
        <div class="flex items-center gap-3">
            <div style="width: 36px; height: 36px; border-radius: 8px; background-color: #3b82f6; display: flex; align-items: center; justify-content: center;">
                <span class="material-symbols-outlined" style="font-size: 20px; color: #ffffff;">confirmation_number</span>
            </div>
            <div>
                <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Create New Ticket</h2>
                <p class="text-xs text-gray-500 mt-0.5" style="font-family: Poppins, sans-serif;">Submit a new support request</p>
            </div>
        </div>
        <a href="{{ route('tickets.index') }}" 
           class="inline-flex items-center gap-1 px-3 text-gray-600 text-xs font-medium rounded border border-gray-300 hover:bg-gray-50 transition"
           style="min-height: 32px; font-family: Poppins, sans-serif;">
            <span class="material-symbols-outlined" style="font-size: 14px;">arrow_back</span>
            BACK
        </a>
    </div>

    <!-- Main Content - 2 Column Layout -->
    <div class="ticket-create-content" style="padding: 24px; display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Left Column - Form -->
        <div style="border: 1px solid #e5e7eb; border-radius: 8px; background-color: #ffffff; overflow: hidden;">
            <div style="padding: 14px 16px; background-color: #eff6ff; border-bottom: 1px solid #bfdbfe; display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background-color: #3b82f6; display: flex; align-items: center; justify-content: center;">
                    <span class="material-symbols-outlined" style="font-size: 18px; color: #ffffff;">edit_note</span>
                </div>
                <h4 style="font-size: 13px; font-weight: 600; color: #1e40af; font-family: Poppins, sans-serif; margin: 0;">Ticket Details</h4>
            </div>
            
            <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" style="padding: 20px;">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <!-- Subject -->
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                            Subject <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required
                               style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                               placeholder="Brief description of the issue"
                               onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                        @error('subject')
                            <p style="font-size: 11px; color: #ef4444; margin-top: 4px; font-family: Poppins, sans-serif;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority & Category Row -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <!-- Priority Custom Dropdown -->
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                                Priority <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="hidden" name="priority_id" id="priority-input" value="{{ old('priority_id', $priorities->where('name', 'Medium')->first()->id ?? $priorities->first()->id) }}" required>
                            <div style="position: relative;">
                                <div id="priority-dropdown-btn" onclick="togglePriorityDropdown()" 
                                     style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; background-color: #ffffff; cursor: pointer; display: flex; align-items: center; justify-content: space-between; min-height: 40px; box-sizing: border-box;">
                                    @php $defaultPriority = $priorities->where('name', 'Medium')->first() ?? $priorities->first(); @endphp
                                    <div id="priority-selected" style="display: flex; align-items: center; gap: 8px;">
                                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 4px; background-color: {{ $defaultPriority->color }}20;">
                                            <span class="material-symbols-outlined" style="font-size: 14px; color: {{ $defaultPriority->color }};">{{ $defaultPriority->icon }}</span>
                                        </span>
                                        <span style="font-size: 12px; color: #1f2937; font-family: Poppins, sans-serif;">{{ $defaultPriority->name }}</span>
                                    </div>
                                    <span class="material-symbols-outlined" style="font-size: 18px; color: #9ca3af;">expand_more</span>
                                </div>
                                <div id="priority-dropdown-list" style="display: none; position: absolute; top: 100%; left: 0; right: 0; margin-top: 4px; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 6px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); z-index: 50; max-height: 200px; overflow-y: auto;">
                                    @foreach($priorities as $priority)
                                    <div onclick="selectPriority('{{ $priority->id }}', '{{ $priority->name }}', '{{ $priority->color }}', '{{ $priority->icon }}')" 
                                         style="padding: 10px 12px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: background-color 0.15s;"
                                         onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#ffffff'">
                                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 6px; background-color: {{ $priority->color }}20;">
                                            <span class="material-symbols-outlined" style="font-size: 16px; color: {{ $priority->color }};">{{ $priority->icon }}</span>
                                        </span>
                                        <span style="font-size: 12px; color: #1f2937; font-family: Poppins, sans-serif;">{{ $priority->name }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @error('priority_id')
                                <p style="font-size: 11px; color: #ef4444; margin-top: 4px; font-family: Poppins, sans-serif;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category Custom Dropdown -->
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                                Category
                            </label>
                            <input type="hidden" name="category_id" id="category-input" value="{{ old('category_id') }}">
                            <div style="position: relative;">
                                <div id="category-dropdown-btn" onclick="toggleCategoryDropdown()" 
                                     style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; background-color: #ffffff; cursor: pointer; display: flex; align-items: center; justify-content: space-between; min-height: 40px; box-sizing: border-box;">
                                    <div id="category-selected" style="display: flex; align-items: center; gap: 8px;">
                                        <span style="font-size: 12px; color: #9ca3af; font-family: Poppins, sans-serif;">Select Category</span>
                                    </div>
                                    <span class="material-symbols-outlined" style="font-size: 18px; color: #9ca3af;">expand_more</span>
                                </div>
                                <div id="category-dropdown-list" style="display: none; position: absolute; top: 100%; left: 0; right: 0; margin-top: 4px; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 6px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); z-index: 50; max-height: 200px; overflow-y: auto;">
                                    <div onclick="selectCategory('', 'Select Category', '', '', '')" 
                                         style="padding: 10px 12px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: background-color 0.15s; color: #9ca3af;"
                                         onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#ffffff'">
                                        <span style="font-size: 12px; font-family: Poppins, sans-serif;">-- No Category --</span>
                                    </div>
                                    @foreach($categories as $category)
                                    <div onclick="selectCategory('{{ $category->id }}', '{{ $category->name }}', '{{ $category->color }}', '{{ $category->icon }}')" 
                                         style="padding: 10px 12px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: background-color 0.15s;"
                                         onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#ffffff'">
                                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 6px; background-color: {{ $category->color }}20;">
                                            <span class="material-symbols-outlined" style="font-size: 16px; color: {{ $category->color }};">{{ $category->icon }}</span>
                                        </span>
                                        <span style="font-size: 12px; color: #1f2937; font-family: Poppins, sans-serif;">{{ $category->name }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message -->
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                            Message <span style="color: #ef4444;">*</span>
                        </label>
                        <textarea name="description" required rows="6"
                                  style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; resize: vertical; outline: none; box-sizing: border-box;"
                                  placeholder="Describe your issue in detail..."
                                  onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">{{ old('description') }}</textarea>
                        @error('description')
                            <p style="font-size: 11px; color: #ef4444; margin-top: 4px; font-family: Poppins, sans-serif;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Attachments -->
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">Attachments</label>
                        <div style="position: relative;">
                            <input type="file" name="attachments[]" multiple id="ticket-attachments" style="display: none;">
                            <label for="ticket-attachments" 
                                   style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 16px; border: 2px dashed #d1d5db; border-radius: 6px; background-color: #f9fafb; cursor: pointer; transition: all 0.15s; box-sizing: border-box;"
                                   onmouseover="this.style.borderColor='#3b82f6'; this.style.backgroundColor='#eff6ff'" 
                                   onmouseout="this.style.borderColor='#d1d5db'; this.style.backgroundColor='#f9fafb'">
                                <span class="material-symbols-outlined" style="font-size: 24px; color: #9ca3af;">cloud_upload</span>
                                <span id="attachment-label" style="font-size: 12px; color: #6b7280; font-family: Poppins, sans-serif;">Click to upload files</span>
                            </label>
                        </div>
                        <p style="font-size: 10px; color: #9ca3af; margin-top: 4px; font-family: Poppins, sans-serif;">Max 10MB per file. Supported: JPG, PNG, PDF, DOC, DOCX</p>
                    </div>

                    <!-- Action Buttons -->
                    <div style="display: flex; align-items: center; gap: 12px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
                        <button type="submit" 
                                style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background-color: #3b82f6; color: #ffffff; border: none; border-radius: 6px; font-size: 11px; font-weight: 500; font-family: Poppins, sans-serif; cursor: pointer;">
                            <span class="material-symbols-outlined" style="font-size: 16px;">send</span>
                            SUBMIT TICKET
                        </button>
                        <a href="{{ route('tickets.index') }}" 
                           style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background-color: #ffffff; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; font-size: 11px; font-weight: 500; font-family: Poppins, sans-serif; text-decoration: none;">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Right Column - Info Panels -->
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <!-- Support Hours -->
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; background-color: #ffffff; overflow: hidden;">
                <div style="padding: 14px 16px; background-color: #f0fdf4; border-bottom: 1px solid #bbf7d0; display: flex; align-items: center; gap: 10px;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background-color: #22c55e; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="font-size: 18px; color: #ffffff;">schedule</span>
                    </div>
                    <h4 style="font-size: 12px; font-weight: 600; color: #166534; font-family: Poppins, sans-serif; margin: 0;">Support Hours</h4>
                </div>
                <div style="padding: 16px;">
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div style="display: flex; align-items: flex-start; gap: 12px;">
                            <div style="width: 28px; height: 28px; border-radius: 6px; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span class="material-symbols-outlined" style="font-size: 16px; color: #64748b;">calendar_today</span>
                            </div>
                            <div style="flex: 1;">
                                <p style="font-size: 10px; color: #94a3b8; margin: 0 0 2px 0; text-transform: uppercase; letter-spacing: 0.5px;">Working Days</p>
                                <p style="font-size: 12px; font-weight: 500; color: #1e293b; margin: 0; font-family: Poppins, sans-serif;">Monday - Friday</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; gap: 12px;">
                            <div style="width: 28px; height: 28px; border-radius: 6px; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span class="material-symbols-outlined" style="font-size: 16px; color: #64748b;">access_time</span>
                            </div>
                            <div style="flex: 1;">
                                <p style="font-size: 10px; color: #94a3b8; margin: 0 0 2px 0; text-transform: uppercase; letter-spacing: 0.5px;">Working Hours</p>
                                <p style="font-size: 12px; font-weight: 500; color: #1e293b; margin: 0; font-family: Poppins, sans-serif;">9:00 AM - 6:00 PM</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; gap: 12px;">
                            <div style="width: 28px; height: 28px; border-radius: 6px; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span class="material-symbols-outlined" style="font-size: 16px; color: #64748b;">public</span>
                            </div>
                            <div style="flex: 1;">
                                <p style="font-size: 10px; color: #94a3b8; margin: 0 0 2px 0; text-transform: uppercase; letter-spacing: 0.5px;">Timezone</p>
                                <p style="font-size: 12px; font-weight: 500; color: #1e293b; margin: 0; font-family: Poppins, sans-serif;">Asia/Kuala_Lumpur (GMT+8)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Response Time -->
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; background-color: #ffffff; overflow: hidden;">
                <div style="padding: 14px 16px; background-color: #fef3c7; border-bottom: 1px solid #fde68a; display: flex; align-items: center; gap: 10px;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background-color: #f59e0b; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="font-size: 18px; color: #ffffff;">hourglass_top</span>
                    </div>
                    <h4 style="font-size: 12px; font-weight: 600; color: #92400e; font-family: Poppins, sans-serif; margin: 0;">Response Time</h4>
                </div>
                <div style="padding: 16px;">
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background-color: #fecaca; border-radius: 6px;">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined" style="font-size: 14px; color: #dc2626;">priority_high</span>
                                <span style="font-size: 11px; color: #991b1b; font-family: Poppins, sans-serif; font-weight: 500;">Urgent</span>
                            </div>
                            <span style="font-size: 11px; color: #991b1b; font-family: Poppins, sans-serif;">< 1 hour</span>
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background-color: #fed7aa; border-radius: 6px;">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined" style="font-size: 14px; color: #ea580c;">arrow_upward</span>
                                <span style="font-size: 11px; color: #9a3412; font-family: Poppins, sans-serif; font-weight: 500;">High</span>
                            </div>
                            <span style="font-size: 11px; color: #9a3412; font-family: Poppins, sans-serif;">< 4 hours</span>
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background-color: #fef08a; border-radius: 6px;">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined" style="font-size: 14px; color: #ca8a04;">flag</span>
                                <span style="font-size: 11px; color: #854d0e; font-family: Poppins, sans-serif; font-weight: 500;">Medium</span>
                            </div>
                            <span style="font-size: 11px; color: #854d0e; font-family: Poppins, sans-serif;">< 8 hours</span>
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background-color: #dcfce7; border-radius: 6px;">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined" style="font-size: 14px; color: #22c55e;">arrow_downward</span>
                                <span style="font-size: 11px; color: #166534; font-family: Poppins, sans-serif; font-weight: 500;">Low</span>
                            </div>
                            <span style="font-size: 11px; color: #166534; font-family: Poppins, sans-serif;">< 24 hours</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; background-color: #ffffff; overflow: hidden;">
                <div style="padding: 14px 16px; background-color: #eff6ff; border-bottom: 1px solid #bfdbfe; display: flex; align-items: center; gap: 10px;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background-color: #3b82f6; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="font-size: 18px; color: #ffffff;">tips_and_updates</span>
                    </div>
                    <h4 style="font-size: 12px; font-weight: 600; color: #1e40af; font-family: Poppins, sans-serif; margin: 0;">Tips for Faster Resolution</h4>
                </div>
                <div style="padding: 16px;">
                    <ul style="margin: 0; padding: 0 0 0 16px; display: flex; flex-direction: column; gap: 8px;">
                        <li style="font-size: 11px; color: #4b5563; font-family: Poppins, sans-serif;">Be specific about the issue you're experiencing</li>
                        <li style="font-size: 11px; color: #4b5563; font-family: Poppins, sans-serif;">Include any error messages or screenshots</li>
                        <li style="font-size: 11px; color: #4b5563; font-family: Poppins, sans-serif;">Mention steps to reproduce the problem</li>
                        <li style="font-size: 11px; color: #4b5563; font-family: Poppins, sans-serif;">Select the appropriate priority level</li>
                    </ul>
                </div>
            </div>

            <!-- Contact Info -->
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; background-color: #ffffff; overflow: hidden;">
                <div style="padding: 14px 16px; background-color: #faf5ff; border-bottom: 1px solid #e9d5ff; display: flex; align-items: center; gap: 10px;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background-color: #a855f7; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="font-size: 18px; color: #ffffff;">contact_support</span>
                    </div>
                    <h4 style="font-size: 12px; font-weight: 600; color: #6b21a8; font-family: Poppins, sans-serif; margin: 0;">Need Urgent Help?</h4>
                </div>
                <div style="padding: 16px;">
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span class="material-symbols-outlined" style="font-size: 16px; color: #64748b;">phone</span>
                            <span style="font-size: 12px; color: #1e293b; font-family: Poppins, sans-serif;">+60 3-1234 5678</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span class="material-symbols-outlined" style="font-size: 16px; color: #64748b;">mail</span>
                            <span style="font-size: 12px; color: #1e293b; font-family: Poppins, sans-serif;">support@company.com</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Priority Dropdown
function togglePriorityDropdown() {
    const list = document.getElementById('priority-dropdown-list');
    const categoryList = document.getElementById('category-dropdown-list');
    categoryList.style.display = 'none';
    list.style.display = list.style.display === 'none' ? 'block' : 'none';
}

function selectPriority(id, name, color, icon) {
    document.getElementById('priority-input').value = id;
    document.getElementById('priority-selected').innerHTML = `
        <span style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 4px; background-color: ${color}20;">
            <span class="material-symbols-outlined" style="font-size: 14px; color: ${color};">${icon}</span>
        </span>
        <span style="font-size: 12px; color: #1f2937; font-family: Poppins, sans-serif;">${name}</span>
    `;
    document.getElementById('priority-dropdown-list').style.display = 'none';
}

// Category Dropdown
function toggleCategoryDropdown() {
    const list = document.getElementById('category-dropdown-list');
    const priorityList = document.getElementById('priority-dropdown-list');
    priorityList.style.display = 'none';
    list.style.display = list.style.display === 'none' ? 'block' : 'none';
}

function selectCategory(id, name, color, icon) {
    document.getElementById('category-input').value = id;
    const selected = document.getElementById('category-selected');
    if (id && color && icon) {
        selected.innerHTML = `
            <span style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 4px; background-color: ${color}20;">
                <span class="material-symbols-outlined" style="font-size: 14px; color: ${color};">${icon}</span>
            </span>
            <span style="font-size: 12px; color: #1f2937; font-family: Poppins, sans-serif;">${name}</span>
        `;
    } else {
        selected.innerHTML = `<span style="font-size: 12px; color: #9ca3af; font-family: Poppins, sans-serif;">Select Category</span>`;
    }
    document.getElementById('category-dropdown-list').style.display = 'none';
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    const priorityBtn = document.getElementById('priority-dropdown-btn');
    const priorityList = document.getElementById('priority-dropdown-list');
    const categoryBtn = document.getElementById('category-dropdown-btn');
    const categoryList = document.getElementById('category-dropdown-list');
    
    if (!priorityBtn.contains(e.target) && !priorityList.contains(e.target)) {
        priorityList.style.display = 'none';
    }
    if (!categoryBtn.contains(e.target) && !categoryList.contains(e.target)) {
        categoryList.style.display = 'none';
    }
});

// File attachment label update
document.getElementById('ticket-attachments').addEventListener('change', function(e) {
    const label = document.getElementById('attachment-label');
    const count = e.target.files.length;
    if (count > 0) {
        label.textContent = count + ' file' + (count > 1 ? 's' : '') + ' selected';
    } else {
        label.textContent = 'Click to upload files';
    }
});
</script>
@endpush
@endsection
