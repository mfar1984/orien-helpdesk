<?php

namespace App\Services;

use App\Models\BadWord;
use App\Models\BadWebsite;
use App\Models\BannedEmail;
use App\Models\BannedIp;

class ContentFilterService
{
    /**
     * Convert wildcard pattern to regex.
     * Supports:
     * - * = any characters
     * - ? = single character
     * 
     * Examples:
     * - *@spam.com -> matches any email ending with @spam.com
     * - *.spam.com -> matches any subdomain of spam.com
     * - 192.168.* -> matches any IP starting with 192.168.
     * - *://*.example.com/* -> matches any URL with example.com domain
     */
    public static function wildcardToRegex(string $pattern): string
    {
        // Escape regex special chars except * and ?
        $regex = preg_quote($pattern, '/');
        
        // Convert * to regex (any characters)
        $regex = str_replace('\\*', '.*', $regex);
        
        // Convert ? to regex (single character)
        $regex = str_replace('\\?', '.', $regex);
        
        return '/^' . $regex . '$/i';
    }
    
    /**
     * Check if value matches a pattern (supports wildcards).
     */
    public static function matchesPattern(string $value, string $pattern): bool
    {
        // If pattern contains wildcards, use regex
        if (strpos($pattern, '*') !== false || strpos($pattern, '?') !== false) {
            $regex = self::wildcardToRegex($pattern);
            return (bool) preg_match($regex, $value);
        }
        
        // Otherwise, exact match (case-insensitive)
        return strcasecmp($value, $pattern) === 0;
    }
    
    /**
     * Check if email is banned (supports wildcards).
     * 
     * Examples:
     * - *@spam.com -> blocks all emails from spam.com
     * - *@*.spam.com -> blocks all emails from any subdomain of spam.com
     * - spam* -> blocks any email starting with "spam"
     */
    public static function isEmailBanned(string $email): array
    {
        $email = strtolower(trim($email));
        $bannedEmails = BannedEmail::all();
        
        foreach ($bannedEmails as $banned) {
            if (self::matchesPattern($email, strtolower($banned->email))) {
                return [
                    'banned' => true,
                    'pattern' => $banned->email,
                    'reason' => $banned->reason,
                ];
            }
        }
        
        return ['banned' => false];
    }
    
    /**
     * Check if IP is banned (supports wildcards).
     * 
     * Examples:
     * - 192.168.1.* -> blocks all IPs in 192.168.1.x range
     * - 10.*.*.* -> blocks all IPs starting with 10.
     * - 192.168.?.1 -> blocks 192.168.0.1 to 192.168.9.1
     */
    public static function isIpBanned(string $ip): array
    {
        $ip = trim($ip);
        $bannedIps = BannedIp::all();
        
        foreach ($bannedIps as $banned) {
            if (self::matchesPattern($ip, $banned->ip_address)) {
                return [
                    'banned' => true,
                    'pattern' => $banned->ip_address,
                    'reason' => $banned->reason,
                ];
            }
        }
        
        return ['banned' => false];
    }
    
    /**
     * Check if content contains bad words (supports wildcards).
     * 
     * Examples:
     * - f*ck -> matches fuck, f**ck, f-u-c-k variations
     * - sh*t -> matches variations
     * - *porn* -> matches anything containing "porn"
     */
    public static function checkBadWords(string $content): array
    {
        $badWords = BadWord::where('status', 'active')->get();
        $detectedWords = [];
        $maxSeverity = null;
        
        $contentLower = strtolower($content);
        $words = preg_split('/\s+/', $contentLower);
        
        foreach ($badWords as $badWord) {
            $pattern = strtolower($badWord->word);
            $matched = false;
            
            // Check if pattern has wildcards
            if (strpos($pattern, '*') !== false || strpos($pattern, '?') !== false) {
                // For wildcard patterns, check each word
                foreach ($words as $word) {
                    // Clean word from punctuation
                    $cleanWord = preg_replace('/[^\w]/', '', $word);
                    if (self::matchesPattern($cleanWord, $pattern)) {
                        $matched = true;
                        break;
                    }
                }
                
                // Also check if pattern like *word* should match within text
                if (!$matched && strpos($pattern, '*') === 0 && substr($pattern, -1) === '*') {
                    $innerPattern = trim($pattern, '*');
                    if (stripos($contentLower, $innerPattern) !== false) {
                        $matched = true;
                    }
                }
            } else {
                // Exact word match with word boundaries
                if (preg_match('/\b' . preg_quote($pattern, '/') . '\b/i', $content)) {
                    $matched = true;
                }
            }
            
            if ($matched) {
                $detectedWords[] = [
                    'word' => $badWord->word,
                    'severity' => $badWord->severity,
                    'reason' => $badWord->reason,
                ];
                
                if ($maxSeverity === null || self::compareSeverity($badWord->severity, $maxSeverity) > 0) {
                    $maxSeverity = $badWord->severity;
                }
            }
        }
        
        return [
            'detected' => !empty($detectedWords),
            'words' => $detectedWords,
            'severity' => $maxSeverity,
        ];
    }
    
