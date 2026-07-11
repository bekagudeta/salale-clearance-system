@extends('layouts.admin')

@section('title', 'Admin Dashboard - Salale University')
@section('page-title', 'Admin Dashboard')
@section('page-subtitle', 'System operations, users, departments, and platform health')

@section('content')
@php
    $totalClearances = (int) ($stats['total_clearances'] ?? 0);
    $completedClearances = (int) ($stats['completed_clearances'] ?? 0);
    $completionRate = $totalClearances > 0 ? round(($completedClearances / $totalClearances) * 100) : 0;
@endphp

<div class="space-y-6">
    <section class="dashboard-hero overflow-hidden p-6 sm:p-8">
        <div class="grid gap-6 lg:grid-cols-[1.45fr_0.85fr] lg:items-center">
            <div class="space-y-5">
                <p class="dashboard-kicker">Administration control center</p>
                <div>
                    <h1 class="dashboard-title text-4xl font-bold sm:text-5xl">Run the clearance platform with confidence.</h1>
                    <p class="mt-4 max-w-3xl text-sm leading-7 text-[#EAF7F6]/78">
                        Monitor users, departments, activity, and clearance performance from one polished workspace aligned with the Salale University portal identity.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.users.index') }}" class="btn-primary">
                        <i class="fas fa-users"></i>
                        Manage Users
                    </a>
                    <a href="{{ route('admin.departments.index') }}" class="btn-secondary border-white/20 bg-white/10 text-white hover:bg-white/15">
                        <i class="fas fa-building"></i>
                        Departments
                    </a>
                </div>
            </div>
            <div class="rounded-[22px] border border-white/10 bg-white/10 p-5 text-white shadow-xl">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#38C9EB]">System snapshot</p>
                <div class="mt-5 grid gap-3">
                    <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                        <span class="text-sm text-white/72">Completion rate</span>
                        <span class="text-2xl font-extrabold">{{ $completionRate }}%</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                        <span class="text-sm text-white/72">Pending jobs</span>
                        <span class="text-2xl font-extrabold">{{ $systemHealth['pending_jobs'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                        <span class="text-sm text-white/72">Database size</span>
                        <span class="text-2xl font-extrabold">{{ $systemHealth['database_size'] ?? 0 }} MB</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
        <div class="surface-card stat-card p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="stat-label">Total users</p>
                    <p class="stat-value mt-3">{{ $stats['total_users'] }}</p>
                </div>
                <div class="icon-tile">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <div class="surface-card stat-card p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="stat-label">Clearance requests</p>
                    <p class="stat-value mt-3">{{ $totalClearances }}</p>
                </div>
                <div class="icon-tile">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
            </div>
        </div>

        <div class="surface-card stat-card p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="stat-label">Completed</p>
                    <p class="stat-value mt-3 text-[#166534]">{{ $completedClearances }}</p>
                </div>
                <div class="icon-tile icon-tile-success">
                    <i class="fas fa-check-double text-xl"></i>
                </div>
            </div>
        </div>

        <div class="surface-card stat-card p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="stat-label">Active departments</p>
                    <p class="stat-value mt-3">{{ $stats['active_departments'] }}</p>
                </div>
                <div class="icon-tile icon-tile-accent">
                    <i class="fas fa-building text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.25fr_0.75fr]">
        <section class="surface-card overflow-hidden">
            <div class="flex flex-col gap-2 border-b border-[#0E7490]/10 bg-[#F0FAFB] px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-bold text-[#0B1F2A]">Recent System Activities</h3>
                    <p class="text-sm text-[#64748B]">Latest administrative and workflow events.</p>
                </div>
                <a href="{{ route('admin.logs.index') }}" class="btn-secondary px-4 py-2 text-sm">
                    <i class="fas fa-history"></i>
                    Activity Logs
                </a>
            </div>
            <div class="divide-y divide-[#0E7490]/10">
                @forelse($recentActivities as $activity)
                    <div class="flex items-start gap-4 p-5 transition hover:bg-[#F8FEFF]">
                        <div class="icon-tile h-11 w-11 rounded-2xl">
                            <i class="fas fa-history"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-[#102A32]">{{ $activity->description }}</p>
                            <p class="mt-1 text-xs text-[#64748B]">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-[#64748B]">
                        <div class="empty-state-icon mx-auto mb-4">
                            <i class="fas fa-inbox text-2xl"></i>
                        </div>
                        <p class="font-semibold text-[#102A32]">No recent activity yet</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="surface-card p-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-[#0B1F2A]">Platform Health</h3>
                    <p class="text-sm text-[#64748B]">Operational signals for maintenance.</p>
                </div>
                <div class="icon-tile icon-tile-success">
                    <i class="fas fa-shield-alt text-xl"></i>
                </div>
            </div>

            <div class="mt-6 space-y-3">
                <div class="flex items-center justify-between rounded-2xl bg-[#F0FAFB] px-4 py-3">
                    <span class="text-sm font-semibold text-[#64748B]">Storage used</span>
                    <span class="font-bold text-[#102A32]">{{ $systemHealth['storage_used'] ?? 0 }} GB</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-[#F0FAFB] px-4 py-3">
                    <span class="text-sm font-semibold text-[#64748B]">Failed jobs</span>
                    <span class="font-bold {{ ($systemHealth['failed_jobs'] ?? 0) > 0 ? 'text-red-600' : 'text-[#166534]' }}">{{ $systemHealth['failed_jobs'] ?? 0 }}</span>
                </div>
                <div class="rounded-2xl bg-[#F0FAFB] px-4 py-3">
                    <span class="text-sm font-semibold text-[#64748B]">Last backup</span>
                    <p class="mt-1 font-bold text-[#102A32]">{{ $systemHealth['last_backup'] ?? 'No backup recorded' }}</p>
                </div>
            </div>
        </section>
    </div>

    <section class="surface-card overflow-hidden">
        <div class="border-b border-[#0E7490]/10 bg-[#F0FAFB] px-6 py-5">
            <h3 class="text-lg font-bold text-[#0B1F2A]">Department Performance</h3>
            <p class="text-sm text-[#64748B]">Approval quality and throughput by department.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="table-shell min-w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs uppercase">Department</th>
                        <th class="px-6 py-4 text-left text-xs uppercase">Processed</th>
                        <th class="px-6 py-4 text-left text-xs uppercase">Approved</th>
                        <th class="px-6 py-4 text-left text-xs uppercase">Rejected</th>
                        <th class="px-6 py-4 text-left text-xs uppercase">Approval Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#0E7490]/10 bg-white">
                    @forelse($departmentPerformance as $department)
                        @php
                            $processed = (int) $department->total_approvals;
                            $approved = (int) $department->approved_count;
                            $rate = $processed > 0 ? round(($approved / $processed) * 100) : 0;
                        @endphp
                        <tr class="transition hover:bg-[#F8FEFF]">
                            <td class="px-6 py-4 text-sm font-bold text-[#102A32]">{{ $department->name }}</td>
                            <td class="px-6 py-4 text-sm text-[#64748B]">{{ $processed }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-[#166534]">{{ $approved }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-red-600">{{ $department->rejected_count }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $rate >= 80 ? 'badge-success' : ($rate >= 50 ? 'badge-accent' : 'badge-muted') }}">
                                    {{ $rate }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-[#64748B]">No department performance data yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
