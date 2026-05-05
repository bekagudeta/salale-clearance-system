@extends('layouts.admin')

@section('title', 'Manage Users - Admin')
@section('page-title', 'User Management')
@section('page-subtitle', 'Create, edit, and manage system users')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-lg p-4">
            <p class="text-gray-500 text-sm">Total Users</p>
            <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-blue-50 rounded-xl shadow-lg p-4">
            <p class="text-blue-600 text-sm">Students</p>
            <p class="text-2xl font-bold text-blue-700">{{ $stats['students'] }}</p>
        </div>
        <div class="bg-green-50 rounded-xl shadow-lg p-4">
            <p class="text-green-600 text-sm">Officers</p>
            <p class="text-2xl font-bold text-green-700">{{ $stats['officers'] }}</p>
        </div>
        <div class="bg-purple-50 rounded-xl shadow-lg p-4">
            <p class="text-purple-600 text-sm">Registrars</p>
            <p class="text-2xl font-bold text-purple-700">{{ $stats['registrars'] }}</p>
        </div>
        <div class="bg-red-50 rounded-xl shadow-lg p-4">
            <p class="text-red-600 text-sm">Admins</p>
            <p class="text-2xl font-bold text-red-700">{{ $stats['admins'] }}</p>
        </div>
    </div>
    
    <!-- Actions Bar -->
    <div class="bg-white rounded-xl shadow-lg p-4">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.create') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-purple-700 transition">
                    <i class="fas fa-plus mr-2"></i> Add User
                </a>
                <button onclick="confirmBulkDelete()" class="border border-red-600 text-red-600 px-4 py-2 rounded-lg hover:bg-red-50 transition">
                    <i class="fas fa-trash mr-2"></i> Bulk Delete
                </button>
            </div>
            
            <form method="GET" class="flex gap-2">
                <select name="role" class="px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="all">All Roles</option>
                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Students</option>
                    <option value="department_officer" {{ request('role') == 'department_officer' ? 'selected' : '' }}>Officers</option>
                    <option value="registrar" {{ request('role') == 'registrar' ? 'selected' : '' }}>Registrars</option>
                </select>
                <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}"
                    class="px-3 py-2 border border-gray-300 rounded-lg w-64">
                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
    
    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <form id="bulkDeleteForm" action="{{ route('admin.users.bulk-delete') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                @if(!$user->hasRole('super_admin'))
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-checkbox rounded border-gray-300">
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                        @if($user->student)
                                            <p class="text-xs text-gray-400">ID: {{ $user->student->student_id }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $roleColors = [
                                        'super_admin' => 'bg-red-100 text-red-800',
                                        'registrar' => 'bg-purple-100 text-purple-800',
                                        'department_officer' => 'bg-green-100 text-green-800',
                                        'student' => 'bg-blue-100 text-blue-800',
                                    ];
                                    $userRole = $user->roles->first();
                                @endphp
                                @if($userRole)
                                    <span class="px-2 py-1 text-xs rounded-full {{ $roleColors[$userRole->name] ?? 'bg-gray-100' }}">
                                        {{ ucfirst(str_replace('_', ' ', $userRole->name)) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($user->email_verified_at)
                                    <span class="text-green-600"><i class="fas fa-check-circle"></i> Verified</span>
                                @else
                                    <span class="text-yellow-600"><i class="fas fa-clock"></i> Pending</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!$user->hasRole('super_admin') && $user->id !== auth()->id())
                                        <button onclick="deleteUser({{ $user->id }})" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                    @if(auth()->user()->hasRole('super_admin'))
                                        <a href="{{ route('admin.users.impersonate', $user->id) }}" class="text-purple-600 hover:text-purple-800">
                                            <i class="fas fa-mask"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-users text-4xl mb-2"></i>
                                <p>No users found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
        
        @if(method_exists($users, 'links'))
            <div class="px-6 py-4 border-t">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Delete User Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Delete User</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to delete this user? This action cannot be undone.</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Delete User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function deleteUser(id) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        form.action = '/admin/users/' + id;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    function confirmBulkDelete() {
        const checked = document.querySelectorAll('.user-checkbox:checked');
        if (checked.length === 0) {
            alert('Please select at least one user to delete.');
            return;
        }
        if (confirm(`Are you sure you want to delete ${checked.length} user(s)? This action cannot be undone.`)) {
            document.getElementById('bulkDeleteForm').submit();
        }
    }
    
    document.getElementById('selectAll')?.addEventListener('change', function(e) {
        document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = e.target.checked);
    });
</script>
@endpush
@endsection