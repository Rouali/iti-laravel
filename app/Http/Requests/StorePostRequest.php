<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Change to authorization logic if needed
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:3|unique:posts,title',
            'description' => 'required|string|min:10',
            'user_id' => 'required|exists:users,id', // Prevents fake user IDs
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required.',
            'title.min' => 'Title must be at least 3 characters long.',
            'title.unique' => 'Title must be unique.',
            'description.required' => 'Description is required.',
            'description.min' => 'Description must be at least 10 characters long.',
            'user_id.required' => 'User ID is required.',
            'user_id.exists' => 'Invalid user ID selected.',
        ];
    }
}
