<x-ui.data-table
    :headers="[
        ['label' => 'Name', 'align' => 'text-left'],
        ['label' => 'Color', 'align' => 'text-center'],
        ['label' => 'Icon', 'align' => 'text-center'],
        ['label' => 'Order', 'align' => 'text-center'],
        ['label' => 'Status', 'align' => 'text-center'],
    ]"
    :actions="true"
    empty-message="No priorities found."
>
    @forelse($items as $item)
    <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center gap-2">
                <div class="flex items-center justify-center flex-shrink-0" style="width: 28px; height: 28px; border-radius: 6px; background-color: {{ $item->color }}20;">
                    <span class="material-symbols-outlined" style="font-size: 16px; color: {{ $item->color }};">{{ $item->icon }}</span>
                </div>
                <div class="text-sm font-medium text-gray-900" style="font-family: Poppins, sans-serif; font-size: 12px;">{{ $item->name }}</div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <div class="flex items-center justify-center gap-2">
                <div style="width: 20px; height: 20px; border-radius: 4px; background-color: {{ $item->color }};"></div>
                <span class="text-xs text-gray-500" style="font-family: monospace;">{{ $item->color }}</span>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <span class="material-symbols-outlined" style="font-size: 18px; color: {{ $item->color }};">{{ $item->icon }}</span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <span class="text-xs text-gray-500">{{ $item->sort_order }}</span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <span class="inline-flex px-2 py-1 text-xs font-medium rounded {{ $item->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}" style="font-size: 10px;">
                {{ ucfirst($item->status) }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
            <x-ui.action-buttons
                :edit-onclick="($canEdit ?? false) ? 'showEditModal(' . json_encode($item) . ')' : null"
                :delete-onclick="($canDelete ?? false) ? 'showDeleteModal(\'' . route('settings.categories.priority.destroy', $item) . '\')' : null"
            />
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-sm">
            No priorities found.
        </td>
    </tr>
    @endforelse
</x-ui.data-table>
