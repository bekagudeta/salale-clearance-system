@extends('layouts.student')

@section('title', 'Clearance Details - ' . $clearance->reference_no)
@section('page-title', 'Clearance Details')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-white font-semibold text-lg">Clearance Request #{{ $clearance->reference_no }}</h3>
                    <p class="text-blue-100 text-sm">Submitted on {{ $clearance->created_at->format('F d, Y H:i') }}</p>
                </div>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-500',
                        'in_progress' => 'bg-blue-500',
                        'approved' => 'bg-green-500',
                        'rejected' => 'bg-red-500',
                        'completed' => 'bg-purple-500',
                    ];
                @endphp
                <span class="px-4 py-2 {{ $statusColors[$clearance->status] }} text-white rounded-full text-sm font-semibold">
                    {{ strtoupper(str_replace('_', ' ', $clearance->status)) }}
                </span>
            </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="p-6 border-b">
            <h4 class="font-semibold text-gray-800 mb-3">Approval Progress</h4>
            @php
                $total = $clearance->approvals->count();
                $approved = $clearance->approvals->where('status', 'approved')->count();
                $percentage = $total > 0 ? ($approved / $total) * 100 : 0;
            @endphp
            <div class="relative pt-1">
                <div class="flex mb-2 items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold inline-block text-blue-600">
                            {{ $approved }} of {{ $total }} departments approved
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-semibold inline-block text-blue-600">
                            {{ round($percentage) }}%
                        </span>
                    </div>
                </div>
                <div class="overflow-hidden h-3 text-xs flex rounded-full bg-gray-200">
                    <div style="width: {{ $percentage }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-blue-500 to-purple-600 transition-all duration-500"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Department Approvals -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="font-semibold text-gray-800">Department Approvals</h3>
        </div>
        
        <div class="divide-y divide-gray-200">
            @foreach($clearance->approvals->sortBy('department.priority_order') as $approval)
            <div class="p-6 hover:bg-gray-50 transition">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                <i class="fas fa-building text-gray-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $approval->department->name }}</h4>
                                @if($approval->status == 'approved')
                                    <p class="text-sm text-green-600">Approved by {{ $approval->officer->name ?? 'System' }} on {{ $approval->approved_at->format('M d, Y H:i') }}</p>
                                @elseif($approval->status == 'rejected')
                                    <p class="text-sm text-red-600">Rejected: {{ $approval->remarks }}</p>
                                @else
                                    <p class="text-sm text-gray-500">Pending review</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div>
                        @if($approval->status == 'approved')
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                <i class="fas fa-check-circle mr-1"></i> Approved
                            </span>
                        @elseif($approval->status == 'rejected')
                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">
                                <i class="fas fa-times-circle mr-1"></i> Rejected
                            </span>
                        @else
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                                <i class="fas fa-clock mr-1"></i> Pending
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="mt-6 flex justify-between">
        <a href="{{ route('student.clearance.history') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to History
        </a>
        
        @if($clearance->status == 'completed')
            <a href="{{ route('registrar.clearance.download', $clearance->id) }}" class="px-6 py-2 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg hover:from-green-700 hover:to-teal-700 transition shadow-md">
                <i class="fas fa-download mr-2"></i> Download Certificate
            </a>
        @endif
    </div>
</div>
@endsection