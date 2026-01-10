<form method="POST" action="{{ route('settings.integrations.api.save') }}">
@csrf
@php
    $apiSettings = $settings['api_settings'] ?? [];
    $isEnabled = $apiSettings['api_enabled'] ?? true;
    $canEdit = $canEdit ?? false;
    $disabledAttr = $canEdit ? '' : 'disabled';
    $disabledClass = $canEdit ? '' : 'bg-gray-100 cursor-not-allowed';
@endphp

<div class="space-y-6">
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" style="font-family: Poppins, sans-serif; font-size: 12px;">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined" style="font-size: 18px;">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" style="font-family: Poppins, sans-serif; font-size: 12px;">
        <div class="flex items-start gap-2">
            <span class="material-symbols-outlined" style="font-size: 18px;">error</span>
            <div>
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Status Card -->
    <div class="bg-gray-50 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: #f3e8ff;">
                    <span class="material-symbols-outlined" style="font-size: 20px; color: #9333ea;">api</span>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">API Settings</h3>
                    <p class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">Manage API keys and access tokens</p>
                </div>
            </div>
            @if($isEnabled)
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700" style="font-family: Poppins, sans-serif;">
                    ✓ Active
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600" style="font-family: Poppins, sans-serif;">
                    Disabled
                </span>
            @endif
        </div>
    </div>

    <!-- API Key Section -->
    <div class="border border-gray-200 rounded-lg overflow-hidden">
        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center gap-2">
            <span class="material-symbols-outlined text-gray-500" style="font-size: 18px;">key</span>
            <h3 class="text-xs font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">API Keys</h3>
        </div>
        <div class="p-4">
            <div class="mb-4">
                <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                    Public API Key
                </label>
                <div class="flex gap-2">
                    <input type="text" value="pk_live_xxxxxxxxxxxxxxxxxxxxxxxx" readonly
                           class="flex-1 px-3 border border-gray-300 rounded-md text-gray-600 bg-gray-50"
                           style="min-height: 36px; font-size: 12px; font-family: 'Courier New', monospace;">
                    <button type="button" 
                            class="inline-flex items-center gap-1.5 px-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-all"
                            style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;">
                        <span class="material-symbols-outlined" style="font-size: 16px;">content_copy</span>
                        Copy
                    </button>
                </div>
            </div>
            <div class="mb-0">
                <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                    Secret API Key
                </label>
                <div class="flex gap-2">
                    <input type="password" value="sk_test_xxxxxxxxxxxxxxxxxxxx" readonly
                           class="flex-1 px-3 border border-gray-300 rounded-md text-gray-600 bg-gray-50"
                           style="min-height: 36px; font-size: 12px; font-family: 'Courier New', monospace;">
                    <button type="button" 
                            class="inline-flex items-center gap-1.5 px-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-all"
                            style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;">
                        <span class="material-symbols-outlined" style="font-size: 16px;">visibility</span>
                        Show
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-1.5" style="font-family: Poppins, sans-serif;">Keep this key secret. Do not share it publicly.</p>
            </div>
        </div>
    </div>

    <!-- API Settings -->
    <div class="border border-gray-200 rounded-lg overflow-hidden">
        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center gap-2">
            <span class="material-symbols-outlined text-gray-500" style="font-size: 18px;">settings</span>
            <h3 class="text-xs font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">API Configuration</h3>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                        Rate Limit (requests/minute)
                    </label>
                    <input type="number" name="rate_limit" value="{{ $apiSettings['rate_limit'] ?? 60 }}" 
                           class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                           style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" {{ $disabledAttr }}>
                </div>
                <div>
                    <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                        Token Expiry (days)
                    </label>
                    <input type="number" name="token_expiry" value="{{ $apiSettings['token_expiry'] ?? 30 }}" 
                           class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                           style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" {{ $disabledAttr }}>
                </div>
            </div>
            <div>
                <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg {{ $canEdit ? 'cursor-pointer hover:bg-gray-50' : 'cursor-not-allowed bg-gray-50' }} transition-colors">
                    <input type="checkbox" name="api_enabled" value="1" {{ $isEnabled ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ $disabledAttr }}>
                    <div>
                        <span class="text-gray-700 block" style="font-size: 12px; font-family: Poppins, sans-serif; font-weight: 500;">Enable API Access</span>
                        <p class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">Allow external applications to access the API</p>
                    </div>
                </label>
            </div>
        </div>
    </div>

    <!-- Webhooks Section -->
    <div class="border border-gray-200 rounded-lg overflow-hidden">
        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center gap-2">
            <span class="material-symbols-outlined text-gray-500" style="font-size: 18px;">webhook</span>
            <h3 class="text-xs font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Webhooks</h3>
        </div>
        <div class="p-4">
            <div class="mb-4">
                <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                    Webhook URL
                </label>
                <input type="url" name="webhook_url" value="{{ $apiSettings['webhook_url'] ?? '' }}" 
                       class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                       style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" 
                       placeholder="https://your-domain.com/webhook" {{ $disabledAttr }}>
                <p class="text-xs text-gray-400 mt-1.5" style="font-family: Poppins, sans-serif;">URL that will receive webhook events</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                    Webhook Secret
                </label>
                <div class="flex gap-2">
                    <input type="text" name="webhook_secret" id="webhookSecret" value="{{ $apiSettings['webhook_secret'] ?? '' }}" 
                           class="flex-1 px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                           style="min-height: 36px; font-size: 12px; font-family: 'Courier New', monospace;" 
                           placeholder="Click generate to create secret" {{ $disabledAttr }}>
                    @if($canEdit)
                    <button type="button" onclick="generateWebhookSecret()"
                            class="inline-flex items-center px-3 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-all"
                            style="min-height: 36px;" title="Generate new secret">
                        <span class="material-symbols-outlined" style="font-size: 16px;">refresh</span>
                    </button>
                    @endif
                </div>
                <p class="text-xs text-gray-400 mt-1.5" style="font-family: Poppins, sans-serif;">Use this secret to verify webhook signatures</p>
            </div>

            <!-- Webhook Events -->
            <div>
                <label class="block text-gray-700 mb-2" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                    Events to Send
                </label>
                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                    @php
                        $selectedEvents = $apiSettings['events'] ?? [];
                    @endphp
                    <label class="flex items-center gap-3 {{ $canEdit ? 'cursor-pointer' : 'cursor-not-allowed opacity-60' }} group">
                        <input type="checkbox" name="events[]" value="ticket.created" 
                               {{ in_array('ticket.created', $selectedEvents) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ $disabledAttr }}>
                        <span class="text-gray-700 group-hover:text-gray-900" style="font-size: 12px; font-family: Poppins, sans-serif;">
                            ticket.created - When a new ticket is created
                        </span>
                    </label>
                    <label class="flex items-center gap-3 {{ $canEdit ? 'cursor-pointer' : 'cursor-not-allowed opacity-60' }} group">
                        <input type="checkbox" name="events[]" value="ticket.updated" 
                               {{ in_array('ticket.updated', $selectedEvents) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ $disabledAttr }}>
                        <span class="text-gray-700 group-hover:text-gray-900" style="font-size: 12px; font-family: Poppins, sans-serif;">
                            ticket.updated - When a ticket is updated
                        </span>
                    </label>
                    <label class="flex items-center gap-3 {{ $canEdit ? 'cursor-pointer' : 'cursor-not-allowed opacity-60' }} group">
                        <input type="checkbox" name="events[]" value="ticket.resolved" 
                               {{ in_array('ticket.resolved', $selectedEvents) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ $disabledAttr }}>
                        <span class="text-gray-700 group-hover:text-gray-900" style="font-size: 12px; font-family: Poppins, sans-serif;">
                            ticket.resolved - When a ticket is resolved
                        </span>
                    </label>
                    <label class="flex items-center gap-3 {{ $canEdit ? 'cursor-pointer' : 'cursor-not-allowed opacity-60' }} group">
                        <input type="checkbox" name="events[]" value="user.created" 
                               {{ in_array('user.created', $selectedEvents) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ $disabledAttr }}>
                        <span class="text-gray-700 group-hover:text-gray-900" style="font-size: 12px; font-family: Poppins, sans-serif;">
                            user.created - When a new user is created
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Payload Example -->
    <div class="border border-gray-200 rounded-lg overflow-hidden">
        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center gap-2">
            <span class="material-symbols-outlined text-gray-500" style="font-size: 18px;">code</span>
            <h3 class="text-xs font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Payload Example</h3>
        </div>
        <div class="p-4">
            <p class="text-xs text-gray-500 mb-3" style="font-family: Poppins, sans-serif;">Example payload that will be sent to your webhook URL:</p>
            
            <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                <pre class="text-xs" style="color: #4ade80; font-family: 'Courier New', monospace; margin: 0;">{
  "event": "ticket.created",
  "timestamp": "{{ now()->toIso8601String() }}",
  "data": {
    "ticket_id": "TKT-{{ now()->format('Ymd') }}001",
    "subject": "Sample Ticket",
    "priority": "high"
  },
  "signature": "sha256=..."
}</pre>
            </div>
            
            <div class="mt-4 p-3 rounded-lg" style="background-color: #eff6ff; border: 1px solid #bfdbfe;">
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined" style="font-size: 18px; color: #2563eb;">info</span>
                    <div style="font-family: Poppins, sans-serif;">
                        <p class="font-medium text-xs" style="color: #1e40af;">Verify Webhook Signature</p>
                        <p class="mt-1 text-xs" style="color: #3b82f6;">Use the webhook secret to verify the signature in the <code class="px-1 rounded" style="background-color: #dbeafe;">X-Webhook-Signature</code> header</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center border-t border-gray-200 pt-5" style="gap: 10px;">
        @if($canEdit)
        <button type="submit" 
                class="inline-flex items-center gap-2 px-4 text-white rounded-md hover:opacity-90 transition-all shadow-sm"
                style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif; background-color: #9333ea;">
            <span class="material-symbols-outlined" style="font-size: 16px;">save</span>
            Save Settings
        </button>
        <button type="button"
                class="inline-flex items-center gap-2 px-4 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-all"
                style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;">
            <span class="material-symbols-outlined" style="font-size: 16px;">refresh</span>
            Regenerate Keys
        </button>
        @else
        <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-500 rounded-md" style="font-size: 12px; font-family: Poppins, sans-serif;">
            <span class="material-symbols-outlined" style="font-size: 16px;">visibility</span>
            View Only
        </span>
        @endif
    </div>
</div>
</form>

@push('scripts')
<script>
function generateWebhookSecret() {
    const secret = 'whsec_' + Array.from(crypto.getRandomValues(new Uint8Array(24)))
        .map(b => b.toString(16).padStart(2, '0'))
        .join('');
    document.getElementById('webhookSecret').value = secret;
}
</script>
@endpush
