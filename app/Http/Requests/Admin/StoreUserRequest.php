<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('super_admin');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ];

        // Additional validation for student role
        if ($this->role === 'student') {
            $rules['student_id'] = 'required|string|unique:students,student_id|max:50';
            $rules['faculty'] = 'required|string|max:255';
            $rules['department'] = 'required|string|max:255';
            $rules['year'] = 'required|integer|min:1|max:6';
            $rules['semester'] = 'required|string|in:First,Second,Summer';
            $rules['phone'] = 'nullable|string|max:20';
            $rules['gender'] = 'nullable|in:male,female,other';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'User name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'role.required' => 'Please select a role for the user.',
            'role.exists' => 'Selected role is invalid.',
            
            // Student validation messages
            'student_id.required' => 'Student ID is required for student role.',
            'student_id.unique' => 'This Student ID is already registered.',
            'faculty.required' => 'Faculty is required for student role.',
            'department.required' => 'Department is required for student role.',
            'year.required' => 'Year of study is required for student role.',
            'semester.required' => 'Semester is required for student role.',
            'semester.in' => 'Semester must be First, Second, or Summer.',
            'gender.in' => 'Gender must be male, female, or other.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'full name',
            'email' => 'email address',
            'password' => 'password',
            'role' => 'user role',
            'student_id' => 'student ID',
            'faculty' => 'faculty',
            'department' => 'department',
            'year' => 'year of study',
            'semester' => 'semester',
            'phone' => 'phone number',
            'gender' => 'gender',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->name),
            'email' => trim($this->email),
        ]);

        if ($this->student_id) {
            $this->merge([
                'student_id' => strtoupper(trim($this->student_id)),
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Additional validation logic
            if ($this->role === 'student') {
                // Check if student ID format is valid (customize as needed)
                if (!preg_match('/^[A-Z0-9]+$/i', $this->student_id)) {
                    $validator->errors()->add('student_id', 'Student ID should contain only letters and numbers.');
                }
            }
        });
    }
}