<div id="create-category-modal" class="fixed inset-0 flex items-center justify-center" style="background-color: rgba(0,0,0,0.5) !important; z-index: 9999 !important; display: none;">
    <div style="background-color: #ffffff !important; border-radius: 12px !important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important; width: 100% !important; max-width: 520px !important; margin: 16px !important; overflow: hidden !important;">
        <div style="padding: 16px 20px !important; border-bottom: 1px solid #e5e7eb !important; display: flex !important; align-items: center !important; justify-content: space-between !important; background-color: #f9fafb !important;">
            <div style="display: flex !important; align-items: center !important; gap: 10px !important;">
                <div style="width: 36px !important; height: 36px !important; border-radius: 8px !important; background-color: #3b82f6 !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                    <span class="material-symbols-outlined" style="font-size: 20px !important; color: #ffffff !important;">folder</span>
                </div>
                <h3 id="category-modal-title" style="font-size: 14px !important; font-weight: 600 !important; color: #111827 !important; font-family: Poppins, sans-serif !important; margin: 0 !important;">Create New Category</h3>
            </div>
            <button type="button" onclick="closeCreateCategoryModal()" style="width: 32px !important; height: 32px !important; border-radius: 6px !important; border: none !important; background-color: transparent !important; cursor: pointer !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                <span class="material-symbols-outlined" style="font-size: 20px !important; color: #6b7280 !important;">close</span>
            </button>
        </div>
        <form id="category-form" action="{{ route('knowledgebase.categories.store') }}" method="POST">
            @csrf
            <div id="category-method-field"></div>
            <input type="hidden" name="icon" id="category-icon" value="folder">
            <div style="padding: 20px !important; max-height: 65vh !important; overflow-y: auto !important;">
                <div style="display: flex !important; flex-direction: column !important; gap: 16px !important;">
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Name <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <input type="text" name="name" id="category-name" required style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important;" placeholder="Category name">
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">Description</label>
                        <textarea name="description" id="category-description" rows="2" style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; resize: vertical !important; outline: none !important; box-sizing: border-box !important;" placeholder="Category description"></textarea>
                    </div>
                    <!-- Icon Picker Grid -->
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">Icon</label>
                        <div style="border: 1px solid #d1d5db !important; border-radius: 6px !important; padding: 12px !important;">
                            <div id="kb-icon-grid" style="display: grid !important; grid-template-columns: repeat(8, 1fr) !important; gap: 6px !important; max-height: 120px !important; overflow-y: auto !important;"></div>
                            <div style="margin-top: 8px !important; padding-top: 8px !important; border-top: 1px solid #e5e7eb !important;">
                                <span style="font-size: 10px !important; color: #6b7280 !important; font-family: Poppins, sans-serif !important;">Selected: <span id="kb-selected-icon-name" style="font-weight: 500 !important; color: #374151 !important;">folder</span></span>
                            </div>
                        </div>
                    </div>
                    <div style="display: grid !important; grid-template-columns: 1fr 1fr !important; gap: 12px !important;">
                        <div>
                            <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">Color</label>
                            <input type="color" name="color" id="category-color" value="#3b82f6" style="width: 100% !important; padding: 4px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; height: 40px !important; cursor: pointer !important; box-sizing: border-box !important;">
                        </div>
                        <div>
                            <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">Order</label>
                            <input type="number" name="sort_order" id="category-order" value="0" style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important;">
                        </div>
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">Status <span style="color: #ef4444 !important;">*</span></label>
                        <select name="status" id="category-status" required style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; background-color: #ffffff !important; outline: none !important; cursor: pointer !important; box-sizing: border-box !important;">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div style="padding: 16px 20px !important; border-top: 1px solid #e5e7eb !important; display: flex !important; justify-content: flex-end !important; gap: 10px !important; background-color: #f9fafb !important;">
                <button type="button" onclick="closeCreateCategoryModal()" style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #374151 !important; background-color: #ffffff !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;">Cancel</button>
                <button type="submit" style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #ffffff !important; background-color: #3b82f6 !important; border: none !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important; display: flex !important; align-items: center !important; gap: 6px !important;">
                    <span class="material-symbols-outlined" style="font-size: 16px !important;">save</span>
                    Save Category
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const kbIcons = ['folder', 'folder_open', 'article', 'description', 'help', 'help_outline', 'info', 'lightbulb', 'school', 'menu_book', 'auto_stories', 'library_books', 'bookmark', 'bookmarks', 'collections_bookmark', 'topic', 'label', 'sell', 'category', 'widgets', 'extension', 'settings', 'build', 'code', 'terminal', 'api', 'integration_instructions', 'developer_guide', 'quick_reference', 'tips_and_updates'];

function initKbIconGrid() {
    const grid = document.getElementById('kb-icon-grid');
    if (!grid) return;
    grid.innerHTML = kbIcons.map(icon => `
        <button type="button" onclick="selectKbIcon('${icon}')" class="kb-icon-option" data-icon="${icon}" style="width: 32px !important; height: 32px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 6px !important; border: none !important; background-color: transparent !important; cursor: pointer !important;">
            <span class="material-symbols-outlined" style="font-size: 18px !important; color: #6b7280 !important;">${icon}</span>
        </button>
    `).join('');
}

function selectKbIcon(iconName) {
    document.querySelectorAll('.kb-icon-option').forEach(btn => {
        btn.style.backgroundColor = 'transparent';
        btn.style.boxShadow = 'none';
    });
    const selectedBtn = document.querySelector(`.kb-icon-option[data-icon="${iconName}"]`);
    if (selectedBtn) {
        selectedBtn.style.backgroundColor = '#dbeafe';
        selectedBtn.style.boxShadow = '0 0 0 2px #3b82f6';
    }
    document.getElementById('category-icon').value = iconName;
    document.getElementById('kb-selected-icon-name').textContent = iconName;
}

function showCreateCategoryModal() {
    document.getElementById('category-modal-title').textContent = 'Create New Category';
    document.getElementById('category-form').action = '{{ route('knowledgebase.categories.store') }}';
    document.getElementById('category-method-field').innerHTML = '';
    document.getElementById('category-form').reset();
    selectKbIcon('folder');
    document.getElementById('category-color').value = '#3b82f6';
    document.getElementById('category-order').value = '0';
    document.getElementById('category-status').value = 'active';
    document.getElementById('create-category-modal').style.display = 'flex';
}

function showEditCategoryModal(category) {
    document.getElementById('category-modal-title').textContent = 'Edit Category';
    document.getElementById('category-form').action = '/knowledgebase/categories/' + category.id;
    document.getElementById('category-method-field').innerHTML = '@method("PUT")';
    document.getElementById('category-name').value = category.name;
    document.getElementById('category-description').value = category.description || '';
    selectKbIcon(category.icon || 'folder');
    document.getElementById('category-color').value = category.color || '#3b82f6';
    document.getElementById('category-order').value = category.sort_order || 0;
    document.getElementById('category-status').value = category.status;
    document.getElementById('create-category-modal').style.display = 'flex';
}

function closeCreateCategoryModal() {
    document.getElementById('create-category-modal').style.display = 'none';
    document.getElementById('category-form').reset();
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeCreateCategoryModal();
});

document.getElementById('create-category-modal').addEventListener('click', function(e) {
    if (e.target === this) closeCreateCategoryModal();
});

document.addEventListener('DOMContentLoaded', function() {
    initKbIconGrid();
    selectKbIcon('folder');
});
</script>
@endpush
