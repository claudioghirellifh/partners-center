<?php

namespace App\Http\Requests\AdminRoot;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = $this->route('company');
        if (is_object($companyId)) {
            $companyId = $companyId->getKey();
        }

        $uriRegex = '/^[a-z0-9]+(?:-[a-z0-9]+)*$/';

        return [
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'uri' => ['required', 'string', 'max:60', 'regex:'.$uriRegex, Rule::unique('companies', 'uri')->ignore($companyId)],
            'locale' => ['required', Rule::in(['pt-BR', 'en', 'es-AR'])],
            'is_active' => ['required', 'boolean'],
            'brand_color' => ['nullable', 'regex:/^#?[0-9A-Fa-f]{6}$/'],

            'logo' => ['nullable', 'file', 'max:1024'],
            'favicon' => ['nullable', 'file', 'mimes:ico,png,svg'],
        ];
    }
}
