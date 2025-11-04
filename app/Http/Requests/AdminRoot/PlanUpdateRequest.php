<?php

namespace App\Http\Requests\AdminRoot;

use Illuminate\Foundation\Http\FormRequest;

class PlanUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'annual_price' => ['required', 'numeric', 'min:0'],
            'annual_discount_percentage' => ['nullable', 'integer', 'between:0,100'],
            'description' => ['nullable', 'string'],
        ];
    }
}
