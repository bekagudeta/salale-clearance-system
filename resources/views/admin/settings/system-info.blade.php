@extends('layouts.admin')

@section('title', 'System Information - Admin')
@section('page-title', 'System Information')
@section('page-subtitle', 'View system configuration and server details')

@section('content')
<div class="space-y-6">
    <!-- System Overview -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">System Overview</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Laravel Version</h3>
                <p class="text-lg font-semibold text-gray-900">{{ $info['laravel_version'] }}</p>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-500 mb-2">PHP Version</h3>
                <p class="text-lg font-semibold text-gray-900">{{ $info['php_version'] }}</p>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-500 mb-2">MySQL Version</h3>
                <p class="text-lg font-semibold text-gray-900">{{ $info['mysql_version'] }}</p>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Server Software</h3>
                <p class="text-lg font-semibold text-gray-900">{{ $info['server_software'] }}</p>
            </div>
        </div>
    </div>
    
    <!-- Server Configuration -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Server Configuration</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Operating System</h3>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">System</span>
                        <span class="text-sm font-medium text-gray-900">{{ $info['server_os']['sysname'] ?? 'Unknown' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Release</span>
                        <span class="text-sm font-medium text-gray-900">{{ $info['server_os']['release'] ?? 'Unknown' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Version</span>
                        <span class="text-sm font-medium text-gray-900">{{ $info['server_os']['version'] ?? 'Unknown' }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm text-gray-500">Machine</span>
                        <span class="text-sm font-medium text-gray-900">{{ $info['server_os']['machine'] ?? 'Unknown' }}</span>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">PHP Configuration</h3>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Max Execution Time</span>
                        <span class="text-sm font-medium text-gray-900">{{ $info['max_execution_time'] }}s</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Memory Limit</span>
                        <span class="text-sm font-medium text-gray-900">{{ $info['memory_limit'] }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Upload Max Filesize</span>
                        <span class="text-sm font-medium text-gray-900">{{ $info['upload_max_filesize'] }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm text-gray-500">Post Max Size</span>
                        <span class="text-sm font-medium text-gray-900">{{ $info['post_max_size'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- PHP Extensions -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Loaded PHP Extensions</h2>
        
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm text-gray-700 leading-relaxed">{{ $info['loaded_extensions'] }}</p>
        </div>
        
        <div class="mt-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Required Extensions Status</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @php
                    $requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo'];
                @endphp
                @foreach($requiredExtensions as $extension)
                    <div class="flex items-center space-x-2 p-3 border border-gray-200 rounded-lg">
                        @if(extension_loaded($extension))
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-sm font-medium text-green-700">{{ $extension }}</span>
                        @else
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <span class="text-sm font-medium text-red-700">{{ $extension }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- System Health -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">System Health</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Database Connection</h3>
                        <p class="text-lg font-semibold text-green-600">Healthy</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Storage Space</h3>
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
                        <h3 class="text-sm font-medium text-gray-500">Cache Status</h3>
                        <p class="text-lg font-semibold text-yellow-600">Clearable</p>
                    </div>
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-broom text-yellow-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Log Files</h3>
                        <p class="text-lg font-semibold text-green-600">Writable</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-alt text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Environment</h3>
                        <p class="text-lg font-semibold text-purple-600">{{ config('app.env') }}</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-cog text-purple-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Debug Mode</h3>
                        <p class="text-lg font-semibold text-{{ config('app.debug') ? 'red' : 'green' }}-600">{{ config('app.debug') ? 'Enabled' : 'Disabled' }}</p>
                    </div>
                    <div class="w-10 h-10 bg-{{ config('app.debug') ? 'red' : 'green' }}-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-{{ config('app.debug') ? 'bug' : 'shield-alt' }} text-{{ config('app.debug') ? 'red' : 'green' }}-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actions -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">System Actions</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <button onclick="optimizeApplication()" class="w-full text-left bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg p-4 transition">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-rocket text-blue-600"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-gray-900">Optimize</h3>
                        <p class="text-xs text-gray-500">Cache and config</p>
                    </div>
                </div>
            </button>
            
            <button onclick="runMaintenance()" class="w-full text-left bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg p-4 transition">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-tools text-yellow-600"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-gray-900">Maintenance</h3>
                        <p class="text-xs text-gray-500">System checks</p>
                    </div>
                </div>
            </button>
            
            <button onclick="downloadSystemInfo()" class="w-full text-left bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg p-4 transition">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-download text-green-600"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-gray-900">Export Info</h3>
                        <p class="text-xs text-gray-500">Download report</p>
                    </div>
                </div>
            </button>
            
            <button onclick="refreshSystemInfo()" class="w-full text-left bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg p-4 transition">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-sync text-purple-600"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-gray-900">Refresh</h3>
                        <p class="text-xs text-gray-500">Update data</p>
                    </div>
                </div>
            </button>
        </div>
    </div>
</div>

<script>
function optimizeApplication() {
    showNotification('Optimizing application...', 'info');
    fetch('{{ route('admin.settings.optimize') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Application optimized successfully!', 'success');
        } else {
            showNotification('Optimization failed: ' + data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Error optimizing application', 'error');
    });
}

function runMaintenance() {
    showNotification('Running maintenance tasks...', 'info');
    fetch('{{ route('admin.settings.maintenance') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Maintenance tasks completed!', 'success');
        } else {
            showNotification('Maintenance failed: ' + data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Error running maintenance', 'error');
    });
}

function downloadSystemInfo() {
    window.open('{{ route('admin.settings.export-info') }}', '_blank');
}

function refreshSystemInfo() {
    location.reload();
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'} text-white px-4 py-2 rounded-lg shadow-lg z-50`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection
