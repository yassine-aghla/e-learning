<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTagRequest extends FormRequest
{
    public function rules(): array
    {
        return [
          'tags' => 'array',
          
            'tags.*.name' => 'string|max:255',
        ];
    }
}