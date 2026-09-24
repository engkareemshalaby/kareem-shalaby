<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(), 'language' => 'ar', 'title_ar' => fake()->sentence(),
            'slug' => fake()->unique()->slug(), 'excerpt_ar' => fake()->paragraph(),
            'body_ar' => fake()->paragraphs(4, true), 'status' => 'published',
            'is_featured' => false, 'published_at' => now()->subDay(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => ['status' => 'draft', 'published_at' => null]);
    }
}
