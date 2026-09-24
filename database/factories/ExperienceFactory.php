<?php

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role' => fake()->jobTitle(),
            'company' => fake()->company(),
            'location' => fake()->city(),
            'start_date' => 'January 2024',
            'end_date' => 'Present',
            'highlights' => [fake()->sentence()],
            'is_visible' => true,
            'sort_order' => 1,
        ];
    }
}
