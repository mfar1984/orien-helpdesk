@php
    $spamSettings = $settings['spam_settings'] ?? [];
    $canEdit = $canEdit ?? false;
    $disabledAttr = $canEdit ? '' : 'disabled';
    $disabledClass = $canEdit ? '' : 'bg-gray-100 cursor-not-allowed';
@endphp

<form action="{{ route('settings.integrations.spam.save') }}" method="POST">
    @csrf
    
    <div class="spam-integration-content space-y-6">
        <!-- StopForumSpam (FREE - No API Key) -->
        <div class="spam-service-card border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-red-600" style="font-size: 20px;">mail</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">StopForumSpam</h3>
                        <p class="text-xs text-gray-500">Email & IP spam database (FREE - Unlimited)</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="stopforumspam_enabled" value="1" class="sr-only peer" {{ ($spamSettings['stopforumspam_enabled'] ?? false) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            
            <div class="spam-service-grid grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1" style="font-family: Poppins, sans-serif;">Auto-ban Threshold (%)</label>
                    <input type="number" name="stopforumspam_threshold" value="{{ $spamSettings['stopforumspam_threshold'] ?? 90 }}" min="1" max="100"
                        class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                        style="font-family: Poppins, sans-serif; min-height: 36px; font-size: 12px;">
                    <p class="text-xs text-gray-400 mt-1">Block if confidence score exceeds this value</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1" style="font-family: Poppins, sans-serif;">&nbsp;</label>
                    <button type="button" onclick="testSpamAPI('stopforumspam')" 
                        class="inline-flex items-center gap-2 px-4 bg-gray-100 text-gray-700 text-xs font-medium rounded hover:bg-gray-200 transition"
                        style="font-family: Poppins, sans-serif; min-height: 36px;">
                        <span class="material-symbols-outlined" style="font-size: 14px;">science</span>
                        Test Connection
                    </button>
                </div>
            </div>
            
            <div class="bg-green-50 border border-green-200 rounded p-3">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-600" style="font-size: 16px;">check_circle</span>
                    <span class="text-xs text-green-700" style="font-family: Poppins, sans-serif;">No API key required - 100% FREE</span>
                </div>
            </div>
        </div>

        <!-- AbuseIPDB (FREE - 1000/day) -->
        <div class="spam-service-card border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-orange-600" style="font-size: 20px;">lan</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">AbuseIPDB</h3>
                        <p class="text-xs text-gray-500">IP reputation database (FREE - 1,000 checks/day)</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="abuseipdb_enabled" value="1" class="sr-only peer" {{ ($spamSettings['abuseipdb_enabled'] ?? false) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            
            <div class="spam-service-grid-3 grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1" style="font-family: Poppins, sans-serif;">API Key</label>
                    <input type="password" name="abuseipdb_api_key" value="{{ $spamSettings['abuseipdb_api_key'] ?? '' }}" placeholder="Enter your API key"
                        class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                        style="font-family: Poppins, sans-serif; min-height: 36px; font-size: 12px;">
                    <a href="https://www.abuseipdb.com/account/api" target="_blank" class="text-xs text-blue-600 hover:underline mt-1 inline-block" style="font-family: Poppins, sans-serif;">
                        <span class="material-symbols-outlined" style="font-size: 12px; vertical-align: middle;">open_in_new</span>
                        Get Free API Key
                    </a>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1" style="font-family: Poppins, sans-serif;">Auto-ban Threshold (%)</label>
                    <input type="number" name="abuseipdb_threshold" value="{{ $spamSettings['abuseipdb_threshold'] ?? 80 }}" min="1" max="100"
                        class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                        style="font-family: Poppins, sans-serif; min-height: 36px; font-size: 12px;">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1" style="font-family: Poppins, sans-serif;">&nbsp;</label>
                    <button type="button" onclick="testSpamAPI('abuseipdb')" 
                        class="inline-flex items-center gap-2 px-4 bg-gray-100 text-gray-700 text-xs font-medium rounded hover:bg-gray-200 transition"
                        style="font-family: Poppins, sans-serif; min-height: 36px;">
                        <span class="material-symbols-outlined" style="font-size: 14px;">science</span>
                        Test Connection
                    </button>
                </div>
            </div>
        </div>

        <!-- Google Safe Browsing (FREE - 10000/day) -->
        <div class="spam-service-card border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600" style="font-size: 20px;">security</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Google Safe Browsing</h3>
                        <p class="text-xs text-gray-500">Malicious URL detection (FREE - 10,000 checks/day)</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="safebrowsing_enabled" value="1" class="sr-only peer" {{ ($spamSettings['safebrowsing_enabled'] ?? false) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            
            <div class="spam-service-grid grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1" style="font-family: Poppins, sans-serif;">API Key</label>
                    <input type="password" name="safebrowsing_api_key" value="{{ $spamSettings['safebrowsing_api_key'] ?? '' }}" placeholder="Enter your API key"
                        class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                        style="font-family: Poppins, sans-serif; min-height: 36px; font-size: 12px;">
                    <a href="https://console.cloud.google.com/apis/credentials" target="_blank" class="text-xs text-blue-600 hover:underline mt-1 inline-block" style="font-family: Poppins, sans-serif;">
                        <span class="material-symbols-outlined" style="font-size: 12px; vertical-align: middle;">open_in_new</span>
                        Get Free API Key (Google Cloud Console)
                    </a>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1" style="font-family: Poppins, sans-serif;">&nbsp;</label>
                    <button type="button" onclick="testSpamAPI('safebrowsing')" 
                        class="inline-flex items-center gap-2 px-4 bg-gray-100 text-gray-700 text-xs font-medium rounded hover:bg-gray-200 transition"
                        style="font-family: Poppins, sans-serif; min-height: 36px;">
                        <span class="material-symbols-outlined" style="font-size: 14px;">science</span>
                        Test Connection
                    </button>
                </div>
            </div>
        </div>

        <!-- PurgoMalum (FREE - Unlimited) -->
        <div class="spam-service-card border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-600" style="font-size: 20px;">text_fields</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">PurgoMalum</h3>
                        <p class="text-xs text-gray-500">Profanity filter for bad words (FREE - Unlimited)</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="purgomalum_enabled" value="1" class="sr-only peer" {{ ($spamSettings['purgomalum_enabled'] ?? false) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            
            <div class="spam-service-grid grid grid-cols-2 gap-4">
                <div class="bg-green-50 border border-green-200 rounded p-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-600" style="font-size: 16px;">check_circle</span>
                        <span class="text-xs text-green-700" style="font-family: Poppins, sans-serif;">No API key required - 100% FREE & Unlimited</span>
                    </div>
                </div>
                <div>
                    <button type="button" onclick="testSpamAPI('purgomalum')" 
                        class="inline-flex items-center gap-2 px-4 bg-gray-100 text-gray-700 text-xs font-medium rounded hover:bg-gray-200 transition"
                        style="font-family: Poppins, sans-serif; min-height: 36px;">
                        <span class="material-symbols-outlined" style="font-size: 14px;">science</span>
                        Test Connection
                    </button>
                </div>
            </div>
        </div>

        <!-- Auto-Check Settings -->
        <div class="spam-service-card border border-gray-200 rounded-lg p-4">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-gray-600" style="font-size: 20px;">settings</span>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Auto-Check Settings</h3>
                    <p class="text-xs text-gray-500">Configure when spam checks are performed</p>
                </div>
            </div>
            
            <div class="spam-autocheck-grid grid grid-cols-2 gap-4">
                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition">
                    <input type="checkbox" name="check_on_registration" value="1" class="w-4 h-4 text-blue-600 rounded" {{ ($spamSettings['check_on_registration'] ?? false) ? 'checked' : '' }}>
                    <div>
                        <span class="text-xs font-medium text-gray-900" style="font-family: Poppins, sans-serif;">Check on Registration</span>
                        <p class="text-xs text-gray-500">Verify email when user registers</p>
                    </div>
                </label>
                
                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition">
                    <input type="checkbox" name="check_on_login" value="1" class="w-4 h-4 text-blue-600 rounded" {{ ($spamSettings['check_on_login'] ?? false) ? 'checked' : '' }}>
                    <div>
                        <span class="text-xs font-medium text-gray-900" style="font-family: Poppins, sans-serif;">Check on Login</span>
                        <p class="text-xs text-gray-500">Verify IP when user logs in</p>
                    </div>
                </label>
                
                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition">
                    <input type="checkbox" name="check_on_ticket" value="1" class="w-4 h-4 text-blue-600 rounded" {{ ($spamSettings['check_on_ticket'] ?? false) ? 'checked' : '' }}>
                    <div>
                        <span class="text-xs font-medium text-gray-900" style="font-family: Poppins, sans-serif;">Check on Ticket/Reply</span>
                        <p class="text-xs text-gray-500">Scan content for bad words & URLs</p>
                    </div>
                </label>
                
                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition">
                    <input type="checkbox" name="auto_add_to_database" value="1" class="w-4 h-4 text-blue-600 rounded" {{ ($spamSettings['auto_add_to_database'] ?? false) ? 'checked' : '' }}>
                    <div>
                        <span class="text-xs font-medium text-gray-900" style="font-family: Poppins, sans-serif;">Auto-add to Tools Database</span>
                        <p class="text-xs text-gray-500">Automatically save detected spam to Tools</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Integration Info -->
        <div class="spam-info-box bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-blue-600" style="font-size: 20px;">info</span>
                <div>
                    <h4 class="text-xs font-semibold text-blue-900 mb-1" style="font-family: Poppins, sans-serif;">Integration with Tools</h4>
                    <p class="text-xs text-blue-700" style="font-family: Poppins, sans-serif;">
                        When spam is detected, it will be automatically added to the corresponding Tools database:
                    </p>
                    <ul class="text-xs text-blue-700 mt-2 space-y-1" style="font-family: Poppins, sans-serif;">
                        <li>• <strong>StopForumSpam</strong> → Ban Email & Ban IPs</li>
                        <li>• <strong>AbuseIPDB</strong> → Ban IPs</li>
                        <li>• <strong>Google Safe Browsing</strong> → Bad Website</li>
                        <li>• <strong>PurgoMalum</strong> → Bad Word</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="spam-save-button flex justify-end">
            @if($canEdit)
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition" style="font-family: Poppins, sans-serif;">
                <span class="material-symbols-outlined" style="font-size: 16px;">save</span>
                SAVE SETTINGS
            </button>
            @else
            <span class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 text-gray-500 text-xs font-medium rounded" style="font-family: Poppins, sans-serif;">
                <span class="material-symbols-outlined" style="font-size: 16px;">visibility</span>
                View Only
            </span>
            @endif
        </div>
    </div>
</form>

<!-- Test Modal -->
<div id="spamTestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center" style="z-index: 9999;">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 id="modalTitle" class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Test API Connection</h3>
            <button type="button" onclick="closeTestModal()" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6">
            <div id="testInputSection">
                <label id="testInputLabel" class="block text-xs font-medium text-gray-700 mb-2" style="font-family: Poppins, sans-serif;">Test Value</label>
                <input type="text" id="testInput" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-500" style="font-family: Poppins, sans-serif;">
                <p id="testInputHint" class="text-xs text-gray-400 mt-1" style="font-family: Poppins, sans-serif;"></p>
            </div>
            <div id="testResultSection" class="hidden mt-4">
                <div id="testResult" class="p-4 rounded-lg"></div>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
            <button type="button" onclick="closeTestModal()" class="px-4 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200 transition" style="font-family: Poppins, sans-serif;">
                Close
            </button>
            <button type="button" id="runTestBtn" onclick="runTest()" class="px-4 py-2 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition" style="font-family: Poppins, sans-serif;">
                <span class="material-symbols-outlined" style="font-size: 14px; vertical-align: middle;">play_arrow</span>
                Run Test
            </button>
        </div>
    </div>
</div>

<script>
let currentService = '';

function testSpamAPI(service) {
    currentService = service;
    const modal = document.getElementById('spamTestModal');
    const title = document.getElementById('modalTitle');
    const label = document.getElementById('testInputLabel');
    const hint = document.getElementById('testInputHint');
    const input = document.getElementById('testInput');
    
    // Reset
    document.getElementById('testResultSection').classList.add('hidden');
    document.getElementById('testInputSection').classList.remove('hidden');
    input.value = '';
    
    switch(service) {
        case 'stopforumspam':
            title.textContent = 'Test StopForumSpam API';
            label.textContent = 'Email Address to Check';
            hint.textContent = 'Enter an email to check if it\'s in spam database';
            input.placeholder = 'test@example.com';
            input.value = 'test@test.com';
            break;
        case 'abuseipdb':
            title.textContent = 'Test AbuseIPDB API';
            label.textContent = 'IP Address to Check';
            hint.textContent = 'Enter an IP to check its abuse score';
            input.placeholder = '8.8.8.8';
            input.value = '8.8.8.8';
            break;
        case 'safebrowsing':
            title.textContent = 'Test Google Safe Browsing API';
            label.textContent = 'URL to Check';
            hint.textContent = 'Enter a URL to check if it\'s malicious';
            input.placeholder = 'https://example.com';
            input.value = 'https://google.com';
            break;
        case 'purgomalum':
            title.textContent = 'Test PurgoMalum API';
            label.textContent = 'Text to Filter';
            hint.textContent = 'Enter text to check for profanity';
            input.placeholder = 'Enter some text...';
            input.value = 'This is a test message';
            break;
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeTestModal() {
    const modal = document.getElementById('spamTestModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

async function runTest() {
    const input = document.getElementById('testInput');
    const resultSection = document.getElementById('testResultSection');
    const resultDiv = document.getElementById('testResult');
    const btn = document.getElementById('runTestBtn');
    
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined animate-spin" style="font-size: 14px; vertical-align: middle;">progress_activity</span> Testing...';
    
    try {
        const response = await fetch('{{ route("settings.integrations.spam.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                service: currentService,
                test_value: input.value
            })
        });
        
        const data = await response.json();
        
        resultSection.classList.remove('hidden');
        
        if (data.success) {
            let html = '<div class="bg-green-50 border border-green-200 rounded p-3 mb-3">';
            html += '<div class="flex items-center gap-2"><span class="material-symbols-outlined text-green-600" style="font-size: 16px;">check_circle</span>';
            html += '<span class="text-xs text-green-700 font-medium">' + data.message + '</span></div></div>';
            
            if (data.data) {
                html += '<div class="bg-gray-50 rounded p-3 space-y-2">';
                for (const [key, value] of Object.entries(data.data)) {
                    const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    html += '<div class="flex justify-between text-xs" style="font-family: Poppins, sans-serif;">';
                    html += '<span class="text-gray-600">' + label + ':</span>';
                    html += '<span class="font-medium text-gray-900">' + value + '</span>';
                    html += '</div>';
                }
                html += '</div>';
            }
            
            resultDiv.innerHTML = html;
        } else {
            resultDiv.innerHTML = '<div class="bg-red-50 border border-red-200 rounded p-3">' +
                '<div class="flex items-center gap-2"><span class="material-symbols-outlined text-red-600" style="font-size: 16px;">error</span>' +
                '<span class="text-xs text-red-700 font-medium">' + data.message + '</span></div></div>';
        }
    } catch (error) {
        resultSection.classList.remove('hidden');
        resultDiv.innerHTML = '<div class="bg-red-50 border border-red-200 rounded p-3">' +
            '<div class="flex items-center gap-2"><span class="material-symbols-outlined text-red-600" style="font-size: 16px;">error</span>' +
            '<span class="text-xs text-red-700 font-medium">Connection failed: ' + error.message + '</span></div></div>';
    }
    
    btn.disabled = false;
    btn.innerHTML = '<span class="material-symbols-outlined" style="font-size: 14px; vertical-align: middle;">play_arrow</span> Run Test';
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeTestModal();
});
</script>
