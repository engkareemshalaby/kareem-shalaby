<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Category;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::defaults(['locale' => 'ar']);
        Model::preventLazyLoading(! $this->app->isProduction());

        View::composer('layouts.app', function ($view): void {
            if (! Schema::hasTable('categories')) {
                return;
            }

            $view->with([
                'footerCategories' => Category::visible()->get(['name_ar', 'name_en', 'slug']),
                'footerArticles' => Article::published()->latest('published_at')->limit(3)->get(['title_ar', 'title_en', 'slug']),
                'contactSettings' => Schema::hasTable('site_settings') ? SiteSetting::query()->pluck('value', 'key') : collect(),
                'socialLinks' => Schema::hasTable('social_links') ? SocialLink::visible()->get() : collect(),
            ]);
        });
    }
}
