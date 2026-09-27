<?php

namespace Tests\Feature\Admin;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_user_is_forbidden_from_admin_dashboard(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_admin_can_create_published_arabic_article(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create();
        $tag = Tag::factory()->create(['name' => 'Laravel', 'slug' => 'laravel']);

        $response = $this->actingAs($admin)->post(route('admin.articles.store'), [
            'category_id' => $category->id,
            'language' => 'ar',
            'title_ar' => 'مقال للاختبار',
            'slug' => 'tested-article',
            'excerpt_ar' => 'ملخص واضح',
            'body_ar' => 'محتوى المقال المفصل',
            'status' => 'published',
            'is_featured' => '1',
            'tags' => [$tag->id],
            'new_tag_name' => 'PHP',
            'new_tag_slug' => 'php',
            'cover_image' => UploadedFile::fake()->image('cover.webp', 1600, 900),
        ]);

        $article = Article::where('slug', 'tested-article')->firstOrFail();
        $response->assertRedirect();
        $this->assertDatabaseHas('articles', [
            'slug' => 'tested-article',
            'status' => 'published',
            'is_featured' => true,
        ]);
        $this->assertDatabaseHas('article_tag', ['tag_id' => $tag->id]);
        $this->assertDatabaseHas('tags', ['name' => 'PHP', 'slug' => 'php']);
        $this->assertTrue($article->tags()->where('slug', 'php')->exists());
        Storage::disk('public')->assertExists($article->cover_image);

        $this->get(route('articles.index'))
            ->assertOk()
            ->assertSee(asset('storage/'.$article->cover_image), false);
    }

    public function test_updating_article_cover_replaces_the_old_file(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create();
        $article = Article::factory()->create([
            'category_id' => $category->id,
            'cover_image' => UploadedFile::fake()->image('old.webp')->store('articles/covers', 'public'),
        ]);
        $oldCoverImage = $article->cover_image;

        $response = $this->actingAs($admin)->put(route('admin.articles.update', $article), [
            'category_id' => $category->id,
            'language' => 'ar',
            'title_ar' => $article->title_ar,
            'slug' => $article->slug,
            'body_ar' => $article->body_ar,
            'status' => 'draft',
            'cover_image' => UploadedFile::fake()->image('new.webp', 1600, 900),
        ]);

        $response->assertRedirect();
        Storage::disk('public')->assertMissing($oldCoverImage);
        Storage::disk('public')->assertExists($article->fresh()->cover_image);
    }

    public function test_article_upload_rejects_non_image_file(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.articles.store'), [
            'category_id' => $category->id,
            'language' => 'ar',
            'title_ar' => 'Invalid cover',
            'slug' => 'invalid-cover',
            'body_ar' => 'Article body',
            'status' => 'draft',
            'cover_image' => UploadedFile::fake()->create('payload.php', 10, 'application/x-php'),
        ]);

        $response->assertSessionHasErrors('cover_image');
        $this->assertDatabaseMissing('articles', ['slug' => 'invalid-cover']);
    }

    public function test_article_creation_rejects_missing_arabic_content(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.articles.store'), [
            'category_id' => $category->id,
            'language' => 'ar',
            'slug' => 'invalid-article',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors(['title_ar', 'body_ar']);
        $this->assertDatabaseMissing('articles', ['slug' => 'invalid-article']);
    }
}
