@extends('layouts.admin')

@section('title', 'Database Backup - Admin')
@section('page-title', 'Database Backup')
@section('page-subtitle', 'Create and manage database backups')

@section('content')
<div class="space-y-6">
    <!-- Quick Actions -->
    <div class="surface-card p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Backup Management</h2>
            <button onclick="createBackup()" class="btn-primary px-4 py-2 inline-flex items-center gap-2">
                <i class="fas fa-plus"></i> Create New Backup
            </button>
        </div>
        
        <!-- Backup Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-500">Total Backups</p>
                <p class="text-2xl font-bold text-gray-900">{{ count($backups) }}</p>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-500">Latest Backup</p>
                <p class="text-sm font-semibold text-green-600">
                    {{ $backups[0]['date'] ?? 'No backups' }}
                </p>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-500">Total Size</p>
                <p class="text-sm font-semibold text-blue-600">
                    {{ array_sum(array_column($backups, 'size')) }} MB
                </p>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-500">Auto Backup</p>
                <p class="text-sm font-semibold text-purple-600">Disabled</p>
            </div>
        </div>
        
        <!-- Backups List -->
        <div class="space-y-3">
            @forelse($backups as $backup)
                <div class="surface-card rounded-lg p-4 transition hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-database text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">{{ $backup['name'] }}</h3>
                                <p class="text-xs text-gray-500">{{ $backup['date'] }} • {{ $backup['size'] }} MB</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.settings.backup.download', $backup['name']) }}" class="text-blue-600 hover:text-blue-800 p-2">
                                <i class="fas fa-download"></i>
                            </a>
                            <button onclick="confirmDeleteBackup('{{ $backup['name'] }}')" class="text-red-600 hover:text-red-800 p-2">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-database text-4xl mb-4 block text-gray-300"></i>
                    <p class="text-lg font-medium">No backups found</p>
                    <p class="text-sm">Create your first database backup to get started</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Backup Settings -->
    <div class="surface-card p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Backup Settings</h2>
        
        <form method="POST" action="{{ route('admin.settings.backup-schedule') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Auto Backup</label>
                    <select name="auto_backup" class="form-input w-full px-3 py-2">
                        <option value="disabled">Disabled</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Retention Period</label>
                    <select name="retention_days" class="form-input w-full px-3 py-2">
                        <option value="7">7 days</option>
                        <option value="30">30 days</option>
                        <option value="90">90 days</option>
                        <option value="365">1 year</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Backup Modal -->
<div id="deleteBackupModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Delete Backup</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete backup "<span id="backupName"></span>"? This action cannot be undone.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <form id="deleteBackupForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="closeDeleteBackupModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 hover:bg-red-700">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function createBackup() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Creating Backup...';
    
    fetch('{{ route('admin.settings.backup.create') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Backup created successfully!', 'success');
            setTimeout(() => location.reload(), 2000);
        } else {
            showNotification('Failed to create backup: ' + data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Error creating backup', 'error');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function confirmDeleteBackup(name) {
    document.getElementById('backupName').textContent = name;
    document.getElementById('deleteBackupForm').action = '{{ route('admin.settings.backup.delete', ':filename') }}'.replace(':filename', name);
    document.getElementById('deleteBackupModal').classList.remove('hidden');
}

function closeDeleteBackupModal() {
    document.getElementById('deleteBackupModal').classList.add('hidden');
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
