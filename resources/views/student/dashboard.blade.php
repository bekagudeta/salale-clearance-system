@extends('layouts.student')

@section('title', 'Student Dashboard - Salale University')
@section('page-title', 'Student Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Hero Summary -->
    <div class="surface-card overflow-hidden rounded-[28px] p-6 relative">
        <div class="absolute inset-0 bg-gradient-to-br from-[#001722] via-[#084A48] to-[#6BCFCB] opacity-10"></div>
        <div class="relative grid gap-6 lg:grid-cols-[1.6fr_1fr] items-center">
            <div class="space-y-4">
                <p class="inline-flex items-center gap-2 rounded-full border border-[#6BCFCB]/20 bg-[#6BCFCB]/10 px-4 py-2 text-sm font-semibold uppercase tracking-[0.2em] text-[#084A48]">Student Dashboard</p>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-[#001722]">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="max-w-2xl text-slate-600">Your clearance progress is updated in real time. Send new requests, review recent status, and stay on track with the university clearance process.</p>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-[22px] border border-[#084A48]/10 bg-white/90 p-4 shadow-lg">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#6BCFCB]">Student ID</p>
                        <p class="mt-3 text-2xl font-semibold text-[#001722]">{{ auth()->user()->student->student_id }}</p>
                    </div>
                    <div class="rounded-[22px] border border-[#084A48]/10 bg-white/90 p-4 shadow-lg">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#FE580B]">Today</p>
                        <p class="mt-3 text-2xl font-semibold text-[#001722]">{{ date('F j, Y') }}</p>
                        <p class="text-sm text-slate-500">{{ date('l') }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-[28px] border border-[#084A48]/10 bg-[#001722] p-6 text-white shadow-xl">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-[#6BCFCB]">My faculty</p>
                        <p class="mt-3 text-xl font-semibold">{{ auth()->user()->student->faculty }}</p>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-[#6BCFCB]/15 text-[#6BCFCB] text-xl shadow-inner shadow-[#6BCFCB]/20">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
                <div class="mt-6 rounded-[24px] bg-[#084A48]/10 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-[#084A48]">Department</p>
                    <p class="mt-2 text-lg font-semibold text-[#001722]">{{ auth()->user()->student->department }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-5">
        <div class="surface-card rounded-[24px] p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Requests</p>
                    <p class="mt-3 text-3xl font-bold text-[#001722]">{{ $stats['total'] }}</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-[#6BCFCB]/15 text-[#084A48] shadow-sm">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="surface-card rounded-[24px] p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Pending</p>
                    <p class="mt-3 text-3xl font-bold text-[#FE580B]">{{ $stats['pending'] }}</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-[#FE580B]/15 text-[#FE580B] shadow-sm">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="surface-card rounded-[24px] p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Approved</p>
                    <p class="mt-3 text-3xl font-bold text-[#084A48]">{{ $stats['approved'] }}</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-[#084A48]/15 text-[#084A48] shadow-sm">
                    <i class="fas fa-check text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="surface-card rounded-[24px] p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Completed</p>
                    <p class="mt-3 text-3xl font-bold text-[#001722]">{{ $stats['completed'] }}</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-[#001722]/10 text-[#001722] shadow-sm">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="surface-card rounded-[24px] p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Rejected</p>
                    <p class="mt-3 text-3xl font-bold text-[#FE580B]">{{ $stats['rejected'] }}</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-[#FE580B]/15 text-[#FE580B] shadow-sm">
                    <i class="fas fa-times-circle text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Clearance Requests -->
    <div class="surface-card overflow-hidden rounded-[28px] border border-[#084A48]/10">
        <div class="flex flex-col gap-4 px-6 py-5 border-b border-[#084A48]/10 md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-xl font-semibold text-[#001722]">Recent Clearance Requests</h3>
                <p class="text-sm text-slate-500">Monitor the latest requests and check current statuses in one place.</p>
            </div>
            <a href="{{ route('student.clearance.history') }}" class="inline-flex items-center gap-2 rounded-full border border-[#6BCFCB]/25 bg-[#6BCFCB]/10 px-4 py-2 text-sm font-semibold text-[#084A48] transition hover:bg-[#6BCFCB]/20">
                View All
                <i class="fas fa-arrow-right text-sm"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-0">
                <thead class="bg-gradient-to-r from-[#001722] via-[#084A48] to-[#6BCFCB] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em]">Reference No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em]">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em]">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em]">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em]">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @php
                        $statusColors = [
                            'pending' => 'bg-[#FE580B]/15 text-[#FE580B]',
                            'in_progress' => 'bg-[#6BCFCB]/15 text-[#084A48]',
                            'approved' => 'bg-[#084A48]/15 text-[#084A48]',
                            'rejected' => 'bg-[#FF4D4D]/15 text-[#B52B2B]',
                            'completed' => 'bg-[#001722]/10 text-[#001722]',
                        ];
                    @endphp
                    @forelse($recentClearances as $clearance)
                        <tr class="hover:bg-[#F8FEFF] transition">
                            <td class="px-6 py-4 text-sm font-semibold text-[#001722]">{{ $clearance->reference_no }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ ucfirst(str_replace('_', ' ', $clearance->type)) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $clearance->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusColors[$clearance->status] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ ucfirst(str_replace('_', ' ', $clearance->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('student.clearance.show', $clearance->id) }}" class="inline-flex items-center gap-2 text-[#084A48] font-semibold hover:text-[#001722]">
                                    <i class="fas fa-eye"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-[#6BCFCB]/15 text-[#084A48]">
                                    <i class="fas fa-inbox text-3xl"></i>
                                </div>
                                <p class="text-lg font-semibold text-[#001722]">No clearance requests found</p>
                                <p class="mt-2 text-sm text-slate-500">Create your first request to start your clearance journey.</p>
                                <a href="{{ route('student.clearance.create') }}" class="mt-4 inline-flex items-center justify-center rounded-full bg-[#6BCFCB] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#5bc0b5]">
                                    Create Request
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8 rounded-[28px] border border-[#084A48]/10 bg-[#F5FFFE] p-6 text-right">
        <a href="{{ route('student.clearance.create') }}" class="btn-primary inline-flex items-center gap-2">
            <i class="fas fa-plus"></i>
            New Clearance Request
        </a>
    </div>
</div>
@endsection