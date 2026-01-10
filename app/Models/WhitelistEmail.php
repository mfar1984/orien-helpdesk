<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WhitelistEmail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'email',
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
     * Check if an email is whitelisted.
     */
    public static function isWhitelisted(string $email): bool
    {
        return static::where('email', strtolower($email))->exists();
    }
}
