<?php

namespace Tests\Feature\Admin;

use App\Models\Experience;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ProfessionalProfileManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_can_create_an_experience_with_separate_highlights(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('admin.experiences.store'), [
            'role' => 'Software Engineer',
            'company' => 'Example Company',
            'start_date' => 'January 2025',
            'end_date' => 'Present',
            'highlights' => "First achievement\nSecond achievement",
            'sort_order' => 1,
            'is_visible' => '1',
        ]);

        $response->assertRedirect(route('admin.experiences.index'));
        $experience = Experience::where('company', 'Example Company')->firstOrFail();
        $this->assertSame(['First achievement', 'Second achievement'], $experience->highlights);
        $this->assertTrue($experience->is_visible);
    }

    public function test_admin_can_update_contact_information(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->put(route('admin.profile.update'), [
            'phone' => '+20 100 000 0000',
            'email' => 'kareem@example.com',
            'linkedin_url' => 'https://linkedin.com/in/kareem',
            'location' => 'Giza, Egypt',
            'professional_summary' => 'A concise professional summary.',
        ]);

        $response->assertRedirect();
        $this->assertSame('kareem@example.com', SiteSetting::value('email'));
        $this->assertSame('+20 100 000 0000', SiteSetting::value('phone'));
    }

    public function test_professional_page_escapes_experience_content(): void
    {
        Experience::factory()->create([
            'role' => '<script>alert("xss")</script>',
            'highlights' => ['Safe achievement'],
        ]);

        $response = $this->get(route('professional'));

        $response->assertOk()->assertDontSee('<script>alert("xss")</script>', false)->assertSee('Safe achievement');
    }
}
