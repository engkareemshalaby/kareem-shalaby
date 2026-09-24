<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
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
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('articles', [
            'slug' => 'tested-article',
            'status' => 'published',
            'is_featured' => true,
        ]);
        $this->assertDatabaseHas('article_tag', ['tag_id' => $tag->id]);
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
