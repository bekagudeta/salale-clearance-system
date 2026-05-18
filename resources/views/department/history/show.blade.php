@extends('layouts.officer')

@section('title', 'Approval Details - Department')
@section('page-title', 'Approval Details')

@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('department.history') }}" class="inline-flex items-center gap-2 rounded-full bg-[#E6FAF8] px-4 py-2 text-sm font-semibold text-[#084A48] hover:bg-[#d8f3ef] transition">
            <i class="fas fa-arrow-left"></i>
            Back to History
        </a>
    </div>

    <div class="surface-card overflow-hidden shadow-xl">
        <div class="p-6 md:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between mb-6 border-b border-[#E5F7F6] pb-6">
                <div>
                    <h2 class="text-3xl font-extrabold text-[#001722] mb-2">{{ $approval->request->student->full_name }}</h2>
                    <p class="text-sm text-[#627f7c]">Reference: <span class="font-semibold text-[#001722]">{{ $approval->request->reference_no }}</span></p>
                </div>
                <div>
                    @if($approval->status == 'approved')
                        <span class="inline-flex items-center gap-2 rounded-full bg-[#E6FAF8] px-4 py-2 text-sm font-semibold text-[#084A48]">
                            <i class="fas fa-check-circle"></i>
                            Approved
                        </span>
                    @elseif($approval->status == 'rejected')
                        <span class="inline-flex items-center gap-2 rounded-full bg-[#FFE9E8] px-4 py-2 text-sm font-semibold text-[#B52B2B]">
                            <i class="fas fa-times-circle"></i>
                            Rejected
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 rounded-full bg-[#FFF4E5] px-4 py-2 text-sm font-semibold text-[#7f3f08]">
                            <i class="fas fa-hourglass-half"></i>
                            Pending
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
                <div class="surface-card-soft p-5">
                    <h3 class="text-lg font-semibold text-[#001722] mb-4">Student Information</h3>
                    <div class="space-y-4 text-sm text-[#627f7c]">
                        <div>
                            <p class="text-xs uppercase tracking-[0.28em] text-[#084A48] mb-1">Student ID</p>
                            <p class="font-semibold text-[#001722]">{{ $approval->request->student->student_id }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.28em] text-[#084A48] mb-1">Email</p>
                            <p class="font-semibold text-[#001722]">{{ $approval->request->student->email }}</p>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <p class="text-xs uppercase tracking-[0.28em] text-[#084A48] mb-1">Faculty</p>
                                <p class="font-semibold text-[#001722]">{{ $approval->request->student->faculty }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.28em] text-[#084A48] mb-1">Department</p>
                                <p class="font-semibold text-[#001722]">{{ $approval->request->student->department }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="surface-card-soft p-5">
                    <h3 class="text-lg font-semibold text-[#001722] mb-4">Clearance Information</h3>
                    <div class="space-y-4 text-sm text-[#627f7c]">
                        <div>
                            <p class="text-xs uppercase tracking-[0.28em] text-[#084A48] mb-1">Clearance Type</p>
                            <p class="font-semibold text-[#001722]">{{ ucfirst(str_replace('_', ' ', $approval->request->type)) }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.28em] text-[#084A48] mb-1">Year / Semester</p>
                            <p class="font-semibold text-[#001722]">Year {{ $approval->request->student->year }} - {{ $approval->request->student->semester }} Semester</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.28em] text-[#084A48] mb-1">Submitted Date</p>
                            <p class="font-semibold text-[#001722]">{{ $approval->request->created_at->format('F d, Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.28em] text-[#084A48] mb-1">Decision Date</p>
                            <p class="font-semibold text-[#001722]">{{ $approval->updated_at->format('F d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($approval->request->reason)
                <div class="mb-8 rounded-[24px] bg-[#F5FFFE] p-6 border border-[#084A48]/10">
                    <h3 class="text-base font-semibold text-[#001722] mb-3">Request Notes</h3>
                    <p class="text-sm text-[#627f7c]">{{ $approval->request->reason }}</p>
                </div>
            @endif

            @if($approval->remarks)
                <div class="mb-8 rounded-[24px] bg-white p-6 border-l-4 border-[#084A48] shadow-sm">
                    <h3 class="text-base font-semibold text-[#001722] mb-3">Officer Remarks</h3>
                    <p class="text-sm text-[#627f7c]">{{ $approval->remarks }}</p>
                </div>
            @endif

            <div class="mb-8">
                <h3 class="text-lg font-semibold text-[#001722] mb-4">Approval Timeline</h3>
                <div class="space-y-5">
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center gap-2">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#084A48] text-white font-semibold">1</div>
                            <div class="h-full w-px bg-[#E5F7F6]"></div>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#001722]">Request Submitted</p>
                            <p class="text-xs text-[#627f7c]">{{ $approval->request->created_at->format('F d, Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex flex-col items-center gap-2">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full">
                                <span class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-semibold {{ $approval->status == 'approved' ? 'bg-[#084A48] text-white' : ($approval->status == 'rejected' ? 'bg-[#d33b3b] text-white' : 'bg-[#cbd5d1] text-[#1f2937]') }}">2</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#001722]">
                                @if($approval->status == 'approved')
                                    Request Approved
                                @elseif($approval->status == 'rejected')
                                    Request Rejected
                                @else
                                    Awaiting Decision
                                @endif
                            </p>
                            @if($approval->status != 'pending')
                                <p class="text-xs text-[#627f7c]">{{ $approval->updated_at->format('F d, Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($approval->request->approvals && $approval->request->approvals->count() > 1)
                <div class="rounded-[24px] bg-[#F5FFFE] p-6 border border-[#084A48]/10">
                    <h3 class="text-base font-semibold text-[#001722] mb-4">Other Department Approvals</h3>
                    <div class="space-y-3">
                        @foreach($approval->request->approvals as $related)
                            @if($related->id != $approval->id)
                                <div class="flex items-center justify-between gap-4 rounded-3xl bg-white p-4 border border-[#E5F7F6]">
                                    <div>
                                        <p class="font-semibold text-[#001722]">{{ $related->department->name }}</p>
                                    </div>
                                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $related->status == 'approved' ? 'badge-teal' : ($related->status == 'rejected' ? 'badge-danger' : 'badge-warning') }}">
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
