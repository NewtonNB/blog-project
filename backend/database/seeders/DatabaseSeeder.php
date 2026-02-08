<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;

/**
 * Main database seeder
 * Seeds the database with sample data for development
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample users
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'bio' => 'Full-stack developer and tech enthusiast',
        ]);

        User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'bio' => 'Frontend developer specializing in React',
        ]);

        // Seed categories
        $this->call(CategorySeeder::class);

        // Create additional sample users
        User::factory(3)->create();

        // Create sample posts
        $users = User::all();
        $categories = Category::all();

        foreach ($users as $user) {
            Post::factory(rand(2, 5))->create([
                'user_id' => $user->id,
                'category_id' => $categories->random()->id,
            ]);
        }
    }
}
