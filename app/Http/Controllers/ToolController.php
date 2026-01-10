<?php

namespace App\Http\Controllers;

use App\Models\BannedEmail;
use App\Models\BannedIp;
use App\Models\WhitelistEmail;
use App\Models\WhitelistIp;
use App\Models\BadWord;
use App\Models\BadWebsite;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    /**
     * Map tab to permission key.
     */
    private function getTabPermission($tab)
    {
        return match ($tab) {
            'ban-email' => 'tools_ban_emails',
            'ban-ip' => 'tools_ban_ips',
            'whitelist-email' => 'tools_whitelist_emails',
            'whitelist-ip' => 'tools_whitelist_ips',
            'bad-word' => 'tools_bad_words',
            'bad-website' => 'tools_bad_websites',
            default => 'tools_ban_emails',
        };
    }

    /**
     * Display tools with tabs.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->get('tab', 'ban-email');
        
        // Handle legacy URL with 'ban-ips' 
        if ($tab === 'ban-ips') {
            $tab = 'ban-ip';
        }
        
        $tabs = [
            'ban-email' => 'Ban Email',
            'ban-ip' => 'Ban IPs',
            'whitelist-ip' => 'Whitelist IP',
            'whitelist-email' => 'Whitelist Email',
            'bad-word' => 'Bad Word',
            'bad-website' => 'Bad Website',
        ];
        
        // Validate tab exists
        if (!array_key_exists($tab, $tabs)) {
            $tab = 'ban-email';
        }
        
        // Check permission for the current tab
        $permissionKey = $this->getTabPermission($tab);
        if (!$user->hasPermission($permissionKey . '.view')) {
            // Try to find another tab user has access to
            $foundAccessibleTab = false;
            foreach (array_keys($tabs) as $tabKey) {
                $perm = $this->getTabPermission($tabKey);
                if ($user->hasPermission($perm . '.view')) {
                    return redirect()->route('tools.index', ['tab' => $tabKey]);
                }
            }
            // No access to any tool tab
            abort(403, 'You do not have permission to access Tools.');
        }

        // Filter tabs based on user permissions
        $filteredTabs = [];
        foreach ($tabs as $tabKey => $tabLabel) {
            $perm = $this->getTabPermission($tabKey);
            if ($user->hasPermission($perm . '.view')) {
                $filteredTabs[$tabKey] = $tabLabel;
            }
        }

        // Get data based on current tab
        $data = [];
        switch ($tab) {
            case 'ban-email':
                $data = BannedEmail::with('addedBy')->latest()->get();
                break;
            case 'ban-ip':
                $data = BannedIp::with('addedBy')->latest()->get();
                break;
            case 'whitelist-email':
                $data = WhitelistEmail::with('addedBy')->latest()->get();
                break;
            case 'whitelist-ip':
                $data = WhitelistIp::with('addedBy')->latest()->get();
                break;
            case 'bad-word':
                $data = BadWord::with('addedBy')->latest()->get();
                break;
            case 'bad-website':
                $data = BadWebsite::with('addedBy')->latest()->get();
                break;
        }
        
        return view('tools.index', [
            'currentTab' => $tab,
            'tabs' => $filteredTabs,
            'data' => $data,
        ]);
    }

    /**
     * Store ban email.
     */
    public function storeBanEmail(Request $request)
    {
        if (!auth()->user()->hasPermission('tools_ban_emails.create')) {
            abort(403, 'You do not have permission to create banned emails.');
        }
        
        $validated = $request->validate([
            'email' => 'required|email|unique:banned_emails,email',
            'reason' => 'required|string|max:500',
        ]);

        $bannedEmail = BannedEmail::create([
            'email' => strtolower($validated['email']),
            'reason' => $validated['reason'],
            'added_by' => auth()->id(),
        ]);
        
        ActivityLogService::logCreate($bannedEmail, 'tools', "Email banned: {$bannedEmail->email}");

        return redirect()->route('tools.index', ['tab' => 'ban-email'])
            ->with('success', 'Email banned successfully.');
    }

    /**
     * Update ban email.
     */
    public function updateBanEmail(Request $request, BannedEmail $bannedEmail)
    {
        if (!auth()->user()->hasPermission('tools_ban_emails.edit')) {
            abort(403, 'You do not have permission to edit banned emails.');
        }
        
        $validated = $request->validate([
            'email' => 'required|email|unique:banned_emails,email,' . $bannedEmail->id,
            'reason' => 'required|string|max:500',
        ]);

        $oldValues = $bannedEmail->only(['email', 'reason']);
        $bannedEmail->update([
            'email' => strtolower($validated['email']),
            'reason' => $validated['reason'],
        ]);
        
        ActivityLogService::logUpdate($bannedEmail, 'tools', $oldValues, "Ban email updated: {$bannedEmail->email}");

        return redirect()->route('tools.index', ['tab' => 'ban-email'])
            ->with('success', 'Ban email updated successfully.');
    }

    /**
     * Delete ban email.
     */
    public function destroyBanEmail(BannedEmail $bannedEmail)
    {
        if (!auth()->user()->hasPermission('tools_ban_emails.delete')) {
            abort(403, 'You do not have permission to delete banned emails.');
        }
        
        ActivityLogService::logDelete($bannedEmail, 'tools', "Email ban removed: {$bannedEmail->email}");
        $bannedEmail->delete();

        return redirect()->route('tools.index', ['tab' => 'ban-email'])
            ->with('success', 'Email ban removed successfully.');
    }

    /**
     * Bulk store ban emails.
     */
    public function bulkBanEmail(Request $request)
    {
        if (!auth()->user()->hasPermission('tools_ban_emails.create')) {
            abort(403, 'You do not have permission to create banned emails.');
        }
        
        $validated = $request->validate([
            'emails' => 'required|string',
            'reason' => 'required|string|max:500',
        ]);

        $emails = array_filter(array_map('trim', explode("\n", $validated['emails'])));
        $added = 0;
        $skipped = 0;
        $restored = 0;

        foreach ($emails as $email) {
            $email = strtolower($email);
            
            // Skip invalid emails (but allow wildcards like *@spam.com)
            if (!str_contains($email, '*') && !str_contains($email, '?') && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skipped++;
                continue;
            }
            
            // Check if exists (including soft deleted)
            $existing = BannedEmail::withTrashed()->where('email', $email)->first();
            
            if ($existing) {
                if ($existing->trashed()) {
                    // Restore if soft deleted
                    $existing->restore();
                    $existing->update(['reason' => $validated['reason'], 'added_by' => auth()->id()]);
                    $restored++;
                } else {
                    // Already exists and not deleted
                    $skipped++;
                }
                continue;
            }

            BannedEmail::create([
                'email' => $email,
                'reason' => $validated['reason'],
                'added_by' => auth()->id(),
            ]);
            $added++;
        }
        
        ActivityLogService::log('create', 'tools', "Bulk banned {$added} emails, restored {$restored}");

        $message = "Bulk import complete: {$added} added";
        if ($restored > 0) $message .= ", {$restored} restored";
        if ($skipped > 0) $message .= ", {$skipped} skipped";
        
        return redirect()->route('tools.index', ['tab' => 'ban-email'])
            ->with('success', $message);
    }

    /**
     * Store ban IP.
     */
    public function storeBanIp(Request $request)
    {
        if (!auth()->user()->hasPermission('tools_ban_ips.create')) {
            abort(403, 'You do not have permission to create banned IPs.');
        }
        
        $validated = $request->validate([
            'ip_address' => 'required|ip|unique:banned_ips,ip_address',
            'reason' => 'required|string|max:500',
        ]);

        $bannedIp = BannedIp::create([
            'ip_address' => $validated['ip_address'],
            'reason' => $validated['reason'],
            'added_by' => auth()->id(),
        ]);
        
        ActivityLogService::logCreate($bannedIp, 'tools', "IP banned: {$bannedIp->ip_address}");

        return redirect()->route('tools.index', ['tab' => 'ban-ip'])
            ->with('success', 'IP banned successfully.');
    }

    /**
     * Update ban IP.
     */
    public function updateBanIp(Request $request, BannedIp $bannedIp)
    {
        if (!auth()->user()->hasPermission('tools_ban_ips.edit')) {
            abort(403, 'You do not have permission to edit banned IPs.');
        }
        
        $validated = $request->validate([
            'ip_address' => 'required|ip|unique:banned_ips,ip_address,' . $bannedIp->id,
            'reason' => 'required|string|max:500',
        ]);

        $oldValues = $bannedIp->only(['ip_address', 'reason']);
        $bannedIp->update([
            'ip_address' => $validated['ip_address'],
            'reason' => $validated['reason'],
        ]);
        
        ActivityLogService::logUpdate($bannedIp, 'tools', $oldValues, "Ban IP updated: {$bannedIp->ip_address}");

        return redirect()->route('tools.index', ['tab' => 'ban-ip'])
            ->with('success', 'Ban IP updated successfully.');
    }

    /**
     * Delete ban IP.
     */
    public function destroyBanIp(BannedIp $bannedIp)
    {
        if (!auth()->user()->hasPermission('tools_ban_ips.delete')) {
            abort(403, 'You do not have permission to delete banned IPs.');
        }
        
        ActivityLogService::logDelete($bannedIp, 'tools', "IP ban removed: {$bannedIp->ip_address}");
        $bannedIp->delete();

        return redirect()->route('tools.index', ['tab' => 'ban-ip'])
            ->with('success', 'IP ban removed successfully.');
    }

    /**
     * Bulk store ban IPs.
     */
    public function bulkBanIp(Request $request)
    {
        if (!auth()->user()->hasPermission('tools_ban_ips.create')) {
            abort(403, 'You do not have permission to create banned IPs.');
        }
        
        $validated = $request->validate([
            'ip_addresses' => 'required|string',
            'reason' => 'required|string|max:500',
        ]);

        $ips = array_filter(array_map('trim', explode("\n", $validated['ip_addresses'])));
        $added = 0;
        $skipped = 0;
        $restored = 0;

        foreach ($ips as $ip) {
            // Skip invalid IPs (but allow wildcards like 192.168.*)
            if (!str_contains($ip, '*') && !str_contains($ip, '?') && !filter_var($ip, FILTER_VALIDATE_IP)) {
                $skipped++;
                continue;
            }
            
            $existing = BannedIp::withTrashed()->where('ip_address', $ip)->first();
            
            if ($existing) {
                if ($existing->trashed()) {
                    $existing->restore();
                    $existing->update(['reason' => $validated['reason'], 'added_by' => auth()->id()]);
                    $restored++;
                } else {
                    $skipped++;
                }
                continue;
            }

            BannedIp::create([
                'ip_address' => $ip,
                'reason' => $validated['reason'],
                'added_by' => auth()->id(),
            ]);
            $added++;
        }
        
        ActivityLogService::log('create', 'tools', "Bulk banned {$added} IPs, restored {$restored}");

        $message = "Bulk import complete: {$added} added";
        if ($restored > 0) $message .= ", {$restored} restored";
        if ($skipped > 0) $message .= ", {$skipped} skipped";

        return redirect()->route('tools.index', ['tab' => 'ban-ip'])
            ->with('success', $message);
    }

    /**
     * Store whitelist email.
     */
    public function storeWhitelistEmail(Request $request)
    {
        if (!auth()->user()->hasPermission('tools_whitelist_emails.create')) {
            abort(403, 'You do not have permission to create whitelist emails.');
        }
        
        $validated = $request->validate([
            'email' => 'required|email|unique:whitelist_emails,email',
            'reason' => 'required|string|max:500',
        ]);

        $whitelistEmail = WhitelistEmail::create([
            'email' => strtolower($validated['email']),
            'reason' => $validated['reason'],
            'added_by' => auth()->id(),
        ]);
        
        ActivityLogService::logCreate($whitelistEmail, 'tools', "Email whitelisted: {$whitelistEmail->email}");

        return redirect()->route('tools.index', ['tab' => 'whitelist-email'])
            ->with('success', 'Email whitelisted successfully.');
    }

    /**
     * Update whitelist email.
     */
    public function updateWhitelistEmail(Request $request, WhitelistEmail $whitelistEmail)
    {
        if (!auth()->user()->hasPermission('tools_whitelist_emails.edit')) {
            abort(403, 'You do not have permission to edit whitelist emails.');
        }
        
        $validated = $request->validate([
            'email' => 'required|email|unique:whitelist_emails,email,' . $whitelistEmail->id,
            'reason' => 'required|string|max:500',
        ]);

        $oldValues = $whitelistEmail->only(['email', 'reason']);
        $whitelistEmail->update([
            'email' => strtolower($validated['email']),
            'reason' => $validated['reason'],
        ]);
        
        ActivityLogService::logUpdate($whitelistEmail, 'tools', $oldValues, "Whitelist email updated: {$whitelistEmail->email}");

        return redirect()->route('tools.index', ['tab' => 'whitelist-email'])
            ->with('success', 'Whitelist email updated successfully.');
    }

    /**
     * Delete whitelist email.
     */
    public function destroyWhitelistEmail(WhitelistEmail $whitelistEmail)
    {
        if (!auth()->user()->hasPermission('tools_whitelist_emails.delete')) {
            abort(403, 'You do not have permission to delete whitelist emails.');
        }
        
        ActivityLogService::logDelete($whitelistEmail, 'tools', "Email whitelist removed: {$whitelistEmail->email}");
        $whitelistEmail->delete();

        return redirect()->route('tools.index', ['tab' => 'whitelist-email'])
            ->with('success', 'Email whitelist removed successfully.');
    }

    /**
     * Bulk store whitelist emails.
     */
    public function bulkWhitelistEmail(Request $request)
    {
        if (!auth()->user()->hasPermission('tools_whitelist_emails.create')) {
            abort(403, 'You do not have permission to create whitelist emails.');
        }
        
        $validated = $request->validate([
            'emails' => 'required|string',
            'reason' => 'required|string|max:500',
        ]);

        $emails = array_filter(array_map('trim', explode("\n", $validated['emails'])));
        $added = 0;
        $skipped = 0;
        $restored = 0;

        foreach ($emails as $email) {
            $email = strtolower($email);
            
            // Skip invalid emails (but allow wildcards)
            if (!str_contains($email, '*') && !str_contains($email, '?') && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skipped++;
                continue;
            }
            
            $existing = WhitelistEmail::withTrashed()->where('email', $email)->first();
            
            if ($existing) {
                if ($existing->trashed()) {
                    $existing->restore();
                    $existing->update(['reason' => $validated['reason'], 'added_by' => auth()->id()]);
                    $restored++;
                } else {
                    $skipped++;
                }
                continue;
            }

            WhitelistEmail::create([
                'email' => $email,
                'reason' => $validated['reason'],
                'added_by' => auth()->id(),
            ]);
            $added++;
        }
        
        ActivityLogService::log('create', 'tools', "Bulk whitelisted {$added} emails, restored {$restored}");

        $message = "Bulk import complete: {$added} added";
        if ($restored > 0) $message .= ", {$restored} restored";
        if ($skipped > 0) $message .= ", {$skipped} skipped";

        return redirect()->route('tools.index', ['tab' => 'whitelist-email'])
            ->with('success', $message);
    }

    /**
     * Store whitelist IP.
     */
    public function storeWhitelistIp(Request $request)
    {
        if (!auth()->user()->hasPermission('tools_whitelist_ips.create')) {
            abort(403, 'You do not have permission to create whitelist IPs.');
        }
        
        $validated = $request->validate([
            'ip_address' => 'required|ip|unique:whitelist_ips,ip_address',
            'reason' => 'required|string|max:500',
        ]);

        $whitelistIp = WhitelistIp::create([
            'ip_address' => $validated['ip_address'],
            'reason' => $validated['reason'],
            'added_by' => auth()->id(),
        ]);
        
        ActivityLogService::logCreate($whitelistIp, 'tools', "IP whitelisted: {$whitelistIp->ip_address}");

        return redirect()->route('tools.index', ['tab' => 'whitelist-ip'])
            ->with('success', 'IP whitelisted successfully.');
    }

    /**
     * Update whitelist IP.
     */
    public function updateWhitelistIp(Request $request, WhitelistIp $whitelistIp)
    {
        if (!auth()->user()->hasPermission('tools_whitelist_ips.edit')) {
            abort(403, 'You do not have permission to edit whitelist IPs.');
        }
        
        $validated = $request->validate([
            'ip_address' => 'required|ip|unique:whitelist_ips,ip_address,' . $whitelistIp->id,
            'reason' => 'required|string|max:500',
        ]);

        $oldValues = $whitelistIp->only(['ip_address', 'reason']);
        $whitelistIp->update([
            'ip_address' => $validated['ip_address'],
            'reason' => $validated['reason'],
        ]);
        
        ActivityLogService::logUpdate($whitelistIp, 'tools', $oldValues, "Whitelist IP updated: {$whitelistIp->ip_address}");

        return redirect()->route('tools.index', ['tab' => 'whitelist-ip'])
            ->with('success', 'Whitelist IP updated successfully.');
    }

    /**
     * Delete whitelist IP.
     */
    public function destroyWhitelistIp(WhitelistIp $whitelistIp)
    {
        if (!auth()->user()->hasPermission('tools_whitelist_ips.delete')) {
            abort(403, 'You do not have permission to delete whitelist IPs.');
        }
        
        ActivityLogService::logDelete($whitelistIp, 'tools', "IP whitelist removed: {$whitelistIp->ip_address}");
        $whitelistIp->delete();

        return redirect()->route('tools.index', ['tab' => 'whitelist-ip'])
            ->with('success', 'IP whitelist removed successfully.');
    }

    /**
     * Bulk store whitelist IPs.
     */
    public function bulkWhitelistIp(Request $request)
    {
        if (!auth()->user()->hasPermission('tools_whitelist_ips.create')) {
            abort(403, 'You do not have permission to create whitelist IPs.');
        }
        
        $validated = $request->validate([
            'ip_addresses' => 'required|string',
            'reason' => 'required|string|max:500',
        ]);

        $ips = array_filter(array_map('trim', explode("\n", $validated['ip_addresses'])));
        $added = 0;
        $skipped = 0;
        $restored = 0;

        foreach ($ips as $ip) {
            // Skip invalid IPs (but allow wildcards)
            if (!str_contains($ip, '*') && !str_contains($ip, '?') && !filter_var($ip, FILTER_VALIDATE_IP)) {
                $skipped++;
                continue;
            }
            
            $existing = WhitelistIp::withTrashed()->where('ip_address', $ip)->first();
            
            if ($existing) {
                if ($existing->trashed()) {
                    $existing->restore();
                    $existing->update(['reason' => $validated['reason'], 'added_by' => auth()->id()]);
                    $restored++;
                } else {
                    $skipped++;
                }
                continue;
            }

            WhitelistIp::create([
                'ip_address' => $ip,
                'reason' => $validated['reason'],
                'added_by' => auth()->id(),
            ]);
            $added++;
        }
        
        ActivityLogService::log('create', 'tools', "Bulk whitelisted {$added} IPs, restored {$restored}");

        $message = "Bulk import complete: {$added} added";
        if ($restored > 0) $message .= ", {$restored} restored";
        if ($skipped > 0) $message .= ", {$skipped} skipped";

        return redirect()->route('tools.index', ['tab' => 'whitelist-ip'])
            ->with('success', $message);
    }

    // ==================== BAD WORD CRUD ====================

    /**
     * Store bad word.
     */
    public function storeBadWord(Request $request)
    {
        if (!auth()->user()->hasPermission('tools_bad_words.create')) {
            abort(403, 'You do not have permission to create bad words.');
        }
        
        $validated = $request->validate([
            'word' => 'required|string|max:255|unique:bad_words,word',
            'reason' => 'nullable|string|max:500',
            'severity' => 'required|in:low,medium,high',
        ]);

        $badWord = BadWord::create([
            'word' => strtolower($validated['word']),
            'reason' => $validated['reason'],
            'severity' => $validated['severity'],
            'added_by' => auth()->id(),
        ]);
        
        ActivityLogService::logCreate($badWord, 'tools', "Bad word added: {$badWord->word}");

        return redirect()->route('tools.index', ['tab' => 'bad-word'])
            ->with('success', 'Bad word added successfully.');
    }

    /**
     * Update bad word.
     */
    public function updateBadWord(Request $request, BadWord $badWord)
    {
        if (!auth()->user()->hasPermission('tools_bad_words.edit')) {
            abort(403, 'You do not have permission to edit bad words.');
        }
        
        $validated = $request->validate([
            'word' => 'required|string|max:255|unique:bad_words,word,' . $badWord->id,
            'reason' => 'nullable|string|max:500',
            'severity' => 'required|in:low,medium,high',
        ]);

        $oldValues = $badWord->only(['word', 'reason', 'severity']);
        $badWord->update([
            'word' => strtolower($validated['word']),
            'reason' => $validated['reason'],
            'severity' => $validated['severity'],
        ]);
        
        ActivityLogService::logUpdate($badWord, 'tools', $oldValues, "Bad word updated: {$badWord->word}");

        return redirect()->route('tools.index', ['tab' => 'bad-word'])
            ->with('success', 'Bad word updated successfully.');
    }

    /**
     * Delete bad word.
     */
    public function destroyBadWord(BadWord $badWord)
    {
        if (!auth()->user()->hasPermission('tools_bad_words.delete')) {
            abort(403, 'You do not have permission to delete bad words.');
        }
        
        ActivityLogService::logDelete($badWord, 'tools', "Bad word removed: {$badWord->word}");
        $badWord->delete();

        return redirect()->route('tools.index', ['tab' => 'bad-word'])
            ->with('success', 'Bad word removed successfully.');
    }

    /**
     * Bulk store bad words.
     */
    public function bulkBadWord(Request $request)
    {
        if (!auth()->user()->hasPermission('tools_bad_words.create')) {
            abort(403, 'You do not have permission to create bad words.');
        }
        
        $validated = $request->validate([
            'words' => 'required|string',
            'severity' => 'required|in:low,medium,high',
            'reason' => 'nullable|string|max:500',
        ]);

        $words = array_filter(array_map('trim', explode("\n", $validated['words'])));
        $added = 0;
        $skipped = 0;
        $restored = 0;

        foreach ($words as $word) {
            $word = strtolower($word);
            if (empty($word)) {
                continue;
            }
            
            $existing = BadWord::withTrashed()->where('word', $word)->first();
            
            if ($existing) {
                if ($existing->trashed()) {
                    $existing->restore();
                    $existing->update([
                        'reason' => $validated['reason'],
                        'severity' => $validated['severity'],
                        'added_by' => auth()->id()
                    ]);
                    $restored++;
                } else {
                    $skipped++;
                }
                continue;
            }

            BadWord::create([
                'word' => $word,
                'reason' => $validated['reason'],
                'severity' => $validated['severity'],
                'added_by' => auth()->id(),
            ]);
            $added++;
        }
        
        ActivityLogService::log('create', 'tools', "Bulk added {$added} bad words, restored {$restored}");

        $message = "Bulk import complete: {$added} added";
        if ($restored > 0) $message .= ", {$restored} restored";
        if ($skipped > 0) $message .= ", {$skipped} skipped";

        return redirect()->route('tools.index', ['tab' => 'bad-word'])
            ->with('success', $message);
    }

    // ==================== BAD WEBSITE CRUD ====================

    /**
     * Store bad website.
     */
    public function storeBadWebsite(Request $request)
    {
        if (!auth()->user()->hasPermission('tools_bad_websites.create')) {
            abort(403, 'You do not have permission to create bad websites.');
        }
        
        $validated = $request->validate([
            'url' => 'required|string|max:500|unique:bad_websites,url',
            'reason' => 'nullable|string|max:500',
            'severity' => 'required|in:low,medium,high',
        ]);

        $badWebsite = BadWebsite::create([
            'url' => strtolower($validated['url']),
            'reason' => $validated['reason'],
            'severity' => $validated['severity'],
            'added_by' => auth()->id(),
        ]);
        
        ActivityLogService::logCreate($badWebsite, 'tools', "Bad website added: {$badWebsite->url}");

        return redirect()->route('tools.index', ['tab' => 'bad-website'])
            ->with('success', 'Bad website added successfully.');
    }

    /**
     * Update bad website.
     */
    public function updateBadWebsite(Request $request, BadWebsite $badWebsite)
    {
        if (!auth()->user()->hasPermission('tools_bad_websites.edit')) {
            abort(403, 'You do not have permission to edit bad websites.');
        }
        
        $validated = $request->validate([
            'url' => 'required|string|max:500|unique:bad_websites,url,' . $badWebsite->id,
            'reason' => 'nullable|string|max:500',
            'severity' => 'required|in:low,medium,high',
        ]);

        $oldValues = $badWebsite->only(['url', 'reason', 'severity']);
        $badWebsite->update([
            'url' => strtolower($validated['url']),
            'reason' => $validated['reason'],
            'severity' => $validated['severity'],
        ]);
        
        ActivityLogService::logUpdate($badWebsite, 'tools', $oldValues, "Bad website updated: {$badWebsite->url}");

        return redirect()->route('tools.index', ['tab' => 'bad-website'])
            ->with('success', 'Bad website updated successfully.');
    }

    /**
     * Delete bad website.
     */
    public function destroyBadWebsite(BadWebsite $badWebsite)
    {
        if (!auth()->user()->hasPermission('tools_bad_websites.delete')) {
            abort(403, 'You do not have permission to delete bad websites.');
        }
        
        ActivityLogService::logDelete($badWebsite, 'tools', "Bad website removed: {$badWebsite->url}");
        $badWebsite->delete();

        return redirect()->route('tools.index', ['tab' => 'bad-website'])
            ->with('success', 'Bad website removed successfully.');
    }

    /**
     * Bulk store bad websites.
     */
    public function bulkBadWebsite(Request $request)
    {
        if (!auth()->user()->hasPermission('tools_bad_websites.create')) {
            abort(403, 'You do not have permission to create bad websites.');
        }
        
        $validated = $request->validate([
            'urls' => 'required|string',
            'severity' => 'required|in:low,medium,high',
            'reason' => 'nullable|string|max:500',
        ]);

        $urls = array_filter(array_map('trim', explode("\n", $validated['urls'])));
        $added = 0;
        $skipped = 0;
        $restored = 0;

        foreach ($urls as $url) {
            $url = strtolower($url);
            if (empty($url)) {
                continue;
            }
            
            $existing = BadWebsite::withTrashed()->where('url', $url)->first();
            
            if ($existing) {
                if ($existing->trashed()) {
                    $existing->restore();
                    $existing->update([
                        'reason' => $validated['reason'],
                        'severity' => $validated['severity'],
                        'added_by' => auth()->id()
                    ]);
                    $restored++;
                } else {
                    $skipped++;
                }
                continue;
            }

            BadWebsite::create([
                'url' => $url,
                'reason' => $validated['reason'],
                'severity' => $validated['severity'],
                'added_by' => auth()->id(),
            ]);
            $added++;
        }
        
        ActivityLogService::log('create', 'tools', "Bulk added {$added} bad websites, restored {$restored}");

        $message = "Bulk import complete: {$added} added";
        if ($restored > 0) $message .= ", {$restored} restored";
        if ($skipped > 0) $message .= ", {$skipped} skipped";

        return redirect()->route('tools.index', ['tab' => 'bad-website'])
            ->with('success', $message);
    }

    /**
     * Export data based on tab.
     */
    public function export(Request $request)
    {
        $tab = $request->get('tab', 'ban-email');
        $permissionKey = $this->getTabPermission($tab);
        
        if (!auth()->user()->hasPermission($permissionKey . '.export')) {
            abort(403, 'You do not have permission to export this data.');
        }

        $headers = [];
        $rows = collect();
        $filename = 'export_' . date('Y-m-d') . '.csv';

        switch ($tab) {
            case 'ban-email':
                $data = BannedEmail::with('addedBy')->latest()->get();
                $filename = 'banned_emails_' . date('Y-m-d') . '.csv';
                $headers = ['Email', 'Reason', 'Added By', 'Date Added', 'Date Updated'];
                $rows = $data->map(function($item) {
                    return [
                        $item->email,
                        $item->reason ?? '',
                        $item->addedBy->name ?? 'N/A',
                        $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '',
                        $item->updated_at ? $item->updated_at->format('Y-m-d H:i:s') : '',
                    ];
                });
                break;
            case 'ban-ip':
                $data = BannedIp::with('addedBy')->latest()->get();
                $filename = 'banned_ips_' . date('Y-m-d') . '.csv';
                $headers = ['IP Address', 'Reason', 'Added By', 'Date Added', 'Date Updated'];
                $rows = $data->map(function($item) {
                    return [
                        $item->ip_address,
                        $item->reason ?? '',
                        $item->addedBy->name ?? 'N/A',
                        $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '',
                        $item->updated_at ? $item->updated_at->format('Y-m-d H:i:s') : '',
                    ];
                });
                break;
            case 'whitelist-ip':
                $data = WhitelistIp::with('addedBy')->latest()->get();
                $filename = 'whitelist_ips_' . date('Y-m-d') . '.csv';
                $headers = ['IP Address', 'Reason', 'Added By', 'Date Added', 'Date Updated'];
                $rows = $data->map(function($item) {
                    return [
                        $item->ip_address,
                        $item->reason ?? '',
                        $item->addedBy->name ?? 'N/A',
                        $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '',
                        $item->updated_at ? $item->updated_at->format('Y-m-d H:i:s') : '',
                    ];
                });
                break;
            case 'whitelist-email':
                $data = WhitelistEmail::with('addedBy')->latest()->get();
                $filename = 'whitelist_emails_' . date('Y-m-d') . '.csv';
                $headers = ['Email', 'Reason', 'Added By', 'Date Added', 'Date Updated'];
                $rows = $data->map(function($item) {
                    return [
                        $item->email,
                        $item->reason ?? '',
                        $item->addedBy->name ?? 'N/A',
                        $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '',
                        $item->updated_at ? $item->updated_at->format('Y-m-d H:i:s') : '',
                    ];
                });
                break;
            case 'bad-word':
                $data = BadWord::with('addedBy')->latest()->get();
                $filename = 'bad_words_' . date('Y-m-d') . '.csv';
                $headers = ['Word', 'Reason', 'Severity', 'Added By', 'Date Added', 'Date Updated'];
                $rows = $data->map(function($item) {
                    return [
                        $item->word,
                        $item->reason ?? '',
                        ucfirst($item->severity ?? 'low'),
                        $item->addedBy->name ?? 'N/A',
                        $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '',
                        $item->updated_at ? $item->updated_at->format('Y-m-d H:i:s') : '',
                    ];
                });
                break;
            case 'bad-website':
                $data = BadWebsite::with('addedBy')->latest()->get();
                $filename = 'bad_websites_' . date('Y-m-d') . '.csv';
                $headers = ['URL', 'Reason', 'Severity', 'Added By', 'Date Added', 'Date Updated'];
                $rows = $data->map(function($item) {
                    return [
                        $item->url,
                        $item->reason ?? '',
                        ucfirst($item->severity ?? 'low'),
                        $item->addedBy->name ?? 'N/A',
                        $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '',
                        $item->updated_at ? $item->updated_at->format('Y-m-d H:i:s') : '',
                    ];
                });
                break;
            default:
                return redirect()->route('tools.index')->with('error', 'Invalid tab for export.');
        }

        // Log export activity
        ActivityLogService::log('export', 'tools', "Exported {$rows->count()} records from {$tab}");
        
        // Generate CSV with proper headers to force download
        $csvContent = '';
        
        // Add BOM for Excel UTF-8 compatibility
        $csvContent .= "\xEF\xBB\xBF";
        
        // Add headers
        $csvContent .= implode(',', array_map(function($h) {
            return '"' . str_replace('"', '""', $h) . '"';
        }, $headers)) . "\n";
        
        // Add rows
        foreach ($rows as $row) {
            $csvContent .= implode(',', array_map(function($cell) {
                return '"' . str_replace('"', '""', $cell ?? '') . '"';
            }, $row)) . "\n";
        }

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Pragma', 'no-cache')
            ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->header('Expires', '0');
    }
}
