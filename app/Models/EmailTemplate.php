<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'subject',
        'type',
        'body',
        'description',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    /**
     * Get available template types.
     */
    public static function getTypes(): array
    {
        return [
            'notification' => 'Notification',
            'auto-reply' => 'Auto Reply',
            'escalation' => 'Escalation',
            'reminder' => 'Reminder',
            'welcome' => 'Welcome',
        ];
    }
}
