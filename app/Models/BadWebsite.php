<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BadWebsite extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'url',
        'reason',
        'severity',
        'added_by',
        'status',
    ];

    /**
     * Get the user who added this bad website.
     */
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
