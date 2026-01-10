<!-- Create/Edit Status Modal -->
<div id="item-modal" class="fixed inset-0 flex items-center justify-center" style="background-color: rgba(0,0,0,0.5) !important; z-index: 9999 !important; display: none;">
    <div style="background-color: #ffffff !important; border-radius: 12px !important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important; width: 100% !important; max-width: 520px !important; margin: 16px !important; overflow: hidden !important;">
        <div style="padding: 16px 20px !important; border-bottom: 1px solid #e5e7eb !important; display: flex !important; align-items: center !important; justify-content: space-between !important; background-color: #f9fafb !important;">
            <div style="display: flex !important; align-items: center !important; gap: 10px !important;">
                <div style="width: 36px !important; height: 36px !important; border-radius: 8px !important; background-color: #3b82f6 !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                    <span class="material-symbols-outlined" style="font-size: 20px !important; color: #ffffff !important;">toggle_on</span>
                </div>
                <h3 id="modal-title" style="font-size: 14px !important; font-weight: 600 !important; color: #111827 !important; font-family: Poppins, sans-serif !important; margin: 0 !important;">Create Status</h3>
            </div>
            <button type="button" onclick="closeModal()" style="width: 32px !important; height: 32px !important; border-radius: 6px !important; border: none !important; background-color: transparent !important; cursor: pointer !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                <span class="material-symbols-outlined" style="font-size: 20px !important; color: #6b7280 !important;">close</span>
            </button>
        </div>
        <form id="item-form" method="POST" action="{{ route('settings.categories.status.store') }}">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            <input type="hidden" name="icon" id="input-icon" value="toggle_on">
            <div style="padding: 20px !important; max-height: 65vh !important; overflow-y: auto !important;">
                <div style="display: flex !important; flex-direction: column !important; gap: 16px !important;">
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Name <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <input type="text" name="name" id="input-name" required style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important;" placeholder="Status name">
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">Description</label>
                        <textarea name="description" id="input-description" rows="2" style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; resize: vertical !important; outline: none !important; box-sizing: border-box !important;" placeholder="Status description"></textarea>
                    </div>
                    <!-- Icon Picker Grid -->
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">Icon</label>
                        <div style="border: 1px solid #d1d5db !important; border-radius: 6px !important; padding: 12px !important;">
                            <div id="icon-grid" style="display: grid !important; grid-template-columns: repeat(8, 1fr) !important; gap: 6px !important; max-height: 120px !important; overflow-y: auto !important;"></div>
                            <div style="margin-top: 8px !important; padding-top: 8px !important; border-top: 1px solid #e5e7eb !important;">
                                <span style="font-size: 10px !important; color: #6b7280 !important; font-family: Poppins, sans-serif !important;">Selected: <span id="selected-icon-name" style="font-weight: 500 !important; color: #374151 !important;">toggle_on</span></span>
                            </div>
                        </div>
                    </div>
                    <div style="display: grid !important; grid-template-columns: 1fr 1fr !important; gap: 12px !important;">
                        <div>
                            <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">Color <span style="color: #ef4444 !important;">*</span></label>
                            <div style="display: flex !important; align-items: center !important; gap: 8px !important;">
                                <input type="color" name="color" id="input-color" value="#3b82f6" style="width: 40px !important; height: 40px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; cursor: pointer !important; padding: 2px !important;">
                                <input type="text" id="input-color-text" value="#3b82f6" maxlength="7" style="flex: 1 !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: monospace !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important;">
                            </div>
                        </div>
                        <div>
                            <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">Sort Order</label>
                            <input type="number" name="sort_order" id="input-sort_order" value="0" style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important;">
                        </div>
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">Status <span style="color: #ef4444 !important;">*</span></label>
                        <select name="status" id="input-status" required style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; background-color: #ffffff !important; outline: none !important; cursor: pointer !important; box-sizing: border-box !important;">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div style="display: grid !important; grid-template-columns: 1fr 1fr !important; gap: 12px !important;">
                        <div style="display: flex !important; align-items: center !important; gap: 8px !important;">
                            <input type="checkbox" name="is_default" id="input-is_default" value="1" style="width: 16px !important; height: 16px !important; cursor: pointer !important;">
                            <label for="input-is_default" style="font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; font-family: Poppins, sans-serif !important; cursor: pointer !important;">Default Status</label>
                        </div>
                        <div style="display: flex !important; align-items: center !important; gap: 8px !important;">
                            <input type="checkbox" name="is_closed" id="input-is_closed" value="1" style="width: 16px !important; height: 16px !important; cursor: pointer !important;">
                            <label for="input-is_closed" style="font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; font-family: Poppins, sans-serif !important; cursor: pointer !important;">Closed Status</label>
                        </div>
                    </div>
                </div>
            </div>
            <div style="padding: 16px 20px !important; border-top: 1px solid #e5e7eb !important; display: flex !important; justify-content: flex-end !important; gap: 10px !important; background-color: #f9fafb !important;">
                <button type="button" onclick="closeModal()" style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #374151 !important; background-color: #ffffff !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;">Cancel</button>
                <button type="submit" style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #ffffff !important; background-color: #3b82f6 !important; border: none !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important; display: flex !important; align-items: center !important; gap: 6px !important;">
                    <span class="material-symbols-outlined" style="font-size: 16px !important;">save</span>
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const statusIcons = ['toggle_on', 'toggle_off', 'check_circle', 'cancel', 'pending', 'hourglass_empty', 'schedule', 'play_circle', 'pause_circle', 'stop_circle', 'radio_button_checked', 'radio_button_unchecked', 'task_alt', 'unpublished', 'published_with_changes', 'sync', 'autorenew', 'loop', 'done', 'done_all', 'remove_done', 'close', 'block', 'do_not_disturb', 'lock', 'lock_open', 'visibility', 'visibility_off'];

