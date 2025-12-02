<?php

namespace App\Http\Requests\Admin;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'client_email' => ['required', 'string', 'email', 'max:150'],
            'plan_id' => ['required', 'integer', Rule::exists('plans', 'id')],
            'billing_cycle' => ['required', Rule::in([Project::BILLING_MONTHLY, Project::BILLING_ANNUAL])],
            'charge_setup' => ['nullable', 'boolean'],
            'setup_fee' => ['nullable', 'numeric', 'min:0', 'required_if:charge_setup,1'],
            'starts_on' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
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
