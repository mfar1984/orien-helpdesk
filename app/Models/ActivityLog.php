<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'old_values',
        'new_values',
        'subject_id',
        'subject_type',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject of the activity.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get action badge color.
     */
    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'login' => 'bg-green-100 text-green-600',
            'logout' => 'bg-gray-100 text-gray-600',
            'create' => 'bg-blue-100 text-blue-600',
            'update' => 'bg-yellow-100 text-yellow-600',
            'delete' => 'bg-red-100 text-red-600',
            'restore' => 'bg-purple-100 text-purple-600',
            'assign' => 'bg-indigo-100 text-indigo-600',
            'status_change' => 'bg-orange-100 text-orange-600',
            'priority_change' => 'bg-amber-100 text-amber-600',
            'password_change' => 'bg-pink-100 text-pink-600',
            'lock' => 'bg-red-100 text-red-600',
            'unlock' => 'bg-green-100 text-green-600',
            'suspend' => 'bg-red-100 text-red-600',
            'unsuspend' => 'bg-green-100 text-green-600',
            'failed_login' => 'bg-red-100 text-red-600',
            'register' => 'bg-teal-100 text-teal-600',
            'export' => 'bg-cyan-100 text-cyan-600',
            'reply' => 'bg-blue-100 text-blue-600',
            'force_delete' => 'bg-red-100 text-red-600',
            'empty_recycle_bin' => 'bg-red-100 text-red-600',
            '2fa_enable' => 'bg-green-100 text-green-600',
            '2fa_disable' => 'bg-yellow-100 text-yellow-600',
            '2fa_regenerate_codes' => 'bg-purple-100 text-purple-600',
            default => 'bg-gray-100 text-gray-600',
        };
    }

    /**
     * Get action icon.
     */
    public function getActionIconAttribute(): string
    {
        return match ($this->action) {
            'login' => 'login',
            'logout' => 'logout',
            'create' => 'add_circle',
            'update' => 'edit',
            'delete' => 'delete',
            'restore' => 'restore',
            'assign' => 'person_add',
            'status_change' => 'sync',
            'priority_change' => 'priority_high',
            'password_change' => 'key',
            'lock' => 'lock',
            'unlock' => 'lock_open',
            'suspend' => 'block',
            'unsuspend' => 'check_circle',
            'failed_login' => 'error',
            'register' => 'person_add',
            'export' => 'download',
            'reply' => 'reply',
            'force_delete' => 'delete_forever',
            'empty_recycle_bin' => 'delete_sweep',
            '2fa_enable' => 'security',
            '2fa_disable' => 'no_encryption',
            '2fa_regenerate_codes' => 'refresh',
            default => 'info',
        };
    }

    /**
     * Log an activity.
     */
    public static function log(
        string $action,
        string $module,
        string $description,
        ?Model $subject = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): self {
        $user = auth()->user();
        
        return self::create([
            'user_id' => $user?->id,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'subject_id' => $subject?->id,
            'subject_type' => $subject ? get_class($subject) : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Maximum storage size in bytes (3MB)
     */
    public const MAX_SIZE_BYTES = 3 * 1024 * 1024;

    /**
     * Get the table size in bytes.
     */
    public static function getTableSize(): int
    {
        $result = \DB::select("SELECT 
            (data_length + index_length) as size 
            FROM information_schema.tables 
            WHERE table_schema = ? AND table_name = 'activity_logs'", 
            [config('database.connections.mysql.database')]
        );

        return $result[0]->size ?? 0;
    }

    /**
     * Get formatted table size.
     */
    public static function getTableSizeFormatted(): string
    {
        $bytes = self::getTableSize();
        
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        
        return $bytes . ' bytes';
    }

    /**
     * Cleanup old logs to maintain size limit.
     */
    public static function cleanup(): void
    {
        // Delete logs when size exceeds limit
        $currentSize = self::getTableSize();
        
        if ($currentSize > self::MAX_SIZE_BYTES) {
            // Delete oldest 20% of logs
            $totalLogs = self::count();
            $deleteCount = (int) ($totalLogs * 0.2);
            
            if ($deleteCount > 0) {
                $oldestIds = self::orderBy('created_at', 'asc')
                    ->limit($deleteCount)
                    ->pluck('id');
                
                self::whereIn('id', $oldestIds)->delete();
            }
        }
    }
}
