<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClearanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('student');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'type' => [
                'required',
                Rule::in(['graduation', 'withdrawal', 'transfer', 'dismissal', 'temporary_leave', 'semester_completion'])
            ],
            'reason' => 'nullable|string|max:500',
            'additional_notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Please select a clearance type.',
            'type.in' => 'Selected clearance type is invalid.',
            'reason.max' => 'Reason cannot exceed 500 characters.',
            'additional_notes.max' => 'Additional notes cannot exceed 1000 characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'requested_date' => now(),
        ]);
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'type' => 'clearance type',
            'reason' => 'reason for clearance',
            'additional_notes' => 'additional notes',
        ];
    }
}