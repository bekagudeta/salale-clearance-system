@extends('layouts.officer')

@section('title', 'Approval Details - Department')
@section('page-title', 'Approval Details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('department.history') }}" class="inline-flex items-center px-3 py-2 rounded-full text-sm font-semibold text-white bg-[#6BCFCB] hover:bg-[#84dadd] transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to History
        </a>
    </div>

    <!-- Main Card -->
    <div class="surface-card overflow-hidden">
        <div class="p-6 md:p-8">
            <!-- Header with Status -->
            <div class="flex flex-col lg:flex-row justify-between items-start gap-4 mb-6 pb-6 border-b border-slate-200">
                <div>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-2">
                        {{ $approval->request->student->full_name }}
                    </h2>
                    <p class="text-sm text-slate-500">Reference: <span class="font-semibold text-slate-800">{{ $approval->request->reference_no }}</span></p>
                </div>
                <div>
                    @if($approval->status == 'approved')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold badge-teal">
                            <i class="fas fa-check-circle mr-2"></i> Approved
                        </span>
                    @elseif($approval->status == 'rejected')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold badge-danger">
                            <i class="fas fa-times-circle mr-2"></i> Rejected
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold badge-warning">
                            <i class="fas fa-hourglass-half mr-2"></i> Pending
                        </span>
                    @endif
                </div>
            </div>

            <!-- Info Blocks -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
                <div class="surface-card-soft p-5">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Student Information</h3>
                    <div class="space-y-4 text-sm text-slate-700">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Student ID</p>
                            <p class="font-semibold">{{ $approval->request->student->student_id }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Email</p>
                            <p class="font-semibold">{{ $approval->request->student->email }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-1">
                            <div>
                                <p class="text-xs text-slate-500 mb-1">Faculty</p>
                                <p class="font-semibold">{{ $approval->request->student->faculty }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 mb-1">Department</p>
                                <p class="font-semibold">{{ $approval->request->student->department }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="surface-card-soft p-5">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Clearance Information</h3>
                    <div class="space-y-4 text-sm text-slate-700">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Clearance Type</p>
                            <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $approval->request->type)) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Year / Semester</p>
                            <p class="font-semibold">Year {{ $approval->request->student->year }} - {{ $approval->request->student->semester }} Semester</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Submitted Date</p>
                            <p class="font-semibold">{{ $approval->request->created_at->format('F d, Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Decision Date</p>
                            <p class="font-semibold">{{ $approval->updated_at->format('F d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Request Notes -->
            @if($approval->request->reason)
            <div class="mb-8 p-5 surface-card-soft">
                <h3 class="text-base font-semibold text-slate-900 mb-3">Request Notes</h3>
                <p class="text-sm text-slate-700">{{ $approval->request->reason }}</p>
            </div>
            @endif

            @if($approval->remarks)
            <div class="mb-8 p-5 surface-card-soft border-l-4 border-[#084A48]">
                <h3 class="text-base font-semibold text-slate-900 mb-3">Officer Remarks</h3>
                <p class="text-sm text-slate-700">{{ $approval->remarks }}</p>
            </div>
            @endif

            <!-- Approval Timeline -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Approval Timeline</h3>
                <div class="space-y-5">
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-12 h-12 rounded-full bg-[#084A48] text-white flex items-center justify-center font-semibold">1</div>
                            <div class="h-full w-px bg-slate-200"></div>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Request Submitted</p>
                            <p class="text-xs text-slate-500">{{ $approval->request->created_at->format('F d, Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-12 h-12 rounded-full text-white flex items-center justify-center font-semibold ">
                                <span class="inline-flex h-full w-full items-center justify-center rounded-full text-sm font-semibold 
                                    {{ $approval->status == 'approved' ? 'bg-[#084A48]' : ($approval->status == 'rejected' ? 'bg-[#d33b3b]' : 'bg-slate-300') }}">
                                    2
                                </span>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">
                                @if($approval->status == 'approved')
                                    Request Approved
                                @elseif($approval->status == 'rejected')
                                    Request Rejected
                                @else
                                    Awaiting Decision
                                @endif
                            </p>
                            @if($approval->status != 'pending')
                                <p class="text-xs text-slate-500">{{ $approval->updated_at->format('F d, Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Approvals -->
            @if($approval->request->approvals && $approval->request->approvals->count() > 1)
            <div class="mb-8 p-5 surface-card-soft">
                <h3 class="text-base font-semibold text-slate-900 mb-4">Other Department Approvals</h3>
                <div class="space-y-3">
                    @foreach($approval->request->approvals as $related)
                        @if($related->id != $approval->id)
                        <div class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 px-4 py-3 bg-white/80">
                            <div>
                                <p class="font-medium text-slate-900">{{ $related->department->name }}</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $related->status == 'approved' ? 'badge-teal' : ($related->status == 'rejected' ? 'badge-danger' : 'badge-warning') }}">
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
