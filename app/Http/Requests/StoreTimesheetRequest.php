<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTimesheetRequest extends FormRequest
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
        $isAdmin = auth()->user()->isAdmin();

        $rules = [
            'week_start' => ['required', 'date'],
            'week_end' => ['required', 'date', 'after_or_equal:week_start'],
            'total_hours' => ['required', 'numeric', 'min:0', 'max:120'],
            'ot_hours' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'assigned_timesheet_id' => ['nullable', 'exists:assigned_timesheets,id'],
        ];

        if ($isAdmin) {
            $rules['employee_id'] = ['required', 'exists:employees,id'];
        }

        return $rules;
    }
}
