@extends('layouts.officer')

@section('title', 'Department Dashboard - Salale University')
@section('page-title', 'Department Dashboard')
@section('page-subtitle', 'Approve requests, manage cases, and monitor department activity')

@section('content')
<div class="space-y-6">
    <section class="dashboard-hero overflow-hidden p-6 sm:p-8">
        <div class="grid gap-6 lg:grid-cols-[1.45fr_0.85fr] lg:items-center">
            <div class="space-y-5">
                <p class="dashboard-kicker">Department officer workspace</p>
                <div>
                    <h1 class="dashboard-title text-4xl font-bold sm:text-5xl">Welcome back, {{ auth()->user()->name }}.</h1>
                    <p class="mt-4 max-w-3xl text-sm leading-7 text-[#EAF7F6]/78">
                        Review student clearance requests, resolve department cases, and keep approvals moving through a focused operational dashboard.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('department.approvals.index') }}" class="btn-primary">
                        <i class="fas fa-clock"></i>
                        Review Pending
                    </a>
                    <a href="{{ route('department.statistics') }}" class="btn-secondary border-white/20 bg-white/10 text-white hover:bg-white/15">
                        <i class="fas fa-chart-line"></i>
                        View Reports
                    </a>
                </div>
            </div>

            <div class="rounded-[22px] border border-white/10 bg-white/10 p-5 text-white shadow-xl">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#38C9EB]">Assigned department</p>
                <p class="mt-4 text-3xl font-extrabold leading-tight">{{ $department->name }}</p>
                <p class="mt-2 text-sm text-white/70">Officer Portal</p>
                <div class="mt-6 flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                    <span class="text-sm text-white/70">Pending queue</span>
                    <span class="text-3xl font-extrabold">{{ $stats['pending'] }}</span>
                </div>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
        <div class="surface-card stat-card p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="stat-label">Pending approvals</p>
                    <p class="stat-value mt-3 text-[#92400E]">{{ $stats['pending'] }}</p>
                </div>
                <div class="icon-tile icon-tile-accent">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
            <a href="{{ route('department.approvals.index') }}" class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-[#0E7490] hover:text-[#0B1F2A]">
                Review now
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="surface-card stat-card p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="stat-label">Approved today</p>
                    <p class="stat-value mt-3 text-[#166534]">{{ $stats['approved_today'] }}</p>
                </div>
                <div class="icon-tile icon-tile-success">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>

        <div class="surface-card stat-card p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="stat-label">Rejected today</p>
                    <p class="stat-value mt-3 text-red-600">{{ $stats['rejected_today'] }}</p>
                </div>
                <div class="icon-tile icon-tile-danger">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
            </div>
        </div>

        <div class="surface-card stat-card p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="stat-label">Total processed</p>
                    <p class="stat-value mt-3">{{ $stats['total_processed'] }}</p>
                </div>
                <div class="icon-tile">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <section class="surface-card overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-[#0E7490]/10 bg-[#F0FAFB] px-6 py-5 md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-lg font-bold text-[#0B1F2A]">Recent Approvals</h3>
                <p class="text-sm text-[#64748B]">Latest requests handled by your department.</p>
            </div>
            <a href="{{ route('department.history') }}" class="btn-secondary px-4 py-2 text-sm">
                View History
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="table-shell min-w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs uppercase">Student</th>
                        <th class="px-6 py-4 text-left text-xs uppercase">Reference No</th>
                        <th class="px-6 py-4 text-left text-xs uppercase">Type</th>
                        <th class="px-6 py-4 text-left text-xs uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#0E7490]/10 bg-white">
                    @forelse($recentApprovals as $approval)
                        <tr class="transition hover:bg-[#F8FEFF]">
                            <td class="px-6 py-4 text-sm font-bold text-[#102A32]">{{ $approval->request->student->full_name }}</td>
                            <td class="px-6 py-4 text-sm text-[#64748B]">{{ $approval->request->reference_no }}</td>
                            <td class="px-6 py-4 text-sm text-[#64748B]">{{ ucfirst(str_replace('_', ' ', $approval->request->type)) }}</td>
                            <td class="px-6 py-4">@include('components.status-badge', ['status' => $approval->status])</td>
                            <td class="px-6 py-4 text-sm text-[#64748B]">{{ $approval->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-[#64748B]">
                                <div class="empty-state-icon mx-auto mb-4">
                                    <i class="fas fa-check-circle text-2xl"></i>
                                </div>
                                <p class="font-semibold text-[#102A32]">No recent approvals yet</p>
                                <p class="mt-1 text-sm">Handled requests will appear here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
