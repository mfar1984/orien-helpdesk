@php
    $canDelete = $canDelete ?? false;
    $canManage = $canManage ?? false;
    
    // Define all recycle bin sections
    $sections = [
        'tickets' => [
            'title' => 'Deleted Tickets',
            'icon' => 'confirmation_number',
            'color' => 'blue',
            'headers' => ['Ticket ID', 'Subject', 'Created By', 'Deleted At'],
            'data' => $recycleBinData['tickets'] ?? collect(),
            'route_prefix' => 'tickets',
        ],
        'users' => [
            'title' => 'Deleted Users',
            'icon' => 'person',
            'color' => 'purple',
            'headers' => ['Name', 'Email', 'Role', 'Deleted At'],
            'data' => $recycleBinData['users'] ?? collect(),
            'route_prefix' => 'settings.users',
        ],
        'roles' => [
            'title' => 'Deleted Roles',
            'icon' => 'admin_panel_settings',
            'color' => 'indigo',
            'headers' => ['Name', 'Description', 'Deleted At'],
            'data' => $recycleBinData['roles'] ?? collect(),
            'route_prefix' => 'settings.roles',
        ],
        'kb_articles' => [
            'title' => 'Deleted KB Articles',
            'icon' => 'article',
            'color' => 'green',
            'headers' => ['Title', 'Category', 'Deleted At'],
            'data' => $recycleBinData['kb_articles'] ?? collect(),
            'route_prefix' => 'knowledgebase.articles',
        ],
        'kb_categories' => [
            'title' => 'Deleted KB Categories',
            'icon' => 'folder',
            'color' => 'teal',
            'headers' => ['Name', 'Description', 'Deleted At'],
            'data' => $recycleBinData['kb_categories'] ?? collect(),
            'route_prefix' => 'knowledgebase.categories',
        ],
        'ticket_categories' => [
            'title' => 'Deleted Ticket Categories',
            'icon' => 'category',
            'color' => 'orange',
            'headers' => ['Name', 'Description', 'Deleted At'],
            'data' => $recycleBinData['ticket_categories'] ?? collect(),
            'route_prefix' => 'settings.categories.category',
        ],
        'priorities' => [
            'title' => 'Deleted Priorities',
            'icon' => 'priority_high',
            'color' => 'red',
            'headers' => ['Name', 'Color', 'Deleted At'],
            'data' => $recycleBinData['priorities'] ?? collect(),
            'route_prefix' => 'settings.categories.priority',
        ],
        'statuses' => [
            'title' => 'Deleted Statuses',
            'icon' => 'toggle_on',
            'color' => 'cyan',
            'headers' => ['Name', 'Color', 'Deleted At'],
            'data' => $recycleBinData['statuses'] ?? collect(),
            'route_prefix' => 'settings.categories.status',
        ],
        'sla_rules' => [
            'title' => 'Deleted SLA Rules',
            'icon' => 'schedule',
            'color' => 'amber',
            'headers' => ['Name', 'Response Time', 'Deleted At'],
            'data' => $recycleBinData['sla_rules'] ?? collect(),
            'route_prefix' => 'settings.categories.sla-rule',
        ],
        'banned_emails' => [
            'title' => 'Deleted Banned Emails',
            'icon' => 'block',
            'color' => 'rose',
            'headers' => ['Email', 'Reason', 'Deleted At'],
            'data' => $recycleBinData['banned_emails'] ?? collect(),
            'route_prefix' => 'tools.ban-email',
        ],
        'banned_ips' => [
            'title' => 'Deleted Banned IPs',
            'icon' => 'block',
            'color' => 'rose',
            'headers' => ['IP Address', 'Reason', 'Deleted At'],
            'data' => $recycleBinData['banned_ips'] ?? collect(),
            'route_prefix' => 'tools.ban-ip',
        ],
        'whitelist_emails' => [
            'title' => 'Deleted Whitelist Emails',
            'icon' => 'verified_user',
            'color' => 'emerald',
            'headers' => ['Email', 'Reason', 'Deleted At'],
            'data' => $recycleBinData['whitelist_emails'] ?? collect(),
            'route_prefix' => 'tools.whitelist-email',
        ],
        'whitelist_ips' => [
            'title' => 'Deleted Whitelist IPs',
            'icon' => 'verified_user',
            'color' => 'emerald',
            'headers' => ['IP Address', 'Reason', 'Deleted At'],
            'data' => $recycleBinData['whitelist_ips'] ?? collect(),
            'route_prefix' => 'tools.whitelist-ip',
        ],
        'bad_words' => [
            'title' => 'Deleted Bad Words',
            'icon' => 'warning',
            'color' => 'yellow',
            'headers' => ['Word', 'Severity', 'Deleted At'],
            'data' => $recycleBinData['bad_words'] ?? collect(),
            'route_prefix' => 'tools.bad-word',
        ],
        'bad_websites' => [
            'title' => 'Deleted Bad Websites',
            'icon' => 'dangerous',
            'color' => 'pink',
            'headers' => ['URL', 'Severity', 'Deleted At'],
            'data' => $recycleBinData['bad_websites'] ?? collect(),
            'route_prefix' => 'tools.bad-website',
        ],
    ];
    
    // Calculate total deleted items
    $totalDeleted = collect($sections)->sum(fn($s) => $s['data']->count());
