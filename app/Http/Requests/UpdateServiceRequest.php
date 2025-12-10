<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'category_id' => 'nullable|exists:categories,id',
            'name'        => 'string|max:255',
            'description' => 'string',
            'duration'    => 'string',
            'price'       => 'numeric',
            'is_active'   => 'boolean',

            'images'      => 'array',
            'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            'days'        => 'array',
            'days.*'      => 'string',
        ];
    }
}
