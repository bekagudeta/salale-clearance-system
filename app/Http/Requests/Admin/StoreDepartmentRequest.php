<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use \App\Models\User;
use \App\Models\Department;

class StoreDepartmentRequest extends FormRequest
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
        $departmentId = $this->route('department') ? $this->route('department')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments')->ignore($departmentId),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique('departments')->ignore($departmentId),
            ],
            'officer_user_id' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $user = User::find($value);
                        if ($user && !$user->hasRole('department_officer')) {
                            $fail('The selected user must have the department officer role.');
                        }
                    }
                },
            ],
            'priority_order' => 'nullable|integer|min:0|max:100',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Department name is required.',
            'name.unique' => 'A department with this name already exists.',
            'slug.required' => 'Department slug is required.',
            'slug.unique' => 'A department with this slug already exists.',
            'slug.regex' => 'Slug must contain only lowercase letters, numbers, and hyphens.',
            'officer_user_id.exists' => 'Selected officer does not exist.',
            'priority_order.integer' => 'Priority order must be a number.',
            'priority_order.min' => 'Priority order cannot be negative.',
            'priority_order.max' => 'Priority order cannot exceed 100.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'department name',
            'slug' => 'department slug',
            'officer_user_id' => 'department officer',
            'priority_order' => 'priority order',
            'is_active' => 'status',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->name),
            'slug' => $this->slug ? strtolower(trim($this->slug)) : \Str::slug($this->name),
        ]);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Check if slug is valid
            if ($this->slug && !preg_match('/^[a-z0-9-]+$/', $this->slug)) {
                $validator->errors()->add('slug', 'Slug can only contain lowercase letters, numbers, and hyphens.');
            }

            // Check if priority order is already taken
            if ($this->priority_order && $this->priority_order > 0) {
                $exists = Department::where('priority_order', $this->priority_order)
                    ->when($this->route('department'), function($query) {
                        return $query->where('id', '!=', $this->route('department')->id);
                    })
                    ->exists();
                
                if ($exists) {
                    $validator->errors()->add('priority_order', 'This priority order is already assigned to another department.');
                }
            }
        });
    }

    /**
     * Get the validation data.
     */
    protected function validationData(): array
    {
        return array_merge($this->all(), [
            'is_active' => $this->is_active ?? false,
        ]);
    }

    /**
     * Handle dynamic method calls for different request types.
     */
    public function updateRules(): array
    {
        $rules = $this->rules();
        
        // Make password optional for updates
        if (isset($rules['password'])) {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }
        
        return $rules;
    }
}