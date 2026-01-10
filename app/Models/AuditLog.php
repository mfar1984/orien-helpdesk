<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class AuditLog extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'user_type',
        'action',
        'module',
        'route_name',
        'url',
        'method',
        'subject_type',
        'subject_id',
        'subject_name',
        'ip_address',
        'user_agent',
        'request_data',
        'response_code',
        'response_time_ms',
        'created_at',
    ];

    protected $casts = [
        'request_data' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Maximum age in days for audit logs.
     */
    const MAX_AGE_DAYS = 3;

    /**
     * Maximum size in bytes (3MB).
     */
    const MAX_SIZE_BYTES = 3 * 1024 * 1024;

    /**
     * Get the user that owns the audit log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get color class based on action.
     */
    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'view' => 'bg-blue-500',
            'create' => 'bg-green-500',
            'update' => 'bg-yellow-500',
            'delete', 'force_delete' => 'bg-red-500',
            'restore' => 'bg-teal-500',
            'export', 'download' => 'bg-indigo-500',
            'reply' => 'bg-cyan-500',
            'assign' => 'bg-purple-500',
            'status_change' => 'bg-orange-500',
            'priority_change' => 'bg-amber-500',
            'lock', 'suspend' => 'bg-red-600',
            'unlock', 'unsuspend' => 'bg-green-600',
            'enable', 'disable', 'regenerate' => 'bg-violet-500',
            'test' => 'bg-pink-500',
            'clear', 'empty' => 'bg-rose-500',
            'logout' => 'bg-gray-600',
            default => 'bg-gray-500',
        };
    }

    /**
     * Get icon based on action.
     */
    public function getActionIconAttribute(): string
    {
        return match ($this->action) {
            'view' => 'visibility',
            'create' => 'add_circle',
            'update' => 'edit',
            'delete' => 'delete',
            'force_delete' => 'delete_forever',
            'restore' => 'restore',
            'export' => 'download',
            'download' => 'file_download',
            'reply' => 'reply',
            'assign' => 'assignment_ind',
            'status_change' => 'sync_alt',
            'priority_change' => 'priority_high',
            'lock' => 'lock',
            'unlock' => 'lock_open',
            'suspend' => 'block',
            'unsuspend' => 'check_circle',
            'enable' => 'toggle_on',
            'disable' => 'toggle_off',
            'regenerate' => 'refresh',
            'test' => 'science',
            'clear', 'empty' => 'delete_sweep',
            'logout' => 'logout',
            default => 'info',
        };
    }

    /**
     * Get module icon.
     */
    public function getModuleIconAttribute(): string
    {
        return match ($this->module) {
            'dashboard' => 'dashboard',
            'tickets' => 'confirmation_number',
            'knowledgebase' => 'menu_book',
            'reports' => 'bar_chart',
            'tools' => 'build',
            'settings' => 'settings',
            'users' => 'group',
            'roles' => 'admin_panel_settings',
            'categories' => 'category',
            'integrations' => 'extension',
            default => 'folder',
        };
    }

    /**
     * Get user type badge color.
     */
    public function getUserTypeBadgeAttribute(): string
    {
        return match ($this->user_type) {
            'administrator' => 'bg-red-100 text-red-700',
            'agent' => 'bg-blue-100 text-blue-700',
            'customer' => 'bg-green-100 text-green-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Log a view action.
     */
    public static function logView(
        string $module,
        ?string $subjectType = null,
        ?int $subjectId = null,
        ?string $subjectName = null,
        ?array $requestData = null
    ): void {
        $user = auth()->user();
        
        if (!$user) {
            return;
        }

        // Clean up old logs first
        static::cleanup();

        static::create([
            'user_id' => $user->id,
            'user_type' => $user->user_type,
            'action' => 'view',
            'module' => $module,
            'route_name' => request()->route()?->getName(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'subject_name' => $subjectName,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'request_data' => $requestData,
            'response_code' => 200,
            'created_at' => now(),
        ]);
    }

    /**
     * Log an export/download action.
     */
    public static function logExport(string $module, string $filename): void
    {
        $user = auth()->user();
        
        if (!$user) {
            return;
        }

        static::cleanup();

        static::create([
            'user_id' => $user->id,
            'user_type' => $user->user_type,
            'action' => 'export',
            'module' => $module,
            'route_name' => request()->route()?->getName(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'subject_name' => $filename,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'response_code' => 200,
            'created_at' => now(),
        ]);
    }

    /**
     * Cleanup old audit logs (older than 3 days or exceeding 3MB).
     */
    public static function cleanup(): void
    {
        // Delete logs older than 3 days
        static::where('created_at', '<', now()->subDays(self::MAX_AGE_DAYS))->delete();

        // Check table size and delete oldest if exceeding limit
        $tableSize = static::getTableSizeInBytes();
        
        if ($tableSize > self::MAX_SIZE_BYTES) {
            // Delete oldest 10% of records
            $totalCount = static::count();
            $deleteCount = max(1, (int) ($totalCount * 0.1));
            
            $oldestIds = static::orderBy('created_at', 'asc')
                ->limit($deleteCount)
                ->pluck('id');
            
            static::whereIn('id', $oldestIds)->delete();
        }
    }

    /**
     * Get approximate table size in bytes.
     */
    public static function getTableSizeInBytes(): int
    {
        try {
            // For SQLite
            if (config('database.default') === 'sqlite') {
                $count = static::count();
                // Estimate ~500 bytes per row
                return $count * 500;
            }

            // For MySQL
            $result = DB::select("
                SELECT data_length + index_length AS size 
                FROM information_schema.tables 
                WHERE table_schema = ? AND table_name = 'audit_logs'
            ", [config('database.connections.mysql.database')]);

            return $result[0]->size ?? 0;
        } catch (\Exception $e) {
            // Fallback: estimate based on row count
            return static::count() * 500;
        }
    }

    /**
     * Get table size formatted.
     */
    public static function getTableSizeFormatted(): string
    {
        $bytes = static::getTableSizeInBytes();
        
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        
        return $bytes . ' bytes';
    }

    /**
     * Scope to get logs grouped by date.
     */
    public function scopeGroupedByDate($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
