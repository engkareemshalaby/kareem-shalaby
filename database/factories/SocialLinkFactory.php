<?php

namespace Database\Factories;

use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SocialLink>
 */
class SocialLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'platform' => 'github',
            'label' => 'GitHub',
            'url' => 'https://github.com/'.fake()->unique()->userName(),
            'is_visible' => true,
            'sort_order' => 0,
        ];
    }
}
