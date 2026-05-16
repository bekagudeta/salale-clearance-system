@extends('layouts.officer')

@section('title', 'Department Dashboard - Salale University')
@section('page-title', 'Department Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="surface-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2 text-slate-900">Department Dashboard</h1>
                <p class="text-slate-500">{{ $department->name }}</p>
                <p class="text-slate-500">Welcome, {{ auth()->user()->name }}</p>
            </div>
            <div class="text-right">
                <i class="fas fa-building text-6xl text-slate-200"></i>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Pending Approvals</p>
                    <p class="text-3xl font-bold text-[#FE580B]">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#FE580B] bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-[#FE580B] text-xl"></i>
                </div>
            </div>
            <a href="{{ route('department.approvals.index') }}" class="mt-4 inline-block text-sm text-[#084A48] hover:text-[#001722]">
                Review now →
            </a>
        </div>
        
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Approved Today</p>
                    <p class="text-3xl font-bold text-[#084A48]">{{ $stats['approved_today'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#084A48] bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-[#084A48] text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Rejected Today</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['rejected_today'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Total Processed</p>
                    <p class="text-3xl font-bold text-[#6BCFCB]">{{ $stats['total_processed'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#6BCFCB] bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-[#6BCFCB] text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Approvals -->
    <div class="surface-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200">
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
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Approved</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Rejected</span>
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