@extends('layouts.officer')

@section('title', 'Department Reports - Salale University')
@section('page-title', 'Department Reports')

@section('content')
<div class="space-y-6">
    <div class="surface-card p-6 shadow-xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.28em] text-[#6BCFCB]">Department insights</p>
                <h2 class="mt-3 text-3xl font-bold text-[#001722]">Clearance performance overview</h2>
                <p class="mt-2 max-w-2xl text-sm text-[#627f7c]">Review your department clearance metrics and processing efficiency in one place.</p>
            </div>
            <a href="{{ route('department.dashboard') }}" class="btn-secondary">Back to dashboard</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <div class="surface-card p-6 card-hover transition">
            <p class="text-sm text-[#627f7c] uppercase tracking-[0.24em]">Total approvals</p>
            <p class="mt-4 text-4xl font-bold text-[#001722]">{{ $stats['total'] }}</p>
            <p class="mt-3 text-sm text-[#627f7c]">All approval records handled by your department.</p>
        </div>

        <div class="surface-card p-6 card-hover transition">
            <p class="text-sm text-[#627f7c] uppercase tracking-[0.24em]">Pending</p>
            <p class="mt-4 text-4xl font-bold text-[#7f3f08]">{{ $stats['pending'] }}</p>
            <p class="mt-3 text-sm text-[#627f7c]">Requests still awaiting your review.</p>
        </div>

        <div class="surface-card p-6 card-hover transition">
            <p class="text-sm text-[#627f7c] uppercase tracking-[0.24em]">Approved</p>
            <p class="mt-4 text-4xl font-bold text-[#084A48]">{{ $stats['approved'] }}</p>
            <p class="mt-3 text-sm text-[#627f7c]">Requests approved by your department.</p>
        </div>

        <div class="surface-card p-6 card-hover transition">
            <p class="text-sm text-[#627f7c] uppercase tracking-[0.24em]">Rejected</p>
            <p class="mt-4 text-4xl font-bold text-[#d33b3b]">{{ $stats['rejected'] }}</p>
            <p class="mt-3 text-sm text-[#627f7c]">Requests rejected or flagged in this department.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="surface-card p-6 card-hover transition">
            <p class="text-sm text-[#627f7c] uppercase tracking-[0.24em]">Approved today</p>
            <p class="mt-4 text-4xl font-bold text-[#084A48]">{{ $stats['approved_today'] }}</p>
            <p class="mt-3 text-sm text-[#627f7c]">Requests approved by your department today.</p>
        </div>

        <div class="surface-card p-6 card-hover transition">
            <p class="text-sm text-[#627f7c] uppercase tracking-[0.24em]">Average processing time</p>
            <p class="mt-4 text-4xl font-bold text-[#084A48]">{{ $stats['average_processing_time'] }}h</p>
            <p class="mt-3 text-sm text-[#627f7c]">Average hours from submission to decision.</p>
        </div>
    </div>
</div>
@endsection