<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
        $userId = auth()->id();
        
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string|min:8',
            'faculty' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'year' => 'required|integer|min:1|max:6',
            'semester' => 'required|string|in:First,Second,Summer',
            'phone' => 'nullable|string|max:20|regex:/^([0-9\s\-\+\(\)]*)$/',
            'gender' => 'nullable|in:male,female,other',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Full name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'faculty.required' => 'Faculty is required.',
            'department.required' => 'Department is required.',
            'year.required' => 'Year of study is required.',
            'year.min' => 'Year must be at least 1.',
            'year.max' => 'Year cannot exceed 6.',
            'semester.required' => 'Semester is required.',
            'phone.regex' => 'Please enter a valid phone number.',
            'photo.image' => 'Profile photo must be an image.',
            'photo.mimes' => 'Profile photo must be JPEG, PNG, or GIF.',
            'photo.max' => 'Profile photo cannot exceed 2MB.',
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
            'faculty' => 'faculty',
            'department' => 'department',
            'year' => 'year of study',
            'semester' => 'semester',
            'phone' => 'phone number',
            'gender' => 'gender',
            'photo' => 'profile photo',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Trim whitespace from name and email
        $this->merge([
            'name' => trim($this->name),
            'email' => trim($this->email),
        ]);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Check if email is changed and not unique
            if ($this->email !== auth()->user()->email) {
                $exists = \App\Models\User::where('email', $this->email)->exists();
                if ($exists) {
                    $validator->errors()->add('email', 'This email is already taken.');
                }
            }
        });
    }
}