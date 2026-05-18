@extends('layouts.officer')

@section('title', 'Approval History - Department')
@section('page-title', 'Approval History')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-[#627f7c] uppercase tracking-[0.24em]">Total Approvals</p>
                    <p class="mt-4 text-4xl font-bold text-[#001722]">{{ $stats['total'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-3xl bg-[#001722]/10 text-[#001722]">
                    <i class="fas fa-list text-xl"></i>
                </div>
            </div>
        </div>

        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-[#627f7c] uppercase tracking-[0.24em]">Approved</p>
                    <p class="mt-4 text-4xl font-bold text-[#084A48]">{{ $stats['approved'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-3xl bg-[#084A48]/10 text-[#084A48]">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>

        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-[#627f7c] uppercase tracking-[0.24em]">Rejected</p>
                    <p class="mt-4 text-4xl font-bold text-[#d33b3b]">{{ $stats['rejected'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-3xl bg-[#FF4D4D]/10 text-[#d33b3b]">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
            </div>
        </div>

        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-[#627f7c] uppercase tracking-[0.24em]">Pending</p>
                    <p class="mt-4 text-4xl font-bold text-[#7f3f08]">{{ $stats['pending'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-3xl bg-[#FE580B]/10 text-[#7f3f08]">
                    <i class="fas fa-hourglass-half text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="surface-card p-6 card-hover transition">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-[#001722]">Quick filters</h3>
                <p class="mt-1 text-sm text-[#627f7c]">Refine history results by status and date range.</p>
            </div>
            <a href="{{ route('department.history') }}" class="btn-secondary px-4 py-2 text-sm">Clear filters</a>
        </div>
        <form method="GET" action="{{ route('department.history') }}" class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-4">
            <div>
                <label class="block text-sm font-semibold text-[#001722] mb-2">Status</label>
                <select name="status" class="form-input w-full">
                    <option value="all">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-[#001722] mb-2">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm font-semibold text-[#001722] mb-2">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-input w-full">
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary w-full px-4 py-3 text-sm flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <div class="surface-card overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#E5F7F6]">
                <thead class="bg-[#072827] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.22em] text-white">Student Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.22em] text-white">Reference No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.22em] text-white">Clearance Type</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.22em] text-white">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.22em] text-white">Submitted Date</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.22em] text-white">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F5FFFE] divide-y divide-[#E5F7F6]">
                    @forelse($history as $item)
                        <tr class="hover:bg-white transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-[#084A48] to-[#6BCFCB] text-white font-semibold text-sm shadow-sm">
                                        {{ strtoupper(substr($item->request->student->full_name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-[#001722]">{{ $item->request->student->full_name }}</p>
                                        <p class="text-xs text-[#627f7c]">{{ $item->request->student->student_id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-[#001722]">{{ $item->request->reference_no }}</td>
                            <td class="px-6 py-4 text-sm text-[#627f7c]">{{ ucfirst(str_replace('_', ' ', $item->request->type)) }}</td>
                            <td class="px-6 py-4">
                                @if($item->status == 'approved')
                                    <span class="inline-flex items-center gap-2 rounded-full bg-[#E6FAF8] px-3 py-1 text-xs font-semibold text-[#084A48]">
                                        <i class="fas fa-check-circle"></i>
                                        Approved
                                    </span>
                                @elseif($item->status == 'rejected')
                                    <span class="inline-flex items-center gap-2 rounded-full bg-[#FFE9E8] px-3 py-1 text-xs font-semibold text-[#B52B2B]">
                                        <i class="fas fa-times-circle"></i>
                                        Rejected
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 rounded-full bg-[#FFF4E5] px-3 py-1 text-xs font-semibold text-[#7f3f08]">
                                        <i class="fas fa-hourglass-half"></i>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-[#627f7c]">{{ $item->request->created_at->format('F d, Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('department.history.show', $item->id) }}" class="inline-flex items-center gap-2 rounded-full bg-[#E6FAF8] px-3 py-2 text-sm font-semibold text-[#084A48] hover:bg-[#d8f3ef] transition">
                                    <i class="fas fa-eye"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <i class="fas fa-inbox text-5xl text-[#9fc6c4] mb-4 block"></i>
                                <p class="text-lg font-semibold text-[#001722]">No approval history found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($history->hasPages())
            <div class="px-6 py-4 border-t border-[#E5F7F6] bg-[#F5FFFE]">
                {{ $history->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
