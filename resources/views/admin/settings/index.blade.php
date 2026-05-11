@extends('layouts.admin')

@section('title', 'System Settings - Admin')
@section('page-title', 'System Settings')
@section('page-subtitle', 'Configure system-wide settings and preferences')

@section('content')
<div class="space-y-6">
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.settings.email') }}" class="bg-white rounded-xl shadow-lg p-4 hover:shadow-xl transition">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-envelope text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Email Settings</h3>
                    <p class="text-sm text-gray-500">Configure mail server</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.settings.backup') }}" class="bg-white rounded-xl shadow-lg p-4 hover:shadow-xl transition">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-database text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Backup</h3>
                    <p class="text-sm text-gray-500">Database backups</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.settings.system-info') }}" class="bg-white rounded-xl shadow-lg p-4 hover:shadow-xl transition">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-info-circle text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">System Info</h3>
                    <p class="text-sm text-gray-500">Server information</p>
                </div>
            </div>
        </a>
        
        <button onclick="confirmClearCache()" class="bg-white rounded-xl shadow-lg p-4 hover:shadow-xl transition text-left w-full">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-broom text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Clear Cache</h3>
                    <p class="text-sm text-gray-500">Clear system cache</p>
                </div>
            </div>
        </button>
    </div>
    
    <!-- General Settings Form -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">General Settings</h2>
        
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- University Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">University Name</label>
                    <input type="text" name="university_name" value="{{ $settings['university_name'] ?? 'Salale University' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">University Logo</label>
                    <input type="file" name="university_logo" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    @if($settings['university_logo'] ?? false)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $settings['university_logo']) }}" alt="University Logo" class="h-16 rounded">
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                    <input type="tel" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>
            
            <!-- System Settings -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reminder Days</label>
                    <input type="number" name="reminder_days" value="{{ $settings['reminder_days'] ?? 7 }}" min="1" max="30" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">Days before deadline to send reminders</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Auto Complete Days</label>
                    <input type="number" name="auto_complete_days" value="{{ $settings['auto_complete_days'] ?? 30 }}" min="1" max="90" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">Days to auto-complete pending requests</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maintenance Mode</label>
                    <select name="maintenance_mode" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="0" {{ !($settings['maintenance_mode'] ?? false) ? 'selected' : '' }}>Disabled</option>
                        <option value="1" {{ ($settings['maintenance_mode'] ?? false) ? 'selected' : '' }}>Enabled</option>
                    </select>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-purple-700 transition">
                    <i class="fas fa-save mr-2"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
    
    <!-- System Status -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">System Status</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Application Status</p>
                        <p class="text-lg font-semibold text-green-600">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Database</p>
                        <p class="text-lg font-semibold text-green-600">Connected</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-database text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Storage</p>
                        <p class="text-lg font-semibold text-blue-600">Available</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-hdd text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Last Backup</p>
                        <p class="text-lg font-semibold text-yellow-600">Not Available</p>
                    </div>
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Clear Cache Modal -->
<div id="clearCacheModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                <i class="fas fa-broom text-yellow-600"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Clear System Cache</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to clear the system cache? This may temporarily slow down the application.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <form method="POST" action="{{ route('admin.settings.clear-cache') }}">
                    @csrf
                    <button type="button" onclick="closeClearCacheModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-yellow-600 text-white text-base font-medium rounded-md w-24 hover:bg-yellow-700">
                        Clear
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmClearCache() {
    document.getElementById('clearCacheModal').classList.remove('hidden');
}

function closeClearCacheModal() {
    document.getElementById('clearCacheModal').classList.add('hidden');
}

// Auto-save settings every 30 seconds
let formChanged = false;
document.querySelector('form').addEventListener('change', function() {
    formChanged = true;
});

setInterval(function() {
    if (formChanged && !document.hidden) {
        // Show auto-save notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        notification.textContent = 'Auto-saving settings...';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 2000);
        
        formChanged = false;
    }
}, 30000);
</script>
@endsection
