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
            'name'        => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'duration'    => 'sometimes|string',
            'price'       => 'sometimes|numeric',
            'is_active'   => 'sometimes|boolean',
            'category_id' => 'sometimes|nullable|exists:categories,id',
            'days'        => 'sometimes|array',
            'days.*'      => 'sometimes|string',
        ];
    }
}
