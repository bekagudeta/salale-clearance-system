@extends('layouts.admin')

@section('title', 'Email Settings - Admin')
@section('page-title', 'Email Settings')
@section('page-subtitle', 'Configure email server and notification settings')

@section('content')
<div class="space-y-6">
    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-red-600 mt-1 mr-3"></i>
                <div>
                    <h3 class="text-lg font-semibold text-red-900">Validation Error</h3>
                    <ul class="list-disc list-inside text-sm text-red-700 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-center">
                <i class="fas fa-times-circle text-red-600 mr-3"></i>
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif
    
    <!-- Email Settings Form -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-2">Mail Server Configuration</h2>
        <p class="text-sm text-gray-600 mb-6">Configure your SMTP settings for sending emails</p>
        
        <form method="POST" action="{{ route('admin.settings.email.update') }}" class="space-y-6">
            @csrf
            
            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <p class="text-sm text-blue-800">
                    <strong>⚙️ Recommended Settings:</strong> For Gmail use smtp.gmail.com:587 with TLS. For most mail services use port 587 or 465 with appropriate encryption.
                </p>
                <p class="text-sm text-blue-700 mt-2">
                    <strong>💡 Gmail 2FA Users:</strong> If you have 2-factor authentication enabled, use an App Password instead of your regular password. <a href="https://myaccount.google.com/apppasswords" target="_blank" class="underline hover:text-blue-900">Generate one here</a>.
                </p>
            </div>

            <!-- Basic Settings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mail Driver <span class="text-red-500">*</span>
                    </label>
                    <select name="mail_mailer" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mail_mailer') border-red-500 @enderror">
                        <option value="">Select Driver</option>
                        <option value="smtp" {{ old('mail_mailer', $settings['mail_mailer'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP (Recommended)</option>
                        <option value="mail" {{ old('mail_mailer', $settings['mail_mailer'] ?? '') == 'mail' ? 'selected' : '' }}>PHP Mail Function</option>
                        <option value="sendmail" {{ old('mail_mailer', $settings['mail_mailer'] ?? '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Choose how emails will be sent</p>
                    @error('mail_mailer')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Encryption <span class="text-red-500">*</span>
                    </label>
                    <select name="mail_encryption" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mail_encryption') border-red-500 @enderror">
                        <option value="tls" {{ old('mail_encryption', $settings['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS (Port 587) - Recommended</option>
                        <option value="ssl" {{ old('mail_encryption', $settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL (Port 465)</option>
                        <option value="" {{ old('mail_encryption', $settings['mail_encryption'] ?? '') == '' ? 'selected' : '' }}>None</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">TLS is more secure than SSL for most services</p>
                    @error('mail_encryption')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            
            <!-- SMTP Settings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        SMTP Host <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="mail_host" value="{{ old('mail_host', $settings['mail_host'] ?? '') }}" placeholder="e.g., smtp.gmail.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mail_host') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">The SMTP server address</p>
                    @error('mail_host')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        SMTP Port <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="mail_port" value="{{ old('mail_port', $settings['mail_port'] ?? '587') }}" placeholder="587" min="1" max="65535" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mail_port') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Usually 587 (TLS) or 465 (SSL)</p>
                    @error('mail_port')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            
            <!-- Authentication -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Username / Email
                    </label>
                    <input type="text" name="mail_username" value="{{ old('mail_username', $settings['mail_username'] ?? '') }}" placeholder="your-email@example.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mail_username') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Leave empty if no authentication is required</p>
                    @error('mail_username')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" name="mail_password" id="mail_password" value="{{ old('mail_password', $settings['mail_password'] ?? '') }}" placeholder="••••••••••" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mail_password') border-red-500 @enderror">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3 top-2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Leave empty if no authentication is required</p>
                    @error('mail_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            
            <!-- From Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        From Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}" placeholder="noreply@yourdomain.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mail_from_address') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Email address shown as sender</p>
                    @error('mail_from_address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        From Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name'] ?? 'Salale Clearance System') }}" placeholder="Application Name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mail_from_name') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Display name for emails</p>
                    @error('mail_from_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Test Email Section -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Test Email Configuration</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Test Email Address</label>
                        <input id="testEmailAddress" type="email" placeholder="admin@example.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Enter an address to test the configuration</p>
                    </div>
                    <div class="flex flex-col justify-end gap-2">
                        <button type="button" id="sendTestEmailButton" onclick="sendTestEmail()" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">
                            <i class="fas fa-paper-plane mr-2"></i> Send Test Email
                        </button>
                        <label class="flex items-center">
                            <input type="checkbox" name="test_connection" id="test_connection" class="rounded border-gray-300 text-blue-600">
                            <span class="ml-2 text-sm text-gray-700">Test connection before saving</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="flex justify-end gap-3 border-t border-gray-200 pt-6">
                <button type="reset" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class="fas fa-redo mr-2"></i> Reset
                </button>
                <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-blue-800 transition font-semibold">
                    <i class="fas fa-save mr-2"></i> Save Email Settings
                </button>
            </div>
        </form>
    </div>
    
    <!-- Email Templates -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-2">Email Templates</h2>
        <p class="text-sm text-gray-600 mb-6">Customize email templates sent to users</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Clearance Request</h3>
                        <p class="text-sm text-gray-600 mt-1">Sent when student submits clearance request</p>
                    </div>
                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">New</span>
                </div>
                <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <i class="fas fa-edit mr-1"></i> Edit Template
                </a>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Approval Notification</h3>
                        <p class="text-sm text-gray-600 mt-1">Sent when department approves request</p>
                    </div>
                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Active</span>
                </div>
                <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <i class="fas fa-edit mr-1"></i> Edit Template
                </a>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Rejection Notice</h3>
                        <p class="text-sm text-gray-600 mt-1">Sent when department rejects request</p>
                    </div>
                    <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded">Active</span>
                </div>
                <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <i class="fas fa-edit mr-1"></i> Edit Template
                </a>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Completion Alert</h3>
                        <p class="text-sm text-gray-600 mt-1">Sent when clearance is completed</p>
                    </div>
                    <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded">Active</span>
                </div>
                <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <i class="fas fa-edit mr-1"></i> Edit Template
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const field = document.getElementById('mail_password');
    const icon = document.getElementById('toggleIcon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function sendTestEmail() {
    const button = document.getElementById('sendTestEmailButton');
    const emailField = document.getElementById('testEmailAddress');
    const email = emailField.value.trim();

    if (!email) {
        showNotification('Please enter a valid test email address.', 'error');
        return;
    }

    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sending...';

    // Collect form data
    const formData = new FormData(document.querySelector('form'));
    const data = Object.fromEntries(formData);
    data.email = email;

    fetch('{{ route('admin.settings.test-email') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message || 'Test email sent successfully!', 'success');
        } else {
            showNotification(data.message || 'Failed to send test email', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error sending test email. Please try again.', 'error');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transition = 'opacity 0.3s';
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
@endsection
