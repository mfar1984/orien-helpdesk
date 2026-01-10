<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BadWord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'word',
        'reason',
        'severity',
        'added_by',
        'status',
    ];

    /**
     * Get the user who added this bad word.
     */
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
