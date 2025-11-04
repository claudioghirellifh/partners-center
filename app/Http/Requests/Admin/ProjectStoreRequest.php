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
            'plan_id' => ['required', 'integer', Rule::exists('plans', 'id')],
            'billing_cycle' => ['required', Rule::in([Project::BILLING_MONTHLY, Project::BILLING_ANNUAL])],
            'starts_on' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
