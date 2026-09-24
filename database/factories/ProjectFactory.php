<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_ar' => 'منصة تعليمية',
            'name_en' => fake()->unique()->words(3, true),
            'slug' => fake()->unique()->slug(3),
            'description_ar' => 'منصة مبنية لخدمة احتياج حقيقي.',
            'description_en' => fake()->paragraph(),
            'project_type' => 'تعليمي',
            'system_type' => 'LMS',
            'target_audience_ar' => 'الطلاب والمعلمون',
            'target_audience_en' => 'Students and teachers',
            'technologies' => ['Laravel', 'MySQL'],
            'screenshots' => [],
            'is_featured' => false,
            'is_visible' => true,
            'sort_order' => 0,
        ];
    }
}
