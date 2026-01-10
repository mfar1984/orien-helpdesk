<div class="space-y-6">
    @php
        $emailSettings = $settings['email_settings'] ?? [];
        $isConfigured = !empty($emailSettings);
        $provider = $emailSettings['provider'] ?? 'smtp';
        $canEdit = $canEdit ?? false;
        $disabledAttr = $canEdit ? '' : 'disabled';
        $disabledClass = $canEdit ? '' : 'bg-gray-100 cursor-not-allowed';
    @endphp

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
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: #dbeafe;">
                    <span class="material-symbols-outlined" style="font-size: 20px; color: #2563eb;">mail</span>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Email Service</h3>
                    <p class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">SMTP or Gmail Configuration</p>
                </div>
            </div>
            @if($isConfigured)
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700" style="font-family: Poppins, sans-serif;">
                    ✓ Configured
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600" style="font-family: Poppins, sans-serif;">
                    Not Configured
                </span>
            @endif
        </div>
    </div>

    <!-- Configuration Form -->
    <form method="POST" action="{{ route('settings.integrations.email.save') }}" id="emailForm">
        @csrf
        
        <!-- Provider Selection -->
        <div class="mb-5">
            <label class="block text-gray-700 mb-2" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">Provider</label>
            <div class="flex gap-6">
                <label class="flex items-center gap-2 {{ $canEdit ? 'cursor-pointer' : 'cursor-not-allowed opacity-60' }} group">
                    <input type="radio" name="provider" value="smtp" {{ $provider === 'smtp' ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" 
                           onchange="toggleEmailProvider()" {{ $disabledAttr }}>
                    <span class="text-gray-700 group-hover:text-gray-900" style="font-size: 12px; font-family: Poppins, sans-serif;">SMTP</span>
                </label>
                <label class="flex items-center gap-2 {{ $canEdit ? 'cursor-pointer' : 'cursor-not-allowed opacity-60' }} group">
                    <input type="radio" name="provider" value="gmail" {{ $provider === 'gmail' ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" 
                           onchange="toggleEmailProvider()" {{ $disabledAttr }}>
                    <span class="text-gray-700 group-hover:text-gray-900" style="font-size: 12px; font-family: Poppins, sans-serif;">Gmail</span>
                </label>
            </div>
        </div>

        <!-- SMTP Fields -->
        <div id="smtpFields" class="{{ $provider !== 'smtp' ? 'hidden' : '' }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                        SMTP Host <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="host" value="{{ $emailSettings['host'] ?? '' }}" 
                           class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                           style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" 
                           placeholder="smtp.gmail.com" {{ $disabledAttr }}>
                </div>
                <div>
                    <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                        Port <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="port" value="{{ $emailSettings['port'] ?? '587' }}" 
                           class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                           style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" 
                           placeholder="587" {{ $disabledAttr }}>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="username" value="{{ $emailSettings['username'] ?? '' }}" 
                           class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                           style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" 
                           placeholder="your@email.com" {{ $disabledAttr }}>
                </div>
                <div>
                    <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                        Password <small class="text-gray-500">(leave blank to keep existing)</small>
                    </label>
                    <input type="password" name="password" 
                           class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                           style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" 
                           placeholder="Enter password" {{ $disabledAttr }}>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                    Encryption <span class="text-red-500">*</span>
                </label>
                <select name="encryption" 
                        class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                        style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" {{ $disabledAttr }}>
                    <option value="tls" {{ ($emailSettings['encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                    <option value="ssl" {{ ($emailSettings['encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                    <option value="none" {{ ($emailSettings['encryption'] ?? '') === 'none' ? 'selected' : '' }}>None</option>
                </select>
            </div>
        </div>

        <!-- Gmail Fields -->
        <div id="gmailFields" class="{{ $provider !== 'gmail' ? 'hidden' : '' }}">
            <div class="mb-4 p-4 rounded-lg" style="background-color: #fefce8; border: 1px solid #fef08a;">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined" style="font-size: 18px; color: #ca8a04;">info</span>
                    <div style="font-family: Poppins, sans-serif;">
                        <p class="font-medium text-xs" style="color: #854d0e;">Gmail OAuth Setup</p>
                        <p class="mt-1 text-xs" style="color: #a16207;">Get your credentials from <a href="https://console.cloud.google.com" target="_blank" class="underline hover:no-underline">Google Cloud Console</a></p>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                    Client ID <span class="text-red-500">*</span>
                </label>
                <input type="text" name="gmail_client_id" value="{{ $emailSettings['gmail_client_id'] ?? '' }}" 
                       class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                       style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" 
                       placeholder="your-client-id.apps.googleusercontent.com" {{ $disabledAttr }}>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                    Client Secret <small class="text-gray-500">(leave blank to keep existing)</small>
                </label>
                <input type="password" name="gmail_client_secret" 
                       class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                       style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" 
                       placeholder="Enter client secret" {{ $disabledAttr }}>
            </div>
        </div>

        <!-- Common Fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                    From Address <span class="text-red-500">*</span>
                </label>
                <input type="email" name="from_address" value="{{ $emailSettings['from_address'] ?? '' }}" 
                       class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                       style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" 
                       placeholder="noreply@company.com" {{ $disabledAttr }}>
            </div>
            <div>
                <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                    From Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="from_name" value="{{ $emailSettings['from_name'] ?? '' }}" 
                       class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                       style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" 
                       placeholder="ORIEN Helpdesk" {{ $disabledAttr }}>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center border-t border-gray-200 pt-5 mt-6" style="gap: 10px;">
            @if($canEdit)
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-4 text-white rounded-md hover:opacity-90 transition-all shadow-sm"
                    style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif; background-color: #2563eb;">
                <span class="material-symbols-outlined" style="font-size: 16px;">save</span>
                Save Settings
            </button>
            @else
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-500 rounded-md" style="font-size: 12px; font-family: Poppins, sans-serif;">
                <span class="material-symbols-outlined" style="font-size: 16px;">visibility</span>
                View Only
            </span>
            @endif
            <button type="button" onclick="showEmailTestModal()"
                    class="inline-flex items-center gap-2 px-4 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-all"
                    style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;">
                <span class="material-symbols-outlined" style="font-size: 16px;">wifi_tethering</span>
                Test Connection
            </button>
        </div>
    </form>
</div>

<!-- Test Email Modal -->
<div id="emailTestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center" style="display: none; z-index: 9999;">
    <div class="bg-white rounded-lg shadow-xl" style="width: 90%; max-width: 500px;">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: #dbeafe;">
                    <span class="material-symbols-outlined" style="font-size: 20px; color: #2563eb;">mail</span>
                </div>
                <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Test Email Connection</h3>
            </div>
            <button type="button" onclick="closeEmailTestModal()" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined" style="font-size: 20px;">close</span>
            </button>
        </div>
        
        <div class="p-6">
            <div id="emailTestResult" class="hidden mb-4"></div>
            
            <p class="text-xs text-gray-600 mb-4" style="font-family: Poppins, sans-serif;">
                Enter an email address to send a test email and verify your email configuration.
            </p>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                    Test Email Address <span class="text-red-500">*</span>
                </label>
                <input type="email" id="testEmailInput" 
                       class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                       style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" 
                       placeholder="your@email.com">
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-2">
            <button type="button" onclick="closeEmailTestModal()"
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition-all"
                    style="font-size: 12px; font-family: Poppins, sans-serif;">
                Cancel
            </button>
            <button type="button" onclick="sendTestEmail()"
                    class="px-4 py-2 text-white rounded-md hover:opacity-90 transition-all"
                    style="font-size: 12px; font-family: Poppins, sans-serif; background-color: #2563eb;">
                <span class="flex items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 16px;">send</span>
                    Send Test Email
                </span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleEmailProvider() {
    const provider = document.querySelector('input[name="provider"]:checked').value;
    document.getElementById('smtpFields').classList.toggle('hidden', provider !== 'smtp');
    document.getElementById('gmailFields').classList.toggle('hidden', provider !== 'gmail');
}

function showEmailTestModal() {
    document.getElementById('emailTestModal').style.display = 'flex';
    document.getElementById('testEmailInput').value = '';
    document.getElementById('emailTestResult').classList.add('hidden');
}

function closeEmailTestModal() {
    document.getElementById('emailTestModal').style.display = 'none';
}

async function sendTestEmail() {
    const email = document.getElementById('testEmailInput').value;
    const resultDiv = document.getElementById('emailTestResult');
    
    if (!email) {
        resultDiv.className = 'bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4';
        resultDiv.innerHTML = '<div class="flex items-center gap-2" style="font-family: Poppins, sans-serif; font-size: 12px;"><span class="material-symbols-outlined" style="font-size: 18px;">error</span><span>Please enter an email address</span></div>';
        resultDiv.classList.remove('hidden');
        return;
    }
    
    // Show loading
    resultDiv.className = 'bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-4';
    resultDiv.innerHTML = '<div class="flex items-center gap-2" style="font-family: Poppins, sans-serif; font-size: 12px;"><span class="material-symbols-outlined animate-spin" style="font-size: 18px;">sync</span><span>Sending test email...</span></div>';
    resultDiv.classList.remove('hidden');
    
    try {
        const response = await fetch('{{ route("settings.integrations.email.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email: email })
        });
        
        const data = await response.json();
        
        if (data.success) {
            resultDiv.className = 'bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4';
            resultDiv.innerHTML = '<div class="flex items-center gap-2" style="font-family: Poppins, sans-serif; font-size: 12px;"><span class="material-symbols-outlined" style="font-size: 18px;">check_circle</span><span>' + data.message + '</span></div>';
        } else {
            resultDiv.className = 'bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4';
            resultDiv.innerHTML = '<div class="flex items-start gap-2" style="font-family: Poppins, sans-serif; font-size: 12px;"><span class="material-symbols-outlined" style="font-size: 18px;">error</span><div>' + data.message + '</div></div>';
        }
    } catch (error) {
        console.error('Test email error:', error);
        resultDiv.className = 'bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4';
        resultDiv.innerHTML = '<div class="flex items-start gap-2" style="font-family: Poppins, sans-serif; font-size: 12px;"><span class="material-symbols-outlined" style="font-size: 18px;">error</span><div><div class="font-semibold">Connection failed</div><div class="text-xs mt-1">Please check your email settings and try again.</div></div></div>';
    }
}

// Close modal on backdrop click
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('emailTestModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeEmailTestModal();
            }
        });
    }
});
</script>
@endpush
