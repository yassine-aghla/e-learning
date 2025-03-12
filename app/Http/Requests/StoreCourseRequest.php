<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'video' => 'nullable|string',
            'cover' => 'nullable|string',
            'duration' => 'nullable|numeric',
            'level' => 'required|in:beginner,intermediate,advanced',
            'category_id' => 'required|exists:categories,id',
            'tag_ids' => 'nullable|array', 
            'tag_ids.*' => 'exists:tags,id',
        ];
    }
}