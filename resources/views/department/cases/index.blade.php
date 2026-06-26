@extends('layouts.officer')

@section('title', 'Student Cases - Department')
@section('page-title', 'Student Cases')

@section('content')
<div class="space-y-6">
    <div class="surface-card p-6 shadow-xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.28em] text-[#6BCFCB]">Case registry</p>
                <h2 class="mt-3 text-3xl font-bold text-[#001722]">Record student cases</h2>
                <p class="mt-2 max-w-2xl text-sm text-[#627f7c]">
                    Record issues before students request clearance — borrowed books, unpaid fees, missing items, and similar.
                    These cases are checked automatically when a clearance request arrives.
                </p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row">
                <div class="inline-flex items-center gap-2 rounded-full bg-[#FE580B]/10 px-4 py-3 text-sm text-[#7f3f08] shadow-sm">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $openCount }} open cases
                </div>
                <a href="{{ route('department.cases.create') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-5 py-3 text-sm">
                    <i class="fas fa-plus"></i>
                    Record new case
                </a>
            </div>
        </div>
    </div>

    <div class="surface-card p-6 shadow-lg">
        <form method="GET" action="{{ route('department.cases.index') }}" class="flex flex-col gap-4 md:flex-row md:items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-[#001722] mb-2">Search by Student ID</label>
                <input type="text" name="student_id" value="{{ request('student_id') }}" placeholder="e.g. SAL/2024/001" class="form-input w-full">
            </div>
            <div class="md:w-48">
                <label class="block text-sm font-medium text-[#001722] mb-2">Status</label>
                <select name="status" class="form-input w-full">
                    <option value="">All</option>
                    <option value="open" @selected(request('status') === 'open')>Open</option>
                    <option value="cleared" @selected(request('status') === 'cleared')>Cleared</option>
                </select>
            </div>
            <button type="submit" class="btn-secondary px-5 py-3 text-sm">Search</button>
            @if(request()->hasAny(['student_id', 'status']))
                <a href="{{ route('department.cases.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#084A48]/10 px-5 py-3 text-sm font-semibold text-[#084A48] hover:bg-[#F5FFFE] transition">Clear</a>
            @endif
        </form>
    </div>

    @if($cases->count() > 0)
        <div class="surface-card overflow-hidden shadow-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#E5F7F6]">
                    <thead class="bg-[#F5FFFE]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em] text-[#084A48]">Student</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em] text-[#084A48]">Case</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em] text-[#084A48]">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em] text-[#084A48]">Recorded</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.2em] text-[#084A48]">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5F7F6] bg-white">
                        @foreach($cases as $case)
                            <tr class="hover:bg-[#F5FFFE] transition">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-[#001722]">{{ $case->student->full_name }}</p>
                                    <p class="text-sm text-[#627f7c]">{{ $case->student->student_id }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-[#001722]">{{ $case->title }}</p>
                                    @if($case->description)
                                        <p class="mt-1 text-sm text-[#627f7c]">{{ $case->description }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($case->status === 'open')
                                        <span class="inline-flex items-center gap-2 rounded-full border border-[#FE580B]/15 bg-[#FE580B]/10 px-3 py-1 text-xs font-semibold text-[#7f3f08]">
                                            <i class="fas fa-exclamation-circle"></i> Open
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 rounded-full border border-[#084A48]/15 bg-[#084A48]/10 px-3 py-1 text-xs font-semibold text-[#084A48]">
                                            <i class="fas fa-check"></i> Cleared
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-[#627f7c]">
                                    {{ $case->created_at->format('M d, Y') }}
                                    <span class="block text-xs">by {{ $case->recorder->name ?? 'System' }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($case->isOpen())
                                        <form action="{{ route('department.cases.clear', $case->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="btn-primary px-4 py-2 text-xs" onclick="return confirm('Mark this case as cleared?')">Mark cleared</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-[#627f7c]">Cleared {{ $case->cleared_at?->format('M d, Y') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($cases->hasPages())
                <div class="border-t border-[#E5F7F6] px-6 py-4">
                    {{ $cases->withQueryString()->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="surface-card p-12 text-center shadow-xl">
            <div class="mx-auto mb-4 inline-flex h-20 w-20 items-center justify-center rounded-full bg-[#E6FAF8] text-[#084A48] shadow-sm">
                <i class="fas fa-folder-open text-4xl"></i>
            </div>
            <h3 class="text-2xl font-semibold text-[#001722] mb-2">No cases recorded yet</h3>
            <p class="text-sm text-[#627f7c] mb-6">Start by recording student cases before they submit clearance requests.</p>
            <a href="{{ route('department.cases.create') }}" class="btn-primary inline-flex items-center gap-2 px-6 py-3 text-sm">
                <i class="fas fa-plus"></i>
                Record first case
            </a>
        </div>
    @endif
</div>
@endsection