@endphp

<div class="space-y-6">
    <!-- Summary Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Recycle Bin</h3>
            <p class="text-xs text-gray-500 mt-1" style="font-family: Poppins, sans-serif;">
                {{ $totalDeleted }} item(s) in recycle bin. Deleted items can be restored or permanently removed.
            </p>
        </div>
        @if($totalDeleted > 0 && $canDelete)
        <button type="button" onclick="showEmptyAllModal()"
                class="inline-flex items-center gap-2 px-3 text-white text-xs font-medium rounded transition"
                style="min-height: 32px; font-family: Poppins, sans-serif; background-color: #dc2626;"
                onmouseover="this.style.backgroundColor='#b91c1c'" 
                onmouseout="this.style.backgroundColor='#dc2626'">
            <span class="material-symbols-outlined" style="font-size: 14px;">delete_forever</span>
            EMPTY ALL
        </button>
        @endif
    </div>

    @if($totalDeleted === 0)
    <div class="bg-gray-50 rounded-lg p-8 text-center">
        <span class="material-symbols-outlined text-gray-300" style="font-size: 64px;">delete</span>
        <p class="text-gray-500 mt-2" style="font-family: Poppins, sans-serif;">Recycle bin is empty</p>
    </div>
    @else
    <!-- Accordion Sections -->
    <div class="space-y-3" x-data="{ openSection: null }">
        @foreach($sections as $key => $section)
            @if($section['data']->count() > 0)
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <!-- Section Header -->
                <button type="button" 
                        @click="openSection = openSection === '{{ $key }}' ? null : '{{ $key }}'"
                        class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-{{ $section['color'] }}-600" style="font-size: 20px;">{{ $section['icon'] }}</span>
                        <span class="text-sm font-medium text-gray-900" style="font-family: Poppins, sans-serif;">{{ $section['title'] }}</span>
                        <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full bg-{{ $section['color'] }}-100 text-{{ $section['color'] }}-700">
                            {{ $section['data']->count() }}
                        </span>
                    </div>
                    <span class="material-symbols-outlined text-gray-400 transition-transform" :class="{ 'rotate-180': openSection === '{{ $key }}' }" style="font-size: 20px;">expand_more</span>
                </button>
                
                <!-- Section Content -->
                <div x-show="openSection === '{{ $key }}'" x-collapse>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    @foreach($section['headers'] as $header)
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: Poppins, sans-serif;">{{ $header }}</th>
                                    @endforeach
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24" style="font-family: Poppins, sans-serif;">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($section['data'] as $item)
                                <tr class="hover:bg-gray-50">
                                    @switch($key)
                                        @case('tickets')
                                            <td class="px-4 py-3 text-xs text-gray-900" style="font-family: Poppins, sans-serif;">{{ $item->ticket_number }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-900" style="font-family: Poppins, sans-serif;">{{ Str::limit($item->subject, 40) }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ $item->creator->name ?? 'N/A' }}</td>
                                            @break
                                        @case('users')
                                            <td class="px-4 py-3 text-xs text-gray-900" style="font-family: Poppins, sans-serif;">{{ $item->name }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ $item->email }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ $item->role->name ?? 'N/A' }}</td>
                                            @break
                                        @case('roles')
                                            <td class="px-4 py-3 text-xs text-gray-900" style="font-family: Poppins, sans-serif;">{{ $item->name }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ Str::limit($item->description, 40) }}</td>
                                            @break
                                        @case('kb_articles')
                                            <td class="px-4 py-3 text-xs text-gray-900" style="font-family: Poppins, sans-serif;">{{ Str::limit($item->title, 40) }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ $item->category->name ?? 'N/A' }}</td>
                                            @break
                                        @case('kb_categories')
                                        @case('ticket_categories')
                                            <td class="px-4 py-3 text-xs text-gray-900" style="font-family: Poppins, sans-serif;">{{ $item->name }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ Str::limit($item->description, 40) }}</td>
                                            @break
                                        @case('priorities')
                                        @case('statuses')
                                            <td class="px-4 py-3 text-xs text-gray-900" style="font-family: Poppins, sans-serif;">{{ $item->name }}</td>
                                            <td class="px-4 py-3">
                                                <span class="inline-block w-4 h-4 rounded" style="background-color: {{ $item->color }}"></span>
                                            </td>
                                            @break
                                        @case('sla_rules')
                                            <td class="px-4 py-3 text-xs text-gray-900" style="font-family: Poppins, sans-serif;">{{ $item->name }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ $item->response_time }}h</td>
                                            @break
                                        @case('banned_emails')
                                        @case('whitelist_emails')
                                            <td class="px-4 py-3 text-xs text-gray-900" style="font-family: Poppins, sans-serif;">{{ $item->email }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ Str::limit($item->reason, 30) }}</td>
                                            @break
                                        @case('banned_ips')
                                        @case('whitelist_ips')
                                            <td class="px-4 py-3 text-xs text-gray-900" style="font-family: Poppins, sans-serif;">{{ $item->ip_address }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-500" style="font-family: Poppins, sans-serif;">{{ Str::limit($item->reason, 30) }}</td>
                                            @break
                                        @case('bad_words')
                                            <td class="px-4 py-3 text-xs text-gray-900" style="font-family: Poppins, sans-serif;">{{ $item->word }}</td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-0.5 text-xs rounded {{ $item->severity === 'high' ? 'bg-red-100 text-red-700' : ($item->severity === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                                                    {{ ucfirst($item->severity) }}
                                                </span>
                                            </td>
                                            @break
                                        @case('bad_websites')
                                            <td class="px-4 py-3 text-xs text-gray-900" style="font-family: Poppins, sans-serif;">{{ Str::limit($item->url, 40) }}</td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-0.5 text-xs rounded {{ $item->severity === 'high' ? 'bg-red-100 text-red-700' : ($item->severity === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                                                    {{ ucfirst($item->severity) }}
                                                </span>
                                            </td>
                                            @break
                                    @endswitch
                                    <td class="px-4 py-3 text-xs text-gray-500 text-center" style="font-family: Poppins, sans-serif;">
                                        {{ $item->deleted_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            @if($canManage)
                                            <button type="button" onclick="restoreItem('{{ $key }}', {{ $item->id }})" 
                                                    class="p-1 text-green-600 hover:bg-green-50 rounded" title="Restore">
                                                <span class="material-symbols-outlined" style="font-size: 16px;">restore</span>
                                            </button>
                                            @endif
                                            @if($canDelete)
                                            <button type="button" onclick="permanentDeleteItem('{{ $key }}', {{ $item->id }})" 
                                                    class="p-1 text-red-600 hover:bg-red-50 rounded" title="Delete Permanently">
                                                <span class="material-symbols-outlined" style="font-size: 16px;">delete_forever</span>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<x-modals.delete-confirmation id="delete-modal" />

<!-- Empty All Confirmation Modal -->
<div id="empty-all-modal" class="fixed inset-0 z-[100000] hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/50" onclick="hideEmptyAllModal()"></div>
    <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-red-600" style="font-size: 24px;">warning</span>
                <h3 class="text-lg font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Empty All Recycle Bin</h3>
            </div>
            <p class="text-sm text-gray-600 mb-6" style="font-family: Poppins, sans-serif;">
                Are you sure you want to permanently delete ALL {{ $totalDeleted }} items in the recycle bin? This action cannot be undone.
            </p>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="hideEmptyAllModal()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200 transition">
                    Cancel
                </button>
                <form action="{{ route('recycle-bin.empty-all') }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700 transition">
                        Delete All Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function restoreItem(type, id) {
    if (confirm('Restore this item?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/recycle-bin/${type}/${id}/restore`;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function permanentDeleteItem(type, id) {
    // Create a custom delete modal for force delete
    const deleteUrl = `/recycle-bin/${type}/${id}/force-delete`;
    
    // Use the global showDeleteModal function if available
    if (typeof showDeleteModal === 'function') {
        showDeleteModal(deleteUrl);
    } else {
        if (confirm('Permanently delete this item? This action cannot be undone.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = deleteUrl;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
}

function showEmptyAllModal() {
    document.getElementById('empty-all-modal').classList.remove('hidden');
    document.getElementById('empty-all-modal').classList.add('flex');
}

function hideEmptyAllModal() {
    document.getElementById('empty-all-modal').classList.add('hidden');
    document.getElementById('empty-all-modal').classList.remove('flex');
}
</script>
@endpush
