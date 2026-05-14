@extends('layouts.admin')

@section('title', 'Role Management - Admin')
@section('page-title', 'Role & Permission Management')
@section('page-subtitle', 'Manage user roles and permissions with clarity and control')

@section('content')
<div class="space-y-6">
    <div class="grid gap-6 xl:grid-cols-3">
        <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
            <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600">Roles</h4>
            <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $roles->count() }}</p>
            <p class="mt-2 text-sm text-gray-500">Total roles available for assignment.</p>
        </div>
        <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
            <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-purple-600">Permissions</h4>
            <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $permissions->count() }}</p>
            <p class="mt-2 text-sm text-gray-500">Global permissions configured for your system.</p>
        </div>
        <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
            <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-600">Protected roles</h4>
            <p class="mt-3 text-3xl font-semibold text-slate-900">4</p>
            <p class="mt-2 text-sm text-gray-500">Core roles that cannot be deleted from the UI.</p>
        </div>
    </div>

    <div class="rounded-[32px] border border-gray-100 bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 p-6 shadow-2xl text-white">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-2xl font-semibold">Role & Permission Center</h2>
                <p class="mt-2 max-w-xl text-sm text-slate-300">Quickly search existing roles, update permissions, and keep system access organized.</p>
            </div>
            <button onclick="openCreateRoleModal()" class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-900 shadow-lg transition hover:bg-slate-100">
                <i class="fas fa-plus"></i>
                New Role
            </button>
        </div>

        <div class="mt-6 grid gap-4 lg:grid-cols-[1fr_320px]">
            <div class="rounded-3xl bg-slate-900/70 p-5 shadow-inner border border-white/10">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-slate-300">Search roles</p>
                        <p class="mt-1 text-sm text-slate-400">Filter the role list instantly by name.</p>
                    </div>
                    <div class="w-full sm:w-72">
                        <input id="roleSearch" oninput="filterRoles()" type="search" placeholder="Search roles..." class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-sm text-white outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30" />
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-slate-900/70 p-5 shadow-inner border border-white/10">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-slate-300">Active roles</p>
                        <p class="mt-1 text-lg font-semibold text-white">{{ $roles->count() }} stored</p>
                    </div>
                    <span class="inline-flex rounded-full bg-white/10 px-3 py-1 text-sm text-slate-200">{{ $permissions->count() }} permissions</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.4fr_0.9fr]">
        <section class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-slate-900">Roles overview</h3>
                    <p class="mt-1 text-sm text-slate-500">Manage roles and adjust permissions safely.</p>
                </div>
                <button onclick="openCreateRoleModal()" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-600 to-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-sky-200/25 transition hover:from-sky-700 hover:to-indigo-700">
                    <i class="fas fa-plus"></i>
                    Add Role
                </button>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-xs uppercase tracking-[0.2em] text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Permissions</th>
                            <th class="px-4 py-3">Users</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="rolesTableBody" class="divide-y divide-slate-200">
                        @forelse($roles as $role)
                            <tr class="group hover:bg-slate-50 transition-colors" data-role-name="{{ strtolower($role->name) }}">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700">{{ strtoupper(substr($role->name, 0, 1)) }}</span>
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</p>
                                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">{{ $role->guard_name }} guard</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @forelse($role->permissions->take(4) as $permission)
                                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">{{ $permission->name }}</span>
                                        @empty
                                            <span class="text-sm text-slate-500">No permissions assigned</span>
                                        @endforelse
                                        @if($role->permissions->count() > 4)
                                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-500">+{{ $role->permissions->count() - 4 }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-slate-700">{{ $role->users_count ?? 0 }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <button type="button" onclick="editRole({{ $role->id }}, @json($role->name), @json($role->permissions->pluck('id')) )" class="rounded-full border border-slate-200 bg-white px-3 py-2 text-slate-700 transition hover:border-slate-300 hover:text-slate-900" aria-label="Edit role">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if(!in_array($role->name, ['super_admin', 'student', 'department_officer', 'registrar']))
                                            <button type="button" onclick="deleteRole({{ $role->id }})" class="rounded-full border border-red-200 bg-red-50 px-3 py-2 text-red-600 transition hover:bg-red-100" aria-label="Delete role">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-2 text-xs font-medium text-slate-500">Protected</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-sm text-slate-500">No roles found yet. Add a new role to get started.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-slate-900">System permissions</h3>
                    <p class="mt-1 text-sm text-slate-500">Review, add, or remove permissions used by roles.</p>
                </div>
                <button onclick="openCreatePermissionModal()" class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-semibold text-purple-600 shadow-sm shadow-purple-100 transition hover:bg-purple-50">
                    <i class="fas fa-plus"></i>
                    Add Permission
                </button>
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                @forelse($permissions as $permission)
                    <div class="flex items-center justify-between rounded-3xl border border-slate-100 bg-slate-50 px-4 py-3 shadow-sm">
                        <span class="text-sm text-slate-700">{{ $permission->name }}</span>
                        <button type="button" onclick="deletePermission({{ $permission->id }})" class="text-red-600 transition hover:text-red-800" aria-label="Delete permission">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                @empty
                    <div class="col-span-full rounded-3xl border border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                        No permissions configured yet.
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>

<div id="roleModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="w-full max-w-2xl overflow-hidden rounded-3xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
            <div>
                <h3 id="roleModalTitle" class="text-xl font-semibold text-slate-900">Create Role</h3>
                <p class="text-sm text-slate-500">Add a new role and select its permissions.</p>
            </div>
            <button type="button" onclick="closeRoleModal()" class="text-slate-400 hover:text-slate-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="roleForm" method="POST" class="space-y-6 px-6 py-6">
            @csrf
            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="roleName" class="mb-2 block text-sm font-medium text-slate-700">Role Name</label>
                    <input type="text" id="roleName" name="name" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200" />
                </div>
                <div class="lg:col-span-2">
                    <label class="mb-3 block text-sm font-medium text-slate-700">Permissions</label>
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 rounded-3xl border border-slate-200 bg-slate-50 p-4 max-h-72 overflow-y-auto">
                        @foreach($permissions as $permission)
                            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-transparent bg-white px-3 py-2 text-sm transition hover:border-slate-300">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="permission-checkbox h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                <span class="text-slate-700">{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeRoleModal()" class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-700 to-sky-600 px-5 py-3 text-sm font-semibold text-white hover:from-slate-800 hover:to-sky-700">Save Role</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const roleForm = document.getElementById('roleForm');
    const methodFieldName = '_method';

    function openCreateRoleModal() {
        clearRoleForm();
        roleForm.action = '{{ route('admin.roles.store') }}';
        setRoleModalTitle('Create Role');
        removeMethodField();
        toggleRoleModal(true);
    }

    function editRole(id, name, permissions) {
        clearRoleForm();
        roleForm.action = '{{ route('admin.roles.update', [':id']) }}'.replace(':id', id);
        setMethodField('PUT');
        setRoleModalTitle('Edit Role');
        document.getElementById('roleName').value = name;
        document.querySelectorAll('.permission-checkbox').forEach(cb => {
            cb.checked = permissions.includes(parseInt(cb.value, 10));
        });
        toggleRoleModal(true);
    }

    function clearRoleForm() {
        document.getElementById('roleName').value = '';
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
        removeMethodField();
    }

    function setRoleModalTitle(title) {
        document.getElementById('roleModalTitle').textContent = title;
    }

    function toggleRoleModal(show) {
        const modal = document.getElementById('roleModal');
        modal.classList.toggle('hidden', !show);
        modal.classList.toggle('flex', show);
    }

    function setMethodField(value) {
        removeMethodField();
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = methodFieldName;
        input.value = value;
        roleForm.appendChild(input);
    }

    function removeMethodField() {
        const existing = document.querySelector('#roleForm input[name="_method"]');
        if (existing) existing.remove();
    }

    function closeRoleModal() {
        toggleRoleModal(false);
    }

    function filterRoles() {
        const query = document.getElementById('roleSearch').value.toLowerCase();
        document.querySelectorAll('#rolesTableBody tr[data-role-name]').forEach(row => {
            const name = row.getAttribute('data-role-name');
            row.style.display = name.includes(query) ? '' : 'none';
        });
    }

    function deleteRole(id) {
        if (!confirm('Are you sure you want to delete this role? This action cannot be undone.')) {
            return;
        }
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('admin.roles.destroy', [':id']) }}'.replace(':id', id);
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }

    function openCreatePermissionModal() {
        const modal = document.createElement('div');
        modal.id = 'permissionModal';
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4';
        modal.innerHTML = `
            <div class="w-full max-w-lg overflow-hidden rounded-3xl bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900">Add Permission</h3>
                        <p class="text-sm text-slate-500">Create a new system permission.</p>
                    </div>
                    <button type="button" onclick="closePermissionModal()" class="text-slate-400 hover:text-slate-700"><i class="fas fa-times"></i></button>
                </div>
                <form method="POST" action="{{ route('admin.roles.permissions.store') }}" class="space-y-6 px-6 py-6">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Permission Name</label>
                        <input name="name" type="text" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 shadow-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200" />
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closePermissionModal()" class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</button>
                        <button type="submit" class="rounded-2xl bg-purple-600 px-5 py-3 text-sm font-semibold text-white hover:bg-purple-700">Create Permission</button>
                    </div>
                </form>
            </div>
        `;
        document.body.appendChild(modal);
    }

    function closePermissionModal() {
        const modal = document.getElementById('permissionModal');
        if (modal) modal.remove();
    }

    function deletePermission(id) {
        if (!confirm('Are you sure you want to delete this permission?')) {
            return;
        }
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('admin.roles.permissions.destroy', [':id']) }}'.replace(':id', id);
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endpush
@endsection