<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class FlagCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('department_officer');
    }

    public function rules(): array
    {
        return [
            'remarks' => 'required|string|min:5|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'remarks.required' => 'Please tell the student what they must do to clear their case.',
            'remarks.min' => 'The comment must be at least 5 characters.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->remarks) {
            $this->merge(['remarks' => trim($this->remarks)]);
        }
    }
}
