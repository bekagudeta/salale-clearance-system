<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('department_officer');
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Please enter the student ID.',
            'title.required' => 'Please describe the case (e.g. borrowed book title).',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'student_id' => trim((string) $this->student_id),
            'title' => trim((string) $this->title),
            'description' => $this->description ? trim((string) $this->description) : null,
        ]);
    }
}
