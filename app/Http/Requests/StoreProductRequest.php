<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'price' => ['required', 'numeric', 'min:0'],

            'stock' => ['required', 'integer', 'min:0'],

            'country' => ['nullable', 'string', 'max:100'],

            'active' => ['sometimes', 'boolean'],

            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        ];
    }
}