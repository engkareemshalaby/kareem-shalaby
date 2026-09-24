<?php

namespace Tests\Feature\Admin;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_a_tag(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->post(route('admin.tags.store'), [
            'name' => 'Laravel',
            'slug' => 'laravel',
        ])->assertRedirect(route('admin.tags.index'));

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
}
