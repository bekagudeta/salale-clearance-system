@extends('layouts.admin')

@section('title', 'Email Settings - Admin')
@section('page-title', 'Email Settings')
@section('page-subtitle', 'Configure email server and notification settings')

@section('content')
<div class="space-y-6">
    <!-- Test Email -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-blue-900">Test Email Configuration</h3>
                <p class="text-sm text-blue-700">Send a test email to verify your settings</p>
            </div>
            <button onclick="sendTestEmail()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-paper-plane mr-2"></i> Send Test Email
            </button>
        </div>
    </div>
    
    <!-- Email Settings Form -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Mail Server Configuration</h2>
        
        <form method="POST" action="{{ route('admin.settings.email.update') }}" class="space-y-6">
            @csrf
            
            <!-- Basic Settings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mail Driver</label>
                    <select name="mail_mailer" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="smtp" {{ ($settings['mail_mailer'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                        <option value="mail" {{ ($settings['mail_mailer'] ?? '') == 'mail' ? 'selected' : '' }}>PHP Mail</option>
                        <option value="sendmail" {{ ($settings['mail_mailer'] ?? '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                    <select name="mail_encryption" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="" {{ ($settings['mail_encryption'] ?? '') == '' ? 'selected' : '' }}>None</option>
                    </select>
                </div>
            </div>
            
            <!-- SMTP Settings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                    <input type="text" name="mail_host" value="{{ $settings['mail_host'] ?? '' }}" placeholder="smtp.example.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                    <input type="number" name="mail_port" value="{{ $settings['mail_port'] ?? 587 }}" placeholder="587" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>
            
            <!-- Authentication -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" name="mail_username" value="{{ $settings['mail_username'] ?? '' }}" placeholder="email@example.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="mail_password" value="{{ $settings['mail_password'] ?? '' }}" placeholder="••••••••••" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>
            
            <!-- From Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Address</label>
                    <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? '' }}" placeholder="noreply@example.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                    <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? '' }}" placeholder="Application Name" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Test Email Address</label>
                    <input id="testEmailAddress" type="email" name="test_email" value="{{ old('test_email', $settings['mail_from_address'] ?? '') }}" placeholder="admin@example.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">Enter an email address to verify outgoing settings.</p>
                </div>
                <div class="flex items-end justify-end">
                    <button id="sendTestEmailButton" type="button" onclick="sendTestEmail()" class="inline-flex items-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                        <i class="fas fa-paper-plane mr-2"></i> Send Test Email
                    </button>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-purple-700 transition">
                    <i class="fas fa-save mr-2"></i> Save Email Settings
                </button>
            </div>
        </form>
    </div>
    
    <!-- Email Templates -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Email Templates</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Clearance Request</h3>
                <p class="text-sm text-gray-600 mb-3">Sent when student submits clearance request</p>
                <button class="text-blue-600 hover:text-blue-800 text-sm">
                    <i class="fas fa-edit mr-1"></i> Edit Template
                </button>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Approval Notification</h3>
                <p class="text-sm text-gray-600 mb-3">Sent when department approves request</p>
                <button class="text-blue-600 hover:text-blue-800 text-sm">
                    <i class="fas fa-edit mr-1"></i> Edit Template
                </button>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Rejection Notice</h3>
                <p class="text-sm text-gray-600 mb-3">Sent when department rejects request</p>
                <button class="text-blue-600 hover:text-blue-800 text-sm">
                    <i class="fas fa-edit mr-1"></i> Edit Template
                </button>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Completion Alert</h3>
                <p class="text-sm text-gray-600 mb-3">Sent when clearance is completed</p>
                <button class="text-blue-600 hover:text-blue-800 text-sm">
                    <i class="fas fa-edit mr-1"></i> Edit Template
                </button>
            </div>
        </div>
    </div>
</div>

<script>
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

    fetch('{{ route('admin.settings.test-email') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Test email sent successfully!', 'success');
        } else {
            showNotification('Failed to send test email: ' + data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Error sending test email', 'error');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white px-4 py-2 rounded-lg shadow-lg z-50`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection
