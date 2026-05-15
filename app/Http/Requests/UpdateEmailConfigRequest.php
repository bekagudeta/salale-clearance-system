<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmailConfigRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasPermissionTo('manage settings');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'mail_mailer' => 'required|string|in:smtp,mail,sendmail',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|integer|min:1|max:65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|in:tls,ssl,',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
            'test_connection' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'mail_mailer.required' => 'Please select a mail driver.',
            'mail_mailer.in' => 'The selected mail driver is invalid.',
            'mail_host.required' => 'SMTP host address is required.',
            'mail_host.string' => 'SMTP host must be a valid string.',
            'mail_port.required' => 'SMTP port is required.',
            'mail_port.integer' => 'SMTP port must be a number.',
            'mail_port.min' => 'SMTP port must be at least 1.',
            'mail_port.max' => 'SMTP port cannot exceed 65535.',
            'mail_encryption.in' => 'Please select a valid encryption method.',
            'mail_from_address.required' => 'From email address is required.',
            'mail_from_address.email' => 'From email must be a valid email address.',
            'mail_from_name.required' => 'From name is required.',
            'mail_from_name.string' => 'From name must be a valid string.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Trim whitespace from string fields
        $this->merge([
            'mail_host' => trim($this->mail_host),
            'mail_username' => trim($this->mail_username ?? ''),
            'mail_from_address' => trim($this->mail_from_address),
            'mail_from_name' => trim($this->mail_from_name),
        ]);
    }
}
