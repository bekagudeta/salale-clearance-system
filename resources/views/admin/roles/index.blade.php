@extends('layouts.admin')

@section('title', 'Role Management - Admin')
@section('page-title', 'Role & Permission Management')
@section('page-subtitle', 'Manage user roles and permissions')

@section('content')
<div class="space-y-6">
    <!-- Create Role -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-800">Create New Role</h3>
            <button onclick="openCreateRoleModal()" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-purple-700 transition">
                <i class="fas fa-plus mr-2"></i> Add Role
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Permissions</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Users Count</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($roles as $role)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <span class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach($role->permissions->take(3) as $permission)
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-full">{{ $permission->name }}</span>
                                @endforeach
                                @if($role->permissions->count() > 3)
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">+{{ $role->permissions->count() - 3 }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $role->users_count ?? 0 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                <button onclick="editRole({{ $role->id }}, '{{ $role->name }}', {{ json_encode($role->permissions->pluck('id')) }})" 
                                        class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if(!in_array($role->name, ['super_admin', 'student', 'department_officer', 'registrar']))
                                    <button onclick="deleteRole({{ $role->id }})" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Permissions List -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-800">System Permissions</h3>
            <button onclick="openCreatePermissionModal()" class="border border-purple-600 text-purple-600 px-4 py-2 rounded-lg hover:bg-purple-50 transition">
                <i class="fas fa-plus mr-2"></i> Add Permission
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($permissions as $permission)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                <button onclick="deletePermission({{ $permission->id }})" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash-alt text-sm"></i>
                </button>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Create/Edit Role Modal -->
<div id="roleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 id="roleModalTitle" class="text-lg font-semibold text-gray-800">Create Role</h3>
                <button onclick="closeRoleModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="roleForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role Name</label>
                    <input type="text" id="roleName" name="name" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                    <div class="grid grid-cols-2 gap-2 border rounded-lg p-3 max-h-64 overflow-y-auto">
                        @foreach($permissions as $permission)
                        <label class="flex items-center">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="permission-checkbox rounded border-gray-300 mr-2">
                            <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRoleModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700">
                        Save Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentRoleId = null;
    
    function openCreateRoleModal() {
        currentRoleId = null;
        document.getElementById('roleModalTitle').textContent = 'Create Role';
        document.getElementById('roleForm').action = '{{ route("admin.roles.store") }}';
        document.getElementById('roleName').value = '';
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('roleModal').classList.remove('hidden');
        document.getElementById('roleModal').classList.add('flex');
    }
    
    function editRole(id, name, permissions) {
        currentRoleId = id;
        document.getElementById('roleModalTitle').textContent = 'Edit Role';
        document.getElementById('roleForm').action = '/admin/roles/' + id;
        document.getElementById('roleForm').method = 'POST';
        document.getElementById('roleName').value = name;
        
        // Add method spoofing for PUT
        let methodInput = document.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            document.getElementById('roleForm').appendChild(methodInput);
        }
        methodInput.value = 'PUT';
        
        document.querySelectorAll('.permission-checkbox').forEach(cb => {
            cb.checked = permissions.includes(parseInt(cb.value));
        });
        
        document.getElementById('roleModal').classList.remove('hidden');
        document.getElementById('roleModal').classList.add('flex');
    }
    
    function closeRoleModal() {
        document.getElementById('roleModal').classList.add('hidden');
        document.getElementById('roleModal').classList.remove('flex');
    }
    
    function deleteRole(id) {
        if (confirm('Are you sure you want to delete this role? This action cannot be undone.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/roles/' + id;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    function openCreatePermissionModal() {
        const permissionName = prompt('Enter permission name (e.g., "view reports"):');
        if (permissionName) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.roles.permissions.store") }}';
            form.innerHTML = '@csrf <input type="hidden" name="name" value="' + permissionName + '">';
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    function deletePermission(id) {
        if (confirm('Are you sure you want to delete this permission?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/permissions/' + id;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
@endsection