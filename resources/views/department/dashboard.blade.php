@extends('layouts.officer')

@section('title', 'Department Dashboard - Salale University')
@section('page-title', 'Department Dashboard')

@section('content')
<div class="space-y-6">
    <div class="surface-card overflow-hidden rounded-[28px] p-6 shadow-xl">
        <div class="grid gap-6 lg:grid-cols-[2fr_1fr] lg:items-center">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-[#6BCFCB]">Department officer dashboard</p>
                <h1 class="mt-4 text-4xl font-extrabold text-[#001722]">Welcome back, {{ auth()->user()->name }}.</h1>
                <p class="mt-3 max-w-2xl text-sm text-[#627f7c]">Manage your department approvals, monitor recent activity, and stay on top of clearance requests with a clean, efficient workspace.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('department.approvals.index') }}" class="btn-primary">Review Pending</a>
                    <a href="{{ route('department.statistics') }}" class="btn-secondary">View Reports</a>
                </div>
            </div>
            <div class="rounded-[28px] bg-gradient-to-br from-[#084A48] to-[#6BCFCB] p-6 text-white shadow-2xl">
                <div class="flex h-full flex-col justify-between gap-6">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-[#E5FCF9]/90">Department</p>
                        <p class="mt-3 text-2xl font-semibold">{{ $department->name }}</p>
                        <p class="mt-2 text-sm text-[#E5FCF9]/80">Officer Portal</p>
                    </div>
                    <div class="flex items-center justify-center rounded-3xl bg-white/10 p-6">
                        <i class="fas fa-building text-5xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-[#627f7c] uppercase tracking-[0.24em]">Pending Approvals</p>
                    <p class="mt-4 text-4xl font-bold text-[#7f3f08]">{{ $stats['pending'] }}</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-[#FE580B]/10 text-[#FE580B] shadow-sm">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
            <a href="{{ route('department.approvals.index') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-[#084A48] hover:text-[#001722]">Review now →</a>
        </div>

        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-[#627f7c] uppercase tracking-[0.24em]">Approved Today</p>
                    <p class="mt-4 text-4xl font-bold text-[#084A48]">{{ $stats['approved_today'] }}</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-[#084A48]/10 text-[#084A48] shadow-sm">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>

        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-[#627f7c] uppercase tracking-[0.24em]">Rejected Today</p>
                    <p class="mt-4 text-4xl font-bold text-[#d33b3b]">{{ $stats['rejected_today'] }}</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-[#FF4D4D]/10 text-[#d33b3b] shadow-sm">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
            </div>
        </div>

        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-[#627f7c] uppercase tracking-[0.24em]">Total Processed</p>
                    <p class="mt-4 text-4xl font-bold text-[#084A48]">{{ $stats['total_processed'] }}</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-[#6BCFCB]/10 text-[#084A48] shadow-sm">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="surface-card overflow-hidden shadow-xl">
        <div class="px-6 py-5 border-b border-[#084A48]/10 bg-[#F5FFFE]">
            <h3 class="text-xl font-semibold text-[#001722]">Recent Approvals</h3>
            <p class="mt-1 text-sm text-[#627f7c]">Latest requests handled by your department.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#E5F7F6]">
                <thead class="bg-[#072827] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.2em] text-white">Student</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.2em] text-white">Reference No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.2em] text-white">Type</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.2em] text-white">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.2em] text-white">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F5FFFE] divide-y divide-[#E5F7F6]">
                    @forelse($recentApprovals as $approval)
                        <tr class="hover:bg-white transition">
                            <td class="px-6 py-4 text-sm font-semibold text-[#001722]">{{ $approval->request->student->full_name }}</td>
                            <td class="px-6 py-4 text-sm text-[#627f7c]">{{ $approval->request->reference_no }}</td>
                            <td class="px-6 py-4 text-sm text-[#627f7c]">{{ ucfirst(str_replace('_', ' ', $approval->request->type)) }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $approval->status == 'approved' ? 'badge-teal' : 'badge-accent' }}">
                                    {{ ucfirst($approval->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-[#627f7c]">{{ $approval->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-[#627f7c]">No recent approvals yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection