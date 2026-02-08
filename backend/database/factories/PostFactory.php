<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Factory for generating sample posts
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $title = fake()->sentence(6, true);
        $status = fake()->randomElement(['draft', 'published']);
        
        return [
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title),
            'content' => fake()->paragraphs(8, true),
            'excerpt' => fake()->paragraph(3),
            'featured_image' => fake()->imageUrl(800, 400, 'technology'),
            'status' => $status,
            'published_at' => $status === 'published' ? fake()->dateTimeBetween('-1 year', 'now') : null,
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
        ];
    }

    /**
     * Indicate that the post is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Indicate that the post is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }
}