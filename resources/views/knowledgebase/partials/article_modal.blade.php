<div id="create-article-modal" class="fixed inset-0 flex items-center justify-center" style="background-color: rgba(0,0,0,0.5) !important; z-index: 9999 !important; display: none;">
    <div style="background-color: #ffffff !important; border-radius: 12px !important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important; width: 100% !important; max-width: 700px !important; margin: 16px !important; overflow: hidden !important;">
        <div style="padding: 16px 20px !important; border-bottom: 1px solid #e5e7eb !important; display: flex !important; align-items: center !important; justify-content: space-between !important; background-color: #f9fafb !important;">
            <div style="display: flex !important; align-items: center !important; gap: 10px !important;">
                <div style="width: 36px !important; height: 36px !important; border-radius: 8px !important; background-color: #3b82f6 !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                    <span class="material-symbols-outlined" style="font-size: 20px !important; color: #ffffff !important;">article</span>
                </div>
                <h3 id="article-modal-title" style="font-size: 14px !important; font-weight: 600 !important; color: #111827 !important; font-family: Poppins, sans-serif !important; margin: 0 !important;">Create New Article</h3>
            </div>
            <button type="button" onclick="closeCreateArticleModal()" style="width: 32px !important; height: 32px !important; border-radius: 6px !important; border: none !important; background-color: transparent !important; cursor: pointer !important; display: flex !important; align-items: center !important; justify-content: center !important;" onmouseover="this.style.backgroundColor='#e5e7eb'" onmouseout="this.style.backgroundColor='transparent'">
                <span class="material-symbols-outlined" style="font-size: 20px !important; color: #6b7280 !important;">close</span>
            </button>
        </div>
        <form id="article-form" action="{{ route('knowledgebase.articles.store') }}" method="POST">
            @csrf
            <div id="article-method-field"></div>
            <div style="padding: 20px !important; max-height: 70vh !important; overflow-y: auto !important;">
                <div style="display: flex !important; flex-direction: column !important; gap: 16px !important;">
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Title <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <input type="text" name="title" id="article-title" required
                               style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important;"
                               placeholder="Article title"
                               onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                    </div>
                    <div style="display: grid !important; grid-template-columns: 1fr 1fr !important; gap: 12px !important;">
                        <div>
                            <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                                Category <span style="color: #ef4444 !important;">*</span>
                            </label>
                            <select name="category_id" id="article-category" required style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; background-color: #ffffff !important; outline: none !important; cursor: pointer !important; box-sizing: border-box !important;">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                                Status <span style="color: #ef4444 !important;">*</span>
                            </label>
                            <select name="status" id="article-status" required style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; background-color: #ffffff !important; outline: none !important; cursor: pointer !important; box-sizing: border-box !important;">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Excerpt
                        </label>
                        <textarea name="excerpt" id="article-excerpt" rows="2"
                                  style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; resize: vertical !important; outline: none !important; box-sizing: border-box !important;"
                                  placeholder="Brief summary of the article"
                                  onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'"></textarea>
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Content <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <textarea id="article-content" name="content"></textarea>
                    </div>
                </div>
            </div>
            <div style="padding: 16px 20px !important; border-top: 1px solid #e5e7eb !important; display: flex !important; justify-content: flex-end !important; gap: 10px !important; background-color: #f9fafb !important;">
                <button type="button" onclick="closeCreateArticleModal()" 
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #374151 !important; background-color: #ffffff !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;"
                        onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#ffffff'">
                    Cancel
                </button>
                <button type="submit"
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #ffffff !important; background-color: #3b82f6 !important; border: none !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important; display: flex !important; align-items: center !important; gap: 6px !important;"
                        onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">
                    <span class="material-symbols-outlined" style="font-size: 16px !important;">save</span>
                    Save Article
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/g6yrur70ud67rx6rqxxpsl2ppkodlugcrkp46jker7cbacvu/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
let articleEditor = null;

function initTinyMCE() {
    if (articleEditor) {
        tinymce.remove('#article-content');
    }
    tinymce.init({
        selector: '#article-content',
        height: 300,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        content_style: 'body { font-family: Poppins, sans-serif; font-size: 12px; }',
        setup: function(editor) {
            articleEditor = editor;
        }
    });
}

function showCreateArticleModal() {
    document.getElementById('article-modal-title').textContent = 'Create New Article';
    document.getElementById('article-form').action = '{{ route('knowledgebase.articles.store') }}';
    document.getElementById('article-method-field').innerHTML = '';
    document.getElementById('article-form').reset();
    document.getElementById('create-article-modal').style.display = 'flex';
    setTimeout(initTinyMCE, 100);
}

function closeCreateArticleModal() {
    document.getElementById('create-article-modal').style.display = 'none';
    document.getElementById('article-form').reset();
    if (articleEditor) {
        articleEditor.setContent('');
    }
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCreateArticleModal();
    }
});

// Close modal on backdrop click
document.getElementById('create-article-modal').addEventListener('click', function(e) {
    if (e.target === this) closeCreateArticleModal();
});
</script>
@endpush
