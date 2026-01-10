<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\SlaRule;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $tabs = [
        'categories' => 'Categories',
        'priorities' => 'Priorities',
        'status' => 'Status',
        'sla-rules' => 'SLA Rules',
        'email-templates' => 'Email Templates',
    ];

    /**
     * Map tab to permission key.
     */
    private function getTabPermission($tab)
    {
        return match ($tab) {
            'categories' => 'settings_ticket_categories',
            'priorities' => 'settings_priorities',
            'status' => 'settings_status',
            'sla-rules' => 'settings_sla',
            'email-templates' => 'settings_email_templates',
            default => 'settings_ticket_categories',
        };
    }

    /**
     * Display categories settings with tabs.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->get('tab', 'categories');
        $search = $request->get('search');
        $status = $request->get('status');
        
        // Check permission for the current tab
        $permissionKey = $this->getTabPermission($tab);
        if (!$user->hasPermission($permissionKey . '.view')) {
            // Try to find another tab user has access to
            foreach (array_keys($this->tabs) as $tabKey) {
                $perm = $this->getTabPermission($tabKey);
                if ($user->hasPermission($perm . '.view')) {
                    return redirect()->route('settings.categories', ['tab' => $tabKey]);
                }
            }
            // No access to any category tab
            abort(403, 'You do not have permission to access Categories Settings.');
        }

        // Filter tabs based on permissions
        $filteredTabs = [];
        foreach ($this->tabs as $tabKey => $tabLabel) {
            $perm = $this->getTabPermission($tabKey);
            if ($user->hasPermission($perm . '.view')) {
                $filteredTabs[$tabKey] = $tabLabel;
            }
        }

        // Get permissions for current tab
        $canCreate = $user->hasPermission($permissionKey . '.create');
        $canEdit = $user->hasPermission($permissionKey . '.edit');
        $canDelete = $user->hasPermission($permissionKey . '.delete');

        $data = $this->getTabData($tab, $search, $status);

        return view('settings.categories', [
            'currentTab' => $tab,
            'tabs' => $filteredTabs,
            'items' => $data,
            'priorities' => TicketPriority::where('status', 'active')->orderBy('sort_order')->get(),
            'categories' => TicketCategory::where('status', 'active')->orderBy('sort_order')->get(),
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
        ]);
    }

    /**
     * Get data based on current tab.
     */
    private function getTabData(string $tab, ?string $search, ?string $status)
    {
        $query = match ($tab) {
            'categories' => TicketCategory::query(),
            'priorities' => TicketPriority::query(),
            'status' => TicketStatus::query(),
            'sla-rules' => SlaRule::with(['priority', 'category']),
            'email-templates' => EmailTemplate::query(),
            default => TicketCategory::query(),
        };

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        // Email templates don't have sort_order column
        $orderColumn = $tab === 'email-templates' ? 'name' : 'sort_order';
        $orderDirection = 'asc';

        $perPage = (int) setting('pagination_size', 15);
        return $query->orderBy($orderColumn, $orderDirection)->paginate($perPage)->withQueryString();
    }

    // ==================== CATEGORIES ====================

    public function storeCategory(Request $request)
    {
        if (!auth()->user()->hasPermission('settings_ticket_categories.create')) {
            abort(403, 'You do not have permission to create categories.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $category = TicketCategory::create($validated);
        ActivityLogService::logCreate($category, 'settings', "Ticket category created: {$category->name}");

        return redirect()->route('settings.categories', ['tab' => 'categories'])
            ->with('success', 'Category created successfully.');
    }

    public function updateCategory(Request $request, TicketCategory $category)
    {
        if (!auth()->user()->hasPermission('settings_ticket_categories.edit')) {
            abort(403, 'You do not have permission to edit categories.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $oldValues = $category->only(['name', 'status']);
        $category->update($validated);
        ActivityLogService::logUpdate($category, 'settings', $oldValues, "Ticket category updated: {$category->name}");

        return redirect()->route('settings.categories', ['tab' => 'categories'])
            ->with('success', 'Category updated successfully.');
    }

    public function destroyCategory(TicketCategory $category)
    {
        if (!auth()->user()->hasPermission('settings_ticket_categories.delete')) {
            abort(403, 'You do not have permission to delete categories.');
        }
        
        ActivityLogService::logDelete($category, 'settings', "Ticket category deleted: {$category->name}");
        $category->delete();

        return redirect()->route('settings.categories', ['tab' => 'categories'])
            ->with('success', 'Category deleted successfully.');
    }

    // ==================== PRIORITIES ====================

    public function storePriority(Request $request)
    {
        if (!auth()->user()->hasPermission('settings_priorities.create')) {
            abort(403, 'You do not have permission to create priorities.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $priority = TicketPriority::create($validated);
        ActivityLogService::logCreate($priority, 'settings', "Priority created: {$priority->name}");

        return redirect()->route('settings.categories', ['tab' => 'priorities'])
            ->with('success', 'Priority created successfully.');
    }

    public function updatePriority(Request $request, TicketPriority $priority)
    {
        if (!auth()->user()->hasPermission('settings_priorities.edit')) {
            abort(403, 'You do not have permission to edit priorities.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $oldValues = $priority->only(['name', 'status']);
        $priority->update($validated);
        ActivityLogService::logUpdate($priority, 'settings', $oldValues, "Priority updated: {$priority->name}");

        return redirect()->route('settings.categories', ['tab' => 'priorities'])
            ->with('success', 'Priority updated successfully.');
    }

    public function destroyPriority(TicketPriority $priority)
    {
        if (!auth()->user()->hasPermission('settings_priorities.delete')) {
            abort(403, 'You do not have permission to delete priorities.');
        }
        
        ActivityLogService::logDelete($priority, 'settings', "Priority deleted: {$priority->name}");
        $priority->delete();

        return redirect()->route('settings.categories', ['tab' => 'priorities'])
            ->with('success', 'Priority deleted successfully.');
    }

    // ==================== STATUS ====================

    public function storeStatus(Request $request)
    {
        if (!auth()->user()->hasPermission('settings_status.create')) {
            abort(403, 'You do not have permission to create status.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
            'is_default' => 'nullable|boolean',
            'is_closed' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['is_default'] = $request->boolean('is_default');
        $validated['is_closed'] = $request->boolean('is_closed');

        // If setting as default, unset other defaults
        if ($validated['is_default']) {
            TicketStatus::where('is_default', true)->update(['is_default' => false]);
        }

        $ticketStatus = TicketStatus::create($validated);
        ActivityLogService::logCreate($ticketStatus, 'settings', "Status created: {$ticketStatus->name}");

        return redirect()->route('settings.categories', ['tab' => 'status'])
            ->with('success', 'Status created successfully.');
    }

    public function updateStatus(Request $request, TicketStatus $status)
    {
        if (!auth()->user()->hasPermission('settings_status.edit')) {
            abort(403, 'You do not have permission to edit status.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
            'is_default' => 'nullable|boolean',
            'is_closed' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['is_default'] = $request->boolean('is_default');
        $validated['is_closed'] = $request->boolean('is_closed');

        // If setting as default, unset other defaults
        if ($validated['is_default'] && !$status->is_default) {
            TicketStatus::where('is_default', true)->update(['is_default' => false]);
        }

        $oldValues = $status->only(['name', 'status']);
        $status->update($validated);
        ActivityLogService::logUpdate($status, 'settings', $oldValues, "Status updated: {$status->name}");

        return redirect()->route('settings.categories', ['tab' => 'status'])
            ->with('success', 'Status updated successfully.');
    }

    public function destroyStatus(TicketStatus $status)
    {
        if (!auth()->user()->hasPermission('settings_status.delete')) {
            abort(403, 'You do not have permission to delete status.');
        }
        
        if ($status->is_default) {
            return redirect()->route('settings.categories', ['tab' => 'status'])
                ->with('error', 'Cannot delete default status.');
        }

        ActivityLogService::logDelete($status, 'settings', "Status deleted: {$status->name}");
        $status->delete();

        return redirect()->route('settings.categories', ['tab' => 'status'])
            ->with('success', 'Status deleted successfully.');
    }

    // ==================== SLA RULES ====================

    public function storeSlaRule(Request $request)
    {
        if (!auth()->user()->hasPermission('settings_sla.create')) {
            abort(403, 'You do not have permission to create SLA rules.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'response_time' => 'required|integer|min:1',
            'resolution_time' => 'required|integer|min:1',
            'priority_id' => 'nullable|exists:ticket_priorities,id',
            'category_id' => 'nullable|exists:ticket_categories,id',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $slaRule = SlaRule::create($validated);
        ActivityLogService::logCreate($slaRule, 'settings', "SLA Rule created: {$slaRule->name}");

        return redirect()->route('settings.categories', ['tab' => 'sla-rules'])
            ->with('success', 'SLA Rule created successfully.');
    }

    public function updateSlaRule(Request $request, SlaRule $slaRule)
    {
        if (!auth()->user()->hasPermission('settings_sla.edit')) {
            abort(403, 'You do not have permission to edit SLA rules.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'response_time' => 'required|integer|min:1',
            'resolution_time' => 'required|integer|min:1',
            'priority_id' => 'nullable|exists:ticket_priorities,id',
            'category_id' => 'nullable|exists:ticket_categories,id',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $oldValues = $slaRule->only(['name', 'status']);
        $slaRule->update($validated);
        ActivityLogService::logUpdate($slaRule, 'settings', $oldValues, "SLA Rule updated: {$slaRule->name}");

        return redirect()->route('settings.categories', ['tab' => 'sla-rules'])
            ->with('success', 'SLA Rule updated successfully.');
    }

    public function destroySlaRule(SlaRule $slaRule)
    {
        if (!auth()->user()->hasPermission('settings_sla.delete')) {
            abort(403, 'You do not have permission to delete SLA rules.');
        }
        
        ActivityLogService::logDelete($slaRule, 'settings', "SLA Rule deleted: {$slaRule->name}");
        $slaRule->delete();

        return redirect()->route('settings.categories', ['tab' => 'sla-rules'])
            ->with('success', 'SLA Rule deleted successfully.');
    }

    // ==================== EMAIL TEMPLATES ====================

    public function showEmailTemplate(EmailTemplate $emailTemplate)
    {
        if (!auth()->user()->hasPermission('settings_email_templates.view')) {
            abort(403, 'You do not have permission to view email templates.');
        }
        
        return view('settings.email-templates.show', [
            'template' => $emailTemplate,
        ]);
    }

    public function editEmailTemplate(EmailTemplate $emailTemplate)
    {
        if (!auth()->user()->hasPermission('settings_email_templates.edit')) {
            abort(403, 'You do not have permission to edit email templates.');
        }
        
        return view('settings.email-templates.edit', [
            'template' => $emailTemplate,
            'types' => EmailTemplate::getTypes(),
        ]);
    }

    public function updateEmailTemplate(Request $request, EmailTemplate $emailTemplate)
    {
        if (!auth()->user()->hasPermission('settings_email_templates.edit')) {
            abort(403, 'You do not have permission to edit email templates.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'type' => 'required|string|in:notification,auto-reply,escalation,reminder,welcome',
            'body' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $oldValues = $emailTemplate->only(['name', 'status', 'subject']);
        $emailTemplate->update($validated);
        ActivityLogService::logUpdate($emailTemplate, 'settings', $oldValues, "Email template updated: {$emailTemplate->name}");

        return redirect()->route('settings.categories', ['tab' => 'email-templates'])
            ->with('success', 'Email template updated successfully.');
    }
}
