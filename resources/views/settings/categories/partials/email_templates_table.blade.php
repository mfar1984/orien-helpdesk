<x-ui.data-table
    :headers="[
        ['label' => 'Name', 'align' => 'text-left'],
        ['label' => 'Subject', 'align' => 'text-left'],
        ['label' => 'Type', 'align' => 'text-center'],
        ['label' => 'Status', 'align' => 'text-center'],
        ['label' => 'Updated', 'align' => 'text-center'],
    ]"
    :actions="true"
    empty-message="No email templates found."
>
    @forelse($items as $item)
    @php
        $typeColors = [
            'notification' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
            'auto-reply' => ['bg' => 'bg-green-100', 'text' => 'text-green-600'],
            'escalation' => ['bg' => 'bg-red-100', 'text' => 'text-red-600'],
            'reminder' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600'],
            'welcome' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
        ];
        $typeColor = $typeColors[$item->type] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'];
    @endphp
    <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900" style="font-family: Poppins, sans-serif; font-size: 12px;">{{ $item->name }}</div>
        </td>
        <td class="px-6 py-4">
            <div class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ Str::limit($item->subject, 50) }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <span class="inline-flex px-2 py-1 text-xs font-medium rounded {{ $typeColor['bg'] }} {{ $typeColor['text'] }}" style="font-size: 10px;">
                {{ ucfirst(str_replace('-', ' ', $item->type)) }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <span class="inline-flex px-2 py-1 text-xs font-medium rounded {{ $item->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}" style="font-size: 10px;">
                {{ ucfirst($item->status) }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <span class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">
                {{ format_date($item->updated_at) }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
            <x-ui.action-buttons
                :edit-url="($canEdit ?? false) ? route('settings.categories.email-template.edit', $item) : null"
                :show-url="route('settings.categories.email-template.show', $item)"
            />
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-sm">
            No email templates found.
        </td>
    </tr>
    @endforelse
</x-ui.data-table>
