<!-- Create/Edit SLA Rule Modal -->
<div id="item-modal" class="fixed inset-0 flex items-center justify-center" style="background-color: rgba(0,0,0,0.5) !important; z-index: 9999 !important; display: none;">
    <div style="background-color: #ffffff !important; border-radius: 12px !important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important; width: 100% !important; max-width: 480px !important; margin: 16px !important; overflow: hidden !important;">
        <div style="padding: 16px 20px !important; border-bottom: 1px solid #e5e7eb !important; display: flex !important; align-items: center !important; justify-content: space-between !important; background-color: #f9fafb !important;">
            <div style="display: flex !important; align-items: center !important; gap: 10px !important;">
                <div style="width: 36px !important; height: 36px !important; border-radius: 8px !important; background-color: #3b82f6 !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                    <span class="material-symbols-outlined" style="font-size: 20px !important; color: #ffffff !important;">schedule</span>
                </div>
                <h3 id="modal-title" style="font-size: 14px !important; font-weight: 600 !important; color: #111827 !important; font-family: Poppins, sans-serif !important; margin: 0 !important;">Create SLA Rule</h3>
            </div>
            <button type="button" onclick="closeModal()" style="width: 32px; height: 32px; border-radius: 6px; border: none; background-color: transparent; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                <span class="material-symbols-outlined" style="font-size: 20px; color: #6b7280;">close</span>
            </button>
        </div>
        <form id="item-form" method="POST" action="{{ route('settings.categories.sla-rule.store') }}">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            <div style="padding: 20px; max-height: 60vh; overflow-y: auto;">
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                            Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="name" id="input-name" required
                               style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                               placeholder="SLA Rule name">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                            Description
                        </label>
                        <textarea name="description" id="input-description" rows="2"
                                  style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; resize: vertical; outline: none; box-sizing: border-box;"
                                  placeholder="SLA Rule description"></textarea>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                                Response Time (minutes) <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="number" name="response_time" id="input-response_time" required min="1" value="60"
                                   style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                                   placeholder="60">
                        </div>
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                                Resolution Time (minutes) <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="number" name="resolution_time" id="input-resolution_time" required min="1" value="480"
                                   style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                                   placeholder="480">
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                                Priority
                            </label>
                            <select name="priority_id" id="input-priority_id" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; background-color: #ffffff; outline: none; cursor: pointer; box-sizing: border-box;">
                                <option value="">All Priorities</option>
                                @foreach($priorities as $priority)
                                <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                                Category
                            </label>
                            <select name="category_id" id="input-category_id" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; background-color: #ffffff; outline: none; cursor: pointer; box-sizing: border-box;">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                                Sort Order
                            </label>
                            <input type="number" name="sort_order" id="input-sort_order" value="0"
                                   style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;">
                        </div>
                        <div></div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                            Status <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="status" id="input-status" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; background-color: #ffffff; outline: none; cursor: pointer; box-sizing: border-box;">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div style="padding: 16px 20px; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 10px; background-color: #f9fafb;">
                <button type="button" onclick="closeModal()" 
                        style="padding: 10px 20px; font-size: 12px; font-weight: 500; color: #374151; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 6px; cursor: pointer; font-family: Poppins, sans-serif;">
                    Cancel
                </button>
                <button type="submit"
                        style="padding: 10px 20px; font-size: 12px; font-weight: 500; color: #ffffff; background-color: #3b82f6; border: none; border-radius: 6px; cursor: pointer; font-family: Poppins, sans-serif; display: flex; align-items: center; gap: 6px;">
                    <span class="material-symbols-outlined" style="font-size: 16px;">save</span>
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showCreateModal() {
    document.getElementById('modal-title').textContent = 'Create SLA Rule';
    document.getElementById('item-form').action = '{{ route('settings.categories.sla-rule.store') }}';
    document.getElementById('form-method').value = 'POST';
    document.getElementById('input-name').value = '';
    document.getElementById('input-description').value = '';
    document.getElementById('input-response_time').value = '60';
    document.getElementById('input-resolution_time').value = '480';
    document.getElementById('input-priority_id').value = '';
    document.getElementById('input-category_id').value = '';
    document.getElementById('input-sort_order').value = '0';
    document.getElementById('input-status').value = 'active';
    document.getElementById('item-modal').style.display = 'flex';
}

function showEditModal(item) {
    document.getElementById('modal-title').textContent = 'Edit SLA Rule';
    document.getElementById('item-form').action = '{{ url('settings/categories/sla-rule') }}/' + item.id;
    document.getElementById('form-method').value = 'PUT';
    document.getElementById('input-name').value = item.name;
    document.getElementById('input-description').value = item.description || '';
    document.getElementById('input-response_time').value = item.response_time;
    document.getElementById('input-resolution_time').value = item.resolution_time;
    document.getElementById('input-priority_id').value = item.priority_id || '';
    document.getElementById('input-category_id').value = item.category_id || '';
    document.getElementById('input-sort_order').value = item.sort_order || 0;
    document.getElementById('input-status').value = item.status;
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
</script>
@endpush
