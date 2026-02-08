<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeder for creating sample categories
 */
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'Latest trends and news in technology',
            ],
            [
                'name' => 'Web Development',
                'slug' => 'web-development',
                'description' => 'Web development tutorials and best practices',
            ],
            [
                'name' => 'Mobile Development',
                'slug' => 'mobile-development',
                'description' => 'Mobile app development guides and tips',
            ],
            [
                'name' => 'Programming',
                'slug' => 'programming',
                'description' => 'Programming languages and coding techniques',
            ],
            [
                'name' => 'Design',
                'slug' => 'design',
                'description' => 'UI/UX design and visual design principles',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}