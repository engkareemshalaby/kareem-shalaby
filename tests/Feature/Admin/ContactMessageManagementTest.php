<?php

namespace Tests\Feature\Admin;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ContactMessageManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_can_view_a_contact_message_and_mark_it_as_read(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $message = ContactMessage::factory()->create([
            'name' => 'عميل محتمل',
            'message' => 'تفاصيل الرسالة الخاصة بالمشروع',
            'ip_address' => '198.51.100.10',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.contact-messages.show', $message))
            ->assertOk()
            ->assertSee('عميل محتمل')
            ->assertSee('تفاصيل الرسالة الخاصة بالمشروع')
            ->assertSee('198.51.100.10');

        $this->assertNotNull($message->fresh()->read_at);
    }

    public function test_non_admin_cannot_view_contact_messages(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(route('admin.contact-messages.index'))
            ->assertForbidden();
    }

    public function test_admin_can_delete_a_contact_message(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $message = ContactMessage::factory()->create();

        $this->actingAs($admin)
            ->delete(route('admin.contact-messages.destroy', $message))
            ->assertRedirect(route('admin.contact-messages.index'));

        $this->assertModelMissing($message);
    }
}
