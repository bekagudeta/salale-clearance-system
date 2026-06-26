@extends('layouts.student')

@section('title', 'Clearance Details - ' . $clearance->reference_no)
@section('page-title', 'Clearance Details')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    @php
        $statusColors = [
            'pending' => 'bg-[#FE580B]/15 text-[#7f3f08] border border-[#FE580B]/20',
            'in_progress' => 'bg-[#6BCFCB]/15 text-[#084A48] border border-[#6BCFCB]/20',
            'approved' => 'bg-[#084A48]/15 text-[#084A48] border border-[#084A48]/20',
            'rejected' => 'bg-[#FF4D4D]/15 text-[#B52B2B] border border-[#FF4D4D]/20',
            'returned' => 'bg-[#FE580B]/15 text-[#7f3f08] border border-[#FE580B]/20',
            'completed' => 'bg-[#001722]/10 text-[#001722] border border-[#001722]/10',
        ];
        $total = $clearance->approvals->count();
        $approved = $clearance->approvals->where('status', 'approved')->count();
        $percentage = $total > 0 ? ($approved / $total) * 100 : 0;
        $academicApproval = $clearance->academicApproval();
        $awaitingHead = ! $clearance->hasServiceApprovals();
    @endphp

    @if($clearance->status === 'returned' && $academicApproval)
        <div class="surface-card overflow-hidden border-l-4 border-[#FE580B] bg-[#FFF5EA] p-6 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-3xl bg-[#FE580B]/15 text-[#FE580B]">
                    <i class="fas fa-rotate-left text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-[#7f3f08]">Returned by your department head</h3>
                    <p class="mt-1 text-sm text-[#7f3f08]">
                        {{ $academicApproval->department->name }} returned this request for fixing.
                        @if($academicApproval->remarks)
                            <span class="block mt-1"><span class="font-semibold">Reason:</span> {{ $academicApproval->remarks }}</span>
                        @endif
                    </p>
                    <form action="{{ route('student.clearance.resubmit', $clearance->id) }}" method="POST" class="mt-4 space-y-3">
                        @csrf
                        <label class="block text-xs font-semibold uppercase tracking-[0.2em] text-[#7f3f08]">Updated reason (optional)</label>
                        <textarea name="reason" rows="3" maxlength="500" class="w-full rounded-2xl border border-[#FE580B]/20 bg-white p-3 text-sm text-[#001722] focus:border-[#FE580B] focus:outline-none">{{ old('reason', $clearance->reason) }}</textarea>
                        <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i>
                            Fix &amp; Resubmit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <div class="surface-card overflow-hidden shadow-xl">
        <div class="bg-gradient-to-r from-[#084A48] via-[#6BCFCB] to-[#001722] px-6 py-5">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-[#E5FCF9]/90">Clearance request overview</p>
                    <h1 class="mt-3 text-2xl font-semibold text-white">Request #{{ $clearance->reference_no }}</h1>
                    <p class="mt-1 text-sm text-[#E5FCF9]/90">Submitted on {{ $clearance->created_at->format('F d, Y H:i') }}</p>
                </div>
                <div class="inline-flex items-center gap-3 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-semibold text-white shadow-sm">
                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-[#FE580B]"></span>
                    {{ strtoupper(str_replace('_', ' ', $clearance->status)) }}
                </div>
            </div>
        </div>

        <div class="grid gap-6 p-6 md:grid-cols-[2fr_1fr]">
            <div class="space-y-6">
                <div class="rounded-[24px] border border-[#084A48]/10 bg-[#F5FFFE] p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-[#001722]">Approval Progress</h2>
                            @if($awaitingHead && $clearance->status !== 'returned')
                                <p class="mt-2 text-sm text-[#627f7c]">Awaiting your department head's approval before reaching other departments.</p>
                            @else
                                <p class="mt-2 text-sm text-[#627f7c]">{{ $approved }} of {{ $total }} departments have approved this request.</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-[#084A48]">{{ round($percentage) }}%</div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="h-3 overflow-hidden rounded-full bg-[#d9f3f0]">
                            <div class="h-full rounded-full bg-gradient-to-r from-[#6BCFCB] to-[#084A48] transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[24px] border border-[#084A48]/10 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-4">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-3xl bg-[#E6FAF8] p-4">
                                <p class="text-xs uppercase tracking-[0.3em] text-[#084A48]">Clearance type</p>
                                <p class="mt-2 font-semibold text-[#001722]">{{ ucfirst(str_replace('_', ' ', $clearance->type)) }}</p>
                            </div>
                            <div class="rounded-3xl bg-[#FFF5EA] p-4">
                                <p class="text-xs uppercase tracking-[0.3em] text-[#FE580B]">Status</p>
                                <p class="mt-2 font-semibold text-[#7f3f08]">{{ ucfirst(str_replace('_', ' ', $clearance->status)) }}</p>
                            </div>
                        </div>
                        <div class="rounded-3xl bg-[#F5FFFE] p-4">
                            <p class="text-xs uppercase tracking-[0.3em] text-[#084A48]">Reason</p>
                            <p class="mt-2 text-sm text-[#627f7c]">{{ $clearance->reason ?? 'No reason provided.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-[24px] border border-[#084A48]/10 bg-[#F5FFFE] p-5 shadow-sm">
                <p class="text-sm uppercase tracking-[0.3em] text-[#084A48]">Request details</p>
                <dl class="mt-4 space-y-4 text-sm text-[#627f7c]">
                    <div class="flex items-center justify-between rounded-3xl bg-white p-4 shadow-sm">
                        <dt class="text-[#001722] font-medium">Submitted by</dt>
                        <dd>{{ auth()->user()->name }}</dd>
                    </div>
                    <div class="flex items-center justify-between rounded-3xl bg-white p-4 shadow-sm">
                        <dt class="text-[#001722] font-medium">Student ID</dt>
                        <dd>{{ auth()->user()->student->student_id }}</dd>
                    </div>
                    <div class="flex items-center justify-between rounded-3xl bg-white p-4 shadow-sm">
                        <dt class="text-[#001722] font-medium">Faculty</dt>
                        <dd>{{ auth()->user()->student->faculty }}</dd>
                    </div>
                    <div class="flex items-center justify-between rounded-3xl bg-white p-4 shadow-sm">
                        <dt class="text-[#001722] font-medium">Department</dt>
                        <dd>{{ auth()->user()->student->department }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <div class="surface-card overflow-hidden shadow-xl">
        <div class="px-6 py-5 border-b border-[#084A48]/10 bg-[#F5FFFE]">
            <h3 class="text-lg font-semibold text-[#001722]">Department Approvals</h3>
        </div>
        <div class="divide-y divide-[#E5F7F6]">
            @foreach($clearance->approvals->sortBy('department.priority_order') as $approval)
                <div class="flex flex-col gap-4 p-6 md:flex-row md:items-center md:justify-between hover:bg-[#F5FFFE] transition">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-3xl bg-[#E8F9F6] text-[#084A48] shadow-sm">
                            <i class="fas fa-building text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-[#001722]">{{ $approval->department->name }}</h4>
                            @if($approval->status == 'approved')
                                <p class="mt-1 text-sm text-[#084A48]">Approved by {{ $approval->officer->name ?? 'System' }} on {{ $approval->approved_at->format('M d, Y H:i') }}</p>
                            @elseif($approval->status == 'rejected')
                                <p class="mt-1 text-sm text-[#B52B2B]">Rejected: {{ $approval->remarks }}</p>
                            @elseif($approval->status == 'on_hold')
                                <p class="mt-1 text-sm text-[#7f3f08]">On hold — please clear your case: {{ $approval->remarks }}</p>
                            @else
                                <p class="mt-1 text-sm text-[#627f7c]">Pending review</p>
                            @endif
                        </div>
                    </div>
                    <div>
                        @if($approval->status == 'approved')
                            <span class="inline-flex items-center gap-2 rounded-full border border-[#084A48]/15 bg-[#084A48]/10 px-4 py-2 text-sm font-semibold text-[#084A48]">
                                <i class="fas fa-check-circle"></i>
                                Approved
                            </span>
                        @elseif($approval->status == 'rejected')
                            <span class="inline-flex items-center gap-2 rounded-full border border-[#FF4D4D]/15 bg-[#FF4D4D]/10 px-4 py-2 text-sm font-semibold text-[#B52B2B]">
                                <i class="fas fa-times-circle"></i>
                                Rejected
                            </span>
                        @elseif($approval->status == 'on_hold')
                            <span class="inline-flex items-center gap-2 rounded-full border border-[#FE580B]/15 bg-[#FE580B]/10 px-4 py-2 text-sm font-semibold text-[#7f3f08]">
                                <i class="fas fa-pause-circle"></i>
                                On Hold
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 rounded-full border border-[#FE580B]/15 bg-[#FE580B]/10 px-4 py-2 text-sm font-semibold text-[#7f3f08]">
                                <i class="fas fa-clock"></i>
                                Pending
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex flex-col gap-3 md:flex-row md:justify-between">
        <a href="{{ route('student.clearance.history') }}" class="inline-flex items-center justify-center rounded-full border border-[#084A48]/15 bg-white px-6 py-3 text-sm font-semibold text-[#084A48] transition hover:bg-[#F5FFFE]">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to History
        </a>

        @if($clearance->status == 'completed')
            <a href="{{ route('student.clearance.download', $clearance->id) }}" class="btn-primary inline-flex items-center justify-center gap-2">
                <i class="fas fa-download"></i>
                Download Certificate
            </a>
        @endif
    </div>
</div>
@endsection