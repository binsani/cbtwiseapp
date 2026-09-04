<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $fillable = [
        'category_id', 'author_id', 'author_bio', 'title', 'slug', 'excerpt', 'body', 'toc_json',
        'featured_image', 'meta_description', 'tags', 'reading_time', 'view_count', 'is_published', 'newsletter_cta_enabled', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published'           => 'boolean',
            'newsletter_cta_enabled' => 'boolean',
            'published_at'           => 'datetime',
            'tags'                   => 'array',
            'toc_json'               => 'array',
            'reading_time'           => 'integer',
            'view_count'             => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            if ($post->reading_time <= 1) {
                $wordCount = str_word_count(strip_tags($post->body));
                $post->reading_time = max(1, (int) ceil($wordCount / 200));
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getRenderedBodyAttribute(): string
    {
        return Str::markdown($this->body, [
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
        ]);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->where('published_at', '<=', now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
