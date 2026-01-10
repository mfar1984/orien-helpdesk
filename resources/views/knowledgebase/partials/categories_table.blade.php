<x-ui.data-table
    :headers="[
        ['label' => 'Name', 'align' => 'text-left'],
        ['label' => 'Articles', 'align' => 'text-center'],
        ['label' => 'Status', 'align' => 'text-center'],
        ['label' => 'Order', 'align' => 'text-center'],
    ]"
    :actions="true"
    empty-message="No categories found."
>
    @forelse($items as $category)
    <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded flex items-center justify-center" style="background-color: {{ $category->color }}20;">
                    <span class="material-symbols-outlined" style="font-size: 16px; color: {{ $category->color }};">{{ $category->icon }}</span>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-900" style="font-family: Poppins, sans-serif; font-size: 12px;">{{ $category->name }}</div>
                    @if($category->description)
                    <div class="text-xs text-gray-400 truncate max-w-xs" style="font-family: Poppins, sans-serif;">{{ Str::limit($category->description, 40) }}</div>
                    @endif
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <span class="text-xs text-gray-500">{{ $category->articles_count ?? 0 }}</span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            @if($category->status === 'active')
            <span class="inline-flex px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-600" style="font-size: 10px;">Active</span>
            @else
            <span class="inline-flex px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-600" style="font-size: 10px;">Inactive</span>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <span class="text-xs text-gray-500">{{ $category->sort_order }}</span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
            @php
                $canEdit = auth()->user()->hasPermission('knowledgebase_categories.edit');
                $canDelete = auth()->user()->hasPermission('knowledgebase_categories.delete');
            @endphp
            <x-ui.action-buttons 
                :edit-onclick="$canEdit ? 'showEditCategoryModal(' . json_encode($category) . ')' : null" 
                :delete-onclick="$canDelete ? 'showDeleteModal(\'' . route('knowledgebase.categories.destroy', $category) . '\')' : null" 
            />
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="px-6 py-8 text-center">
            <div class="flex flex-col items-center">
                <span class="material-symbols-outlined text-gray-300" style="font-size: 48px;">folder</span>
                <p class="text-sm text-gray-500 mt-2" style="font-family: Poppins, sans-serif;">No categories found.</p>
            </div>
        </td>
    </tr>
    @endforelse
</x-ui.data-table>
