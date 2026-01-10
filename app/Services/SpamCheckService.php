<?php

namespace App\Services;

use App\Models\BannedEmail;
use App\Models\BannedIp;
use App\Models\BadWord;
use App\Models\BadWebsite;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class SpamCheckService
{
    protected static $settings = null;

    /**
     * Get spam settings from database.
     */
    protected static function getSettings(): array
    {
        if (self::$settings === null) {
            $settings = Setting::getByGroup('integration_spam');
            self::$settings = $settings['spam_settings'] ?? [];
        }
        return self::$settings;
    }

    /**
     * Check if email is spam using StopForumSpam API.
     */
    public static function checkEmail(string $email): array
    {
        $settings = self::getSettings();
        
        if (empty($settings['stopforumspam_enabled'])) {
            return ['is_spam' => false, 'reason' => null];
        }

        try {
            $url = "https://api.stopforumspam.org/api?email=" . urlencode($email) . "&json";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                Log::warning('StopForumSpam API failed', ['http_code' => $httpCode]);
                return ['is_spam' => false, 'reason' => null];
            }

            $data = json_decode($response, true);
            
            if (!$data || !isset($data['success'])) {
                return ['is_spam' => false, 'reason' => null];
            }

            $isSpam = isset($data['email']['appears']) && $data['email']['appears'] == 1;
            $confidence = $data['email']['confidence'] ?? 0;
            $threshold = $settings['stopforumspam_threshold'] ?? 90;

            if ($isSpam && $confidence >= $threshold) {
                // Auto-add to database if enabled
                if (!empty($settings['auto_add_to_database'])) {
                    self::addBannedEmail($email, "StopForumSpam: {$confidence}% confidence");
                }

                return [
                    'is_spam' => true,
                    'reason' => "Email detected as spam by StopForumSpam ({$confidence}% confidence)",
                    'confidence' => $confidence
                ];
            }

            return ['is_spam' => false, 'reason' => null, 'confidence' => $confidence];

        } catch (\Exception $e) {
            Log::error('StopForumSpam check failed', ['error' => $e->getMessage()]);
            return ['is_spam' => false, 'reason' => null];
        }
    }

    /**
     * Check if IP is abusive using AbuseIPDB API.
     */
    public static function checkIP(string $ip): array
    {
        $settings = self::getSettings();
        
        // Also check StopForumSpam for IP
        $sfsResult = self::checkIPStopForumSpam($ip);
        if ($sfsResult['is_spam']) {
            return $sfsResult;
        }

        if (empty($settings['abuseipdb_enabled']) || empty($settings['abuseipdb_api_key'])) {
            return ['is_spam' => false, 'reason' => null];
        }

        try {
            $url = "https://api.abuseipdb.com/api/v2/check?ipAddress=" . urlencode($ip);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Key: ' . $settings['abuseipdb_api_key'],
                'Accept: application/json'
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                Log::warning('AbuseIPDB API failed', ['http_code' => $httpCode]);
                return ['is_spam' => false, 'reason' => null];
            }

            $data = json_decode($response, true);
            
            if (!$data || !isset($data['data'])) {
                return ['is_spam' => false, 'reason' => null];
            }

            $abuseScore = $data['data']['abuseConfidenceScore'] ?? 0;
            $threshold = $settings['abuseipdb_threshold'] ?? 80;

            if ($abuseScore >= $threshold) {
                // Auto-add to database if enabled
                if (!empty($settings['auto_add_to_database'])) {
                    self::addBannedIP($ip, "AbuseIPDB: {$abuseScore}% abuse score");
                }

                return [
                    'is_spam' => true,
                    'reason' => "IP detected as abusive by AbuseIPDB ({$abuseScore}% abuse score)",
                    'abuse_score' => $abuseScore
                ];
            }

            return ['is_spam' => false, 'reason' => null, 'abuse_score' => $abuseScore];

        } catch (\Exception $e) {
            Log::error('AbuseIPDB check failed', ['error' => $e->getMessage()]);
            return ['is_spam' => false, 'reason' => null];
        }
    }

    /**
     * Check IP using StopForumSpam.
     */
    protected static function checkIPStopForumSpam(string $ip): array
    {
        $settings = self::getSettings();
        
        if (empty($settings['stopforumspam_enabled'])) {
            return ['is_spam' => false, 'reason' => null];
        }

        try {
            $url = "https://api.stopforumspam.org/api?ip=" . urlencode($ip) . "&json";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                return ['is_spam' => false, 'reason' => null];
            }

            $data = json_decode($response, true);
            
            if (!$data || !isset($data['success'])) {
                return ['is_spam' => false, 'reason' => null];
            }

            $isSpam = isset($data['ip']['appears']) && $data['ip']['appears'] == 1;
            $confidence = $data['ip']['confidence'] ?? 0;
            $threshold = $settings['stopforumspam_threshold'] ?? 90;

            if ($isSpam && $confidence >= $threshold) {
                if (!empty($settings['auto_add_to_database'])) {
                    self::addBannedIP($ip, "StopForumSpam: {$confidence}% confidence");
                }

                return [
                    'is_spam' => true,
                    'reason' => "IP detected as spam by StopForumSpam ({$confidence}% confidence)",
                    'confidence' => $confidence
                ];
            }

            return ['is_spam' => false, 'reason' => null];

        } catch (\Exception $e) {
            return ['is_spam' => false, 'reason' => null];
        }
    }

    /**
     * Check if URL is malicious using Google Safe Browsing API.
     */
    public static function checkURL(string $url): array
    {
        $settings = self::getSettings();
        
        if (empty($settings['safebrowsing_enabled']) || empty($settings['safebrowsing_api_key'])) {
            return ['is_malicious' => false, 'reason' => null];
        }

        try {
            $apiUrl = "https://safebrowsing.googleapis.com/v4/threatMatches:find?key=" . $settings['safebrowsing_api_key'];
            
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
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                Log::warning('Safe Browsing API failed', ['http_code' => $httpCode]);
                return ['is_malicious' => false, 'reason' => null];
            }

            $data = json_decode($response, true);
            
            $isMalicious = !empty($data['matches']);
            
            if ($isMalicious) {
                $threats = [];
                foreach ($data['matches'] as $match) {
                    $threats[] = $match['threatType'];
                }
                $threatStr = implode(', ', $threats);

                // Auto-add to database if enabled
                if (!empty($settings['auto_add_to_database'])) {
                    self::addBadWebsite($url, "Google Safe Browsing: {$threatStr}");
                }

                return [
                    'is_malicious' => true,
                    'reason' => "URL detected as malicious: {$threatStr}",
                    'threats' => $threats
                ];
            }

            return ['is_malicious' => false, 'reason' => null];

        } catch (\Exception $e) {
            Log::error('Safe Browsing check failed', ['error' => $e->getMessage()]);
            return ['is_malicious' => false, 'reason' => null];
        }
    }

    /**
     * Check text for profanity using PurgoMalum API.
     */
    public static function checkProfanity(string $text): array
    {
        $settings = self::getSettings();
        
        if (empty($settings['purgomalum_enabled'])) {
            return ['has_profanity' => false, 'filtered' => $text, 'reason' => null];
        }

        try {
            $url = "https://www.purgomalum.com/service/json?text=" . urlencode($text);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                Log::warning('PurgoMalum API failed', ['http_code' => $httpCode]);
                return ['has_profanity' => false, 'filtered' => $text, 'reason' => null];
            }

            $data = json_decode($response, true);
            
            if (!$data || !isset($data['result'])) {
                return ['has_profanity' => false, 'filtered' => $text, 'reason' => null];
            }

            $filtered = $data['result'];
            $hasProfanity = $filtered !== $text;

            if ($hasProfanity) {
                // Extract bad words (words replaced with *)
                preg_match_all('/\*+/', $filtered, $matches);
                
                return [
                    'has_profanity' => true,
                    'filtered' => $filtered,
                    'reason' => 'Profanity detected in text'
                ];
            }

            return ['has_profanity' => false, 'filtered' => $text, 'reason' => null];

        } catch (\Exception $e) {
            Log::error('PurgoMalum check failed', ['error' => $e->getMessage()]);
            return ['has_profanity' => false, 'filtered' => $text, 'reason' => null];
        }
    }

    /**
     * Extract and check all URLs in text.
     */
    public static function checkURLsInText(string $text): array
    {
        $maliciousUrls = [];
        
        // Extract URLs from text
        preg_match_all('/https?:\/\/[^\s<>"\']+/i', $text, $matches);
        
        foreach ($matches[0] as $url) {
            $result = self::checkURL($url);
            if ($result['is_malicious']) {
                $maliciousUrls[] = [
                    'url' => $url,
                    'reason' => $result['reason'],
                    'threats' => $result['threats'] ?? []
                ];
            }
        }

        return [
            'has_malicious' => count($maliciousUrls) > 0,
            'malicious_urls' => $maliciousUrls
        ];
    }

    /**
     * Comprehensive content check (profanity + URLs).
     */
    public static function checkContent(string $text): array
    {
        $results = [
            'is_clean' => true,
            'issues' => []
        ];

        // Check profanity
        $profanityCheck = self::checkProfanity($text);
        if ($profanityCheck['has_profanity']) {
            $results['is_clean'] = false;
            $results['issues'][] = [
                'type' => 'profanity',
                'reason' => $profanityCheck['reason'],
                'filtered_text' => $profanityCheck['filtered']
            ];
        }

        // Check URLs
        $urlCheck = self::checkURLsInText($text);
        if ($urlCheck['has_malicious']) {
            $results['is_clean'] = false;
            foreach ($urlCheck['malicious_urls'] as $malUrl) {
                $results['issues'][] = [
                    'type' => 'malicious_url',
                    'url' => $malUrl['url'],
                    'reason' => $malUrl['reason']
                ];
            }
        }

        return $results;
    }

    /**
     * Add email to banned list.
     */
    protected static function addBannedEmail(string $email, string $reason): void
    {
        try {
            BannedEmail::firstOrCreate(
                ['email' => strtolower($email)],
                [
                    'reason' => $reason,
                    'added_by' => auth()->id() ?? 1
                ]
            );
        } catch (\Exception $e) {
            Log::error('Failed to add banned email', ['email' => $email, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Add IP to banned list.
     */
    protected static function addBannedIP(string $ip, string $reason): void
    {
        try {
            BannedIp::firstOrCreate(
                ['ip_address' => $ip],
                [
                    'reason' => $reason,
                    'added_by' => auth()->id() ?? 1
                ]
            );
        } catch (\Exception $e) {
            Log::error('Failed to add banned IP', ['ip' => $ip, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Add URL to bad website list.
     */
    protected static function addBadWebsite(string $url, string $reason): void
    {
        try {
            // Extract domain from URL
            $parsed = parse_url($url);
            $domain = $parsed['host'] ?? $url;

            BadWebsite::firstOrCreate(
                ['url' => $domain],
                [
                    'reason' => $reason,
                    'added_by' => auth()->id() ?? 1
                ]
            );
        } catch (\Exception $e) {
            Log::error('Failed to add bad website', ['url' => $url, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Check if spam protection is enabled.
     */
    public static function isEnabled(): bool
    {
        $settings = self::getSettings();
        return !empty($settings['stopforumspam_enabled']) 
            || !empty($settings['abuseipdb_enabled'])
            || !empty($settings['safebrowsing_enabled'])
            || !empty($settings['purgomalum_enabled']);
    }

    /**
     * Check if registration checks are enabled.
     */
    public static function checkOnRegistration(): bool
    {
        $settings = self::getSettings();
        return !empty($settings['check_on_registration']);
    }

    /**
     * Check if login checks are enabled.
     */
    public static function checkOnLogin(): bool
    {
        $settings = self::getSettings();
        return !empty($settings['check_on_login']);
    }

    /**
     * Check if ticket/reply checks are enabled.
     */
    public static function checkOnTicket(): bool
    {
        $settings = self::getSettings();
        return !empty($settings['check_on_ticket']);
    }
}
