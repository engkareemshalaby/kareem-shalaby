<?php

namespace Tests\Feature\Admin;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_multiple_tags_then_update_and_delete_one(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->post(route('admin.tags.store'), [
            'tags' => [
                ['name' => 'Laravel', 'slug' => 'laravel'],
                ['name' => 'Architecture', 'slug' => 'architecture'],
                ['name' => 'PHP', 'slug' => 'php'],
            ],
        ])->assertRedirect(route('admin.tags.index'));

        $this->assertDatabaseHas('tags', ['name' => 'Architecture', 'slug' => 'architecture']);
        $this->assertDatabaseHas('tags', ['name' => 'PHP', 'slug' => 'php']);

        $tag = Tag::where('slug', 'laravel')->firstOrFail();

        $this->actingAs($admin)->put(route('admin.tags.update', $tag), [
            'name' => 'Laravel Framework',
            'slug' => 'laravel-framework',
        ])->assertRedirect();

        $tag->refresh();
        $this->assertSame('Laravel Framework', $tag->name);

        $this->actingAs($admin)->delete(route('admin.tags.destroy', $tag))->assertRedirect(route('admin.tags.index'));
        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }

    public function test_bulk_creation_rejects_duplicate_tags_in_the_same_request(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('admin.tags.store'), [
            'tags' => [
                ['name' => 'Laravel', 'slug' => 'laravel'],
                ['name' => 'Laravel', 'slug' => 'laravel-framework'],
            ],
        ]);

        $response->assertSessionHasErrors('tags.1.name');
        $this->assertDatabaseCount('tags', 0);
    }
}
