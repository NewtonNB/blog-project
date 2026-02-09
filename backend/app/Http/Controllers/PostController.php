<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostCollection;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

/**
 * PostController handles CRUD operations for blog posts
 * Provides API endpoints for managing posts
 */
class PostController extends Controller
{
    /**
     * Display a listing of posts
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Post::with(['user:id,name,email', 'category:id,name,slug']);

            // Filter by status if provided
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by category if provided
            if ($request->has('category')) {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('slug', $request->category);
                });
            }

            // Search in title and content if query provided
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 10);
            $posts = $query->latest('published_at')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => new PostCollection($posts)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created post
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        try {
            // Generate unique slug from title
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $counter = 1;

            // Ensure slug is unique
            while (Post::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Create the post
            $post = Post::create([
                'title' => $request->title,
                'slug' => $slug,
                'content' => $request->content,
                'excerpt' => $request->excerpt,
                'featured_image' => $request->featured_image,
                'status' => $request->status,
                'published_at' => $request->status === 'published' 
                    ? ($request->published_at ?? now()) 
                    : null,
                'user_id' => $request->user()->id,
                'category_id' => $request->category_id,
            ]);

            // Load relationships
            $post->load(['user:id,name,email', 'category:id,name,slug']);

            return response()->json([
                'success' => true,
                'message' => 'Post created successfully',
                'data' => new PostResource($post)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified post
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $post = Post::with(['user:id,name,email,bio', 'category:id,name,slug'])
                       ->where('slug', $slug)
                       ->first();

            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new PostResource($post)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified post
     */
    public function update(UpdatePostRequest $request, string $slug): JsonResponse
    {
        // Find the post
        $post = Post::where('slug', $slug)->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 404);
        }

        // Check if user owns the post or is admin
        if ($post->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this post'
            ], 403);
        }

        try {
            $updateData = $request->only([
                'title', 'content', 'excerpt', 'featured_image', 
                'status', 'category_id', 'published_at'
            ]);

            // Update slug if title changed
            if ($request->has('title') && $request->title !== $post->title) {
                $slug = Str::slug($request->title);
                $originalSlug = $slug;
                $counter = 1;

                // Ensure slug is unique (excluding current post)
                while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
                $updateData['slug'] = $slug;
            }

            // Set published_at if status changed to published
            if ($request->status === 'published' && $post->status !== 'published') {
                $updateData['published_at'] = $request->published_at ?? now();
            }

            $post->update($updateData);
            $post->load(['user:id,name,email', 'category:id,name,slug']);

            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully',
                'data' => new PostResource($post)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified post
     */
    public function destroy(Request $request, string $slug): JsonResponse
    {
        try {
            $post = Post::where('slug', $slug)->first();

            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post not found'
                ], 404);
            }

            // Check if user owns the post
            if ($post->user_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this post'
                ], 403);
            }

            $post->delete(); // Soft delete

            return response()->json([
                'success' => true,
                'message' => 'Post moved to trash successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get trashed posts for authenticated user
     */
    public function trashed(Request $request): JsonResponse
    {
        try {
            $posts = Post::onlyTrashed()
                ->with(['user:id,name,email', 'category:id,name,slug'])
                ->where('user_id', $request->user()->id)
                ->latest('deleted_at')
                ->get();

            return response()->json([
                'success' => true,
                'data' => PostResource::collection($posts)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch trashed posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore a trashed post
     */
    public function restore(Request $request, string $slug): JsonResponse
    {
        try {
            $post = Post::onlyTrashed()->where('slug', $slug)->first();

            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post not found in trash'
                ], 404);
            }

            // Check if user owns the post
            if ($post->user_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to restore this post'
                ], 403);
            }

            $post->restore();
            $post->load(['user:id,name,email', 'category:id,name,slug']);

            return response()->json([
                'success' => true,
                'message' => 'Post restored successfully',
                'data' => new PostResource($post)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Permanently delete a trashed post
     */
    public function forceDelete(Request $request, string $slug): JsonResponse
    {
        try {
            $post = Post::onlyTrashed()->where('slug', $slug)->first();

            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post not found in trash'
                ], 404);
            }

            // Check if user owns the post
            if ($post->user_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to permanently delete this post'
                ], 403);
            }

            $post->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Post permanently deleted'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to permanently delete post',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}