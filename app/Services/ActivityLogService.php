<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    /**
     * Send activity notification to Telegram.
     */
    private static function notifyTelegram(string $action, string $module, string $description, ?array $context = null): void
    {
        // Send to Telegram asynchronously (non-blocking)
        try {
            TelegramService::sendActivityNotification($action, $module, $description, $context);
        } catch (\Exception $e) {
            // Silently fail - don't break the main flow
            \Log::debug('Telegram notification failed: ' . $e->getMessage());
        }
    }

    /**
     * Log a user login.
     */
    public static function logLogin(): void
    {
        ActivityLog::log(
            'login',
            'auth',
            'User logged in successfully'
        );
        
        self::notifyTelegram('login', 'auth', 'User logged in: ' . (auth()->user()->name ?? 'Unknown'));
    }

    /**
     * Log a user logout.
     */
    public static function logLogout(): void
    {
        $userName = auth()->user()->name ?? 'Unknown';
        
        ActivityLog::log(
            'logout',
            'auth',
            'User logged out'
        );
        
        self::notifyTelegram('logout', 'auth', "User logged out: {$userName}");
    }

    /**
     * Log a failed login attempt.
     */
    public static function logFailedLogin(string $email): void
    {
        ActivityLog::create([
            'user_id' => null,
            'action' => 'failed_login',
            'module' => 'auth',
            'description' => "Failed login attempt for: {$email}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        self::notifyTelegram('failed_login', 'auth', "Failed login attempt for: {$email}");
    }

    /**
     * Log a password change.
     */
    public static function logPasswordChange(): void
    {
        $userName = auth()->user()->name ?? 'Unknown';
        
        ActivityLog::log(
            'password_change',
            'auth',
            'Password changed successfully'
        );
        
        self::notifyTelegram('password_change', 'auth', "Password changed by: {$userName}");
    }

    /**
     * Log a model creation.
     */
    public static function logCreate(Model $model, string $module, ?string $description = null): void
    {
        $modelName = class_basename($model);
        $desc = $description ?? "{$modelName} created: " . ($model->name ?? $model->title ?? $model->subject ?? "ID #{$model->id}");
        
        ActivityLog::log(
            'create',
            $module,
            $desc,
            $model,
            null,
            $model->toArray()
        );
        
        // Build context for Telegram
        $context = [];
        if ($model->ticket_number ?? null) {
            $context['ticket_number'] = $model->ticket_number;
        }
        if ($model->subject ?? null) {
            $context['subject'] = $model->subject;
        }
        
        self::notifyTelegram('create', $module, $desc, $context ?: null);
    }

    /**
     * Log a model update.
     */
    public static function logUpdate(Model $model, string $module, array $oldValues, ?string $description = null): void
    {
        $modelName = class_basename($model);
        $desc = $description ?? "{$modelName} updated: " . ($model->name ?? $model->title ?? $model->subject ?? "ID #{$model->id}");
        
        // Filter only changed values
        $changedOld = [];
        $changedNew = [];
        foreach ($model->getDirty() as $key => $newValue) {
            if (isset($oldValues[$key]) && $oldValues[$key] !== $newValue) {
                $changedOld[$key] = $oldValues[$key];
                $changedNew[$key] = $newValue;
            }
        }
        
        ActivityLog::log(
            'update',
            $module,
            $desc,
            $model,
            $changedOld ?: $oldValues,
            $changedNew ?: $model->getDirty()
        );
        
        self::notifyTelegram('update', $module, $desc);
    }

    /**
     * Log a model deletion.
     */
    public static function logDelete(Model $model, string $module, ?string $description = null): void
    {
        $modelName = class_basename($model);
        $desc = $description ?? "{$modelName} deleted: " . ($model->name ?? $model->title ?? $model->subject ?? "ID #{$model->id}");
        
        ActivityLog::log(
            'delete',
            $module,
            $desc,
            $model,
            $model->toArray()
        );
        
        self::notifyTelegram('delete', $module, $desc);
    }

    /**
     * Log a model restore.
     */
    public static function logRestore(Model $model, string $module, ?string $description = null): void
    {
        $modelName = class_basename($model);
        $desc = $description ?? "{$modelName} restored: " . ($model->name ?? $model->title ?? $model->subject ?? "ID #{$model->id}");
        
        ActivityLog::log(
            'restore',
            $module,
            $desc,
            $model
        );
        
        self::notifyTelegram('restore', $module, $desc);
    }

    /**
     * Log a ticket assignment.
     */
    public static function logAssign(Model $ticket, array $assignees): void
    {
        $assigneeNames = implode(', ', $assignees);
        $desc = "Ticket #{$ticket->ticket_number} assigned to: {$assigneeNames}";
        
        ActivityLog::log(
            'assign',
            'tickets',
            $desc,
            $ticket,
            null,
            ['assignees' => $assignees]
        );
        
        self::notifyTelegram('assign', 'tickets', $desc, [
            'ticket_number' => $ticket->ticket_number,
            'subject' => $ticket->subject,
        ]);
    }

    /**
     * Log a status change.
     */
    public static function logStatusChange(Model $model, string $module, string $oldStatus, string $newStatus): void
    {
        $modelName = class_basename($model);
        $identifier = $model->ticket_number ?? $model->name ?? $model->title ?? "ID #{$model->id}";
        $desc = "{$modelName} #{$identifier} status changed from '{$oldStatus}' to '{$newStatus}'";
        
        ActivityLog::log(
            'status_change',
            $module,
            $desc,
            $model,
            ['status' => $oldStatus],
            ['status' => $newStatus]
        );
        
        $context = ['status' => $newStatus];
        if ($model->ticket_number ?? null) {
            $context['ticket_number'] = $model->ticket_number;
        }
        
        self::notifyTelegram('status_change', $module, $desc, $context);
    }

    /**
     * Log user lock.
     */
    public static function logLock(Model $user): void
    {
        $desc = "User locked: {$user->name} ({$user->email})";
        
        ActivityLog::log(
            'lock',
            'users',
            $desc,
            $user
        );
        
        self::notifyTelegram('lock', 'users', $desc);
    }

    /**
     * Log user unlock.
     */
    public static function logUnlock(Model $user): void
    {
        $desc = "User unlocked: {$user->name} ({$user->email})";
        
        ActivityLog::log(
            'unlock',
            'users',
            $desc,
            $user
        );
        
        self::notifyTelegram('unlock', 'users', $desc);
    }

    /**
     * Log user suspend.
     */
    public static function logSuspend(Model $user, ?string $reason = null): void
    {
        $desc = "User suspended: {$user->name} ({$user->email})";
        if ($reason) {
            $desc .= " - Reason: {$reason}";
        }
        
        ActivityLog::log(
            'suspend',
            'users',
            $desc,
            $user
        );
        
        self::notifyTelegram('suspend', 'users', $desc);
    }

    /**
     * Log user unsuspend.
     */
    public static function logUnsuspend(Model $user): void
    {
        $desc = "User unsuspended: {$user->name} ({$user->email})";
        
        ActivityLog::log(
            'unsuspend',
            'users',
            $desc,
            $user
        );
        
        self::notifyTelegram('unsuspend', 'users', $desc);
    }

    /**
     * Log an export action.
     */
    public static function logExport(string $module, string $type, ?int $count = null): void
    {
        $desc = "Exported {$type} from {$module}";
        if ($count !== null) {
            $desc .= " ({$count} records)";
        }
        
        ActivityLog::log(
            'export',
            $module,
            $desc
        );
        
        self::notifyTelegram('export', $module, $desc);
    }

    /**
     * Log a force delete (permanent delete).
     */
    public static function logForceDelete(Model $model, string $module, ?string $description = null): void
    {
        $modelName = class_basename($model);
        $desc = $description ?? "{$modelName} permanently deleted: " . ($model->name ?? $model->title ?? $model->subject ?? "ID #{$model->id}");
        
        ActivityLog::log(
            'force_delete',
            $module,
            $desc,
            $model,
            $model->toArray()
        );
        
        self::notifyTelegram('force_delete', $module, $desc);
    }

    /**
     * Log a custom action.
     */
    public static function log(string $action, string $module, string $description, ?Model $subject = null): void
    {
        ActivityLog::log($action, $module, $description, $subject);
        
        self::notifyTelegram($action, $module, $description);
    }

    /**
     * Log a ticket reply.
     */
    public static function logReply(Model $ticket, string $replyContent): void
    {
        $desc = "Reply added to Ticket #{$ticket->ticket_number}";
        
        ActivityLog::log(
            'reply',
            'tickets',
            $desc,
            $ticket
        );
        
        // Send detailed ticket notification
        TelegramService::sendTicketNotification('replied', [
            'ticket_number' => $ticket->ticket_number,
            'subject' => $ticket->subject,
            'status' => $ticket->status->name ?? 'Unknown',
            'priority' => $ticket->priority->name ?? 'Unknown',
            'message' => $replyContent,
            'user_name' => auth()->user()->name ?? 'Unknown',
            'url' => route('tickets.show', $ticket->id),
        ]);
    }

    /**
     * Log empty recycle bin action.
     */
    public static function logEmptyRecycleBin(int $totalDeleted): void
    {
        $desc = "Recycle bin emptied - {$totalDeleted} items permanently deleted";
        
        ActivityLog::log(
            'empty_recycle_bin',
            'recycle_bin',
            $desc
        );
        
        self::notifyTelegram('empty_recycle_bin', 'recycle_bin', $desc);
    }
}
