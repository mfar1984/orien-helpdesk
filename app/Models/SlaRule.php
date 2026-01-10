<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SlaRule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'response_time',
        'resolution_time',
        'priority_id',
        'category_id',
        'sort_order',
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

    public function priority()
    {
        return $this->belongsTo(TicketPriority::class, 'priority_id');
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    /**
     * Format response time for display.
     */
    public function getFormattedResponseTimeAttribute(): string
    {
        return $this->formatMinutes($this->response_time);
    }

    /**
     * Format resolution time for display.
     */
    public function getFormattedResolutionTimeAttribute(): string
    {
        return $this->formatMinutes($this->resolution_time);
    }

    private function formatMinutes(int $minutes): string
    {
        if ($minutes < 60) {
            return $minutes . ' min';
        }
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        if ($mins === 0) {
            return $hours . ' hr';
        }
        return $hours . ' hr ' . $mins . ' min';
    }
}
