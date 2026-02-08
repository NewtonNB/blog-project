<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request validation for creating new posts
 */
class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string|min:10',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'category_id' => 'required|exists:categories,id',
            'published_at' => 'nullable|date',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Post title is required',
            'title.min' => 'Post title must be at least 3 characters',
            'content.required' => 'Post content is required',
            'content.min' => 'Post content must be at least 10 characters',
            'status.in' => 'Status must be either draft or published',
            'category_id.exists' => 'Selected category does not exist',
        ];
    }
}