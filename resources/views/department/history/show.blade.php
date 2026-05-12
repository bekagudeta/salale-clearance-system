@extends('layouts.officer')

@section('title', 'Approval Details - Department')
@section('page-title', 'Approval Details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('department.history') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Back to History
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 md:p-8">
            <!-- Header with Status -->
            <div class="flex flex-wrap justify-between items-start mb-6 pb-6 border-b border-gray-200">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        {{ $approval->request->student->full_name }}
                    </h2>
                    <p class="text-gray-500">Reference: {{ $approval->request->reference_no }}</p>
                </div>
                <div>
                    @if($approval->status == 'approved')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-2"></i> Approved
                        </span>
                    @elseif($approval->status == 'rejected')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-2"></i> Rejected
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                            <i class="fas fa-hourglass-half mr-2"></i> Pending
                        </span>
                    @endif
                </div>
            </div>

            <!-- Student Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Student Information</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Student ID</p>
                            <p class="text-sm font-semibold text-gray-700">{{ $approval->request->student->student_id }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Email</p>
                            <p class="text-sm text-gray-700">{{ $approval->request->student->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Faculty</p>
                            <p class="text-sm text-gray-700">{{ $approval->request->student->faculty }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Department</p>
                            <p class="text-sm text-gray-700">{{ $approval->request->student->department }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Clearance Information</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Clearance Type</p>
                            <p class="text-sm font-semibold text-gray-700">{{ ucfirst(str_replace('_', ' ', $approval->request->type)) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Year / Semester</p>
                            <p class="text-sm text-gray-700">Year {{ $approval->request->student->year }} - {{ $approval->request->student->semester }} Semester</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Submitted Date</p>
                            <p class="text-sm text-gray-700">{{ $approval->request->created_at->format('F d, Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Decision Date</p>
                            <p class="text-sm text-gray-700">{{ $approval->updated_at->format('F d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reason / Notes -->
            @if($approval->request->reason)
            <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-800 mb-2">Request Notes</h3>
                <p class="text-sm text-gray-700">{{ $approval->request->reason }}</p>
            </div>
            @endif

            @if($approval->remarks)
            <div class="mb-8 p-4 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                <h3 class="text-sm font-semibold text-gray-800 mb-2">Officer Remarks</h3>
                <p class="text-sm text-gray-700">{{ $approval->remarks }}</p>
            </div>
            @endif

            <!-- Approval Timeline -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Approval Timeline</h3>
                <div class="space-y-4">
                    <div class="flex">
                        <div class="flex flex-col items-center mr-4">
                            <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold text-sm">
                                1
                            </div>
                            <div class="w-0.5 h-12 bg-gray-200"></div>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Request Submitted</p>
                            <p class="text-xs text-gray-500">{{ $approval->request->created_at->format('F d, Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex">
                        <div class="flex flex-col items-center mr-4">
                            <div class="w-10 h-10 rounded-full 
                                @if($approval->status != 'pending')
                                    @if($approval->status == 'approved')
                                        bg-green-500
                                    @else
                                        bg-red-500
                                    @endif
                                @else
                                    bg-gray-300
                                @endif
                                text-white flex items-center justify-center font-bold text-sm">
                                2
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">
                                @if($approval->status == 'approved')
                                    Request Approved
                                @elseif($approval->status == 'rejected')
                                    Request Rejected
                                @else
                                    Awaiting Decision
                                @endif
                            </p>
                            @if($approval->status != 'pending')
                                <p class="text-xs text-gray-500">{{ $approval->updated_at->format('F d, Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Approvals -->
            @if($approval->request->approvals && $approval->request->approvals->count() > 1)
            <div class="mb-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h3 class="text-sm font-semibold text-gray-800 mb-3">Other Department Approvals for this Request</h3>
                <div class="space-y-2">
                    @foreach($approval->request->approvals as $related)
                    @if($related->id != $approval->id)
                    <div class="flex items-center justify-between text-sm">
                        <div>
                            <p class="font-medium text-gray-700">{{ $related->department->name }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold
                            @if($related->status == 'approved')
                                bg-green-100 text-green-800
                            @elseif($related->status == 'rejected')
                                bg-red-100 text-red-800
                            @else
                                bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($related->status) }}
                        </span>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
