<x-ui.data-table
    :headers="[
        ['label' => 'Title', 'align' => 'text-left'],
        ['label' => 'Category', 'align' => 'text-center'],
        ['label' => 'Views', 'align' => 'text-center'],
        ['label' => 'Status', 'align' => 'text-center'],
        ['label' => 'Updated', 'align' => 'text-center'],
    ]"
    :actions="true"
    empty-message="No articles found."
>
    @forelse($items as $article)
    <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900" style="font-family: Poppins, sans-serif; font-size: 12px;">{{ $article->title }}</div>
            @if($article->excerpt)
            <div class="text-xs text-gray-400 truncate max-w-xs" style="font-family: Poppins, sans-serif;">{{ Str::limit($article->excerpt, 50) }}</div>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <span class="text-xs text-gray-500">{{ $article->category->name ?? '-' }}</span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <span class="text-xs text-gray-500">{{ number_format($article->views) }}</span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            @if($article->status === 'published')
            <span class="inline-flex px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-600" style="font-size: 10px;">Published</span>
            @else
            <span class="inline-flex px-2 py-1 text-xs font-medium rounded bg-yellow-100 text-yellow-600" style="font-size: 10px;">Draft</span>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <span class="text-xs text-gray-500">{{ format_date($article->updated_at) }}</span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
            @php
                $canEdit = auth()->user()->hasPermission('knowledgebase_articles.edit');
                $canDelete = auth()->user()->hasPermission('knowledgebase_articles.delete');
            @endphp
            <x-ui.action-buttons 
                :edit-url="$canEdit ? route('knowledgebase.articles.edit', $article) : null" 
                :show-url="route('knowledgebase.article', $article)" 
                :delete-onclick="$canDelete ? 'showDeleteModal(\'' . route('knowledgebase.articles.destroy', $article) . '\')' : null" 
            />
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="px-6 py-8 text-center">
            <div class="flex flex-col items-center">
                <span class="material-symbols-outlined text-gray-300" style="font-size: 48px;">article</span>
                <p class="text-sm text-gray-500 mt-2" style="font-family: Poppins, sans-serif;">No articles found.</p>
            </div>
        </td>
    </tr>
    @endforelse
</x-ui.data-table>
