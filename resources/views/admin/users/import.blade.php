@extends('layouts.admin')

@section('title', 'Import Students - Admin')
@section('page-title', 'Import Students')
@section('page-subtitle', 'Bulk upload students from Excel or CSV')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="surface-card p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Upload file</h3>

            <form action="{{ route('admin.users.import.preview.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Excel or CSV file</label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#E5FCF9] file:text-[#084A48] hover:file:bg-[#C7F0E5] @error('file') border-red-500 @enderror">
                    @error('file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-2">Supported: .xlsx, .xls, .csv (max 5 MB, up to {{ \App\Services\StudentImportService::MAX_ROWS }} rows)</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="btn-primary px-4 py-2 inline-flex items-center gap-2">
                        <i class="fas fa-search"></i> Preview import
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary px-4 py-2 inline-flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Back to users
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="space-y-6">
        <div class="surface-card p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Template</h3>
            <p class="text-sm text-gray-600 mb-4">
                Download the template, fill in your student list, then upload it here. Leave the Password column empty to use a default (<code class="text-xs">Salale@</code> + student ID).
            </p>
            <a href="{{ route('admin.users.import.template') }}" class="btn-primary w-full justify-center px-4 py-2 inline-flex items-center gap-2">
                <i class="fas fa-download"></i> Download template
            </a>
        </div>

        <div class="surface-card p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Required columns</h3>
            <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
                <li>Student ID, Full Name, Email</li>
                <li>Faculty, Department, Year (1–6)</li>
                <li>Semester: First, Second, or Summer</li>
                <li>Phone and Gender are optional</li>
                <li>Password is optional (min 8 characters if set)</li>
            </ul>
        </div>
    </div>
</div>
@endsection
