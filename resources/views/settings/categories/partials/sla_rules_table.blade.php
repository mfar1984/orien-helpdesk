<x-ui.data-table
    :headers="[
        ['label' => 'Name', 'align' => 'text-left'],
        ['label' => 'Response Time', 'align' => 'text-center'],
        ['label' => 'Resolution Time', 'align' => 'text-center'],
        ['label' => 'Priority', 'align' => 'text-center'],
        ['label' => 'Category', 'align' => 'text-center'],
        ['label' => 'Order', 'align' => 'text-center'],
        ['label' => 'Status', 'align' => 'text-center'],
    ]"
    :actions="true"
    empty-message="No SLA rules found."
>
    @forelse($items as $item)
    <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900" style="font-family: Poppins, sans-serif; font-size: 12px;">{{ $item->name }}</div>
            @if($item->description)
            <div class="text-xs text-gray-500 mt-0.5">{{ Str::limit($item->description, 50) }}</div>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs bg-blue-50 text-blue-600">
                <span class="material-symbols-outlined" style="font-size: 12px;">schedule</span>
                {{ $item->formatted_response_time }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs bg-green-50 text-green-600">
                <span class="material-symbols-outlined" style="font-size: 12px;">check_circle</span>
                {{ $item->formatted_resolution_time }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            @if($item->priority)
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs" style="background-color: {{ $item->priority->color }}20; color: {{ $item->priority->color }};">
                {{ $item->priority->name }}
            </span>
            @else
            <span class="text-xs text-gray-400">All</span>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            @if($item->category)
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs" style="background-color: {{ $item->category->color }}20; color: {{ $item->category->color }};">
                {{ $item->category->name }}
            </span>
            @else
            <span class="text-xs text-gray-400">All</span>
            @endif
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
                :delete-onclick="($canDelete ?? false) ? 'showDeleteModal(\'' . route('settings.categories.sla-rule.destroy', $item) . '\')' : null"
            />
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" class="px-6 py-8 text-center text-gray-500 text-sm">
            No SLA rules found.
        </td>
    </tr>
    @endforelse
</x-ui.data-table>
