<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Get all comments for a post
     */
    public function index($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $comments = $post->comments;

        return response()->json([
            'success' => true,
            'data' => CommentResource::collection($comments),
        ]);
    }

    /**
     * Store a new comment
     */
    public function store(Request $request, $slug)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $post = Post::where('slug', $slug)->firstOrFail();

        $comment = Comment::create([
            'content' => $request->content,
            'post_id' => $post->id,
            'user_id' => Auth::id(),
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'data' => new CommentResource($comment),
        ], 201);
    }

    /**
     * Update a comment
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = Comment::findOrFail($id);

        // Check if user owns the comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $comment->update([
            'content' => $request->content,
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully',
            'data' => new CommentResource($comment),
        ]);
    }

    /**
     * Delete a comment
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->load('post');

        // Check if user owns the comment OR owns the post (post author can delete any comment on their post)
        if ($comment->user_id !== Auth::id() && $comment->post->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
        ]);
    }
}
