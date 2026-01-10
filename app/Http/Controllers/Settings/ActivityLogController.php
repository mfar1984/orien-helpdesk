<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    protected array $tabs = [
        'activity' => 'Activity Log',
        'audit' => 'Audit Log',
    ];

    /**
     * Map tab to permission key.
     */
    private function getTabPermission($tab)
    {
        return match ($tab) {
            'activity' => 'settings_activity_logs',
            'audit' => 'settings_audit_logs',
            default => 'settings_activity_logs',
        };
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $currentTab = $request->get('tab', 'activity');
        
        if (!array_key_exists($currentTab, $this->tabs)) {
            $currentTab = 'activity';
        }

        // Check permission for the current tab
        $permissionKey = $this->getTabPermission($currentTab);
        if (!$user->hasPermission($permissionKey . '.view')) {
            // Try to find another tab user has access to
            foreach (array_keys($this->tabs) as $tabKey) {
                $perm = $this->getTabPermission($tabKey);
                if ($user->hasPermission($perm . '.view')) {
                    return redirect()->route('settings.activity-logs', ['tab' => $tabKey]);
                }
            }
            abort(403, 'You do not have permission to view Activity Logs.');
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
        $canDelete = $user->hasPermission($permissionKey . '.delete');
        $canExport = $user->hasPermission($permissionKey . '.export');

        // Get permissions for audit tab specifically
        $canExportAudit = $user->hasPermission('settings_audit_logs.export');
        $canDeleteAudit = $user->hasPermission('settings_audit_logs.delete');

        $data = [
            'tabs' => $filteredTabs,
            'currentTab' => $currentTab,
            'canDelete' => $canDelete,
            'canExport' => $canExport,
            'canExportAudit' => $canExportAudit,
            'canDeleteAudit' => $canDeleteAudit,
        ];

        if ($currentTab === 'audit') {
            // Audit Log data
            $data = array_merge($data, $this->getAuditLogData($request));
        } else {
            // Activity Log data
            $data = array_merge($data, $this->getActivityLogData($request));
        }

        return view('settings.activity-logs', $data);
    }

    /**
     * Get Activity Log data.
     */
    private function getActivityLogData(Request $request): array
    {
        $query = ActivityLog::with('user')->latest();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $perPage = (int) setting('pagination_size', 15);
        $logs = $query->paginate($perPage)->withQueryString();

        // Get filter options
        $users = User::orderBy('name')->get(['id', 'name']);
        $actions = ActivityLog::distinct()->pluck('action')->filter();
        $modules = ActivityLog::distinct()->pluck('module')->filter();

        // Get activity log stats
        $activityStats = [
            'total_logs' => ActivityLog::count(),
            'table_size' => ActivityLog::getTableSizeFormatted(),
            'oldest_log' => ActivityLog::oldest('created_at')->first()?->created_at,
            'max_size' => '3 MB',
        ];

        // Run cleanup occasionally (1% chance per request)
        if (rand(1, 100) === 1) {
            ActivityLog::cleanup();
        }

        return [
            'logs' => $logs,
            'users' => $users,
            'actions' => $actions,
            'modules' => $modules,
            'activityStats' => $activityStats,
        ];
    }

    /**
     * Get Audit Log data.
     */
    private function getAuditLogData(Request $request): array
    {
        // Get filter options - only users for audit log
        $users = User::orderBy('name')->get(['id', 'name']);

        // Get audit log stats
        $auditStats = [
            'total_logs' => AuditLog::count(),
            'table_size' => AuditLog::getTableSizeFormatted(),
            'oldest_log' => AuditLog::oldest('created_at')->first()?->created_at,
            'max_age_days' => AuditLog::MAX_AGE_DAYS,
            'max_size' => '3 MB',
        ];

        // Check if user has applied any filter (search request)
        $hasFilter = $request->filled('user_id') || $request->filled('datetime_from') || $request->filled('datetime_to');

        if (!$hasFilter) {
            // No filter applied - don't show any data
            return [
                'auditLogs' => null,
                'groupedLogs' => collect(),
                'users' => $users,
                'auditStats' => $auditStats,
                'hasFilter' => false,
            ];
        }

        // Filter applied - fetch data
        $query = AuditLog::with('user')->latest('created_at');

        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->user_id);
        }

        if ($request->filled('datetime_from')) {
            // Convert datetime-local format (2026-01-10T16:39) to proper datetime
            $datetimeFrom = str_replace('T', ' ', $request->datetime_from) . ':00';
            $query->where('created_at', '>=', $datetimeFrom);
        }

        if ($request->filled('datetime_to')) {
            // Convert datetime-local format and add :59 seconds to include the full minute
            $datetimeTo = str_replace('T', ' ', $request->datetime_to) . ':59';
            $query->where('created_at', '<=', $datetimeTo);
        }

        // Validate datetime range - if TO is before FROM, swap them
        if ($request->filled('datetime_from') && $request->filled('datetime_to')) {
            $from = strtotime(str_replace('T', ' ', $request->datetime_from));
            $to = strtotime(str_replace('T', ' ', $request->datetime_to));
            if ($to < $from) {
                // TO is before FROM - this is invalid, show no results
                return [
                    'auditLogs' => collect(),
                    'groupedLogs' => collect(),
                    'users' => $users,
                    'auditStats' => $auditStats,
                    'hasFilter' => true,
                    'filterError' => 'End date/time must be after start date/time.',
                ];
            }
        }

        $perPage = (int) setting('pagination_size', 15);
        $auditLogs = $query->paginate($perPage)->withQueryString();

        // Group logs by date for timeline view
        $groupedLogs = $auditLogs->getCollection()->groupBy(function ($log) {
            return $log->created_at->format('Y-m-d');
        });

        return [
            'auditLogs' => $auditLogs,
            'groupedLogs' => $groupedLogs,
            'users' => $users,
            'auditStats' => $auditStats,
            'hasFilter' => true,
        ];
    }

    /**
     * Delete a single activity log.
     */
    public function destroy(ActivityLog $activityLog)
    {
        if (!auth()->user()->hasPermission('settings_activity_logs.delete')) {
            abort(403, 'You do not have permission to delete activity logs.');
        }

        $activityLog->delete();

        return redirect()->route('settings.activity-logs')
            ->with('success', 'Activity log deleted successfully.');
    }

    /**
     * Clear all activity logs.
     */
    public function clear(Request $request)
    {
        if (!auth()->user()->hasPermission('settings_activity_logs.delete')) {
            abort(403, 'You do not have permission to clear activity logs.');
        }

        $tab = $request->get('tab', 'activity');
        
        ActivityLog::truncate();

        return redirect()->route('settings.activity-logs', ['tab' => $tab])
            ->with('success', 'All activity logs have been cleared.');
    }

    /**
     * Clear all audit logs.
     */
    public function clearAudit(Request $request)
    {
        if (!auth()->user()->hasPermission('settings_audit_logs.delete')) {
            abort(403, 'You do not have permission to clear audit logs.');
        }
        
        AuditLog::truncate();

        return redirect()->route('settings.activity-logs', ['tab' => 'audit'])
            ->with('success', 'All audit logs have been cleared.');
    }

    /**
     * Export activity logs.
     */
    public function export(Request $request)
    {
        if (!auth()->user()->hasPermission('settings_activity_logs.export')) {
            abort(403, 'You do not have permission to export activity logs.');
        }

        $query = ActivityLog::with('user')->latest();

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%");
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->get();

        $filename = 'activity_logs_' . date('Y-m-d_His') . '.csv';
        $headers = ['User', 'Action', 'Module', 'Description', 'IP Address', 'Date/Time'];

        $callback = function() use ($logs, $headers) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            fputcsv($file, $headers);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->user?->name ?? 'System',
                    ucfirst(str_replace('_', ' ', $log->action)),
                    ucfirst($log->module),
                    $log->description,
                    $log->ip_address ?? '-',
                    $log->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export audit logs as PDF with timeline design.
     */
    public function exportAudit(Request $request)
    {
        if (!auth()->user()->hasPermission('settings_audit_logs.export')) {
            abort(403, 'You do not have permission to export audit logs.');
        }

        $query = AuditLog::with('user')->latest('created_at');

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->user_id);
        }

        if ($request->filled('datetime_from')) {
            $datetimeFrom = str_replace('T', ' ', $request->datetime_from) . ':00';
            $query->where('created_at', '>=', $datetimeFrom);
        }

        if ($request->filled('datetime_to')) {
            $datetimeTo = str_replace('T', ' ', $request->datetime_to) . ':59';
            $query->where('created_at', '<=', $datetimeTo);
        }

        $logs = $query->get();

        // Group logs by date
        $groupedLogs = $logs->groupBy(function ($log) {
            return $log->created_at->format('Y-m-d');
        });

        // Get filter info for header
        $filterInfo = [];
        if ($request->filled('user_id')) {
            $user = User::find($request->user_id);
            $filterInfo['user'] = $user ? $user->name : 'Unknown';
        }
        if ($request->filled('datetime_from')) {
            $filterInfo['from'] = str_replace('T', ' ', $request->datetime_from);
        }
        if ($request->filled('datetime_to')) {
            $filterInfo['to'] = str_replace('T', ' ', $request->datetime_to);
        }

        $pdf = \PDF::loadView('exports.audit-log-pdf', [
            'groupedLogs' => $groupedLogs,
            'totalLogs' => $logs->count(),
            'filterInfo' => $filterInfo,
            'exportDate' => now()->format('d M Y H:i:s'),
        ]);

        $pdf->setPaper('a4', 'portrait');

        $filename = 'audit_logs_' . date('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }
}
