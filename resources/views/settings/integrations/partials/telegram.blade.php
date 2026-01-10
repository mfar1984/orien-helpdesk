<div class="space-y-6">
    @php
        $telegramSettings = $settings['telegram_settings'] ?? [];
        $isConfigured = !empty($telegramSettings['bot_token']) && !empty($telegramSettings['channel_id']);
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
                    <span class="material-symbols-outlined" style="font-size: 20px; color: #2563eb;">send</span>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Telegram Service</h3>
                    <p class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">Bot & Channel Configuration</p>
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
    <form method="POST" action="{{ route('settings.integrations.telegram') }}" id="telegramForm">
        @csrf
        
        <!-- Enable Toggle -->
        <div class="mb-5">
            <label class="flex items-center gap-2 {{ $canEdit ? 'cursor-pointer' : 'cursor-not-allowed opacity-60' }}">
                <input type="checkbox" name="enabled" value="1" 
                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                       {{ ($telegramSettings['enabled'] ?? false) ? 'checked' : '' }} {{ $disabledAttr }}>
                <span class="text-gray-700" style="font-size: 12px; font-family: Poppins, sans-serif;">Enable Telegram Notifications</span>
            </label>
        </div>

        <!-- Bot Configuration -->
        <div class="mb-4 p-4 rounded-lg" style="background-color: #eff6ff; border: 1px solid #bfdbfe;">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined" style="font-size: 18px; color: #2563eb;">info</span>
                <div style="font-family: Poppins, sans-serif;">
                    <p class="font-medium text-xs" style="color: #1e40af;">Telegram Bot Setup</p>
                    <p class="mt-1 text-xs" style="color: #3b82f6;">Create a bot via <a href="https://t.me/BotFather" target="_blank" class="underline hover:no-underline">@BotFather</a> and add it to your channel as admin</p>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                Bot API Token <span class="text-red-500">*</span>
            </label>
            <input type="password" name="bot_token" value="{{ $telegramSettings['bot_token'] ?? '' }}" 
                   class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                   style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" 
                   placeholder="{{ !empty($telegramSettings['bot_token']) ? '••••••••••••••••••••' : 'Enter Bot API Token from @BotFather' }}" {{ $disabledAttr }}>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                    Bot Username
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="font-size: 12px;">@</span>
                    <input type="text" name="bot_username" value="{{ $telegramSettings['bot_username'] ?? '' }}" 
                           class="w-full pl-7 pr-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                           style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" 
                           placeholder="your_bot" {{ $disabledAttr }}>
                </div>
            </div>
            <div>
                <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                    Channel ID <span class="text-red-500">*</span>
                </label>
                <input type="text" name="channel_id" value="{{ $telegramSettings['channel_id'] ?? '' }}" 
                       class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                       style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" 
                       placeholder="-1001234567890" {{ $disabledAttr }}>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                    Owner Username
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="font-size: 12px;">@</span>
                    <input type="text" name="owner_username" value="{{ $telegramSettings['owner_username'] ?? '' }}" 
                           class="w-full pl-7 pr-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                           style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" 
                           placeholder="username" {{ $disabledAttr }}>
                </div>
            </div>
            <div>
                <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                    Owner User ID
                </label>
                <input type="text" name="owner_user_id" value="{{ $telegramSettings['owner_user_id'] ?? '' }}" 
                       class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                       style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" 
                       placeholder="123456789" {{ $disabledAttr }}>
            </div>
        </div>

        <!-- Info about auto-sync -->
        <div class="mb-4 p-4 rounded-lg" style="background-color: #f0fdf4; border: 1px solid #bbf7d0;">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined" style="font-size: 18px; color: #16a34a;">sync</span>
                <div style="font-family: Poppins, sans-serif;">
                    <p class="font-medium text-xs" style="color: #166534;">Auto-Sync with Activity Logs</p>
                    <p class="mt-1 text-xs" style="color: #22c55e;">All activities from Activity Logs will be automatically sent to your Telegram channel when enabled</p>
                </div>
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
            <button type="button" onclick="showTelegramTestModal()"
                    class="inline-flex items-center gap-2 px-4 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-all"
                    style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;">
                <span class="material-symbols-outlined" style="font-size: 16px;">wifi_tethering</span>
                Test Connection
            </button>
        </div>
    </form>
</div>

