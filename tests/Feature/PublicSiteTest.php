<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_home_page_renders_published_content_and_hides_drafts(): void
    {
        Article::factory()->create(['title_ar' => 'مقال منشور']);
        Article::factory()->draft()->create(['title_ar' => 'مسودة خاصة']);

        $response = $this->get('/ar');

        $response->assertOk()->assertSee('مقال منشور')->assertDontSee('مسودة خاصة');
    }

    public function test_published_article_renders_metadata_and_escaped_markdown(): void
    {
        $article = Article::factory()->create([
            'title_ar' => 'عنوان آمن',
            'body_ar' => "## مقدمة\n\n<script>alert('xss')</script> نص المقال",
            'seo_description' => 'وصف مخصص للبحث',
        ]);

        $response = $this->get(route('articles.show', $article));

        $response->assertOk()
            ->assertSee('application/ld+json', false)
            ->assertSee('وصف مخصص للبحث')
            ->assertDontSee("<script>alert('xss')</script>", false);
    }

    public function test_draft_article_returns_not_found(): void
    {
        $article = Article::factory()->draft()->create();

        $this->get(route('articles.show', $article))->assertNotFound();
    }

    public function test_articles_can_be_filtered_by_tag(): void
    {
        $laravelArticle = Article::factory()->create(['title_ar' => 'درس Laravel']);
        $wordpressArticle = Article::factory()->create(['title_ar' => 'درس WordPress']);
        $tag = Tag::factory()->create(['name' => 'Laravel', 'slug' => 'laravel']);
        $laravelArticle->tags()->attach($tag);

        $this->get(route('articles.index', ['tag' => 'laravel']))
            ->assertOk()
            ->assertViewHas('articles', fn ($articles) => $articles->count() === 1 && $articles->first()->is($laravelArticle))
            ->assertSee('درس Laravel')
            ->assertSee('#Laravel');

        $this->get(route('articles.show', $laravelArticle))->assertOk()->assertSee('#Laravel');
    }

    public function test_english_article_route_uses_english_content_and_language_switch(): void
    {
        $article = Article::factory()->create([
            'language' => 'both',
            'title_ar' => 'العنوان العربي',
            'title_en' => 'English title',
            'body_ar' => 'المحتوى العربي',
            'body_en' => 'English content',
        ]);

        $this->get(route('articles.show', ['locale' => 'en', 'article' => $article]))
            ->assertOk()
            ->assertSee('<html lang="en" dir="ltr">', false)
            ->assertSee('English title')
            ->assertSee('English content')
            ->assertSee(route('articles.show', ['locale' => 'ar', 'article' => $article]));
    }

    public function test_legacy_home_redirects_to_arabic_home(): void
    {
        $this->get('/')->assertRedirect('/ar');
    }
}
