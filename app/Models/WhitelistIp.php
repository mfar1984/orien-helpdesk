<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WhitelistIp extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ip_address',
        'reason',
        'added_by',
    ];

    /**
     * Get the user who added this whitelist.
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Check if an IP is whitelisted.
     */
    public static function isWhitelisted(string $ip): bool
    {
        return static::where('ip_address', $ip)->exists();
    }
}