<!-- Test Telegram Modal -->
<div id="telegramTestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center" style="display: none; z-index: 9999;">
    <div class="bg-white rounded-lg shadow-xl" style="width: 90%; max-width: 500px;">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: #dbeafe;">
                    <span class="material-symbols-outlined" style="font-size: 20px; color: #2563eb;">send</span>
                </div>
                <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Test Telegram Connection</h3>
            </div>
            <button type="button" onclick="closeTelegramTestModal()" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined" style="font-size: 20px;">close</span>
            </button>
        </div>
        
        <div class="p-6">
            <div id="telegramTestResult" class="hidden mb-4"></div>
            
            <p class="text-xs text-gray-600 mb-4" style="font-family: Poppins, sans-serif;">
                Send a test message to your Telegram channel to verify the bot configuration.
            </p>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                    Test Message <small class="text-gray-500">(optional)</small>
                </label>
                <textarea id="telegramTestMessage" rows="3"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                       style="font-size: 12px; font-family: Poppins, sans-serif;" 
                       placeholder="Enter a custom message or leave blank for default"></textarea>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-2">
            <button type="button" onclick="closeTelegramTestModal()"
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition-all"
                    style="font-size: 12px; font-family: Poppins, sans-serif;">
                Cancel
            </button>
            <button type="button" onclick="sendTelegramTest()" id="telegramTestBtn"
                    class="px-4 py-2 text-white rounded-md hover:opacity-90 transition-all"
                    style="font-size: 12px; font-family: Poppins, sans-serif; background-color: #2563eb;">
                <span class="flex items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 16px;">send</span>
                    Send Test Message
                </span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showTelegramTestModal() {
    document.getElementById('telegramTestModal').style.display = 'flex';
    document.getElementById('telegramTestMessage').value = '';
    document.getElementById('telegramTestResult').classList.add('hidden');
}

function closeTelegramTestModal() {
    document.getElementById('telegramTestModal').style.display = 'none';
}

async function sendTelegramTest() {
    const message = document.getElementById('telegramTestMessage').value;
    const resultDiv = document.getElementById('telegramTestResult');
    const btn = document.getElementById('telegramTestBtn');
    
    // Show loading
    btn.disabled = true;
    btn.innerHTML = '<span class="flex items-center gap-2"><span class="material-symbols-outlined animate-spin" style="font-size: 16px;">sync</span>Sending...</span>';
    
    resultDiv.className = 'bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-4';
    resultDiv.innerHTML = '<div class="flex items-center gap-2" style="font-family: Poppins, sans-serif; font-size: 12px;"><span class="material-symbols-outlined animate-spin" style="font-size: 18px;">sync</span><span>Sending test message...</span></div>';
    resultDiv.classList.remove('hidden');
    
    try {
        const response = await fetch('{{ route("settings.integrations.telegram.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: message })
        });
        
        const data = await response.json();
        
        if (data.success) {
            resultDiv.className = 'bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4';
            let html = '<div class="flex items-start gap-2" style="font-family: Poppins, sans-serif; font-size: 12px;"><span class="material-symbols-outlined" style="font-size: 18px;">check_circle</span><div><span class="font-medium">' + data.message + '</span>';
            if (data.data) {
                html += '<div class="mt-1 text-xs text-green-600">Message ID: ' + data.data.message_id + ' • Channel: ' + data.data.chat_title + '</div>';
            }
            html += '</div></div>';
            resultDiv.innerHTML = html;
        } else {
            resultDiv.className = 'bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4';
            resultDiv.innerHTML = '<div class="flex items-start gap-2" style="font-family: Poppins, sans-serif; font-size: 12px;"><span class="material-symbols-outlined" style="font-size: 18px;">error</span><div>' + data.message + '</div></div>';
        }
    } catch (error) {
        console.error('Test telegram error:', error);
        resultDiv.className = 'bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4';
        resultDiv.innerHTML = '<div class="flex items-start gap-2" style="font-family: Poppins, sans-serif; font-size: 12px;"><span class="material-symbols-outlined" style="font-size: 18px;">error</span><div><div class="font-semibold">Connection failed</div><div class="text-xs mt-1">Please check your Telegram settings and try again.</div></div></div>';
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<span class="flex items-center gap-2"><span class="material-symbols-outlined" style="font-size: 16px;">send</span>Send Test Message</span>';
    }
}

// Close modal on backdrop click
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('telegramTestModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeTelegramTestModal();
            }
        });
    }
});
</script>
@endpush
