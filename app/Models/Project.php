<?php

namespace App\Models;

use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name_ar', 'name_en', 'slug', 'description_ar', 'description_en', 'project_type', 'system_type', 'client', 'target_audience_ar', 'target_audience_en', 'technologies', 'cover_image', 'screenshots', 'website_url', 'repository_url', 'completed_at', 'is_featured', 'is_visible', 'sort_order'])]
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return ['technologies' => 'array', 'screenshots' => 'array', 'completed_at' => 'date', 'is_featured' => 'boolean', 'is_visible' => 'boolean'];
    }

    #[Scope]
    protected function visible(Builder $query): Builder
    {
        return $query->where('is_visible', true)->orderBy('sort_order')->latest('completed_at');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function name(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return $locale === 'en' ? ($this->name_en ?: $this->name_ar ?? '') : ($this->name_ar ?: $this->name_en ?? '');
    }

    public function description(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return $locale === 'en' ? ($this->description_en ?: $this->description_ar ?? '') : ($this->description_ar ?: $this->description_en ?? '');
    }

    public function targetAudience(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return $locale === 'en' ? ($this->target_audience_en ?: $this->target_audience_ar ?? '') : ($this->target_audience_ar ?: $this->target_audience_en ?? '');
    }
}
