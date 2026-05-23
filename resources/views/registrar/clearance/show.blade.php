@extends('layouts.registrar')

@section('title', 'Clearance Details - ' . $clearance->reference_no)
@section('page-title', 'Clearance Details')
@section('page-subtitle', 'Reference: ' . $clearance->reference_no)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header Card -->
    <div class="surface-card overflow-hidden">
        <div style="background: linear-gradient(90deg, var(--deep-green), var(--pearl-aqua)); padding: 1rem 1.5rem;">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-white font-semibold text-lg">Clearance Request Details</h3>
                    <p class="text-purple-100">Submitted on {{ $clearance->created_at->format('F d, Y H:i') }}</p>
                </div>
                @include('components.status-badge', ['status' => $clearance->status])
            </div>
        </div>
        
        <div class="p-6">
            <!-- Student Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-user-graduate mr-2 text-purple-600"></i>
                        Student Information
                    </h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Full Name:</span>
                            <span class="font-medium">{{ $clearance->student->full_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Student ID:</span>
                            <span class="font-medium">{{ $clearance->student->student_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Faculty:</span>
                            <span>{{ $clearance->student->faculty }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Department:</span>
                            <span>{{ $clearance->student->department }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Year/Semester:</span>
                            <span>Year {{ $clearance->student->year }} - {{ $clearance->student->semester }} Semester</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-file-alt mr-2 text-purple-600"></i>
                        Clearance Information
                    </h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Reference No:</span>
                            <span class="font-mono">{{ $clearance->reference_no }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Clearance Type:</span>
                            <span>{{ ucfirst(str_replace('_', ' ', $clearance->type)) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Requested Date:</span>
                            <span>{{ $clearance->requested_date->format('M d, Y') }}</span>
                        </div>
                        @if($clearance->completed_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Completed Date:</span>
                            <span>{{ $clearance->completed_at->format('M d, Y H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Reason -->
            @if($clearance->reason)
            <div class="mb-6 p-4 bg-yellow-50 rounded-lg">
                <h4 class="font-semibold text-gray-800 mb-2">Reason for Clearance</h4>
                <p class="text-gray-700">{{ $clearance->reason }}</p>
            </div>
            @endif
            
            <!-- Progress Overview -->
            <div class="mb-6">
                <h4 class="font-semibold text-gray-800 mb-3">Overall Progress</h4>
                @php
                    $total = $clearance->approvals->count();
                    $approved = $clearance->approvals->where('status', 'approved')->count();
                    $percentage = $total > 0 ? ($approved / $total) * 100 : 0;
                @endphp
                <div class="bg-gray-200 rounded-full h-4">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-4 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                </div>
                <p class="text-sm text-gray-600 mt-2">{{ $approved }} of {{ $total }} departments approved ({{ round($percentage) }}%)</p>
            </div>
            
            <!-- Department Approvals -->
            <h4 class="font-semibold text-gray-800 mb-3">Department Approvals</h4>
            <div class="border rounded-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Department</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Approved By</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($clearance->approvals->sortBy('department.priority_order') as $approval)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ $approval->department->name }}</td>
                            <td class="px-4 py-3">
                                @if($approval->status == 'approved')
                                    <span class="text-green-600"><i class="fas fa-check-circle"></i> Approved</span>
                                @elseif($approval->status == 'rejected')
                                    <span class="text-red-600"><i class="fas fa-times-circle"></i> Rejected</span>
                                @else
                                    <span class="text-yellow-600"><i class="fas fa-clock"></i> Pending</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $approval->officer?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $approval->approved_at ? $approval->approved_at->format('M d, Y H:i') : '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $approval->remarks ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex flex-col lg:flex-row lg:justify-between gap-4">
        <a href="{{ route('registrar.clearance.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
        <div class="flex items-center gap-3">
            @if($canApproveRegistrar)
                <form action="{{ route('registrar.clearance.approve', $clearance->id) }}" method="POST" onsubmit="return confirm('Approve this request as registrar?')">
                    @csrf
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-md">
                        <i class="fas fa-check mr-2"></i> Approve as Registrar
                    </button>
                </form>
            @endif

            @if($allApproved && $clearance->status !== 'completed')
                <form action="{{ route('registrar.clearance.finalize', $clearance->id) }}" method="POST" onsubmit="return confirm('Finalize this clearance? This will generate the certificate and cannot be undone.')">
                    @csrf
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg hover:from-green-700 hover:to-teal-700 transition shadow-md">
                        <i class="fas fa-check-double mr-2"></i> Finalize Clearance
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection