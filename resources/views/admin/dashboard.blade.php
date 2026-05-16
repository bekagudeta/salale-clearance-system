@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="surface-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Total Users</p>
                    <p class="text-3xl font-bold text-[#001722]">{{ $stats['total_users'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#6BCFCB]/20 rounded-full flex items-center justify-center text-[#084A48]">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="surface-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Total Clearances</p>
                    <p class="text-3xl font-bold text-[#001722]">{{ $stats['total_clearances'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#6BCFCB]/20 rounded-full flex items-center justify-center text-[#084A48]">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="surface-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Completion Rate</p>
                    <p class="text-3xl font-bold text-[#084A48]">{{ $stats['completion_rate'] ?? 85 }}%</p>
                </div>
                <div class="w-12 h-12 bg-[#6BCFCB]/20 rounded-full flex items-center justify-center text-[#084A48]">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="surface-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Active Departments</p>
                    <p class="text-3xl font-bold text-[#001722]">{{ $stats['active_departments'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#FE580B]/20 rounded-full flex items-center justify-center text-[#7f2f00]">
                    <i class="fas fa-building text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div class="surface-card overflow-hidden">
        <div class="px-6 py-4 border-b bg-[#001722]/10">
            <h3 class="font-semibold text-[#001722]">Recent System Activities</h3>
        </div>
        <div class="divide-y divide-[#084A48]/10">
            @foreach($recentActivities as $activity)
            <div class="p-4 hover:bg-[#001722]/10 transition">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-[#6BCFCB]/20 flex items-center justify-center text-[#084A48]">
                        <i class="fas fa-history text-sm"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm text-[#001722]">{{ $activity->description }}</p>
                        <p class="text-xs text-slate-500">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection