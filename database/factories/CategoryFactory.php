<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_ar' => fake()->words(2, true), 'name_en' => fake()->words(2, true),
            'slug' => fake()->unique()->slug(), 'color' => '#167D78', 'is_professional' => false,
            'is_visible' => true, 'sort_order' => 1,
        ];
    }
}
