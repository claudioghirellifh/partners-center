<?php

namespace App\Http\Requests\AdminRoot;

use App\Models\ReleaseNote;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReleaseNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $releaseNoteId = $this->route('release_note')?->id;

        return [
            'version' => [
                'required',
                'string',
                'max:50',
                Rule::unique('release_notes', 'version')->ignore($releaseNoteId),
            ],
            'title' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string'],
            'is_current' => ['required', 'boolean'],
            'is_visible' => ['required', 'boolean'],
            'alert_level' => ['nullable', Rule::in(ReleaseNote::ALERT_LEVELS)],
            'alert_message' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_current' => (bool) $this->input('is_current', false),
            'is_visible' => (bool) $this->input('is_visible', false),
        ]);
    }
}
