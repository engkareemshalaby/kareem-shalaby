<?php

namespace Tests\Feature\Admin;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_can_create_project_with_cover_and_screenshots(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('admin.projects.store'), [
            'name_ar' => 'منصة تعليمية',
            'name_en' => 'Learning Platform',
            'slug' => 'learning-platform',
            'description_ar' => 'وصف المشروع',
            'project_type' => 'تعليمي',
            'system_type' => 'LMS',
            'target_audience_ar' => 'الطلاب والمعلمون',
            'technologies' => 'Laravel, Livewire, MySQL',
            'cover_image' => UploadedFile::fake()->image('cover.webp'),
            'screenshots' => [UploadedFile::fake()->image('dashboard.png'), UploadedFile::fake()->image('courses.jpg')],
            'website_url' => 'https://example.com',
            'is_visible' => '1',
            'is_featured' => '1',
            'sort_order' => 1,
        ]);

        $project = Project::where('slug', 'learning-platform')->firstOrFail();

        $response->assertRedirect(route('admin.projects.edit', $project));
        $this->assertSame(['Laravel', 'Livewire', 'MySQL'], $project->technologies);
        $this->assertCount(2, $project->screenshots);
        Storage::disk('public')->assertExists($project->cover_image);
        Storage::disk('public')->assertExists($project->screenshots);
    }

    public function test_project_upload_rejects_non_image_file(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('admin.projects.store'), [
            'name_ar' => 'مشروع غير صالح',
            'slug' => 'invalid-project',
            'cover_image' => UploadedFile::fake()->create('payload.php', 10, 'application/x-php'),
            'sort_order' => 0,
        ]);

        $response->assertSessionHasErrors('cover_image');
        $this->assertDatabaseMissing('projects', ['slug' => 'invalid-project']);
    }

    public function test_hidden_project_is_not_publicly_accessible(): void
    {
        $project = Project::factory()->create(['is_visible' => false]);

        $this->get(route('projects.show', $project))->assertNotFound();
    }
}
