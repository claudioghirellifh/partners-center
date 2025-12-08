<?php

namespace App\Http\Requests\Admin;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class ProjectUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $company = request()->attributes->get('company');
        $project = $this->route('project');

        return [
            'name' => ['required', 'string', 'max:150'],
            'plan_id' => ['required', 'integer', Rule::exists('plans', 'id')],
            'status' => ['nullable', 'string', Rule::in([
                Project::STATUS_REQUESTED,
                Project::STATUS_INSTALLING,
                Project::STATUS_CANCELLED,
                Project::STATUS_DONE,
            ])],
            'customer_id' => [
                'nullable',
                'integer',
                'required_if:billing_origin,'.Project::ORIGIN_IUGU,
                Rule::exists('customers', 'id')->where(fn ($query) => $company ? $query->where('company_id', $company->id) : $query),
            ],
            'store_domain' => [
                'nullable',
                'string',
                'max:191',
                'regex:/^(?!https?:\\/\\/)[A-Za-z0-9.-]+$/',
                'required_unless:use_temp_domain,1',
                Rule::unique('projects', 'store_domain')
                    ->where(fn ($query) => $company ? $query->where('company_id', $company->id) : $query)
                    ->ignore($project?->id),
            ],
            'use_temp_domain' => ['nullable', 'boolean'],
            'store_name' => ['required', 'string', 'max:150'],
            'store_admin_name' => ['required', 'string', 'max:150'],
            'store_admin_email' => ['required', 'string', 'email', 'max:150'],
            'store_admin_password' => [
                'nullable',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
            'billing_origin' => ['required', Rule::in([Project::ORIGIN_MANUAL, Project::ORIGIN_IUGU])],
            'iugu_subscription_mode' => ['nullable', Rule::in(['existing', 'create'])],
            'iugu_subscription_id' => ['nullable', 'string', 'max:120', Rule::requiredIf(function () {
                return $this->input('billing_origin') === Project::ORIGIN_IUGU
                    && $this->input('iugu_subscription_mode', 'existing') === 'existing';
            })],
            'charge_setup' => ['nullable', 'boolean'],
            'setup_fee' => ['nullable', 'numeric', 'min:0', 'required_if:charge_setup,1'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $domain = $this->input('store_domain');
        if (is_string($domain)) {
            $domain = preg_replace('#^https?://#i', '', trim(strtolower($domain)));
            $domain = trim($domain, '/');
        }
        $useTempDomain = $this->boolean('use_temp_domain');

        $storeAdminEmail = $this->input('store_admin_email');
        if (is_string($storeAdminEmail)) {
            $storeAdminEmail = trim(strtolower($storeAdminEmail));
        }

        $payload = [
            'billing_origin' => $this->input('billing_origin', Project::ORIGIN_MANUAL),
            'iugu_subscription_mode' => $this->input('iugu_subscription_mode', 'existing'),
            'iugu_subscription_id' => $this->input('iugu_subscription_id') ?: null,
            'customer_id' => $this->input('customer_id') ?: null,
            'charge_setup' => $this->boolean('charge_setup'),
            'use_temp_domain' => $useTempDomain,
            'store_domain' => $domain,
            'store_admin_email' => $storeAdminEmail,
            'store_name' => is_string($this->input('store_name')) ? trim($this->input('store_name')) : $this->input('store_name'),
            'store_admin_name' => is_string($this->input('store_admin_name')) ? trim($this->input('store_admin_name')) : $this->input('store_admin_name'),
            'status' => $this->input('status', Project::STATUS_REQUESTED) ?: Project::STATUS_REQUESTED,
        ];

        if ($this->has('setup_fee')) {
            $payload['setup_fee'] = $this->normalizeMoney($this->input('setup_fee'));
        }

        $this->merge($payload);
    }

    private function normalizeMoney($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $normalized = str_replace(['R$', ' '], '', $value);
        $normalized = str_replace('.', '', $normalized);
        $normalized = str_replace(',', '.', $normalized);

        return is_numeric($normalized) ? (float) $normalized : null;
    }

    public function messages(): array
    {
        return [
            'store_domain.required_unless' => 'Informe um domínio da loja ou selecione a opção de domínio temporário.',
            'store_domain.regex' => 'Informe apenas o domínio (sem http/https).',
            'store_admin_password.password.mixed' => 'A senha do administrador deve conter letras maiúsculas e minúsculas.',
            'store_admin_password.password.letters' => 'A senha do administrador deve conter ao menos uma letra.',
            'store_admin_password.password.numbers' => 'A senha do administrador deve conter ao menos um número.',
            'store_admin_password.password.symbols' => 'A senha do administrador deve conter ao menos um símbolo.',
            'store_admin_password.password.min' => 'A senha do administrador deve ter no mínimo :min caracteres.',
            'store_admin_password.confirmed' => 'A confirmação da senha do administrador não confere.',
        ];
    }
}
