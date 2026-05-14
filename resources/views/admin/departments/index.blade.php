@extends('layouts.admin')

@section('title', 'Manage Departments - Admin')
@section('page-title', 'Department Management')
@section('page-subtitle', 'Create, edit, and manage system departments')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-3xl shadow-sm p-5 border border-gray-100">
            <p class="text-sm text-gray-500">Total Departments</p>
            <p class="text-3xl font-semibold text-gray-900">{{ $departments->total() }}</p>
        </div>
        <div class="bg-green-50 rounded-3xl shadow-sm p-5 border border-green-100">
            <p class="text-sm text-green-600">Active Departments</p>
            <p class="text-3xl font-semibold text-green-800">{{ $departments->where('is_active', true)->count() }}</p>
        </div>
        <div class="bg-red-50 rounded-3xl shadow-sm p-5 border border-red-100">
            <p class="text-sm text-red-600">Inactive Departments</p>
            <p class="text-3xl font-semibold text-red-800">{{ $departments->where('is_active', false)->count() }}</p>
        </div>
    </div>

    <!-- Actions Bar -->
    <div class="bg-white rounded-3xl shadow-sm p-5 border border-gray-100">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.departments.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-xl shadow-sm hover:from-blue-700 hover:to-purple-700 transition">
                    <i class="fas fa-plus"></i>
                    Add Department
                </a>
                <button id="reorderButton" type="button" onclick="toggleReorderMode()" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-200 transition">
                    <i class="fas fa-sort"></i>
                    Reorder
                </button>
                <button id="saveOrderBtn" type="button" onclick="saveOrder()" class="hidden inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-xl shadow-sm hover:bg-green-700 transition">
                    <i class="fas fa-save"></i>
                    Save order
                </button>
                <button id="cancelOrderBtn" type="button" onclick="toggleReorderMode()" class="hidden inline-flex items-center gap-2 bg-white text-gray-700 px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                    <i class="fas fa-times"></i>
                    Cancel
                </button>
            </div>
            <form method="GET" class="flex items-center gap-2 w-full md:w-auto">
                <label for="searchInput" class="sr-only">Search departments</label>
                <input id="searchInput" type="text" name="search" value="{{ request('search') }}" placeholder="Search departments..." class="w-full md:w-72 px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button type="submit" class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gray-900 text-white hover:bg-gray-800 transition">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Departments Table -->
    <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-gray-100">
        <form id="reorderForm" action="{{ route('admin.departments.reorder') }}" method="POST" class="hidden">
            @csrf
            <div id="reorderInputs"></div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Officer</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($departments as $department)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="departments[]" value="{{ $department->id }}" class="rounded department-checkbox border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $department->name }}</div>
                                <div class="text-sm text-gray-500">{{ $department->slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($department->officer)
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center text-gray-400">
                                            <img class="h-full w-full object-cover" src="{{ $department->officer->photo ?? asset('images/default-avatar.png') }}" alt="Officer photo">
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $department->officer->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $department->officer->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">Not assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-2">
                                    <span class="priority-label text-sm font-medium text-gray-900">{{ $department->priority_order }}</span>
                                    <input
                                        type="number"
                                        min="1"
                                        value="{{ $department->priority_order }}"
                                        data-department-id="{{ $department->id }}"
                                        class="priority-input hidden w-20 px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    />
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($department->is_active)
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center gap-3">
                                <a href="{{ route('admin.departments.edit', $department->id) }}" class="text-blue-600 hover:text-blue-900" title="Edit department">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" onclick="toggleStatus({{ $department->id }}, '{{ $department->is_active ? 'deactivate' : 'activate' }}')" class="text-{{ $department->is_active ? 'red' : 'green' }}-600 hover:text-{{ $department->is_active ? 'red' : 'green' }}-900" title="{{ $department->is_active ? 'Deactivate' : 'Activate' }} department">
                                    <i class="fas fa-{{ $department->is_active ? 'ban' : 'check' }}"></i>
                                </button>
                                <button type="button" onclick="confirmDelete({{ $department->id }}, '{{ $department->name }}')" class="text-red-600 hover:text-red-900" title="Delete department">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-building text-5xl mb-4 block text-gray-300"></i>
                                <p class="text-lg font-medium">No departments found.</p>
                                <p class="mt-2 text-sm text-gray-500">Create your first department to start managing approvals and workflows.</p>
                                <a href="{{ route('admin.departments.create') }}" class="mt-4 inline-flex items-center gap-2 text-blue-600 hover:text-blue-800">Create your first department</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($departments->hasPages())
            <div class="bg-gray-50 px-5 py-4 border-t border-gray-200 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-sm text-gray-600">
                    Showing <span class="font-semibold">{{ $departments->firstItem() }}</span> to <span class="font-semibold">{{ $departments->lastItem() }}</span> of <span class="font-semibold">{{ $departments->total() }}</span> departments
                </div>
                <div>
                    {{ $departments->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden">
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-3xl bg-white shadow-2xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                <h3 class="mt-4 text-xl font-semibold text-gray-900">Delete Department</h3>
                <p class="mt-3 text-sm text-gray-500">Are you sure you want to delete <strong id="departmentName"></strong>? This action cannot be undone.</p>
                <div class="mt-6 flex justify-center gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">Cancel</button>
                    <form id="deleteForm" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 text-white hover:bg-red-700">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="statusModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden">
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-3xl bg-white shadow-2xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-question text-2xl"></i>
                </div>
                <h3 class="mt-4 text-xl font-semibold text-gray-900">Confirm Status Change</h3>
                <p class="mt-3 text-sm text-gray-500">Are you sure you want to <span id="statusAction"></span> this department?</p>
                <div class="mt-6 flex justify-center gap-3">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">Cancel</button>
                    <form id="statusForm" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-xl bg-yellow-600 text-white hover:bg-yellow-700">Confirm</button>
                    </form>
                </div>
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

    function toggleReorderMode() {
        const isEntering = document.getElementById('saveOrderBtn').classList.contains('hidden');

        document.querySelectorAll('.priority-label').forEach(el => el.classList.toggle('hidden', isEntering));
        document.querySelectorAll('.priority-input').forEach(el => el.classList.toggle('hidden', !isEntering));
        document.getElementById('saveOrderBtn').classList.toggle('hidden', !isEntering);
        document.getElementById('cancelOrderBtn').classList.toggle('hidden', !isEntering);
        document.getElementById('reorderButton').textContent = isEntering ? 'Exit reorder' : 'Reorder';
    }

    function saveOrder() {
        const inputs = Array.from(document.querySelectorAll('.priority-input'));
        const values = inputs.map(input => ({
            id: input.dataset.departmentId,
            order: Number(input.value)
        }));

        const invalid = values.some(item => !item.order || item.order <= 0);
        if (invalid) {
            alert('Please enter a valid positive order number for each department.');
            return;
        }

        const sorted = values.sort((a, b) => a.order - b.order);
        const container = document.getElementById('reorderInputs');
        container.innerHTML = '';

        sorted.forEach(item => {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'departments[]';
            hidden.value = item.id;
            container.appendChild(hidden);
        });

        document.getElementById('reorderForm').submit();
    }

    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.department-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>
@endsection
