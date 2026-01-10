<!-- Create/Edit Category Modal -->
<div id="item-modal" class="fixed inset-0 flex items-center justify-center" style="background-color: rgba(0,0,0,0.5) !important; z-index: 9999 !important; display: none;">
    <div style="background-color: #ffffff !important; border-radius: 12px !important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important; width: 100% !important; max-width: 480px !important; margin: 16px !important; overflow: hidden !important;">
        <div style="padding: 16px 20px !important; border-bottom: 1px solid #e5e7eb !important; display: flex !important; align-items: center !important; justify-content: space-between !important; background-color: #f9fafb !important;">
            <div style="display: flex !important; align-items: center !important; gap: 10px !important;">
                <div style="width: 36px !important; height: 36px !important; border-radius: 8px !important; background-color: #3b82f6 !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                    <span class="material-symbols-outlined" style="font-size: 20px !important; color: #ffffff !important;">category</span>
                </div>
                <h3 id="modal-title" style="font-size: 14px !important; font-weight: 600 !important; color: #111827 !important; font-family: Poppins, sans-serif !important; margin: 0 !important;">Create Category</h3>
            </div>
            <button type="button" onclick="closeModal()" style="width: 32px !important; height: 32px !important; border-radius: 6px !important; border: none !important; background-color: transparent !important; cursor: pointer !important; display: flex !important; align-items: center !important; justify-content: center !important;" onmouseover="this.style.backgroundColor='#e5e7eb'" onmouseout="this.style.backgroundColor='transparent'">
                <span class="material-symbols-outlined" style="font-size: 20px !important; color: #6b7280 !important;">close</span>
            </button>
        </div>
        <form id="item-form" method="POST" action="{{ route('settings.categories.category.store') }}">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            <div style="padding: 20px !important; max-height: 60vh !important; overflow-y: auto !important;">
                <div style="display: flex !important; flex-direction: column !important; gap: 16px !important;">
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Name <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <input type="text" name="name" id="input-name" required
                               style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important;"
                               placeholder="Category name"
                               onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Description
                        </label>
                        <textarea name="description" id="input-description" rows="2"
                                  style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; resize: vertical !important; outline: none !important; box-sizing: border-box !important;"
                                  placeholder="Category description"
                                  onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'"></textarea>
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 8px !important; font-family: Poppins, sans-serif !important;">
                            Color <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <div style="display: flex !important; flex-wrap: wrap !important; gap: 8px !important; margin-bottom: 8px !important;">
                            @php
                                $colors = ['#ef4444', '#f97316', '#f59e0b', '#eab308', '#84cc16', '#22c55e', '#10b981', '#14b8a6', '#06b6d4', '#0ea5e9', '#3b82f6', '#6366f1', '#8b5cf6', '#a855f7', '#d946ef', '#ec4899', '#f43f5e', '#64748b', '#374151', '#1f2937'];
                            @endphp
                            @foreach($colors as $color)
                            <button type="button" onclick="selectColor('{{ $color }}')" class="color-btn" data-color="{{ $color }}"
                                    style="width: 32px !important; height: 32px !important; border-radius: 6px !important; background-color: {{ $color }} !important; border: 2px solid transparent !important; cursor: pointer !important; transition: all 0.15s !important;"
                                    onmouseover="if(this.dataset.color !== document.getElementById('input-color').value) this.style.transform='scale(1.1)'"
                                    onmouseout="this.style.transform='scale(1)'">
                            </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="color" id="input-color" value="#3b82f6" required>
                        <div style="display: flex !important; align-items: center !important; gap: 8px !important; font-size: 10px !important; color: #6b7280 !important;">
                            <span>Selected:</span>
                            <div id="selected-color-preview" style="width: 24px !important; height: 24px !important; border-radius: 4px !important; background-color: #3b82f6 !important; border: 1px solid #e5e7eb !important;"></div>
                            <span id="selected-color-code" style="font-family: monospace !important; color: #374151 !important;">#3b82f6</span>
                        </div>
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 8px !important; font-family: Poppins, sans-serif !important;">
                            Icon <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <div style="display: grid !important; grid-template-columns: repeat(10, 1fr) !important; gap: 6px !important; padding: 8px !important; border: 1px solid #e5e7eb !important; border-radius: 6px !important; background-color: #f9fafb !important; max-height: 150px !important; overflow-y: auto !important;">
                            @php
                                $icons = ['category', 'label', 'bookmark', 'star', 'favorite', 'bug_report', 'build', 'code', 'computer', 'devices', 'dns', 'extension', 'help', 'info', 'lightbulb', 'memory', 'monitor', 'mouse', 'phone_android', 'print', 'router', 'security', 'settings', 'storage', 'support', 'sync', 'terminal', 'update', 'verified', 'warning', 'wifi', 'work', 'folder', 'description', 'article', 'assignment', 'chat', 'email', 'forum', 'message', 'notifications', 'person', 'group', 'business', 'shopping_cart'];
                            @endphp
                            @foreach($icons as $iconName)
                            <button type="button" onclick="selectIcon('{{ $iconName }}')" class="icon-btn" data-icon="{{ $iconName }}"
                                    style="width: 100% !important; aspect-ratio: 1 !important; border-radius: 6px !important; background-color: #f3f4f6 !important; border: 2px solid transparent !important; cursor: pointer !important; display: flex !important; align-items: center !important; justify-content: center !important; transition: all 0.15s !important;">
                                <span class="material-symbols-outlined" style="font-size: 18px !important; color: #374151 !important;">{{ $iconName }}</span>
                            </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="icon" id="input-icon" value="category" required>
                        <div style="margin-top: 8px !important; display: flex !important; align-items: center !important; gap: 8px !important; font-size: 10px !important; color: #6b7280 !important;">
                            <span>Selected:</span>
                            <div id="selected-icon-preview" style="width: 28px !important; height: 28px !important; border-radius: 4px !important; background-color: #f3f4f6 !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                                <span class="material-symbols-outlined" style="font-size: 18px !important; color: #374151 !important;">category</span>
                            </div>
                            <span id="selected-icon-name" style="font-family: monospace !important; color: #374151 !important;">category</span>
                        </div>
                    </div>
                    <div style="display: grid !important; grid-template-columns: 1fr 1fr !important; gap: 12px !important;">
                        <div>
                            <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                                Sort Order
                            </label>
                            <input type="number" name="sort_order" id="input-sort_order" value="0"
                                   style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important;"
                                   onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                        </div>
                        <div>
                            <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                                Status <span style="color: #ef4444 !important;">*</span>
                            </label>
                            <select name="status" id="input-status" required style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; background-color: #ffffff !important; outline: none !important; cursor: pointer !important; box-sizing: border-box !important;">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div style="padding: 16px 20px !important; border-top: 1px solid #e5e7eb !important; display: flex !important; justify-content: flex-end !important; gap: 10px !important; background-color: #f9fafb !important;">
                <button type="button" onclick="closeModal()" 
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #374151 !important; background-color: #ffffff !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;"
                        onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#ffffff'">
                    Cancel
                </button>
                <button type="submit"
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #ffffff !important; background-color: #3b82f6 !important; border: none !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important; display: flex !important; align-items: center !important; gap: 6px !important;"
                        onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">
                    <span class="material-symbols-outlined" style="font-size: 16px !important;">save</span>
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function selectColor(color) {
    document.getElementById('input-color').value = color;
    document.getElementById('selected-color-preview').style.backgroundColor = color;
    document.getElementById('selected-color-code').textContent = color;
    
    // Update button styles
    document.querySelectorAll('.color-btn').forEach(btn => {
        if (btn.dataset.color === color) {
            btn.style.borderColor = '#1f2937';
            btn.style.transform = 'scale(1.1)';
        } else {
            btn.style.borderColor = 'transparent';
            btn.style.transform = 'scale(1)';
        }
    });
}

