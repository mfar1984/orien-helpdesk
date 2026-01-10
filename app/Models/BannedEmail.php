<?php

namespace App\Models;

use App\Services\ContentFilterService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BannedEmail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'email',
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
     * Check if an email is banned (supports wildcards like *@spam.com).
     */
    public static function isBanned(string $email): bool
    {
        $result = ContentFilterService::isEmailBanned($email);
        return $result['banned'];
    }

    /**
     * Get ban reason for an email (supports wildcards).
     */
    public static function getBanReason(string $email): ?string
    {
        $result = ContentFilterService::isEmailBanned($email);
        return $result['banned'] ? ($result['reason'] ?? null) : null;
    }
    
    /**
     * Check if this pattern is a wildcard pattern.
     */
    public function isWildcard(): bool
    {
        return strpos($this->email, '*') !== false || strpos($this->email, '?') !== false;
    }
}
