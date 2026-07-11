@extends('layouts.student')

@section('title', 'Student Dashboard - Salale University')
@section('page-title', 'Student Dashboard')
@section('page-subtitle', 'Track requests, progress, and clearance notifications')

@section('content')
@php
    $student = auth()->user()->student;
@endphp

<div class="space-y-6">
    <section class="dashboard-hero overflow-hidden p-6 sm:p-8">
        <div class="grid gap-6 lg:grid-cols-[1.45fr_0.85fr] lg:items-center">
            <div class="space-y-5">
                <p class="dashboard-kicker">Student clearance portal</p>
                <div>
                    <h1 class="dashboard-title text-4xl font-bold sm:text-5xl">Welcome back, {{ auth()->user()->name }}.</h1>
                    <p class="mt-4 max-w-3xl text-sm leading-7 text-[#EAF7F6]/78">
                        Start clearance requests, monitor department decisions, and keep every step of your university clearance visible in one place.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('student.clearance.create') }}" class="btn-primary">
                        <i class="fas fa-plus-circle"></i>
                        New Clearance Request
                    </a>
                    <a href="{{ route('student.clearance.history') }}" class="btn-secondary border-white/20 bg-white/10 text-white hover:bg-white/15">
                        <i class="fas fa-history"></i>
                        My Requests
                    </a>
                </div>
            </div>

            <div class="rounded-[22px] border border-white/10 bg-white/10 p-5 text-white shadow-xl">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#38C9EB]">Student profile</p>
                <div class="mt-5 space-y-3">
                    <div class="rounded-2xl bg-white/10 px-4 py-3">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-white/55">Student ID</p>
                        <p class="mt-2 text-2xl font-extrabold">{{ $student->student_id }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 px-4 py-3">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-white/55">Faculty</p>
                        <p class="mt-2 text-lg font-bold">{{ $student->faculty }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 px-4 py-3">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-white/55">Department</p>
                        <p class="mt-2 text-lg font-bold">{{ $student->department }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-5">
        <div class="surface-card stat-card p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="stat-label">Total requests</p>
                    <p class="stat-value mt-3">{{ $stats['total'] }}</p>
                </div>
                <div class="icon-tile">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
            </div>
        </div>

        <div class="surface-card stat-card p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="stat-label">Pending</p>
                    <p class="stat-value mt-3 text-[#92400E]">{{ $stats['pending'] }}</p>
                </div>
                <div class="icon-tile icon-tile-accent">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
        </div>

        <div class="surface-card stat-card p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="stat-label">Approved</p>
                    <p class="stat-value mt-3">{{ $stats['approved'] }}</p>
                </div>
                <div class="icon-tile">
                    <i class="fas fa-check text-xl"></i>
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
                    <i class="fas fa-check-double text-xl"></i>
                </div>
            </div>
        </div>

        <div class="surface-card stat-card p-5 card-hover transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="stat-label">Rejected</p>
                    <p class="stat-value mt-3 text-red-600">{{ $stats['rejected'] }}</p>
                </div>
                <div class="icon-tile icon-tile-danger">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <section class="surface-card overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-[#0E7490]/10 bg-[#F0FAFB] px-6 py-5 md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-lg font-bold text-[#0B1F2A]">Recent Clearance Requests</h3>
                <p class="text-sm text-[#64748B]">Monitor the latest submissions and their current status.</p>
            </div>
            <a href="{{ route('student.clearance.history') }}" class="btn-secondary px-4 py-2 text-sm">
                View All
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="table-shell min-w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs uppercase">Reference No</th>
                        <th class="px-6 py-4 text-left text-xs uppercase">Type</th>
                        <th class="px-6 py-4 text-left text-xs uppercase">Date</th>
                        <th class="px-6 py-4 text-left text-xs uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#0E7490]/10 bg-white">
                    @forelse($recentClearances as $clearance)
                        <tr class="transition hover:bg-[#F8FEFF]">
                            <td class="px-6 py-4 text-sm font-bold text-[#102A32]">{{ $clearance->reference_no }}</td>
                            <td class="px-6 py-4 text-sm text-[#64748B]">{{ ucfirst(str_replace('_', ' ', $clearance->type)) }}</td>
                            <td class="px-6 py-4 text-sm text-[#64748B]">{{ $clearance->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">@include('components.status-badge', ['status' => $clearance->status])</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('student.clearance.show', $clearance->id) }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#0E7490] hover:text-[#0B1F2A]">
                                    <i class="fas fa-eye"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-[#64748B]">
                                <div class="empty-state-icon mx-auto mb-4">
                                    <i class="fas fa-inbox text-2xl"></i>
                                </div>
                                <p class="font-semibold text-[#102A32]">No clearance requests found</p>
                                <p class="mt-1 text-sm">Create your first request to start your clearance journey.</p>
                                <a href="{{ route('student.clearance.create') }}" class="btn-primary mt-5 px-5 py-3 text-sm">
                                    <i class="fas fa-plus"></i>
                                    Create Request
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="surface-card p-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-lg font-bold text-[#0B1F2A]">Ready for another request?</h3>
                <p class="mt-1 text-sm text-[#64748B]">Use the official digital workflow instead of paper follow-ups.</p>
            </div>
            <a href="{{ route('student.clearance.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i>
                New Clearance Request
            </a>
        </div>
    </section>
</div>
@endsection
