<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreClearanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('student');
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:graduation,withdrawal,transfer,dismissal,temporary_leave,semester_completion',
            'reason' => 'nullable|string|max:500',
        ];
    }
}