<?php

namespace App\Models;

use App\Services\ContentFilterService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BannedIp extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ip_address',
        'reason',
        'added_by',
    ];

    /**
     * Get the user who added this ban.
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Check if an IP is banned (supports wildcards like 192.168.1.*).
     */
    public static function isBanned(string $ip): bool
    {
        $result = ContentFilterService::isIpBanned($ip);
        return $result['banned'];
    }

    /**
     * Get ban reason for an IP (supports wildcards).
     */
    public static function getBanReason(string $ip): ?string
    {
        $result = ContentFilterService::isIpBanned($ip);
        return $result['banned'] ? ($result['reason'] ?? null) : null;
    }
    
    /**
     * Check if this pattern is a wildcard pattern.
     */
    public function isWildcard(): bool
    {
        return strpos($this->ip_address, '*') !== false || strpos($this->ip_address, '?') !== false;
    }
}