function selectIcon(iconName) {
    document.getElementById('input-icon').value = iconName;
    document.querySelector('#selected-icon-preview .material-symbols-outlined').textContent = iconName;
    document.getElementById('selected-icon-name').textContent = iconName;
    
    // Update button styles
    document.querySelectorAll('.icon-btn').forEach(btn => {
        if (btn.dataset.icon === iconName) {
            btn.style.borderColor = '#2563eb';
            btn.style.backgroundColor = '#dbeafe';
        } else {
            btn.style.borderColor = 'transparent';
            btn.style.backgroundColor = '#f3f4f6';
        }
    });
}

function showCreateModal() {
    document.getElementById('modal-title').textContent = 'Create Category';
    document.getElementById('item-form').action = '{{ route('settings.categories.category.store') }}';
    document.getElementById('form-method').value = 'POST';
    document.getElementById('input-name').value = '';
    document.getElementById('input-description').value = '';
    document.getElementById('input-sort_order').value = '0';
    document.getElementById('input-status').value = 'active';
    selectColor('#3b82f6');
    selectIcon('category');
    document.getElementById('item-modal').style.display = 'flex';
}

function showEditModal(item) {
    document.getElementById('modal-title').textContent = 'Edit Category';
    document.getElementById('item-form').action = '{{ url('settings/categories/category') }}/' + item.id;
    document.getElementById('form-method').value = 'PUT';
    document.getElementById('input-name').value = item.name;
    document.getElementById('input-description').value = item.description || '';
    document.getElementById('input-sort_order').value = item.sort_order || 0;
    document.getElementById('input-status').value = item.status;
    selectColor(item.color);
    selectIcon(item.icon || 'category');
    document.getElementById('item-modal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('item-modal').style.display = 'none';
}

// Close on escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});

// Close on backdrop click
document.getElementById('item-modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endpush
