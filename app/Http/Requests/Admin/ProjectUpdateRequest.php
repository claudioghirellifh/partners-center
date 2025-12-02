<?php

namespace App\Http\Requests\Admin;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
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

        return [
            'name' => ['required', 'string', 'max:150'],
            'plan_id' => ['required', 'integer', Rule::exists('plans', 'id')],
            'customer_id' => [
                'nullable',
                'integer',
                'required_if:billing_origin,'.Project::ORIGIN_IUGU,
                Rule::exists('customers', 'id')->where(fn ($query) => $company ? $query->where('company_id', $company->id) : $query),
            ],
            'billing_cycle' => ['required', Rule::in([Project::BILLING_MONTHLY, Project::BILLING_ANNUAL])],
            'billing_origin' => ['required', Rule::in([Project::ORIGIN_MANUAL, Project::ORIGIN_IUGU])],
            'iugu_subscription_mode' => ['nullable', Rule::in(['existing', 'create'])],
            'iugu_subscription_id' => ['nullable', 'string', 'max:120', Rule::requiredIf(function () {
                return $this->input('billing_origin') === Project::ORIGIN_IUGU
                    && $this->input('iugu_subscription_mode', 'existing') === 'existing';
            })],
            'charge_setup' => ['nullable', 'boolean'],
            'setup_fee' => ['nullable', 'numeric', 'min:0', 'required_if:charge_setup,1'],
            'starts_on' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'billing_origin' => $this->input('billing_origin', Project::ORIGIN_MANUAL),
            'iugu_subscription_mode' => $this->input('iugu_subscription_mode', 'existing'),
            'iugu_subscription_id' => $this->input('iugu_subscription_id') ?: null,
            'customer_id' => $this->input('customer_id') ?: null,
            'charge_setup' => $this->boolean('charge_setup'),
            'setup_fee' => $this->normalizeMoney($this->input('setup_fee')),
        ]);
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
}
