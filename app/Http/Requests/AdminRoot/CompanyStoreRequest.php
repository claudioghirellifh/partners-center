<?php

namespace App\Http\Requests\AdminRoot;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $uriRegex = '/^[a-z0-9]+(?:-[a-z0-9]+)*$/';

        return [
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'uri' => ['required', 'string', 'max:60', 'regex:'.$uriRegex, 'unique:companies,uri'],
            'locale' => ['required', Rule::in(['pt-BR', 'en', 'es-AR'])],
            'is_active' => ['required', 'boolean'],
            'brand_color' => ['nullable', 'regex:/^#?[0-9A-Fa-f]{6}$/'],

            // Logo obrigatório (até 1MB, qualquer extensão)
            'logo' => ['required', 'file', 'max:1024'],
            'favicon' => ['nullable', 'file', 'mimes:ico,png,svg'],

            'admin_name' => ['required', 'string', 'min:2', 'max:120'],
            'admin_email' => ['required', 'email', 'max:190'],
        ];
    }
}
