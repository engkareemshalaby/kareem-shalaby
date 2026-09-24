<?php

namespace App\Models;

use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['category_id', 'language', 'title_ar', 'title_en', 'slug', 'excerpt_ar', 'excerpt_en', 'body_ar', 'body_en', 'cover_image', 'status', 'is_featured', 'seo_title', 'seo_description', 'seo_keywords', 'published_at'])]
class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return ['is_featured' => 'boolean', 'seo_keywords' => 'array', 'published_at' => 'datetime'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query->where('status', 'published')->where('published_at', '<=', now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function title(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return $locale === 'en' ? ($this->title_en ?: $this->title_ar ?? '') : ($this->title_ar ?: $this->title_en ?? '');
    }

    public function excerpt(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return $locale === 'en' ? ($this->excerpt_en ?: $this->excerpt_ar ?? '') : ($this->excerpt_ar ?: $this->excerpt_en ?? '');
    }

    public function body(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return $locale === 'en' ? ($this->body_en ?: $this->body_ar ?? '') : ($this->body_ar ?: $this->body_en ?? '');
    }
}
