<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\BadWebsite;
use App\Models\BadWord;
use App\Models\BannedEmail;
use App\Models\BannedIp;
use App\Models\KbArticle;
use App\Models\KbCategory;
use App\Models\Role;
use App\Models\Setting;
use App\Models\SlaRule;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Models\User;
use App\Models\WhitelistEmail;
use App\Models\WhitelistIp;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    protected $tabs = [
        'email' => 'Email Gateway',
        'telegram' => 'Telegram Gateway',
        'weather' => 'Weather',
        'api' => 'API',
        'spam' => 'Spam Protection',
        'recycle-bin' => 'Recycle Bin',
    ];

    /**
     * Map tab to permission key.
     */
    private function getTabPermission($tab)
    {
        return match ($tab) {
            'email' => 'settings_integrations_email',
            'telegram' => 'settings_integrations_telegram',
            'weather' => 'settings_integrations_weather',
            'api' => 'settings_integrations_api',
            'spam' => 'settings_integrations_spam',
            'recycle-bin' => 'settings_integrations_recycle',
            default => 'settings_integrations_email',
        };
    }

    /**
     * Display integrations settings with tabs.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->get('tab', 'email');

        if (!array_key_exists($tab, $this->tabs)) {
            $tab = 'email';
        }

        // Check permission for the current tab
        $permissionKey = $this->getTabPermission($tab);
        if (!$user->hasPermission($permissionKey . '.view')) {
            // Try to find another tab user has access to
            foreach (array_keys($this->tabs) as $tabKey) {
                $perm = $this->getTabPermission($tabKey);
                if ($user->hasPermission($perm . '.view')) {
                    return redirect()->route('settings.integrations', ['tab' => $tabKey]);
                }
            }
            // No access to any integration tab
            abort(403, 'You do not have permission to access Integrations.');
        }

        // Filter tabs based on permissions
        $filteredTabs = [];
        foreach ($this->tabs as $tabKey => $tabLabel) {
            $perm = $this->getTabPermission($tabKey);
            if ($user->hasPermission($perm . '.view')) {
                $filteredTabs[$tabKey] = $tabLabel;
            }
        }

        // Check if user can edit current tab
        $canEdit = $user->hasPermission($permissionKey . '.edit');
        $canDelete = $user->hasPermission($permissionKey . '.delete');
        $canManage = $user->hasPermission($permissionKey . '.manage');

        $data = [
            'currentTab' => $tab,
            'tabs' => $filteredTabs,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canManage' => $canManage,
        ];

        if ($tab === 'recycle-bin') {
            $data['recycleBinData'] = $this->getRecycleBinData();
        } else {
            $data['settings'] = Setting::getByGroup('integration_' . $tab);
        }
        
        return view('settings.integrations', $data);
    }

    /**
     * Get all soft-deleted data for recycle bin.
     */
    private function getRecycleBinData(): array
    {
        return [
            'tickets' => Ticket::onlyTrashed()->with('creator')->orderBy('deleted_at', 'desc')->get(),
            'users' => User::onlyTrashed()->with('role')->orderBy('deleted_at', 'desc')->get(),
            'roles' => Role::onlyTrashed()->orderBy('deleted_at', 'desc')->get(),
            'kb_articles' => KbArticle::onlyTrashed()->with('category')->orderBy('deleted_at', 'desc')->get(),
            'kb_categories' => KbCategory::onlyTrashed()->orderBy('deleted_at', 'desc')->get(),
            'ticket_categories' => TicketCategory::onlyTrashed()->orderBy('deleted_at', 'desc')->get(),
            'priorities' => TicketPriority::onlyTrashed()->orderBy('deleted_at', 'desc')->get(),
            'statuses' => TicketStatus::onlyTrashed()->orderBy('deleted_at', 'desc')->get(),
            'sla_rules' => SlaRule::onlyTrashed()->orderBy('deleted_at', 'desc')->get(),
            'banned_emails' => BannedEmail::onlyTrashed()->with('addedBy')->orderBy('deleted_at', 'desc')->get(),
            'banned_ips' => BannedIp::onlyTrashed()->with('addedBy')->orderBy('deleted_at', 'desc')->get(),
            'whitelist_emails' => WhitelistEmail::onlyTrashed()->with('addedBy')->orderBy('deleted_at', 'desc')->get(),
            'whitelist_ips' => WhitelistIp::onlyTrashed()->with('addedBy')->orderBy('deleted_at', 'desc')->get(),
            'bad_words' => BadWord::onlyTrashed()->orderBy('deleted_at', 'desc')->get(),
            'bad_websites' => BadWebsite::onlyTrashed()->orderBy('deleted_at', 'desc')->get(),
        ];
    }

    /**
     * Save email settings.
     */
    public function saveEmail(Request $request)
    {
        if (!auth()->user()->hasPermission('settings_integrations_email.edit')) {
            abort(403, 'You do not have permission to edit Email settings.');
        }
        
        $validated = $request->validate([
            'provider' => 'required|in:smtp,gmail',
            'host' => 'required_if:provider,smtp|nullable|string',
            'port' => 'required_if:provider,smtp|nullable|integer',
            'username' => 'required_if:provider,smtp|nullable|string',
            'password' => 'nullable|string',
            'encryption' => 'required_if:provider,smtp|nullable|in:tls,ssl,none',
            'gmail_client_id' => 'required_if:provider,gmail|nullable|string',
            'gmail_client_secret' => 'nullable|string',
            'from_address' => 'required|email',
            'from_name' => 'required|string',
        ]);

        // Get existing settings
        $existingSettings = Setting::getByGroup('integration_email');
        $currentSettings = $existingSettings['email_settings'] ?? [];

        // Don't overwrite password if blank (keep existing)
        if (empty($validated['password']) && isset($currentSettings['password'])) {
            $validated['password'] = $currentSettings['password'];
        }

        // Don't overwrite gmail_client_secret if blank (keep existing)
        if (empty($validated['gmail_client_secret']) && isset($currentSettings['gmail_client_secret'])) {
            $validated['gmail_client_secret'] = $currentSettings['gmail_client_secret'];
        }

        Setting::setValue('email_settings', $validated, 'integration_email');
        
        ActivityLogService::log('update', 'settings', 'Email gateway settings updated');

        return redirect()->route('settings.integrations', ['tab' => 'email'])
            ->with('success', 'Email settings saved successfully.');
    }

    /**
     * Save weather settings.
     */
    public function saveWeather(Request $request)
    {
        if (!auth()->user()->hasPermission('settings_integrations_weather.edit')) {
            abort(403, 'You do not have permission to edit Weather settings.');
        }
        
        $validated = $request->validate([
            'api_key' => 'nullable|string',
            'default_city' => 'nullable|string',
            'units' => 'required|in:metric,imperial',
        ]);

        // Get existing settings
        $existingSettings = Setting::getByGroup('integration_weather');
        $currentSettings = $existingSettings['weather_settings'] ?? [];

        // Don't overwrite API key if blank (keep existing)
        if (empty($validated['api_key']) && isset($currentSettings['api_key'])) {
            $validated['api_key'] = $currentSettings['api_key'];
        } elseif (empty($validated['api_key'])) {
            return back()->withErrors(['api_key' => 'API Key is required.']);
        }

        Setting::setValue('weather_settings', $validated, 'integration_weather');
        
        ActivityLogService::log('update', 'settings', 'Weather settings updated');

        return redirect()->route('settings.integrations', ['tab' => 'weather'])
            ->with('success', 'Weather settings saved successfully.');
    }

    /**
     * Save API settings.
     */
    public function saveApi(Request $request)
    {
        if (!auth()->user()->hasPermission('settings_integrations_api.edit')) {
            abort(403, 'You do not have permission to edit API settings.');
        }
        
        $validated = $request->validate([
            'rate_limit' => 'nullable|integer|min:1',
            'token_expiry' => 'nullable|integer|min:1',
            'api_enabled' => 'nullable|boolean',
            'webhook_url' => 'nullable|url',
            'webhook_secret' => 'nullable|string',
            'events' => 'nullable|array',
        ]);

        $validated['api_enabled'] = $request->boolean('api_enabled');

        Setting::setValue('api_settings', $validated, 'integration_api');
        
        ActivityLogService::log('update', 'settings', 'API settings updated');

        return redirect()->route('settings.integrations', ['tab' => 'api'])
            ->with('success', 'API settings saved successfully.');
    }

    /**
     * Test email connection.
     */
    public function testEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            // Get email settings
            $settings = Setting::getByGroup('integration_email');
            $emailSettings = $settings['email_settings'] ?? [];

            if (empty($emailSettings)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email settings not configured. Please save your email settings first.'
                ]);
            }

            // Validate required fields
            if (empty($emailSettings['host']) && empty($emailSettings['gmail_client_id'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email host or Gmail credentials are required.'
                ]);
            }

            // Configure mail settings
            \App\Services\EmailService::configure();

            // Send test email
            $testEmail = new \App\Mail\TestEmail();
            \Mail::to($request->email)->send($testEmail);

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully! Check your inbox at ' . $request->email
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email address provided.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Test email failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Save spam protection settings.
     */
    public function saveSpam(Request $request)
    {
        if (!auth()->user()->hasPermission('settings_integrations_spam.edit')) {
            abort(403, 'You do not have permission to edit Spam settings.');
        }
        
        $validated = $request->validate([
            'stopforumspam_enabled' => 'nullable|boolean',
            'stopforumspam_threshold' => 'nullable|integer|min:1|max:100',
            'abuseipdb_enabled' => 'nullable|boolean',
            'abuseipdb_api_key' => 'nullable|string',
            'abuseipdb_threshold' => 'nullable|integer|min:1|max:100',
            'safebrowsing_enabled' => 'nullable|boolean',
            'safebrowsing_api_key' => 'nullable|string',
            'purgomalum_enabled' => 'nullable|boolean',
            'check_on_registration' => 'nullable|boolean',
            'check_on_login' => 'nullable|boolean',
            'check_on_ticket' => 'nullable|boolean',
            'auto_add_to_database' => 'nullable|boolean',
        ]);

        // Get existing settings to preserve API keys if not provided
        $existingSettings = Setting::getByGroup('integration_spam');
        $currentSettings = $existingSettings['spam_settings'] ?? [];

        // Preserve API keys if blank
        if (empty($validated['abuseipdb_api_key']) && isset($currentSettings['abuseipdb_api_key'])) {
            $validated['abuseipdb_api_key'] = $currentSettings['abuseipdb_api_key'];
        }
        if (empty($validated['safebrowsing_api_key']) && isset($currentSettings['safebrowsing_api_key'])) {
            $validated['safebrowsing_api_key'] = $currentSettings['safebrowsing_api_key'];
        }

        // Convert checkboxes to boolean
        $validated['stopforumspam_enabled'] = $request->boolean('stopforumspam_enabled');
        $validated['abuseipdb_enabled'] = $request->boolean('abuseipdb_enabled');
        $validated['safebrowsing_enabled'] = $request->boolean('safebrowsing_enabled');
        $validated['purgomalum_enabled'] = $request->boolean('purgomalum_enabled');
        $validated['check_on_registration'] = $request->boolean('check_on_registration');
        $validated['check_on_login'] = $request->boolean('check_on_login');
        $validated['check_on_ticket'] = $request->boolean('check_on_ticket');
        $validated['auto_add_to_database'] = $request->boolean('auto_add_to_database');

        // Set defaults for thresholds
        $validated['stopforumspam_threshold'] = $validated['stopforumspam_threshold'] ?? 90;
        $validated['abuseipdb_threshold'] = $validated['abuseipdb_threshold'] ?? 80;

        Setting::setValue('spam_settings', $validated, 'integration_spam');
        
        ActivityLogService::log('update', 'settings', 'Spam protection settings updated');

        return redirect()->route('settings.integrations', ['tab' => 'spam'])
            ->with('success', 'Spam protection settings saved successfully.');
    }

    /**
     * Test spam API connections.
     */
    public function testSpam(Request $request)
    {
        try {
            $service = $request->input('service');
            $testValue = $request->input('test_value');

            $result = match($service) {
                'stopforumspam' => $this->testStopForumSpam($testValue),
                'abuseipdb' => $this->testAbuseIPDB($testValue),
                'safebrowsing' => $this->testSafeBrowsing($testValue),
                'purgomalum' => $this->testPurgoMalum($testValue),
                default => ['success' => false, 'message' => 'Unknown service']
            };

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test StopForumSpam API (FREE - No API key needed)
     */
    private function testStopForumSpam($email)
    {
        $url = "https://api.stopforumspam.org/api?email=" . urlencode($email) . "&json";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return ['success' => false, 'message' => 'Failed to connect to StopForumSpam API'];
        }

        $data = json_decode($response, true);
        
        if (!$data || !isset($data['success'])) {
            return ['success' => false, 'message' => 'Invalid response from StopForumSpam'];
        }

        $isSpam = isset($data['email']['appears']) && $data['email']['appears'] == 1;
        $confidence = $data['email']['confidence'] ?? 0;

        return [
            'success' => true,
            'message' => 'StopForumSpam API connected successfully!',
            'data' => [
                'email' => $email,
                'is_spam' => $isSpam,
                'confidence' => $confidence . '%',
                'frequency' => $data['email']['frequency'] ?? 0,
                'status' => $isSpam ? '⚠️ SPAM DETECTED' : '✅ CLEAN'
            ]
        ];
    }

    /**
     * Test AbuseIPDB API (FREE - 1000 checks/day)
     */
    private function testAbuseIPDB($ip)
    {
        $settings = Setting::getByGroup('integration_spam');
        $spamSettings = $settings['spam_settings'] ?? [];
        
        if (empty($spamSettings['abuseipdb_api_key'])) {
            return ['success' => false, 'message' => 'AbuseIPDB API key not configured'];
        }

        $url = "https://api.abuseipdb.com/api/v2/check?ipAddress=" . urlencode($ip);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Key: ' . $spamSettings['abuseipdb_api_key'],
            'Accept: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            $error = json_decode($response, true);
            return ['success' => false, 'message' => $error['errors'][0]['detail'] ?? 'Failed to connect to AbuseIPDB'];
        }

        $data = json_decode($response, true);
        
        if (!$data || !isset($data['data'])) {
            return ['success' => false, 'message' => 'Invalid response from AbuseIPDB'];
        }

        $abuseScore = $data['data']['abuseConfidenceScore'] ?? 0;
        $isAbusive = $abuseScore > 50;

        return [
            'success' => true,
            'message' => 'AbuseIPDB API connected successfully!',
            'data' => [
                'ip' => $ip,
                'abuse_score' => $abuseScore . '%',
                'country' => $data['data']['countryCode'] ?? 'Unknown',
                'isp' => $data['data']['isp'] ?? 'Unknown',
                'total_reports' => $data['data']['totalReports'] ?? 0,
                'status' => $isAbusive ? '⚠️ ABUSIVE IP' : '✅ CLEAN'
            ]
        ];
    }

    /**
     * Test Google Safe Browsing API (FREE - 10000 checks/day)
     */
    private function testSafeBrowsing($url)
    {
        $settings = Setting::getByGroup('integration_spam');
        $spamSettings = $settings['spam_settings'] ?? [];
        
        if (empty($spamSettings['safebrowsing_api_key'])) {
            return ['success' => false, 'message' => 'Google Safe Browsing API key not configured'];
        }

        $apiUrl = "https://safebrowsing.googleapis.com/v4/threatMatches:find?key=" . $spamSettings['safebrowsing_api_key'];
        
        $payload = [
            'client' => [
                'clientId' => 'orien-helpdesk',
                'clientVersion' => '1.0.0'
            ],
            'threatInfo' => [
                'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING', 'UNWANTED_SOFTWARE', 'POTENTIALLY_HARMFUL_APPLICATION'],
                'platformTypes' => ['ANY_PLATFORM'],
                'threatEntryTypes' => ['URL'],
                'threatEntries' => [
                    ['url' => $url]
                ]
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            $error = json_decode($response, true);
            return ['success' => false, 'message' => $error['error']['message'] ?? 'Failed to connect to Safe Browsing API'];
        }

        $data = json_decode($response, true);
        
        $isMalicious = !empty($data['matches']);
        $threats = [];
        if ($isMalicious) {
            foreach ($data['matches'] as $match) {
                $threats[] = $match['threatType'];
            }
        }

        return [
            'success' => true,
            'message' => 'Google Safe Browsing API connected successfully!',
            'data' => [
                'url' => $url,
                'is_malicious' => $isMalicious,
                'threats' => $threats,
                'status' => $isMalicious ? '⚠️ MALICIOUS: ' . implode(', ', $threats) : '✅ SAFE'
            ]
        ];
    }

    /**
     * Test PurgoMalum API (FREE - Unlimited, No API key)
     */
    private function testPurgoMalum($text)
    {
        $url = "https://www.purgomalum.com/service/json?text=" . urlencode($text);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return ['success' => false, 'message' => 'Failed to connect to PurgoMalum API'];
        }

        $data = json_decode($response, true);
        
        if (!$data || !isset($data['result'])) {
            return ['success' => false, 'message' => 'Invalid response from PurgoMalum'];
        }

        $filtered = $data['result'];
        $hasProfanity = $filtered !== $text;

        return [
            'success' => true,
            'message' => 'PurgoMalum API connected successfully!',
            'data' => [
                'original' => $text,
                'filtered' => $filtered,
                'has_profanity' => $hasProfanity,
                'status' => $hasProfanity ? '⚠️ PROFANITY DETECTED' : '✅ CLEAN'
            ]
        ];
    }

    /**
     * Save Telegram settings.
     */
    public function saveTelegram(Request $request)
    {
        if (!auth()->user()->hasPermission('settings_integrations_telegram.edit')) {
            abort(403, 'You do not have permission to edit Telegram settings.');
        }
        
        $validated = $request->validate([
            'bot_token' => 'nullable|string',
            'bot_username' => 'nullable|string',
            'owner_username' => 'nullable|string',
            'owner_user_id' => 'nullable|string',
            'channel_id' => 'nullable|string',
            'enabled' => 'nullable|boolean',
        ]);

        // Get existing settings to preserve bot token if not provided
        $existingSettings = Setting::getByGroup('integration_telegram');
        $currentSettings = $existingSettings['telegram_settings'] ?? [];

        // Preserve bot token if blank
        if (empty($validated['bot_token']) && isset($currentSettings['bot_token'])) {
            $validated['bot_token'] = $currentSettings['bot_token'];
        }

        // Convert checkbox to boolean
        $validated['enabled'] = $request->boolean('enabled');

        Setting::setValue('telegram_settings', $validated, 'integration_telegram');
        
        ActivityLogService::log('update', 'settings', 'Telegram gateway settings updated');

        return redirect()->route('settings.integrations', ['tab' => 'telegram'])
            ->with('success', 'Telegram settings saved successfully.');
    }

    /**
     * Test Telegram connection by sending a message to the channel.
     */
    public function testTelegram(Request $request)
    {
        try {
            $request->validate([
                'message' => 'nullable|string|max:1000',
            ]);

            // Get telegram settings
            $settings = Setting::getByGroup('integration_telegram');
            $telegramSettings = $settings['telegram_settings'] ?? [];

            if (empty($telegramSettings['bot_token'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bot API Token not configured. Please save your Telegram settings first.'
                ]);
            }

            if (empty($telegramSettings['channel_id'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Channel ID not configured. Please save your Telegram settings first.'
                ]);
            }

            $botToken = $telegramSettings['bot_token'];
            $channelId = $telegramSettings['channel_id'];
            $message = $request->input('message', '🔔 Test Connection from ' . company_name() . ' Helpdesk System');

            // Build the message with formatting
            $formattedMessage = "🔔 *" . company_name() . " Helpdesk*\n\n";
            $formattedMessage .= "━━━━━━━━━━━━━━━━━━\n";
            $formattedMessage .= "📋 *Test Connection*\n";
            $formattedMessage .= "━━━━━━━━━━━━━━━━━━\n\n";
            $formattedMessage .= "📝 " . $message . "\n\n";
            $formattedMessage .= "⏰ " . now()->format('d M Y, H:i:s') . "\n";
            $formattedMessage .= "👤 Sent by: " . auth()->user()->name . "\n\n";
            $formattedMessage .= "✅ *Connection Successful!*";

            // Send message via Telegram Bot API
            $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'chat_id' => $channelId,
                'text' => $formattedMessage,
                'parse_mode' => 'Markdown',
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                return response()->json([
                    'success' => false,
                    'message' => 'Connection failed: ' . $curlError
                ]);
            }

            $data = json_decode($response, true);

            if ($httpCode !== 200 || !isset($data['ok']) || !$data['ok']) {
                $errorMessage = $data['description'] ?? 'Unknown error occurred';
                return response()->json([
                    'success' => false,
                    'message' => 'Telegram API Error: ' . $errorMessage
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully to Telegram channel!',
                'data' => [
                    'message_id' => $data['result']['message_id'] ?? null,
                    'chat_id' => $data['result']['chat']['id'] ?? null,
                    'chat_title' => $data['result']['chat']['title'] ?? 'Private Chat',
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Telegram test failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test message: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Test weather API connection.
     */
    public function testWeather(Request $request)
    {
        try {
            // Get weather settings
            $settings = Setting::getByGroup('integration_weather');
            $weatherSettings = $settings['weather_settings'] ?? [];

            if (empty($weatherSettings['api_key'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Weather API key not configured. Please save your weather settings first.'
                ], 400);
            }

            $apiKey = $weatherSettings['api_key'];
            $city = $weatherSettings['default_city'] ?? 'Kuala Lumpur';
            $units = $weatherSettings['units'] ?? 'metric';

            // Call OpenWeatherMap API with proper error handling
            $url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&appid={$apiKey}&units={$units}";
            
            // Use cURL for better error handling
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                $errorData = json_decode($response, true);
                return response()->json([
                    'success' => false,
                    'message' => $errorData['message'] ?? 'Failed to fetch weather data. HTTP Code: ' . $httpCode
                ], 400);
            }

            $data = json_decode($response, true);

            if (!$data || isset($data['cod']) && $data['cod'] != 200) {
                return response()->json([
                    'success' => false,
                    'message' => $data['message'] ?? 'Failed to fetch weather data. Please check your API key.'
                ], 400);
            }

            // Format response
            return response()->json([
                'success' => true,
                'message' => 'Weather API connection successful!',
                'data' => [
                    'city' => $data['name'],
                    'country' => $data['sys']['country'],
                    'temp' => round($data['main']['temp']),
                    'feels_like' => round($data['main']['feels_like']),
                    'humidity' => $data['main']['humidity'],
                    'pressure' => $data['main']['pressure'],
                    'wind_speed' => round($data['wind']['speed'], 1),
                    'condition' => ucfirst($data['weather'][0]['description']),
                    'unit' => $units === 'metric' ? 'C' : 'F',
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to Weather API: ' . $e->getMessage()
            ], 500);
        }
    }
}
