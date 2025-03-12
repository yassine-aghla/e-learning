<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTagRequest extends FormRequest
{
    public function rules(): array
    {
        return [

            'tags' => 'array',
            'tags.*.name' => 'string|max:255',
        ];
    }
}