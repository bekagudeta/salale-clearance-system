<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApprovalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('department_officer');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [];

        if ($this->isMethod('post') || $this->route()->getActionMethod() === 'approve') {
            $rules['remarks'] = 'nullable|string|max:500';
        }

        if ($this->route()->getActionMethod() === 'reject') {
            $rules['remarks'] = 'required|string|min:5|max:500';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'remarks.required' => 'Please provide a reason for rejection.',
            'remarks.min' => 'Rejection reason must be at least 5 characters.',
            'remarks.max' => 'Remarks cannot exceed 500 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'remarks' => 'remarks/comments',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->remarks) {
            $this->merge([
                'remarks' => trim($this->remarks),
            ]);
        }
    }

    /**
     * Get the validation data.
     */
    public function validationData(): array
    {
        return array_merge($this->all(), [
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
    }
}