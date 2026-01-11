@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->ticket_number)

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Tickets', 'url' => route('tickets.index')],
        ['label' => $ticket->ticket_number, 'active' => true]
    ]" />
@endsection

@section('content')

<!-- Success/Error Messages -->
@if(session('success'))
<div style="margin-bottom: 16px; padding: 12px 16px; background-color: #d1fae5; border: 1px solid #6ee7b7; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
    <span class="material-symbols-outlined" style="font-size: 20px; color: #059669;">check_circle</span>
    <span style="font-size: 13px; color: #065f46; font-family: Poppins, sans-serif;">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div style="margin-bottom: 16px; padding: 12px 16px; background-color: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
    <span class="material-symbols-outlined" style="font-size: 20px; color: #dc2626;">error</span>
    <span style="font-size: 13px; color: #991b1b; font-family: Poppins, sans-serif;">{{ session('error') }}</span>
</div>
@endif

@if(session('info'))
<div style="margin-bottom: 16px; padding: 12px 16px; background-color: #dbeafe; border: 1px solid #93c5fd; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
    <span class="material-symbols-outlined" style="font-size: 20px; color: #2563eb;">info</span>
    <span style="font-size: 13px; color: #1e40af; font-family: Poppins, sans-serif;">{{ session('info') }}</span>
</div>
@endif

