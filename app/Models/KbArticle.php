<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class KbArticle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'views',
        'read_time',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views' => 'integer',
        'read_time' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
            // Calculate read time based on content (avg 200 words per minute)
            if (empty($article->read_time)) {
                $wordCount = str_word_count(strip_tags($article->content));
                $article->read_time = max(1, ceil($wordCount / 200));
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title') && !$article->isDirty('slug')) {
                $article->slug = Str::slug($article->title);
            }
            // Recalculate read time if content changed
            if ($article->isDirty('content')) {
                $wordCount = str_word_count(strip_tags($article->content));
                $article->read_time = max(1, ceil($wordCount / 200));
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(KbCategory::class, 'category_id');
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePopular($query, $limit = 5)
    {
        return $query->published()->orderByDesc('views')->limit($limit);
    }

    public function scopeRecent($query, $limit = 5)
    {
        return $query->published()->orderByDesc('published_at')->limit($limit);
    }

    public function getFormattedReadTimeAttribute()
    {
        return $this->read_time . ' min read';
    }

    public function isPublished()
    {
        return $this->status === 'published';
    }
}
