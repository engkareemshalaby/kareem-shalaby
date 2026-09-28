<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_contact_page_displays_contact_details_social_links_and_logo(): void
    {
        SiteSetting::create(['key' => 'phone', 'value' => '+20 100 000 0000', 'type' => 'text']);
        SiteSetting::create(['key' => 'phone_secondary', 'value' => '+20 111 111 1111', 'type' => 'text']);
        SocialLink::factory()->create(['label' => 'GitHub', 'url' => 'https://github.com/example']);

        $this->get(route('contact.create'))
            ->assertOk()
            ->assertSee('+20 100 000 0000')
            ->assertSee('+20 111 111 1111')
            ->assertSee('GitHub')
            ->assertSee(asset('ks.png'));
    }

    public function test_message_is_the_only_required_contact_field(): void
    {
        $response = $this
            ->withServerVariables(['REMOTE_ADDR' => '203.0.113.25'])
            ->withHeader('User-Agent', 'Contact test browser')
            ->withHeader('Referer', 'https://example.test/ar/articles')
            ->post(route('contact.store'), ['message' => 'أرغب في مناقشة مشروع برمجي جديد.']);

        $response
            ->assertRedirect()
            ->assertSessionHas('contact_success');
        $this->assertDatabaseHas('contact_messages', [
            'name' => null,
            'email' => null,
            'phone' => null,
            'subject' => null,
            'message' => 'أرغب في مناقشة مشروع برمجي جديد.',
            'ip_address' => '203.0.113.25',
            'user_agent' => 'Contact test browser',
            'referrer' => 'https://example.test/ar/articles',
        ]);
    }

    public function test_contact_form_rejects_an_empty_message(): void
    {
        $this->post(route('contact.store'), [
            'name' => 'Kareem',
            'email' => 'kareem@example.com',
        ])->assertSessionHasErrors('message');

        $this->assertSame(0, ContactMessage::count());
    }
}
