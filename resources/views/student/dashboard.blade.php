@extends('layouts.student')

@section('title', 'Student Dashboard - Salale University')
@section('page-title', 'Student Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="surface-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2 text-slate-900">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-slate-500">Student ID: {{ auth()->user()->student->student_id }}</p>
                <p class="text-slate-500">{{ auth()->user()->student->faculty }} - {{ auth()->user()->student->department }}</p>
            </div>
            <div class="text-right">
                <div class="text-4xl font-bold text-slate-900">{{ date('F j, Y') }}</div>
                <div class="text-slate-500">{{ date('l') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Total Requests</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#6BCFCB] bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-alt text-[#6BCFCB] text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Pending</p>
                    <p class="text-3xl font-bold text-[#FE580B]">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#FE580B] bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-[#FE580B] text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Approved</p>
                    <p class="text-3xl font-bold text-[#084A48]">{{ $stats['approved'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#084A48] bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-[#084A48] text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Completed</p>
                    <p class="text-3xl font-bold text-[#084A48]">{{ $stats['completed'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#084A48] bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-[#084A48] text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Rejected</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Clearances -->
    <div class="surface-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-slate-900">Recent Clearance Requests</h3>
            <a href="{{ route('student.clearance.history') }}" class="text-[#084A48] hover:text-[#001722] text-sm">View All →</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase">Reference No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($recentClearances as $clearance)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $clearance->reference_no }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ ucfirst(str_replace('_', ' ', $clearance->type)) }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $clearance->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'completed' => 'bg-purple-100 text-purple-800',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$clearance->status] ?? 'bg-gray-100' }}">
                                {{ ucfirst(str_replace('_', ' ', $clearance->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('student.clearance.show', $clearance->id) }}" class="text-[#084A48] hover:text-[#001722]">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No clearance requests found</p>
                            <a href="{{ route('student.clearance.create') }}" class="inline-block mt-2 text-[#084A48] hover:text-[#001722]">
                                Create your first clearance request →
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Quick Action Button -->
    <div class="fixed bottom-8 right-8">
        <a href="{{ route('student.clearance.create') }}" class="btn-primary px-6 py-3 rounded-full shadow-lg hover:shadow-xl transition flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>New Clearance Request</span>
        </a>
    </div>
</div>
@endsection