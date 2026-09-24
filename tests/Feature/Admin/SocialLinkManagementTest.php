<?php

namespace Tests\Feature\Admin;

use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class SocialLinkManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_can_create_and_hide_social_link(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->post(route('admin.social-links.store'), [
            'platform' => 'github',
            'label' => 'GitHub',
            'url' => 'https://github.com/kareem',
            'is_visible' => '1',
            'sort_order' => 2,
        ])->assertRedirect(route('admin.social-links.index'));

        $link = SocialLink::where('platform', 'github')->firstOrFail();

        $this->actingAs($admin)->put(route('admin.social-links.update', $link), [
            'platform' => 'github',
            'label' => 'GitHub',
            'url' => 'https://github.com/kareem',
            'sort_order' => 2,
        ])->assertRedirect();

        $this->assertFalse($link->fresh()->is_visible);
    }

    public function test_visible_social_link_is_rendered_in_footer(): void
    {
        SocialLink::factory()->create(['label' => 'YouTube', 'url' => 'https://youtube.com/@kareem']);

        $this->get(route('home'))->assertSee('YouTube')->assertSee('https://youtube.com/@kareem');
    }
}
