@extends('layouts.officer')

@section('title', 'Department Dashboard - Salale University')
@section('page-title', 'Department Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
        <div class="surface-card p-6 flex items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold mb-1 text-slate-900">Department Dashboard</h1>
                <p class="text-sm text-slate-500 font-medium">{{ $department->name }}</p>
                <p class="text-sm text-slate-500">Welcome, {{ auth()->user()->name }}</p>
                <div class="mt-4 flex items-center gap-3">
                    <a href="{{ route('department.approvals.index') }}" class="btn-primary px-4 py-2 text-sm">Review Pending</a>
                    <a href="{{ route('department.statistics') }}" class="btn-secondary px-4 py-2 text-sm">View Reports</a>
                </div>
            </div>
            <div class="text-right hidden md:block">
                <div class="w-28 h-28 rounded-2xl bg-gradient-to-br from-[#084A48] to-[#6BCFCB] flex items-center justify-center shadow-lg">
                    <i class="fas fa-building text-4xl text-white opacity-90"></i>
                </div>
            </div>
        </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Pending Approvals</p>
                    <p class="text-3xl md:text-4xl font-extrabold" style="color:var(--orange)">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: rgba(254,88,11,0.12)">
                    <i class="fas fa-clock text-xl" style="color:var(--orange)"></i>
                </div>
            </div>
            <a href="{{ route('department.approvals.index') }}" class="mt-4 inline-block text-sm btn-primary">Review now →</a>
        </div>
        
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Approved Today</p>
                    <p class="text-3xl md:text-4xl font-extrabold" style="color:var(--deep-green)">{{ $stats['approved_today'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: rgba(8,74,72,0.12)">
                    <i class="fas fa-check-circle text-xl" style="color:var(--deep-green)"></i>
                </div>
            </div>
        </div>
        
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Rejected Today</p>
                    <p class="text-3xl md:text-4xl font-extrabold text-red-600">{{ $stats['rejected_today'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: rgba(220,38,38,0.08)">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Total Processed</p>
                    <p class="text-3xl md:text-4xl font-extrabold" style="color:var(--pearl-aqua)">{{ $stats['total_processed'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: rgba(107,207,203,0.12)">
                    <i class="fas fa-chart-line text-xl" style="color:var(--pearl-aqua)"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Approvals -->
    <div class="surface-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-900">Recent Approvals</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase">Reference No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($recentApprovals as $approval)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm text-slate-900">{{ $approval->request->student->full_name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $approval->request->reference_no }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ ucfirst(str_replace('_', ' ', $approval->request->type)) }}</td>
                        <td class="px-6 py-4">
                            @if($approval->status == 'approved')
                                <span class="px-3 py-1 text-xs rounded-full badge-teal">Approved</span>
                            @else
                                <span class="px-3 py-1 text-xs rounded-full badge-accent">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $approval->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">No recent approvals</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection