<?php

namespace App\Models;

use App\Notifications\CustomResetPasswordNotification;
use App\Services\HashLinkService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'hash_link',
        'role_id',
        'user_type',
        'first_name',
        'last_name',
        'phone',
        'mobile',
        'address',
        'city',
        'state',
        'postcode',
        'country',
        'company_name',
        'company_registration',
        'company_phone',
        'company_email',
        'company_address',
        'company_website',
        'industry',
        'status',
        'last_login_at',
        'login_attempts',
        'locked_at',
        'suspended_at',
        'suspended_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'id',
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
            'locked_at' => 'datetime',
            'suspended_at' => 'datetime',
            'login_attempts' => 'integer',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->hash_link)) {
                $user->hash_link = HashLinkService::generate();
            }
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'hash_link';
    }

    /**
     * Get the role that the user belongs to.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Alias for role relationship (for convenience).
     */
    public function roles()
    {
        return $this->role();
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return $this->role?->hasPermission($permission) ?? false;
    }

    /**
     * Check if user has ANY permission for a module.
     */
    public function hasAnyPermissionFor(string $module): bool
    {
        return $this->role?->hasAnyPermissionFor($module) ?? false;
    }

    /**
     * Check if user has ANY permission matching patterns.
     */
    public function hasAnyPermissionMatching(array $patterns): bool
    {
        return $this->role?->hasAnyPermissionMatching($patterns) ?? false;
    }

    /**
     * Check if user is Administrator.
     * Uses user_type column (permanent, not dependent on role)
     */
    public function isAdmin(): bool
    {
        return $this->user_type === 'administrator';
    }

    /**
     * Check if user is Agent.
     * Uses user_type column (permanent, not dependent on role)
     */
    public function isAgent(): bool
    {
        return $this->user_type === 'agent';
    }

    /**
     * Check if user is Customer.
     * Uses user_type column (permanent, not dependent on role)
     */
    public function isCustomer(): bool
    {
        return $this->user_type === 'customer';
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        // Case-insensitive role check (also check slug)
        $role = $this->role;
        if (!$role) {
            return false;
        }
        
        $roleNameLower = strtolower($roleName);
        return strtolower($role->name) === $roleNameLower 
            || (isset($role->slug) && strtolower($role->slug) === $roleNameLower);
    }

    /**
     * Get full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        if ($this->first_name || $this->last_name) {
            return trim($this->first_name . ' ' . $this->last_name);
        }
        return $this->name;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    /**
     * Get tickets assigned to this user (for agents).
     */
    public function assignedTickets()
    {
        return $this->belongsToMany(\App\Models\Ticket::class, 'ticket_assignments', 'user_id', 'ticket_id')
            ->withTimestamps();
    }

    /**
     * Get the user's two factor recovery codes.
     *
     * @return array
     */
    public function recoveryCodes(): array
    {
        return $this->two_factor_recovery_codes 
            ? json_decode(decrypt($this->two_factor_recovery_codes), true) 
            : [];
    }

    /**
     * Replace the user's two factor recovery codes.
     *
     * @param  array  $codes
     * @return void
     */
    public function replaceRecoveryCodes(array $codes): void
    {
        $this->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode($codes)),
        ])->save();
    }

    /**
     * Determine if two factor authentication is enabled.
     *
     * @return bool
     */
    public function twoFactorEnabled(): bool
    {
        return $this->two_factor_enabled && !is_null($this->two_factor_secret);
    }

    /**
     * Check if user account is locked.
     */
    public function isLocked(): bool
    {
        if (!$this->locked_at) {
            return false;
        }
        
        $lockoutDuration = (int) setting('lockout_duration', 15);
        $lockExpiry = $this->locked_at->copy()->addMinutes($lockoutDuration);
        
        // If lock has expired, auto-unlock
        if (now()->greaterThan($lockExpiry)) {
            $this->unlock();
            return false;
        }
        
        return true;
    }

    /**
     * Check if user account is suspended.
     */
    public function isSuspended(): bool
    {
        return !is_null($this->suspended_at);
    }

    /**
     * Get remaining lockout time in minutes.
     */
    public function getLockoutRemainingMinutes(): int
    {
        if (!$this->locked_at) {
            return 0;
        }
        
        $lockoutDuration = (int) setting('lockout_duration', 15);
        $lockExpiry = $this->locked_at->copy()->addMinutes($lockoutDuration);
        
        if (now()->greaterThan($lockExpiry)) {
            return 0;
        }
        
        return (int) now()->diffInMinutes($lockExpiry);
    }

    /**
     * Lock the user account.
     */
    public function lock(): void
    {
        $this->update([
            'locked_at' => now(),
            'login_attempts' => 0,
        ]);
        
        // Force logout user by invalidating their sessions
        $this->invalidateSessions();
    }

    /**
     * Unlock the user account.
     */
    public function unlock(): void
    {
        $this->update([
            'locked_at' => null,
            'login_attempts' => 0,
        ]);
    }

    /**
     * Suspend the user account.
     */
    public function suspend(?string $reason = null): void
    {
        $this->update([
            'suspended_at' => now(),
            'suspended_reason' => $reason,
            'status' => 'suspended',
        ]);
        
        // Force logout user by invalidating their sessions
        $this->invalidateSessions();
    }

    /**
     * Unsuspend the user account.
     */
    public function unsuspend(): void
    {
        $this->update([
            'suspended_at' => null,
            'suspended_reason' => null,
            'status' => 'active',
        ]);
    }

    /**
     * Invalidate all sessions for this user (force logout).
     */
    public function invalidateSessions(): void
    {
        // Delete all sessions for this user from database
        \DB::table('sessions')
            ->where('user_id', $this->id)
            ->delete();
    }

    /**
     * Increment login attempts and lock if exceeded.
     */
    public function incrementLoginAttempts(): int
    {
        $this->increment('login_attempts');
        $maxAttempts = setting('max_login_attempts', 5);
        
        if ($this->login_attempts >= $maxAttempts) {
            $this->lock();
        }
        
        return $maxAttempts - $this->login_attempts;
    }

    /**
     * Reset login attempts on successful login.
     */
    public function resetLoginAttempts(): void
    {
        $this->update([
            'login_attempts' => 0,
            'locked_at' => null,
        ]);
    }

    /**
     * Get the effective status (priority: suspended > locked > active/inactive).
     */
    public function getEffectiveStatusAttribute(): string
    {
        if ($this->isSuspended()) {
            return 'suspended';
        }
        
        if ($this->isLocked()) {
            return 'locked';
        }
        
        return $this->status ?? 'active';
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->effective_status) {
            'active' => 'green',
            'locked' => 'orange',
            'suspended' => 'red',
            'inactive' => 'gray',
            default => 'gray',
        };
    }
}
