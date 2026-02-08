<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Resources\CategoryResource;

/**
 * CategoryController handles CRUD operations for blog categories
 * Provides API endpoints for managing categories
 */
class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index(): JsonResponse
    {
        try {
            $categories = Category::withCount('posts')->get();

            return response()->json([
                'success' => true,
                'data' => CategoryResource::collection($categories)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request): JsonResponse
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Generate unique slug from name
            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $counter = 1;

            // Ensure slug is unique
            while (Category::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Create the category
            $category = Category::create([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => new CategoryResource($category)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified category
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $category = Category::with(['posts' => function ($query) {
                $query->published()
                      ->with(['user:id,name,email'])
                      ->latest('published_at');
            }])->where('slug', $slug)->first();

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new CategoryResource($category)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, string $slug): JsonResponse
    {
        // Find the category
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = $request->only(['name', 'description']);

            // Update slug if name changed
            if ($request->has('name') && $request->name !== $category->name) {
                $slug = Str::slug($request->name);
                $originalSlug = $slug;
                $counter = 1;

                // Ensure slug is unique (excluding current category)
                while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
                $updateData['slug'] = $slug;
            }

            $category->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => new CategoryResource($category)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified category
     */
    public function destroy(string $slug): JsonResponse
    {
        try {
            $category = Category::where('slug', $slug)->first();

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            // Check if category has posts
            if ($category->posts()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with existing posts'
                ], 400);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}