<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'duration'    => 'required|string',
            'price'       => 'required|numeric',
            'image'       => 'required|image|max:2048',
            'images'      => 'nullable|array|min:1', // على الأقل صورة واحدة
            'images.*'    => 'image|max:2048',       // كل صورة يجب أن تكون image
            'days'        => 'array',
            'days.*'      => 'string',
        ];
    }
}
