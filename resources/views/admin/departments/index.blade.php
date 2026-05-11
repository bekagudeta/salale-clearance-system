@extends('layouts.admin')

@section('title', 'Manage Departments - Admin')
@section('page-title', 'Department Management')
@section('page-subtitle', 'Create, edit, and manage system departments')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-lg p-4">
            <p class="text-gray-500 text-sm">Total Departments</p>
            <p class="text-2xl font-bold">{{ $departments->total() }}</p>
        </div>
        <div class="bg-green-50 rounded-xl shadow-lg p-4">
            <p class="text-green-600 text-sm">Active Departments</p>
            <p class="text-2xl font-bold text-green-700">{{ $departments->where('is_active', true)->count() }}</p>
        </div>
        <div class="bg-red-50 rounded-xl shadow-lg p-4">
            <p class="text-red-600 text-sm">Inactive Departments</p>
            <p class="text-2xl font-bold text-red-700">{{ $departments->where('is_active', false)->count() }}</p>
        </div>
    </div>
    
    <!-- Actions Bar -->
    <div class="bg-white rounded-xl shadow-lg p-4">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div class="flex space-x-3">
                <a href="{{ route('admin.departments.create') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-purple-700 transition">
                    <i class="fas fa-plus mr-2"></i> Add Department
                </a>
                <button onclick="confirmBulkReorder()" class="border border-green-600 text-green-600 px-4 py-2 rounded-lg hover:bg-green-50 transition">
                    <i class="fas fa-sort mr-2"></i> Reorder
                </button>
            </div>
            
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search departments..." class="px-3 py-2 border border-gray-300 rounded-lg">
                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
    
    <!-- Departments Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAll" class="rounded">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Department
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Officer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Priority
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($departments as $department)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="departments[]" value="{{ $department->id }}" class="rounded department-checkbox">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $department->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $department->slug }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($department->officer)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <img class="h-8 w-8 rounded-full" src="{{ $department->officer->photo ?? asset('images/default-avatar.png') }}" alt="">
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $department->officer->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $department->officer->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">Not assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $department->priority_order }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($department->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.departments.edit', $department->id) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="toggleStatus({{ $department->id }}, '{{ $department->is_active ? 'deactivate' : 'activate' }}')" class="text-{{ $department->is_active ? 'red' : 'green' }}-600 hover:text-{{ $department->is_active ? 'red' : 'green' }}-900">
                                        <i class="fas fa-{{ $department->is_active ? 'ban' : 'check' }}"></i>
                                    </button>
                                    <button onclick="confirmDelete({{ $department->id }}, '{{ $department->name }}')" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-building text-4xl mb-4 block text-gray-300"></i>
                                No departments found. 
                                <a href="{{ route('admin.departments.create') }}" class="text-blue-600 hover:text-blue-800">Create your first department</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($departments->hasPages())
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    {{ $departments->links() }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ $departments->firstItem() }}</span>
                            to
                            <span class="font-medium">{{ $departments->lastItem() }}</span>
                            of
                            <span class="font-medium">{{ $departments->total() }}</span>
                            results
                        </p>
                    </div>
                    <div>
                        {{ $departments->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Delete Department</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete "<span id="departmentName"></span>"? This action cannot be undone.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-gray-600">
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

<!-- Status Toggle Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                <i class="fas fa-question text-yellow-600"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Confirm Status Change</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to <span id="statusAction"></span> this department?
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <form id="statusForm" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-yellow-600 text-white text-base font-medium rounded-md w-24 hover:bg-yellow-700">
                        Confirm
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('departmentName').textContent = name;
    document.getElementById('deleteForm').action = '{{ route('admin.departments.destroy', ':id') }}'.replace(':id', id);
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function toggleStatus(id, action) {
    document.getElementById('statusAction').textContent = action;
    document.getElementById('statusForm').action = '{{ route('admin.departments.toggle-status', ':id') }}'.replace(':id', id);
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.department-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
});

function confirmBulkReorder() {
    alert('Bulk reorder functionality will be implemented');
}
</script>
@endsection
