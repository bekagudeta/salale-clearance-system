@extends('layouts.officer')

@section('title', 'Approval History - Department')
@section('page-title', 'Approval History')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Total Approvals</p>
                    <p class="text-3xl font-extrabold text-slate-900">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-[#001722]/10 flex items-center justify-center text-[#001722]">
                    <i class="fas fa-list text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Approved</p>
                    <p class="text-3xl font-extrabold" style="color:var(--deep-green)">{{ $stats['approved'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-[rgba(8,74,72,0.12)] flex items-center justify-center text-[#084A48]">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Rejected</p>
                    <p class="text-3xl font-extrabold text-[#d33b3b]">{{ $stats['rejected'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-[rgba(238,62,67,0.12)] flex items-center justify-center text-[#d33b3b]">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Pending</p>
                    <p class="text-3xl font-extrabold text-[#d97706]">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-[rgba(255,196,92,0.16)] flex items-center justify-center text-[#9a5b00]">
                    <i class="fas fa-hourglass-half text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="surface-card p-6 card-hover transition">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Quick filters</h3>
                <p class="text-sm text-slate-500">Refine history results by status and date range.</p>
            </div>
            <a href="{{ route('department.history') }}" class="btn-secondary px-4 py-2 text-sm">Clear filters</a>
        </div>
        <form method="GET" action="{{ route('department.history') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                <select name="status" class="w-full form-input">
                    <option value="all">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full form-input">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full form-input">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full btn-primary px-4 py-2 text-sm flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- History Table -->
    <div class="surface-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px]">
                <thead class="bg-gradient-to-r from-[#001722] via-[#084A48] to-[#6BCFCB] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.12em]">Student Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.12em]">Reference No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.12em]">Clearance Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.12em]">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.12em]">Submitted Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.12em]">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-slate-50">
                    @forelse($history as $item)
                    <tr class="hover:bg-white transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-[#084A48] to-[#6BCFCB] flex items-center justify-center text-white font-semibold text-sm shadow-sm">
                                    {{ strtoupper(substr($item->request->student->full_name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $item->request->student->full_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->request->student->student_id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-700">{{ $item->request->reference_no }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ ucfirst(str_replace('_', ' ', $item->request->type)) }}</td>
                        <td class="px-6 py-4">
                            @if($item->status == 'approved')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold badge-teal">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Approved
                                </span>
                            @elseif($item->status == 'rejected')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold badge-danger">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Rejected
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold badge-warning">
                                    <i class="fas fa-hourglass-half mr-1"></i>
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $item->request->created_at->format('F d, Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('department.history.show', $item->id) }}" class="inline-flex items-center px-3 py-2 rounded-full text-sm font-semibold text-[#001722] bg-[#6BCFCB]/10 hover:bg-[#6BCFCB]/20 transition">
                                <i class="fas fa-eye mr-2"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <i class="fas fa-inbox text-slate-400 text-5xl mb-4 block"></i>
                            <p class="text-slate-500 font-semibold">No approval history found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($history->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            {{ $history->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
