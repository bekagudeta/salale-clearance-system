@extends('layouts.registrar')

@section('title', 'Registrar Dashboard - Salale University')
@section('page-title', 'Registrar Dashboard')
@section('page-subtitle', 'Final approvals, certificates, and clearance activity')

@section('content')
@php
    $reviewQueue = (int) $stats['pending'] + (int) $stats['in_progress'];
    $totalRequests = (int) $stats['total_requests'];
    $completionRate = $totalRequests > 0 ? round(((int) $stats['completed'] / $totalRequests) * 100) : 0;
@endphp

<div class="space-y-6">
    <section class="dashboard-hero overflow-hidden p-6 sm:p-8">
        <div class="grid gap-6 lg:grid-cols-[1.4fr_0.85fr] lg:items-center">
            <div class="space-y-5">
                <p class="dashboard-kicker">Registrar office</p>
                <div>
                    <h1 class="dashboard-title text-4xl font-bold sm:text-5xl">Finalize clearances with a complete view.</h1>
                    <p class="mt-4 max-w-3xl text-sm leading-7 text-[#EAF7F6]/78">
                        Review department-approved requests, authorize final clearance, and keep certificate delivery traceable and professional.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('registrar.clearance.index') }}" class="btn-primary">
                        <i class="fas fa-file-signature"></i>
                        Review Clearances
                    </a>
                    <a href="{{ route('registrar.certificates.index') }}" class="btn-secondary border-white/20 bg-white/10 text-white hover:bg-white/15">
                        <i class="fas fa-certificate"></i>
                        Certificates
                    </a>
                </div>
            </div>
            <div class="rounded-[22px] border border-white/10 bg-white/10 p-5 text-white shadow-xl">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#38C9EB]">Today</p>
                <p class="mt-4 text-4xl font-extrabold">{{ date('F j, Y') }}</p>
                <p class="mt-1 text-sm text-white/70">{{ date('l') }}</p>
                <div class="mt-6 rounded-2xl bg-white/10 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-white/60">Completion rate</p>
                    <div class="mt-3 flex items-end justify-between gap-4">
                        <span class="text-4xl font-extrabold">{{ $completionRate }}%</span>
                        <span class="badge-success rounded-full px-3 py-1 text-xs font-semibold">{{ $stats['completed'] }} completed</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
        <div class="surface-card stat-card p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="stat-label">Total requests</p>
                    <p class="stat-value mt-3">{{ $stats['total_requests'] }}</p>
                </div>
                <div class="icon-tile">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
            </div>
        </div>

        <div class="surface-card stat-card p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="stat-label">Pending review</p>
                    <p class="stat-value mt-3 text-[#92400E]">{{ $reviewQueue }}</p>
                </div>
                <div class="icon-tile icon-tile-accent">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
        </div>

        <div class="surface-card stat-card p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="stat-label">Awaiting registrar</p>
                    <p class="stat-value mt-3">{{ $stats['awaiting_registrar'] }}</p>
                </div>
                <div class="icon-tile">
                    <i class="fas fa-check-double text-xl"></i>
                </div>
            </div>
        </div>

        <div class="surface-card stat-card p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="stat-label">Completed</p>
                    <p class="stat-value mt-3 text-[#166534]">{{ $stats['completed'] }}</p>
                </div>
                <div class="icon-tile icon-tile-success">
                    <i class="fas fa-certificate text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <section class="surface-card p-6">
        <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-bold text-[#0B1F2A]">Monthly Clearance Trends</h3>
                <p class="text-sm text-[#64748B]">Requests and completions across the current year.</p>
            </div>
            <span class="badge-info rounded-full px-3 py-1 text-xs font-semibold">{{ date('Y') }}</span>
        </div>
        <canvas id="monthlyChart" height="96"></canvas>
    </section>

    <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
        <section class="surface-card overflow-hidden">
            <div class="flex flex-col gap-2 border-b border-[#0E7490]/10 bg-[#F0FAFB] px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-bold text-[#0B1F2A]">Recent Clearance Requests</h3>
                    <p class="text-sm text-[#64748B]">Latest student requests submitted to the office.</p>
                </div>
                <a href="{{ route('registrar.clearance.index') }}" class="btn-secondary px-4 py-2 text-sm">
                    View All
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="table-shell min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left text-xs uppercase">Ref No</th>
                            <th class="px-6 py-4 text-left text-xs uppercase">Student</th>
                            <th class="px-6 py-4 text-left text-xs uppercase">Type</th>
                            <th class="px-6 py-4 text-left text-xs uppercase">Submitted</th>
                            <th class="px-6 py-4 text-left text-xs uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#0E7490]/10 bg-white">
                        @forelse($recentRequests as $request)
                            <tr class="transition hover:bg-[#F8FEFF]">
                                <td class="px-6 py-4 text-sm font-bold text-[#102A32]">{{ $request->reference_no }}</td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-[#102A32]">{{ $request->student->full_name }}</p>
                                    <p class="text-xs text-[#64748B]">{{ $request->student->student_id }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-[#64748B]">{{ ucfirst(str_replace('_', ' ', $request->type)) }}</td>
                                <td class="px-6 py-4 text-sm text-[#64748B]">{{ $request->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">@include('components.status-badge', ['status' => $request->status])</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <a href="{{ route('registrar.clearance.show', $request->id) }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#0E7490] hover:text-[#0B1F2A]">
                                            <i class="fas fa-eye"></i>
                                            View
                                        </a>
                                        @if($request->status === 'approved')
                                            <form action="{{ route('registrar.clearance.finalize', $request->id) }}" method="POST" onsubmit="return confirm('Approve and finalize this clearance request? This will generate the certificate.')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-2 text-sm font-bold text-[#166534] hover:text-[#14532D]">
                                                    <i class="fas fa-check-double"></i>
                                                    Finalize
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-[#64748B]">
                                    <div class="empty-state-icon mx-auto mb-4">
                                        <i class="fas fa-inbox text-2xl"></i>
                                    </div>
                                    <p class="font-semibold text-[#102A32]">No clearance requests found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="surface-card overflow-hidden">
            <div class="border-b border-[#0E7490]/10 bg-[#F0FAFB] px-6 py-5">
                <h3 class="text-lg font-bold text-[#0B1F2A]">Awaiting Registrar Approval</h3>
                <p class="text-sm text-[#64748B]">Department-cleared requests ready for final action.</p>
            </div>
            <div class="divide-y divide-[#0E7490]/10">
                @forelse($awaitingRegistrar as $request)
                    <div class="p-5 transition hover:bg-[#F8FEFF]">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-bold text-[#102A32]">{{ $request->student->full_name }}</p>
                                <p class="mt-1 text-xs text-[#64748B]">{{ $request->reference_no }} - {{ ucfirst(str_replace('_', ' ', $request->type)) }}</p>
                                <p class="mt-1 text-xs text-[#64748B]">Submitted {{ $request->created_at->format('M d, Y') }}</p>
                            </div>
                            <form action="{{ route('registrar.clearance.approve', $request->id) }}" method="POST" onsubmit="return confirm('Approve this request as registrar?')">
                                @csrf
                                <button type="submit" class="btn-primary px-4 py-2 text-sm">
                                    <i class="fas fa-check-double"></i>
                                    Approve
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-[#64748B]">
                        <div class="empty-state-icon mx-auto mb-4">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                        <p class="font-semibold text-[#102A32]">No requests are waiting</p>
                        <p class="mt-1 text-sm">The final approval queue is clear.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <section class="surface-card p-6">
            <h3 class="text-lg font-bold text-[#0B1F2A]">Graduation Statistics {{ date('Y') }}</h3>
            <p class="mt-1 text-sm text-[#64748B]">Graduation clearance performance for the current year.</p>
            <div class="mt-5 space-y-3">
                <div class="flex justify-between rounded-2xl bg-[#F0FAFB] px-4 py-3">
                    <span class="text-sm font-semibold text-[#64748B]">Total graduations</span>
                    <span class="font-bold text-[#102A32]">{{ $graduationStats['total_graduations'] }}</span>
                </div>
                <div class="flex justify-between rounded-2xl bg-[#F0FAFB] px-4 py-3">
                    <span class="text-sm font-semibold text-[#64748B]">Completed</span>
                    <span class="font-bold text-[#166534]">{{ $graduationStats['completed_graduations'] }}</span>
                </div>
                <div class="flex justify-between rounded-2xl bg-[#F0FAFB] px-4 py-3">
                    <span class="text-sm font-semibold text-[#64748B]">Rejected</span>
                    <span class="font-bold text-red-600">{{ $graduationStats['rejected_graduations'] }}</span>
                </div>
                @include('components.progress-bar', [
                    'label' => 'Completion Rate',
                    'value' => $graduationStats['completed_graduations'],
                    'max' => $graduationStats['total_graduations'],
                    'color' => 'from-[#1BA3C6] to-[#22C55E]'
                ])
            </div>
        </section>

        <section class="surface-card p-6">
            <h3 class="text-lg font-bold text-[#0B1F2A]">Quick Actions</h3>
            <p class="mt-1 text-sm text-[#64748B]">Common registrar workflows.</p>
            <div class="mt-5 grid gap-3">
                <a href="{{ route('registrar.reports.index') }}" class="group flex items-center gap-4 rounded-2xl border border-[#0E7490]/10 bg-[#F0FAFB] p-4 transition hover:border-[#1BA3C6]/40 hover:bg-white">
                    <span class="icon-tile">
                        <i class="fas fa-chart-line text-xl"></i>
                    </span>
                    <span>
                        <span class="block font-bold text-[#102A32]">Generate Reports</span>
                        <span class="text-xs text-[#64748B]">View detailed analytics and exports</span>
                    </span>
                </a>
                <a href="{{ route('registrar.certificates.index') }}" class="group flex items-center gap-4 rounded-2xl border border-[#0E7490]/10 bg-[#F0FAFB] p-4 transition hover:border-[#1BA3C6]/40 hover:bg-white">
                    <span class="icon-tile icon-tile-success">
                        <i class="fas fa-certificate text-xl"></i>
                    </span>
                    <span>
                        <span class="block font-bold text-[#102A32]">Manage Certificates</span>
                        <span class="text-xs text-[#64748B]">Verify, view, and regenerate certificates</span>
                    </span>
                </a>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyData = @json($monthlyStats);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month_name),
            datasets: [{
                label: 'Total Requests',
                data: monthlyData.map(item => item.total),
                borderColor: '#1BA3C6',
                backgroundColor: 'rgba(27, 163, 198, 0.12)',
                pointBackgroundColor: '#1BA3C6',
                pointBorderColor: '#ffffff',
                pointRadius: 4,
                tension: 0.38,
                fill: true
            }, {
                label: 'Completed',
                data: monthlyData.map(item => item.completed),
                borderColor: '#22C55E',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                pointBackgroundColor: '#22C55E',
                pointBorderColor: '#ffffff',
                pointRadius: 4,
                tension: 0.38,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 10,
                        boxHeight: 10,
                        color: '#102A32',
                        font: { family: 'Plus Jakarta Sans', weight: '700' }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748B' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(14, 116, 144, 0.1)' },
                    ticks: { precision: 0, color: '#64748B' }
                }
            }
        }
    });
</script>
@endpush
