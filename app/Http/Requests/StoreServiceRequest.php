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
            'category_id' => 'nullable|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'duration'    => 'required|string',
            'price'       => 'required|numeric',
            'is_active'   => 'boolean',

            'images'      => 'array',
            'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            'days'        => 'array',
            'days.*'      => 'string',
        ];
    }
}
