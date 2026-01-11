@extends('layouts.app')

@section('title', 'Tickets')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Tickets', 'active' => true]
    ]" />
@endsection

@section('content')
<div class="bg-white border border-gray-200">
    <!-- Page Header -->
    <div class="tickets-page-header px-6 py-4 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Tickets</h2>
            <p class="text-xs text-gray-500 mt-0.5">Manage and track support tickets</p>
        </div>
        @if(auth()->user()->hasPermission('tickets.create'))
        <div class="flex items-center gap-2">
            <a href="{{ route('tickets.create') }}" 
               class="inline-flex items-center gap-2 px-3 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition"
               style="min-height: 32px; font-family: Poppins, sans-serif;">
                <span class="material-symbols-outlined" style="font-size: 14px;">add_circle</span>
                TICKET
            </a>
        </div>
        @endif
    </div>

    <!-- Tabs Navigation -->
    <div class="border-t border-gray-200">
        <nav class="tickets-tabs-nav flex px-6" aria-label="Tabs">
            @foreach($tabs as $key => $label)
                <a href="{{ route('tickets.index', ['tab' => $key]) }}"
                   class="px-4 py-3 text-xs font-medium border-b-2 {{ $currentTab === $key ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                   style="font-family: Poppins, sans-serif;">
                    <span class="inline-flex items-center gap-1">
                        {{ $label }}
                        <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-medium rounded-full {{ $currentTab === $key ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}" style="font-size: 9px; min-width: 18px;">
                            {{ $counts[$key] ?? 0 }}
                        </span>
                    </span>
                </a>
            @endforeach
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="border-t border-gray-200">
        @if(session('success'))
        <div class="px-6 pt-4">
            <div class="px-4 py-3 bg-green-50 border border-green-200 rounded">
                <p class="text-xs text-green-800" style="font-family: Poppins, sans-serif;">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Filter Form -->
        <div class="px-6 py-3">
            <form action="{{ route('tickets.index') }}" method="GET" class="tickets-filter-form flex items-center gap-2">
                <input type="hidden" name="tab" value="{{ $currentTab }}">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search ticket ID or subject..." 
                           class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                           style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                </div>
                <select name="priority" class="px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 min-w-[120px]" style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                    <option value="">All Priority</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority->id }}" {{ request('priority') == $priority->id ? 'selected' : '' }}>{{ $priority->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 min-w-[120px]" style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                    <option value="">All Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                    @endforeach
                </select>
                <div class="filter-buttons flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-3 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition" style="min-height: 32px;">
                        <span class="material-symbols-outlined" style="font-size: 14px;">search</span>
                        SEARCH
                    </button>
                    <button type="button" onclick="window.location.href='{{ route('tickets.index', ['tab' => $currentTab]) }}'" class="inline-flex items-center gap-2 px-3 text-white text-xs font-medium rounded transition" style="min-height: 32px; background-color: #dc2626;">
                        <span class="material-symbols-outlined" style="font-size: 14px;">refresh</span>
                        RESET
                    </button>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="tickets-table-container px-6">
            <x-ui.data-table
                :headers="[
                    ['label' => 'ID', 'align' => 'text-left', 'width' => 'w-28'],
                    ['label' => 'Subject', 'align' => 'text-left'],
                    ['label' => 'Requester', 'align' => 'text-left', 'width' => 'w-40'],
                    ['label' => 'Status', 'align' => 'text-center', 'width' => 'w-28'],
                    ['label' => 'Priority', 'align' => 'text-center', 'width' => 'w-28'],
                    ['label' => 'Created', 'align' => 'text-center', 'width' => 'w-32'],
                ]"
                :actions="true"
                empty-message="No tickets found."
            >
                @forelse($tickets as $ticket)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('tickets.show', $ticket->id) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium" 
                           style="font-family: Poppins, sans-serif; font-size: 12px;">
                            {{ $ticket->ticket_number }}
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900" style="font-family: Poppins, sans-serif; font-size: 12px;">
                            {{ $ticket->subject }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-medium">
                                {{ strtoupper(substr($ticket->creator->name, 0, 1)) }}
                            </div>
                            <span class="text-xs text-gray-700" style="font-family: Poppins, sans-serif;">{{ $ticket->creator->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded" 
                              style="background-color: {{ $ticket->status->color }}20; color: {{ $ticket->status->color }}; font-size: 10px; font-family: Poppins, sans-serif;">
                            {{ $ticket->status->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded" 
                              style="background-color: {{ $ticket->priority->color }}20; color: {{ $ticket->priority->color }}; font-size: 10px; font-family: Poppins, sans-serif;">
                            <span class="material-symbols-outlined" style="font-size: 12px;">{{ $ticket->priority->icon }}</span>
                            {{ $ticket->priority->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">
                            {{ format_datetime($ticket->created_at) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <x-ui.action-buttons
                            :show-url="route('tickets.show', $ticket->id)"
                            :edit-onclick="$ticket->canBeEditedBy(auth()->user()) ? 'showEditTicketModal(' . $ticket->id . ')' : null"
                            :delete-onclick="$ticket->canBeDeletedBy(auth()->user()) ? 'showDeleteModal(\'' . route('tickets.destroy', $ticket->id) . '\')' : null"
                        />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500 text-sm">
                        No tickets found in this category.
                    </td>
                </tr>
                @endforelse
            </x-ui.data-table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-3">
            <div class="tickets-pagination flex items-center justify-between">
                <p class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">
                    Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} tickets
                </p>
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Edit Ticket Modal -->
<div id="edit-ticket-modal" class="modal-hidden" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 100000;">
    <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
        <div onclick="event.stopPropagation()" style="background-color: #ffffff; border-radius: 8px; width: 100%; max-width: 600px; max-height: 90vh; overflow-y: auto; position: relative;">
            <div style="padding: 20px 24px; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between;">
                <h3 style="font-size: 16px; font-weight: 600; color: #111827; font-family: Poppins, sans-serif; margin: 0;">Edit Ticket</h3>
                <button onclick="closeEditModal()" type="button" style="color: #9ca3af; cursor: pointer; background: none; border: none; padding: 4px;">
                    <span class="material-symbols-outlined" style="font-size: 24px;">close</span>
                </button>
            </div>
            <form id="edit-ticket-form" method="POST" style="padding: 24px;">
                @csrf
                @method('PUT')
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                            Subject <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="subject" id="edit-subject" required
                               style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                            Description <span style="color: #ef4444;">*</span>
                        </label>
                        <textarea name="description" id="edit-description" required rows="6"
                                  style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; resize: vertical; outline: none; box-sizing: border-box;"></textarea>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
                        <button type="submit" 
                                style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background-color: #3b82f6; color: #ffffff; border: none; border-radius: 6px; font-size: 11px; font-weight: 500; font-family: Poppins, sans-serif; cursor: pointer;">
                            <span class="material-symbols-outlined" style="font-size: 16px;">save</span>
                            SAVE CHANGES
                        </button>
                        <button type="button" onclick="closeEditModal()" 
                                style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background-color: #ffffff; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; font-size: 11px; font-weight: 500; font-family: Poppins, sans-serif; cursor: pointer;">
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.modal-hidden {
    display: none !important;
}
.modal-visible {
    display: block !important;
}
</style>

<!-- Delete Confirmation Modal -->
<x-modals.delete-confirmation modal-id="delete-modal" />

@push('scripts')
<script>
function showEditTicketModal(ticketId) {
    const modal = document.getElementById('edit-ticket-modal');
    
    fetch(`/tickets/${ticketId}/edit`)
        .then(response => response.json())
        .then(ticket => {
            document.getElementById('edit-subject').value = ticket.subject;
            document.getElementById('edit-description').value = ticket.description;
            document.getElementById('edit-ticket-form').action = `/tickets/${ticketId}`;
            
            // Show modal
            modal.classList.remove('modal-hidden');
            modal.classList.add('modal-visible');
        })
        .catch(error => {
            alert('Error loading ticket data');
        });
}

function closeEditModal() {
    const modal = document.getElementById('edit-ticket-modal');
    modal.classList.remove('modal-visible');
    modal.classList.add('modal-hidden');
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('edit-ticket-modal');
        if (modal && modal.classList.contains('modal-visible')) {
            closeEditModal();
        }
    }
});

// Close modal on backdrop click
document.getElementById('edit-ticket-modal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script>
@endpush
@endsection