@if($errors->any())
<div style="margin-bottom: 16px; padding: 12px 16px; background-color: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px;">
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
        <span class="material-symbols-outlined" style="font-size: 20px; color: #dc2626;">error</span>
        <span style="font-size: 13px; color: #991b1b; font-weight: 600; font-family: Poppins, sans-serif;">Please fix the following errors:</span>
    </div>
    <ul style="list-style: disc; margin-left: 30px;">
        @foreach($errors->all() as $error)
        <li style="font-size: 12px; color: #991b1b; font-family: Poppins, sans-serif;">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="bg-white border border-gray-200">
    <!-- Header -->
    <div class="ticket-show-header px-6 py-4 flex items-center justify-between border-b border-gray-200">
        <div class="flex items-center gap-4">
            <div>
                <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">
                    {{ $ticket->ticket_number }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">{{ $ticket->subject }}</p>
            </div>
        </div>
        <a href="{{ route('tickets.index') }}" 
           class="inline-flex items-center gap-1 px-3 text-gray-600 text-xs font-medium rounded border border-gray-300 hover:bg-gray-50 transition"
           style="min-height: 32px;">
            <span class="material-symbols-outlined" style="font-size: 14px;">arrow_back</span>
            BACK
        </a>
    </div>

    @if(session('success'))
    <div class="px-6 pt-4">
        <div class="px-4 py-3 bg-green-50 border border-green-200 rounded">
            <p class="text-xs text-green-800" style="font-family: Poppins, sans-serif;">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="ticket-show-content" style="padding: 24px;">
        <!-- Ticket Information Card -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
            <!-- Requester Info -->
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; background-color: #ffffff; overflow: hidden;">
                <div style="padding: 14px 16px; background-color: #f0f9ff; border-bottom: 1px solid #bae6fd; display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background-color: #0ea5e9; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="font-size: 20px; color: #ffffff;">person</span>
                    </div>
                    <h4 style="font-size: 13px; font-weight: 600; color: #0c4a6e; font-family: Poppins, sans-serif; margin: 0;">Requester Information</h4>
                </div>
                <div style="padding: 16px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div style="display: flex; align-items: flex-start; gap: 12px;">
                            <div style="width: 32px; height: 32px; border-radius: 6px; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span class="material-symbols-outlined" style="font-size: 18px; color: #64748b;">badge</span>
                            </div>
                            <div style="flex: 1;">
                                <p style="font-size: 10px; color: #94a3b8; margin: 0 0 2px 0; text-transform: uppercase; letter-spacing: 0.5px;">Name</p>
                                <p style="font-size: 12px; font-weight: 500; color: #1e293b; margin: 0; font-family: Poppins, sans-serif;">{{ $ticket->creator->name }}</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; gap: 12px;">
                            <div style="width: 32px; height: 32px; border-radius: 6px; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span class="material-symbols-outlined" style="font-size: 18px; color: #64748b;">mail</span>
                            </div>
                            <div style="flex: 1;">
                                <p style="font-size: 10px; color: #94a3b8; margin: 0 0 2px 0; text-transform: uppercase; letter-spacing: 0.5px;">Email</p>
                                <p style="font-size: 12px; font-weight: 500; color: #1e293b; margin: 0; font-family: Poppins, sans-serif;">{{ $ticket->creator->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ticket Information -->
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; background-color: #ffffff; overflow: hidden;">
                <div style="padding: 14px 16px; background-color: #f0fdf4; border-bottom: 1px solid #bbf7d0; display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background-color: #22c55e; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="font-size: 20px; color: #ffffff;">info</span>
                    </div>
                    <h4 style="font-size: 13px; font-weight: 600; color: #166534; font-family: Poppins, sans-serif; margin: 0;">Ticket Information</h4>
                </div>
                <div style="padding: 16px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                        <div style="display: flex; align-items: flex-start; gap: 12px;">
                            <div style="width: 32px; height: 32px; border-radius: 6px; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span class="material-symbols-outlined" style="font-size: 18px; color: #64748b;">confirmation_number</span>
                            </div>
                            <div style="flex: 1;">
                                <p style="font-size: 10px; color: #94a3b8; margin: 0 0 2px 0; text-transform: uppercase; letter-spacing: 0.5px;">Ticket ID</p>
                                <p style="font-size: 12px; font-weight: 500; color: #1e293b; margin: 0; font-family: Poppins, sans-serif;">{{ $ticket->ticket_number }}</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; gap: 12px;">
                            <div style="width: 32px; height: 32px; border-radius: 6px; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span class="material-symbols-outlined" style="font-size: 18px; color: #64748b;">flag</span>
                            </div>
                            <div style="flex: 1;">
                                <p style="font-size: 10px; color: #94a3b8; margin: 0 0 2px 0; text-transform: uppercase; letter-spacing: 0.5px;">Priority</p>
                                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; font-size: 11px; font-weight: 500; border-radius: 4px; font-family: Poppins, sans-serif; background-color: {{ $ticket->priority->color }}20; color: {{ $ticket->priority->color }};">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">{{ $ticket->priority->icon }}</span>
                                    {{ $ticket->priority->name }}
                                </span>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; gap: 12px;">
                            <div style="width: 32px; height: 32px; border-radius: 6px; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span class="material-symbols-outlined" style="font-size: 18px; color: #64748b;">schedule</span>
                            </div>
                            <div style="flex: 1;">
                                <p style="font-size: 10px; color: #94a3b8; margin: 0 0 2px 0; text-transform: uppercase; letter-spacing: 0.5px;">Status</p>
                                <span style="display: inline-flex; padding: 3px 10px; font-size: 11px; font-weight: 500; border-radius: 4px; font-family: Poppins, sans-serif; background-color: {{ $ticket->status->color }}20; color: {{ $ticket->status->color }};">
                                    {{ $ticket->status->name }}
                                </span>
                            </div>
                        </div>
                        @if($ticket->category)
                        <div style="display: flex; align-items: flex-start; gap: 12px;">
                            <div style="width: 32px; height: 32px; border-radius: 6px; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span class="material-symbols-outlined" style="font-size: 18px; color: #64748b;">label</span>
                            </div>
                            <div style="flex: 1;">
                                <p style="font-size: 10px; color: #94a3b8; margin: 0 0 2px 0; text-transform: uppercase; letter-spacing: 0.5px;">Category</p>
                                <span style="display: inline-flex; padding: 3px 10px; font-size: 11px; font-weight: 500; border-radius: 4px; background-color: {{ $ticket->category->color }}20; color: {{ $ticket->category->color }}; font-family: Poppins, sans-serif;">
                                    {{ $ticket->category->name }}
                                </span>
                            </div>
                        </div>
                        @endif
                        <div style="display: flex; align-items: flex-start; gap: 12px;">
                            <div style="width: 32px; height: 32px; border-radius: 6px; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span class="material-symbols-outlined" style="font-size: 18px; color: #64748b;">calendar_today</span>
                            </div>
                            <div style="flex: 1;">
                                <p style="font-size: 10px; color: #94a3b8; margin: 0 0 2px 0; text-transform: uppercase; letter-spacing: 0.5px;">Created</p>
                                <p style="font-size: 12px; font-weight: 500; color: #1e293b; margin: 0; font-family: Poppins, sans-serif;">{{ $ticket->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; gap: 12px;">
                            <div style="width: 32px; height: 32px; border-radius: 6px; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span class="material-symbols-outlined" style="font-size: 18px; color: #64748b;">person</span>
                            </div>
                            <div style="flex: 1;">
                                <p style="font-size: 10px; color: #94a3b8; margin: 0 0 2px 0; text-transform: uppercase; letter-spacing: 0.5px;">Assigned To</p>
                                <p style="font-size: 12px; font-weight: 500; color: #1e293b; margin: 0; font-family: Poppins, sans-serif;">{{ $ticket->assignee->name ?? 'Unassigned' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area - Chat & Sidebar -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
            <!-- Chat Messages Column -->
            <div style="display: flex; flex-direction: column; border: 1px solid #e5e7eb; border-radius: 8px; background-color: #ffffff; overflow: hidden;">
                
                <!-- Chat Messages Area -->
                <div id="chat-messages" style="padding: 16px; display: flex; flex-direction: column; gap: 16px;">
                    <!-- Original Ticket Message -->
                    <div style="border: 1px solid #bfdbfe; border-radius: 8px; overflow: hidden;">
                        <div style="padding: 12px 16px; background-color: #dbeafe; border-bottom: 1px solid #bfdbfe; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background-color: #3b82f6; display: flex; align-items: center; justify-content: center;">
                                    <span class="material-symbols-outlined" style="font-size: 16px; color: #ffffff;">person</span>
                                </div>
                                <div>
                                    <p style="font-size: 12px; font-weight: 500; color: #1e40af; font-family: Poppins, sans-serif; margin: 0;">{{ $ticket->creator->name }}</p>
                                    <p style="font-size: 11px; color: #3b82f6; margin: 0;">{{ $ticket->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            <span style="display: inline-flex; padding: 2px 8px; font-size: 10px; font-weight: 500; background-color: #f3e8ff; color: #7c3aed; border-radius: 4px;">
                                Original Message
                            </span>
                        </div>
                        <div style="padding: 16px;">
                            <p style="font-size: 12px; color: #374151; font-family: Poppins, sans-serif; margin: 0; white-space: pre-wrap; line-height: 1.6;">{{ $ticket->description }}</p>
                        </div>
                        @if($ticket->attachments->where('ticket_reply_id', null)->count() > 0)
                        <div style="padding: 12px 16px; border-top: 1px solid #bfdbfe; background-color: #f9fafb;">
                            <p style="font-size: 11px; color: #6b7280; margin: 0 0 8px 0;">Attachments:</p>
                            @foreach($ticket->attachments->where('ticket_reply_id', null) as $attachment)
                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" 
                               style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 4px; text-decoration: none; margin-right: 8px; margin-bottom: 8px;">
                                <span class="material-symbols-outlined" style="font-size: 16px; color: #6b7280;">attach_file</span>
                                <span style="font-size: 11px; color: #374151;">{{ $attachment->file_name }}</span>
                                <span style="font-size: 10px; color: #9ca3af;">({{ $attachment->formatted_size }})</span>
                            </a>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    @foreach($ticket->replies as $reply)
                    @php $replyType = $reply->getType(); @endphp
                    
                    @if($replyType === 'internal')
                    <!-- Internal Note - Yellow -->
                    <div style="border: 1px solid #fde047; border-radius: 8px; overflow: hidden; background-color: #fefce8;">
                        <div style="padding: 12px 16px; background-color: #fef08a; border-bottom: 1px solid #fde047; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background-color: #eab308; display: flex; align-items: center; justify-content: center;">
                                    <span class="material-symbols-outlined" style="font-size: 16px; color: #ffffff;">lock</span>
                                </div>
                                <div>
                                    <p style="font-size: 12px; font-weight: 500; color: #854d0e; font-family: Poppins, sans-serif; margin: 0;">{{ $reply->user->name }}</p>
                                    <p style="font-size: 11px; color: #a16207; margin: 0;">{{ $reply->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            <span style="display: inline-flex; padding: 2px 8px; font-size: 10px; font-weight: 500; background-color: #fde047; color: #854d0e; border-radius: 4px;">
                                Internal Note
                            </span>
                        </div>
                        <div style="padding: 16px;">
                            <p style="font-size: 12px; color: #374151; font-family: Poppins, sans-serif; margin: 0; white-space: pre-wrap; line-height: 1.6;">{{ $reply->message }}</p>
                        </div>
                    </div>
                    @elseif($replyType === 'staff')
                    <!-- Staff Reply - Green -->
                    <div style="border: 1px solid #86efac; border-radius: 8px; overflow: hidden;">
                        <div style="padding: 12px 16px; background-color: #dcfce7; border-bottom: 1px solid #86efac; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background-color: #22c55e; display: flex; align-items: center; justify-content: center;">
                                    <span class="material-symbols-outlined" style="font-size: 16px; color: #ffffff;">support_agent</span>
                                </div>
                                <div>
                                    <p style="font-size: 12px; font-weight: 500; color: #166534; font-family: Poppins, sans-serif; margin: 0;">{{ $reply->user->name }}</p>
                                    <p style="font-size: 11px; color: #16a34a; margin: 0;">{{ $reply->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            <span style="display: inline-flex; padding: 2px 8px; font-size: 10px; font-weight: 500; background-color: #bbf7d0; color: #166534; border-radius: 4px;">
                                Staff Reply
                            </span>
                        </div>
                        <div style="padding: 16px;">
                            <p style="font-size: 12px; color: #374151; font-family: Poppins, sans-serif; margin: 0; white-space: pre-wrap; line-height: 1.6;">{{ $reply->message }}</p>
                        </div>
                        @if($reply->attachments->count() > 0)
                        <div style="padding: 12px 16px; border-top: 1px solid #86efac; background-color: #f9fafb;">
                            <p style="font-size: 11px; color: #6b7280; margin: 0 0 8px 0;">Attachments:</p>
                            @foreach($reply->attachments as $attachment)
                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" 
                               style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 4px; text-decoration: none; margin-right: 8px; margin-bottom: 8px;">
                                <span class="material-symbols-outlined" style="font-size: 16px; color: #6b7280;">attach_file</span>
                                <span style="font-size: 11px; color: #374151;">{{ $attachment->file_name }}</span>
                                <span style="font-size: 10px; color: #9ca3af;">({{ $attachment->formatted_size }})</span>
                            </a>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @else
                    <!-- Customer Reply - Blue -->
                    <div style="border: 1px solid #bfdbfe; border-radius: 8px; overflow: hidden;">
                        <div style="padding: 12px 16px; background-color: #dbeafe; border-bottom: 1px solid #bfdbfe; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background-color: #3b82f6; display: flex; align-items: center; justify-content: center;">
                                    <span class="material-symbols-outlined" style="font-size: 16px; color: #ffffff;">person</span>
                                </div>
                                <div>
                                    <p style="font-size: 12px; font-weight: 500; color: #1e40af; font-family: Poppins, sans-serif; margin: 0;">{{ $reply->user->name }}</p>
                                    <p style="font-size: 11px; color: #3b82f6; margin: 0;">{{ $reply->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            <span style="display: inline-flex; padding: 2px 8px; font-size: 10px; font-weight: 500; background-color: #bfdbfe; color: #1e40af; border-radius: 4px;">
                                Customer Reply
                            </span>
                        </div>
                        <div style="padding: 16px;">
                            <p style="font-size: 12px; color: #374151; font-family: Poppins, sans-serif; margin: 0; white-space: pre-wrap; line-height: 1.6;">{{ $reply->message }}</p>
                        </div>
                        @if($reply->attachments->count() > 0)
                        <div style="padding: 12px 16px; border-top: 1px solid #bfdbfe; background-color: #f9fafb;">
                            <p style="font-size: 11px; color: #6b7280; margin: 0 0 8px 0;">Attachments:</p>
                            @foreach($reply->attachments as $attachment)
                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" 
                               style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 4px; text-decoration: none; margin-right: 8px; margin-bottom: 8px;">
                                <span class="material-symbols-outlined" style="font-size: 16px; color: #6b7280;">attach_file</span>
                                <span style="font-size: 11px; color: #374151;">{{ $attachment->file_name }}</span>
                                <span style="font-size: 10px; color: #9ca3af;">({{ $attachment->formatted_size }})</span>
                            </a>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif
                    @endforeach
                </div>

                <!-- Reply Form -->
                @if($ticket->status->name !== 'Closed' && auth()->user()->hasPermission('tickets.edit'))
                <div style="border-top: 1px solid #e5e7eb; background-color: #f9fafb; flex-shrink: 0;">
                    <div style="padding: 12px 16px; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between;">
                        <h4 style="font-size: 12px; font-weight: 600; color: #374151; font-family: Poppins, sans-serif; margin: 0;">Add Reply</h4>
                    </div>
                    <form action="{{ route('tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="ticket-reply-form" style="padding: 16px; background-color: #ffffff;">
                        @csrf
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <textarea name="message" required rows="3"
                                      style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #111827; resize: none; outline: none; box-sizing: border-box;"
                                      placeholder="Type your reply..."
                                      onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'"></textarea>
                            
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px;">
                                <div style="display: flex; align-items: center; gap: 16px;">
                                    <div style="position: relative;">
                                        <input type="file" name="attachments[]" multiple id="reply-attachments" style="display: none;">
                                        <label for="reply-attachments" 
                                               style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 12px; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px; font-size: 11px; font-family: Poppins, sans-serif; color: #374151; cursor: pointer;">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">attach_file</span>
                                            <span id="file-label">Attach Files</span>
                                        </label>
                                    </div>
                                    @if(auth()->user()->hasRole('administrator') || auth()->user()->hasRole('agent'))
                                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                        <input type="checkbox" name="is_internal_note" value="1" 
                                               style="width: 16px; height: 16px; border-radius: 4px; border: 1px solid #d1d5db;">
                                        <span style="font-size: 12px; color: #4b5563; font-family: Poppins, sans-serif;">Internal Note</span>
                                    </label>
                                    @endif
                                </div>
                                
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <!-- Working Time Input -->
                                    @if(auth()->user()->hasRole('administrator') || auth()->user()->hasRole('agent'))
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <span class="material-symbols-outlined" style="font-size: 18px; color: #6b7280;">schedule</span>
                                        <input type="number" 
                                               name="working_time" 
                                               min="0" 
                                               step="0.5"
                                               placeholder="0.0"
                                               style="width: 80px; padding: 8px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 11px; text-align: center; outline: none;"
                                               onfocus="this.style.borderColor='#3b82f6'" 
                                               onblur="this.style.borderColor='#d1d5db'">
                                        <span style="font-size: 11px; color: #6b7280; font-family: Poppins, sans-serif; white-space: nowrap;">hours</span>
                                    </div>
                                    @endif
                                    
                                    <button type="submit" 
                                            style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background-color: #2563eb; color: #ffffff; border: none; border-radius: 6px; font-size: 11px; font-weight: 500; font-family: Poppins, sans-serif; cursor: pointer; white-space: nowrap;">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">send</span>
                                        SEND REPLY
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @elseif($ticket->status->name === 'Closed')
                <div style="border-top: 1px solid #e5e7eb; padding: 24px; text-align: center; background-color: #f9fafb;">
                    <span class="material-symbols-outlined" style="font-size: 32px; color: #9ca3af;">lock</span>
                    <p style="font-size: 12px; color: #6b7280; margin: 8px 0 0 0; font-family: Poppins, sans-serif;">This ticket is closed.</p>
                </div>
                @else
                <div style="border-top: 1px solid #e5e7eb; padding: 24px; text-align: center; background-color: #f9fafb;">
                    <span class="material-symbols-outlined" style="font-size: 32px; color: #9ca3af;">visibility</span>
                    <p style="font-size: 12px; color: #6b7280; margin: 8px 0 0 0; font-family: Poppins, sans-serif;">View only mode - You don't have permission to reply.</p>
                </div>
                @endif
            </div>

            <!-- Sidebar - Actions -->
            <div class="sidebar-scroll" style="display: flex; flex-direction: column; gap: 16px; position: sticky; top: 80px; padding-right: 4px;">
                <!-- Assigned To -->
                <div style="border: 1px solid #e5e7eb; border-radius: 8px; background-color: #ffffff; overflow: hidden;">
                    <div style="padding: 12px 16px; background-color: #faf5ff; border-bottom: 1px solid #e9d5ff; display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background-color: #a855f7; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 18px; color: #ffffff;">group</span>
                        </div>
                        <h4 style="font-size: 12px; font-weight: 600; color: #6b21a8; font-family: Poppins, sans-serif; margin: 0;">Assigned To</h4>
                    </div>
                    <div style="padding: 10px 12px;">
                        @if($ticket->assignees && $ticket->assignees->count() > 0)
                            <!-- Compact scrollable list - name only, max 3 visible -->
                            <div class="assigned-to-list" style="max-height: 90px; overflow-y: auto; display: flex; flex-direction: column; gap: 4px;">
                                @foreach($ticket->assignees as $assignee)
                                <div style="display: flex; align-items: center; gap: 8px; padding: 6px 8px; border-radius: 4px; background-color: #f9fafb; border: 1px solid #e9d5ff;">
                                    <div style="width: 22px; height: 22px; border-radius: 50%; background-color: #f3e8ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <span class="material-symbols-outlined" style="font-size: 12px; color: #a855f7;">person</span>
                                    </div>
                                    <span style="font-size: 11px; color: #374151; font-family: Poppins, sans-serif; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $assignee->name }}</span>
                                </div>
                                @endforeach
                            </div>
                            @if($ticket->assignees->count() > 3)
                            <p style="font-size: 9px; color: #9ca3af; text-align: center; margin: 4px 0 0 0; font-family: Poppins, sans-serif;">
                                ↕ {{ $ticket->assignees->count() }} assigned
                            </p>
                            @endif
                        @else
                            <p style="font-size: 11px; color: #9ca3af; font-family: Poppins, sans-serif; margin: 0; text-align: center; padding: 4px 0;">Not assigned yet</p>
                        @endif
                    </div>
                </div>

                <!-- Actions (Only for users with manage permission) -->
                @if(auth()->user()->hasPermission('tickets.manage'))
                <div style="border: 1px solid #e5e7eb; border-radius: 8px; background-color: #ffffff; overflow: hidden;">
                    <div style="padding: 12px 16px; background-color: #fef3c7; border-bottom: 1px solid #fde68a; display: flex; align-items: center; gap: 10px; position: sticky; top: 0; z-index: 1;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background-color: #f59e0b; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 18px; color: #ffffff;">settings</span>
                        </div>
                        <h4 style="font-size: 12px; font-weight: 600; color: #92400e; font-family: Poppins, sans-serif; margin: 0;">Actions</h4>
                    </div>
                    <div class="actions-content" style="padding: 16px;">
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <!-- Assign Form (Only if user has manage permission) -->
                            @if(auth()->user()->hasPermission('tickets.manage'))
                            <form id="assign-form" action="{{ route('tickets.assign', $ticket->id) }}" method="POST">
                                @csrf
                                <div>
                                    <label style="display: block; font-size: 11px; color: #374151; margin-bottom: 8px; font-family: Poppins, sans-serif; font-weight: 500;">Assign To (Multiple)</label>
                                    
                                    <!-- Agents Checkbox List - Show 2 items by default (~90px) -->
                                    <div class="assign-checkbox-list" style="max-height: 90px; overflow-y: auto; border: 1px solid #d1d5db; border-radius: 6px; padding: 6px; background-color: #f9fafb;">
                                        @php
                                            $assignedIds = $ticket->assignees->pluck('id')->toArray();
                                        @endphp
                                        @foreach($agents as $agent)
                                        <label style="display: flex; align-items: center; gap: 8px; padding: 8px 6px; cursor: pointer; border-radius: 4px; transition: background-color 0.2s; border-bottom: 1px solid #e5e7eb;"
                                               onmouseover="this.style.backgroundColor='#e5e7eb'" 
                                               onmouseout="this.style.backgroundColor='transparent'">
                                            <input type="checkbox" 
                                                   name="assigned_to[]" 
                                                   value="{{ $agent->id }}"
                                                   {{ in_array($agent->id, $assignedIds) ? 'checked' : '' }}
                                                   class="assign-checkbox"
                                                   style="width: 16px; height: 16px; cursor: pointer; accent-color: #2563eb; flex-shrink: 0;">
                                            <div style="flex: 1; display: flex; flex-direction: column; min-width: 0;">
                                                <span style="font-size: 11px; color: #374151; font-family: Poppins, sans-serif; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $agent->name }}</span>
                                                <span style="font-size: 9px; color: #9ca3af; font-family: Poppins, sans-serif;">{{ $agent->role->name ?? 'No Role' }}</span>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                    @if(count($agents) > 2)
                                    <p style="font-size: 9px; color: #9ca3af; text-align: center; margin: 4px 0 0 0; font-family: Poppins, sans-serif;">
                                        ↕ Scroll for more ({{ count($agents) }} agents)
                                    </p>
                                    @endif
                                    
                                    <button id="assign-button" type="submit"
                                            style="width: 100%; margin-top: 8px; padding: 10px 16px; background-color: #2563eb; color: #ffffff; border: none; border-radius: 6px; font-size: 11px; font-weight: 500; font-family: Poppins, sans-serif; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">person_add</span>
                                        UPDATE ASSIGNMENT
                                    </button>
                                </div>
                            </form>
                            @endif

                            <!-- Status Update (Only if user has manage permission) -->
                            @if(auth()->user()->hasPermission('tickets.manage'))
                            <form id="status-form" action="{{ route('tickets.updateStatus', $ticket->id) }}" method="POST" style="border-top: 1px solid #e5e7eb; padding-top: 12px;">
                                @csrf
                                <div>
                                    <label style="display: block; font-size: 11px; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif; font-weight: 500;">Update Status</label>
                                    <select name="status_id" id="status-select"
                                            style="width: 100%; padding: 8px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 11px; font-family: Poppins, sans-serif; outline: none;">
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}" {{ $ticket->status_id == $status->id ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button id="status-button" type="submit"
                                            style="width: 100%; margin-top: 8px; padding: 10px 16px; background-color: #059669; color: #ffffff; border: none; border-radius: 6px; font-size: 11px; font-weight: 500; font-family: Poppins, sans-serif; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">sync</span>
                                        UPDATE STATUS
                                    </button>
                                </div>
                            </form>
                            @endif

                            <!-- Priority Update (Only if user has manage permission) -->
                            @if(auth()->user()->hasPermission('tickets.manage'))
                            <form id="priority-form" action="{{ route('tickets.updatePriority', $ticket->id) }}" method="POST" style="border-top: 1px solid #e5e7eb; padding-top: 12px;">
                                @csrf
                                <div>
                                    <label style="display: block; font-size: 11px; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif; font-weight: 500;">Update Priority</label>
                                    <select name="priority_id" id="priority-select"
                                            style="width: 100%; padding: 8px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 11px; font-family: Poppins, sans-serif; outline: none;">
                                        @foreach($priorities as $priority)
                                            <option value="{{ $priority->id }}" {{ $ticket->priority_id == $priority->id ? 'selected' : '' }}>
                                                {{ $priority->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button id="priority-button" type="submit"
                                            style="width: 100%; margin-top: 8px; padding: 10px 16px; background-color: #ea580c; color: #ffffff; border: none; border-radius: 6px; font-size: 11px; font-weight: 500; font-family: Poppins, sans-serif; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">flag</span>
                                        UPDATE PRIORITY
                                    </button>
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Custom Scrollbar Styling */
#chat-messages::-webkit-scrollbar,
.sidebar-scroll::-webkit-scrollbar {
    width: 8px;
}

#chat-messages::-webkit-scrollbar-track,
.sidebar-scroll::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 4px;
}

#chat-messages::-webkit-scrollbar-thumb,
.sidebar-scroll::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 4px;
}

#chat-messages::-webkit-scrollbar-thumb:hover,
.sidebar-scroll::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Firefox Scrollbar */
#chat-messages,
.sidebar-scroll {
    scrollbar-width: thin;
    scrollbar-color: #d1d5db #f3f4f6;
}
</style>
@endpush

@push('styles')
<style>
/* Custom scrollbar for sidebar */
.sidebar-scroll::-webkit-scrollbar {
    width: 6px;
}
.sidebar-scroll::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}
.sidebar-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}
.sidebar-scroll::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Custom scrollbar for chat */
#chat-messages::-webkit-scrollbar {
    width: 6px;
}
#chat-messages::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}
#chat-messages::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}
#chat-messages::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 2px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 2px;
}

/* Assigned To list scrollbar - purple theme */
.assigned-to-list::-webkit-scrollbar {
    width: 5px;
}
.assigned-to-list::-webkit-scrollbar-track {
    background: #faf5ff;
    border-radius: 3px;
}
.assigned-to-list::-webkit-scrollbar-thumb {
    background: #c4b5fd;
    border-radius: 3px;
}
.assigned-to-list::-webkit-scrollbar-thumb:hover {
    background: #a78bfa;
}

/* Assign checkbox list scrollbar - blue theme */
.assign-checkbox-list::-webkit-scrollbar {
    width: 5px;
}
.assign-checkbox-list::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}
.assign-checkbox-list::-webkit-scrollbar-thumb {
    background: #93c5fd;
    border-radius: 3px;
}
.assign-checkbox-list::-webkit-scrollbar-thumb:hover {
    background: #60a5fa;
}

/* Remove last border in checkbox list */
.assign-checkbox-list label:last-child {
    border-bottom: none !important;
}

/* Actions content scrollbar - amber/orange theme */
.actions-content::-webkit-scrollbar {
    width: 5px;
}
.actions-content::-webkit-scrollbar-track {
    background: #fef3c7;
    border-radius: 3px;
}
.actions-content::-webkit-scrollbar-thumb {
    background: #fcd34d;
    border-radius: 3px;
}
.actions-content::-webkit-scrollbar-thumb:hover {
    background: #fbbf24;
}
</style>
@endpush

@push('scripts')
<script>
// Auto-scroll chat to bottom on page load
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chat-messages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});

// Update file input label when files are selected
document.getElementById('reply-attachments')?.addEventListener('change', function(e) {
    const fileLabel = document.getElementById('file-label');
    const fileCount = e.target.files.length;
    
    if (fileCount > 0) {
        fileLabel.textContent = fileCount + ' file' + (fileCount > 1 ? 's' : '') + ' selected';
    } else {
        fileLabel.textContent = 'Attach Files';
    }
});

// Handle assign form submission with loading state
const assignForm = document.getElementById('assign-form');
const assignButton = document.getElementById('assign-button');
const assignCheckboxes = document.querySelectorAll('.assign-checkbox');

if (assignForm && assignButton) {
    assignForm.addEventListener('submit', function(e) {
        // Allow unassigning all agents (no validation required)
        // Disable button and show loading state
        assignButton.disabled = true;
        assignButton.style.opacity = '0.6';
        assignButton.style.cursor = 'not-allowed';
        assignButton.innerHTML = '<span class="material-symbols-outlined" style="font-size: 16px;">hourglass_empty</span> UPDATING...';
    });
    
}

// Handle status form submission with loading state
const statusForm = document.getElementById('status-form');
const statusButton = document.getElementById('status-button');
const statusSelect = document.getElementById('status-select');

if (statusForm && statusButton && statusSelect) {
    statusForm.addEventListener('submit', function(e) {
        // Disable button and show loading state
        statusButton.disabled = true;
        statusButton.style.opacity = '0.6';
        statusButton.style.cursor = 'not-allowed';
        statusButton.innerHTML = '<span class="material-symbols-outlined" style="font-size: 16px;">hourglass_empty</span> UPDATING...';
    });
}

// Handle priority form submission with loading state
const priorityForm = document.getElementById('priority-form');
const priorityButton = document.getElementById('priority-button');
const prioritySelect = document.getElementById('priority-select');

if (priorityForm && priorityButton && prioritySelect) {
    priorityForm.addEventListener('submit', function(e) {
        // Disable button and show loading state
        priorityButton.disabled = true;
        priorityButton.style.opacity = '0.6';
        priorityButton.style.cursor = 'not-allowed';
        priorityButton.innerHTML = '<span class="material-symbols-outlined" style="font-size: 16px;">hourglass_empty</span> UPDATING...';
    });
}
</script>
@endpush
@endsection
