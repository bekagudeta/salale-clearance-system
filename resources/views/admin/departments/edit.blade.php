@extends('layouts.admin')

@section('title', 'Edit Department - Admin')
@section('page-title', 'Edit Department')
@section('page-subtitle', 'Update department details and workflow priority')

@section('content')
<div class="space-y-6">
    <div class="surface-card p-6">
        <form action="{{ route('admin.departments.update', $department->id) }}" method="POST" class="space-y-6" id="departmentForm">
            @csrf
            @method('PUT')

            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Department Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $department->name) }}" required class="form-input w-full px-4 py-3 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block mb-2 text-sm font-medium text-gray-700">Department Slug</label>
                    <input id="slug" name="slug" type="text" value="{{ old('slug', $department->slug) }}" required class="form-input w-full px-4 py-3 @error('slug') border-red-500 @enderror">
                    <p class="mt-2 text-sm text-gray-500">Lowercase letters, numbers, and hyphens only.</p>
                    @error('slug')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block mb-2 text-sm font-medium text-gray-700">Category</label>
                    <select id="category" name="category" required class="form-input w-full px-4 py-3 @error('category') border-red-500 @enderror">
                        <option value="service" {{ old('category', $department->category) == 'service' ? 'selected' : '' }}>Service department (clinic, housing, store, ...)</option>
                        <option value="academic" {{ old('category', $department->category) == 'academic' ? 'selected' : '' }}>Academic department (student head/coordinator gate)</option>
                    </select>
                    <p class="mt-2 text-sm text-gray-500">Academic departments approve their own students first, before service departments.</p>
                    @error('category')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="officer_user_id" class="block mb-2 text-sm font-medium text-gray-700">Department Officer</label>
                    <select id="officer_user_id" name="officer_user_id" class="form-input w-full px-4 py-3 @error('officer_user_id') border-red-500 @enderror">
                        <option value="">Select an officer (optional)</option>
                        @foreach($officers as $officer)
                            <option value="{{ $officer->id }}" {{ old('officer_user_id', $department->officer_user_id) == $officer->id ? 'selected' : '' }}>{{ $officer->name }} ({{ $officer->email }})</option>
                        @endforeach
                    </select>
                    @error('officer_user_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="priority_order" class="block mb-2 text-sm font-medium text-gray-700">Priority Order</label>
                    <input id="priority_order" name="priority_order" type="number" min="1" value="{{ old('priority_order', $department->priority_order) }}" class="form-input w-full px-4 py-3 @error('priority_order') border-red-500 @enderror">
                    <p class="mt-2 text-sm text-gray-500">Lower numbers mean higher priority in the clearance workflow.</p>
                    @error('priority_order')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Status</p>
                        <label class="mt-2 inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $department->is_active) ? 'checked' : '' }} class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3 md:flex-row md:justify-end">
                <a href="{{ route('admin.departments.index') }}" class="btn-secondary px-6 py-3 inline-flex items-center justify-center">Cancel</a>
                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-blue-600 text-white hover:bg-blue-700 transition">Update Department</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('name')?.addEventListener('input', function () {
        const slugField = document.getElementById('slug');
        if (!slugField.dataset.edited) {
            slugField.value = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });

    document.getElementById('slug')?.addEventListener('input', function () {
        this.dataset.edited = true;
    });
</script>
@endsection