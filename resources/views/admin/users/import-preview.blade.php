@extends('layouts.admin')

@section('title', 'Import Preview - Admin')
@section('page-title', 'Import Preview')
@section('page-subtitle', 'Review students before importing')

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="surface-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Total Records</p>
                    <p class="text-3xl font-bold text-[#001722]">{{ $preview['summary']['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-600">
                    <i class="fas fa-file-upload text-xl"></i>
                </div>
            </div>
        </div>

        <div class="surface-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Valid Records</p>
                    <p class="text-3xl font-bold text-[#084A48]">{{ $preview['summary']['valid'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#6BCFCB]/20 rounded-full flex items-center justify-center text-[#084A48]">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>

        <div class="surface-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Invalid Records</p>
                    <p class="text-3xl font-bold text-[#FE580B]">{{ $preview['summary']['invalid'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#FE580B]/20 rounded-full flex items-center justify-center text-[#FE580B]">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert -->
    @if($preview['summary']['invalid'] > 0)
    <div class="bg-[#FE580B]/10 border border-[#FE580B]/30 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <i class="fas fa-info-circle text-[#FE580B] mt-1"></i>
            <div>
                <p class="font-semibold text-[#FE580B]">{{ $preview['summary']['invalid'] }} invalid record(s) detected</p>
                <p class="text-sm text-[#FE580B]/80 mt-1">Invalid records will be skipped during import. Review the details below and correct any data in your file before re-importing.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Data Table -->
    <div class="surface-card rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="text-lg font-semibold text-slate-900">Student Records</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Row</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Student ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Full Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Faculty</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Year</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($preview['rows'] as $entry)
                        <tr class="{{ $entry['status'] === 'invalid' ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $entry['row_number'] }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $entry['data']['student_id'] }}</td>
                            <td class="px-6 py-4 text-sm text-slate-900">{{ $entry['data']['full_name'] }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $entry['data']['email'] }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $entry['data']['faculty'] }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $entry['data']['department'] }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $entry['data']['year'] }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($entry['status'] === 'valid')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-[#084A48]/15 text-[#084A48] rounded-full text-xs font-semibold">
                                        <i class="fas fa-check"></i> Valid
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-[#FE580B]/15 text-[#FE580B] rounded-full text-xs font-semibold">
                                        <i class="fas fa-exclamation"></i> Invalid
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @if($entry['status'] === 'invalid' && !empty($entry['errors']))
                            <tr class="bg-red-50">
                                <td colspan="8" class="px-6 py-3">
                                    <div class="text-sm">
                                        <p class="font-semibold text-red-700 mb-1">Errors:</p>
                                        <ul class="list-disc list-inside text-red-600 space-y-0.5">
                                            @foreach($entry['errors'] as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                <i class="fas fa-inbox text-2xl mb-2 block opacity-50"></i>
                                No records found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-3">
        @if($preview['summary']['valid'] > 0)
            <form action="{{ route('admin.users.import.process') }}" method="POST" class="flex gap-3">
                @csrf
                <button type="submit" class="btn-primary px-6 py-2 inline-flex items-center gap-2">
                    <i class="fas fa-upload"></i> Import {{ $preview['summary']['valid'] }} valid record(s)
                </button>
            </form>
        @else
            <button type="button" disabled class="btn-primary px-6 py-2 inline-flex items-center gap-2 opacity-50 cursor-not-allowed">
                <i class="fas fa-upload"></i> No valid records to import
            </button>
        @endif

        <form action="{{ route('admin.users.import.cancel') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="btn-secondary px-6 py-2 inline-flex items-center gap-2">
                <i class="fas fa-times"></i> Cancel
            </button>
        </form>
    </div>

    <!-- Instructions -->
    <div class="surface-card p-6 bg-blue-50 border border-blue-200">
        <h3 class="font-semibold text-blue-900 mb-2">
            <i class="fas fa-lightbulb mr-2"></i> Tips
        </h3>
        <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
            <li>Only valid records will be imported. Invalid records will be skipped.</li>
            <li>Duplicate student IDs and emails (already in system) will be marked as invalid.</li>
            <li>If the password column is empty, a default password will be generated automatically.</li>
            <li>You can cancel this import and correct the file if needed.</li>
        </ul>
    </div>
</div>
@endsection