function initIconGrid() {
    const grid = document.getElementById('icon-grid');
    grid.innerHTML = statusIcons.map(icon => `
        <button type="button" onclick="selectIcon('${icon}')" class="icon-option" data-icon="${icon}" style="width: 32px !important; height: 32px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 6px !important; border: none !important; background-color: transparent !important; cursor: pointer !important;">
            <span class="material-symbols-outlined" style="font-size: 18px !important; color: #6b7280 !important;">${icon}</span>
        </button>
    `).join('');
}

document.getElementById('input-color').addEventListener('input', function() {
    document.getElementById('input-color-text').value = this.value;
});
document.getElementById('input-color-text').addEventListener('input', function() {
    if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
        document.getElementById('input-color').value = this.value;
    }
});

function selectIcon(iconName) {
    document.querySelectorAll('.icon-option').forEach(btn => {
        btn.style.backgroundColor = 'transparent';
        btn.style.boxShadow = 'none';
    });
    const selectedBtn = document.querySelector(`.icon-option[data-icon="${iconName}"]`);
    if (selectedBtn) {
        selectedBtn.style.backgroundColor = '#dbeafe';
        selectedBtn.style.boxShadow = '0 0 0 2px #3b82f6';
    }
    document.getElementById('input-icon').value = iconName;
    document.getElementById('selected-icon-name').textContent = iconName;
}

function showCreateModal() {
    document.getElementById('modal-title').textContent = 'Create Status';
    document.getElementById('item-form').action = '{{ route('settings.categories.status.store') }}';
    document.getElementById('form-method').value = 'POST';
    document.getElementById('input-name').value = '';
    document.getElementById('input-description').value = '';
    selectIcon('toggle_on');
    document.getElementById('input-color').value = '#3b82f6';
    document.getElementById('input-color-text').value = '#3b82f6';
    document.getElementById('input-sort_order').value = '0';
    document.getElementById('input-status').value = 'active';
    document.getElementById('input-is_default').checked = false;
    document.getElementById('input-is_closed').checked = false;
    document.getElementById('item-modal').style.display = 'flex';
}

function showEditModal(item) {
    document.getElementById('modal-title').textContent = 'Edit Status';
    document.getElementById('item-form').action = '{{ url('settings/categories/status') }}/' + item.id;
    document.getElementById('form-method').value = 'PUT';
    document.getElementById('input-name').value = item.name;
    document.getElementById('input-description').value = item.description || '';
    selectIcon(item.icon || 'toggle_on');
    document.getElementById('input-color').value = item.color;
    document.getElementById('input-color-text').value = item.color;
    document.getElementById('input-sort_order').value = item.sort_order || 0;
    document.getElementById('input-status').value = item.status;
    document.getElementById('input-is_default').checked = item.is_default;
    document.getElementById('input-is_closed').checked = item.is_closed;
    document.getElementById('item-modal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('item-modal').style.display = 'none';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});

document.getElementById('item-modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.addEventListener('DOMContentLoaded', function() {
    initIconGrid();
    selectIcon('toggle_on');
});
</script>
@endpush
