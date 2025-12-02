<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'regex:/^[0-9]{8,11}$/'],
            'cpf_cnpj' => ['required', 'string', 'regex:/^[0-9]{11,14}$/'],
            'notes' => ['nullable', 'string'],
            'zip_code' => ['required', 'string', 'regex:/^[0-9]{8}$/'],
            'number' => ['required', 'string', 'max:20'],
            'street' => ['required', 'string', 'max:150'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'size:2'],
            'district' => ['nullable', 'string', 'max:100'],
            'complement' => ['nullable', 'string', 'max:100'],
            'iugu_mode' => ['required', Rule::in(['create', 'existing'])],
            'iugu_customer_id' => ['nullable', 'string', 'max:100', 'required_if:iugu_mode,existing'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => $this->onlyDigits($this->input('phone')),
            'cpf_cnpj' => $this->onlyDigits($this->input('cpf_cnpj')),
            'zip_code' => $this->onlyDigits($this->input('zip_code')),
            'state' => strtoupper((string) $this->input('state')),
            'iugu_mode' => $this->input('iugu_mode', 'create'),
            'iugu_customer_id' => $this->sanitizeId($this->input('iugu_customer_id')),
        ]);
    }

    private function onlyDigits(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $value);

        return $digits === '' ? null : $digits;
    }

    private function sanitizeId(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }
}
