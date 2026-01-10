<?php

namespace App\Http\Controllers;

use App\Models\BadWebsite;
use App\Models\BadWord;
use App\Models\BannedEmail;
use App\Models\BannedIp;
use App\Models\KbArticle;
use App\Models\KbCategory;
use App\Models\Role;
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

class RecycleBinController extends Controller
{
    /**
     * Model mapping for recycle bin types.
     */
    protected array $modelMap = [
        'tickets' => Ticket::class,
        'users' => User::class,
        'roles' => Role::class,
        'kb_articles' => KbArticle::class,
        'kb_categories' => KbCategory::class,
        'ticket_categories' => TicketCategory::class,
        'priorities' => TicketPriority::class,
        'statuses' => TicketStatus::class,
        'sla_rules' => SlaRule::class,
        'banned_emails' => BannedEmail::class,
        'banned_ips' => BannedIp::class,
        'whitelist_emails' => WhitelistEmail::class,
        'whitelist_ips' => WhitelistIp::class,
        'bad_words' => BadWord::class,
        'bad_websites' => BadWebsite::class,
    ];

    /**
     * Module names for activity logging.
     */
    protected array $moduleNames = [
        'tickets' => 'tickets',
        'users' => 'users',
        'roles' => 'roles',
        'kb_articles' => 'knowledgebase',
        'kb_categories' => 'knowledgebase',
        'ticket_categories' => 'categories',
        'priorities' => 'priorities',
        'statuses' => 'statuses',
        'sla_rules' => 'sla_rules',
        'banned_emails' => 'tools',
        'banned_ips' => 'tools',
        'whitelist_emails' => 'tools',
        'whitelist_ips' => 'tools',
        'bad_words' => 'tools',
        'bad_websites' => 'tools',
    ];

    /**
     * Restore a soft-deleted item.
     */
    public function restore(string $type, int $id)
    {
        if (!auth()->user()->hasPermission('settings_integrations_recycle.manage')) {
            abort(403, 'You do not have permission to restore items.');
        }

        if (!isset($this->modelMap[$type])) {
            abort(404, 'Invalid recycle bin type.');
        }

        $modelClass = $this->modelMap[$type];
        $item = $modelClass::onlyTrashed()->findOrFail($id);
        $item->restore();

        ActivityLogService::logRestore($item, $this->moduleNames[$type]);

        return redirect()->back()->with('success', 'Item restored successfully.');
    }

    /**
     * Permanently delete a soft-deleted item.
     */
    public function forceDelete(string $type, int $id)
    {
        if (!auth()->user()->hasPermission('settings_integrations_recycle.delete')) {
            abort(403, 'You do not have permission to permanently delete items.');
        }

        if (!isset($this->modelMap[$type])) {
            abort(404, 'Invalid recycle bin type.');
        }

        $modelClass = $this->modelMap[$type];
        $item = $modelClass::onlyTrashed()->findOrFail($id);
        
        ActivityLogService::logForceDelete($item, $this->moduleNames[$type]);
        
        $item->forceDelete();

        return redirect()->back()->with('success', 'Item permanently deleted.');
    }

    /**
     * Empty all items from recycle bin.
     */
    public function emptyAll()
    {
        if (!auth()->user()->hasPermission('settings_integrations_recycle.delete')) {
            abort(403, 'You do not have permission to empty the recycle bin.');
        }

        $totalDeleted = 0;

        foreach ($this->modelMap as $type => $modelClass) {
            $count = $modelClass::onlyTrashed()->count();
            if ($count > 0) {
                $modelClass::onlyTrashed()->forceDelete();
                $totalDeleted += $count;
            }
        }

        ActivityLogService::logEmptyRecycleBin('recycle_bin', $totalDeleted);

        return redirect()->back()->with('success', "Recycle bin emptied. {$totalDeleted} items permanently deleted.");
    }
}
