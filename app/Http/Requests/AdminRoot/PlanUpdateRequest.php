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
            'description' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'monthly_price' => $this->normalizePrice($this->input('monthly_price')),
        ]);
    }

    protected function normalizePrice($value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        if (is_string($value)) {
            $value = str_replace(['.', ' '], '', $value);
            $value = str_replace(',', '.', $value);
        }

        return (float) $value;
    }
}