    /**
     * Check if content contains bad websites (supports wildcards).
     * 
     * Examples:
     * - *.spam.com -> matches any subdomain of spam.com
     * - *://*.spam.com/* -> matches any protocol, subdomain, path
     * - spam.com/* -> matches any path on spam.com
     * - *porn* -> matches any URL containing "porn"
     */
    public static function checkBadWebsites(string $content): array
    {
        $badWebsites = BadWebsite::where('status', 'active')->get();
        $detectedWebsites = [];
        $maxSeverity = null;
        
        // Extract URLs from content
        $urls = self::extractUrls($content);
        $contentLower = strtolower($content);
        
        foreach ($badWebsites as $badWebsite) {
            $pattern = strtolower($badWebsite->url);
            $matched = false;
            $matchedUrl = null;
            
            // Check if pattern has wildcards
            if (strpos($pattern, '*') !== false || strpos($pattern, '?') !== false) {
                // Check each extracted URL
                foreach ($urls as $url) {
                    $urlLower = strtolower($url);
                    
                    // Try matching full URL
                    if (self::matchesPattern($urlLower, $pattern)) {
                        $matched = true;
                        $matchedUrl = $url;
                        break;
                    }
                    
                    // Try matching just the domain part
                    $domain = self::extractDomain($url);
                    if ($domain && self::matchesPattern($domain, $pattern)) {
                        $matched = true;
                        $matchedUrl = $url;
                        break;
                    }
                }
                
                // Also check raw content for domain patterns
                if (!$matched) {
                    // Extract just domain pattern (remove protocol wildcards)
                    $domainPattern = preg_replace('/^\*:\/\//', '', $pattern);
                    $domainPattern = preg_replace('/\/\*$/', '', $domainPattern);
                    
                    if (strpos($domainPattern, '*') !== false) {
                        // Build a simpler regex for content scanning
                        $simpleRegex = str_replace('*.', '(?:[a-z0-9-]+\\.)*', preg_quote($domainPattern, '/'));
                        $simpleRegex = str_replace('\\*', '[a-z0-9-]*', $simpleRegex);
                        if (preg_match('/' . $simpleRegex . '/i', $contentLower)) {
                            $matched = true;
                        }
                    }
                }
            } else {
                // Simple string match
                if (stripos($content, $pattern) !== false) {
                    $matched = true;
                }
            }
            
            if ($matched) {
                $detectedWebsites[] = [
                    'url' => $badWebsite->url,
                    'matched_url' => $matchedUrl,
                    'severity' => $badWebsite->severity,
                    'reason' => $badWebsite->reason,
                ];
                
                if ($maxSeverity === null || self::compareSeverity($badWebsite->severity, $maxSeverity) > 0) {
                    $maxSeverity = $badWebsite->severity;
                }
            }
        }
        
        return [
            'detected' => !empty($detectedWebsites),
            'websites' => $detectedWebsites,
            'severity' => $maxSeverity,
        ];
    }
    
    /**
     * Extract all URLs from content.
     */
    private static function extractUrls(string $content): array
    {
        $urls = [];
        
        // Match URLs with protocol
        preg_match_all('/https?:\/\/[^\s<>"\']+/i', $content, $matches);
        if (!empty($matches[0])) {
            $urls = array_merge($urls, $matches[0]);
        }
        
        // Match www URLs
        preg_match_all('/www\.[a-zA-Z0-9][a-zA-Z0-9\-\.]*[^\s<>"\'"]*/i', $content, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $url) {
                $urls[] = 'http://' . $url;
            }
        }
        
        return array_unique($urls);
    }
    
    /**
     * Extract domain from URL.
     */
    private static function extractDomain(string $url): ?string
    {
        $parsed = parse_url($url);
        return $parsed['host'] ?? null;
    }
    
    /**
     * Check both bad words and bad websites in content.
     */
    public static function checkContent(string $content): array
    {
        $badWords = self::checkBadWords($content);
        $badWebsites = self::checkBadWebsites($content);
        
        $allIssues = array_merge($badWords['words'], $badWebsites['websites']);
        $maxSeverity = null;
        
        if ($badWords['severity']) {
            $maxSeverity = $badWords['severity'];
        }
        
        if ($badWebsites['severity'] && 
            ($maxSeverity === null || self::compareSeverity($badWebsites['severity'], $maxSeverity) > 0)) {
            $maxSeverity = $badWebsites['severity'];
        }
        
        return [
            'detected' => !empty($allIssues),
            'bad_words' => $badWords['words'],
            'bad_websites' => $badWebsites['websites'],
            'all_issues' => $allIssues,
            'severity' => $maxSeverity,
        ];
    }
    
    /**
     * Compare severity levels.
     */
    private static function compareSeverity(string $a, string $b): int
    {
        $levels = ['low' => 1, 'medium' => 2, 'high' => 3];
        return ($levels[$a] ?? 0) <=> ($levels[$b] ?? 0);
    }
    
    /**
     * Mask bad words in content.
     */
    public static function maskBadWords(string $content): string
    {
        $badWords = BadWord::where('status', 'active')->pluck('word');
        
        foreach ($badWords as $word) {
            if (strpos($word, '*') !== false || strpos($word, '?') !== false) {
                // For wildcard patterns, we need more complex masking
                continue; // Skip wildcards for now in masking
            }
            
            $replacement = str_repeat('*', strlen($word));
            $content = preg_replace('/\b' . preg_quote($word, '/') . '\b/i', $replacement, $content);
        }
        
        return $content;
    }
}
