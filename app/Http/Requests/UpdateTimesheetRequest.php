<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTimesheetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'total_hours' => ['required', 'numeric', 'min:1', 'max:120'],
            'ot_hours' => ['nullable', 'numeric', 'min:0', 'lte:total_hours'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
