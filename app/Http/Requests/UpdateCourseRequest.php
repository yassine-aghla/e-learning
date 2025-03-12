<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'content' => 'sometimes|string',
            'video' => 'nullable|string',
            'cover' => 'nullable|string',
            'duration' => 'nullable|numeric',
            'level' => 'sometimes|in:beginner,intermediate,advanced',
            'category_id' => 'sometimes|exists:categories,id',
            'tag_ids' => 'nullable|array', 
            'tag_ids.*' => 'exists:tags,id',
        ];
    }
}