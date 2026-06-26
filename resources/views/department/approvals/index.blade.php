@extends('layouts.officer')

@section('title', 'Pending Approvals - Department')
@section('page-title', 'Pending Approvals')

@section('content')
<div class="space-y-6">
    <div class="surface-card p-6 shadow-xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.28em] text-[#6BCFCB]">Approval queue</p>
                <h2 class="mt-3 text-3xl font-bold text-[#001722]">Pending clearance requests</h2>
                <p class="mt-2 max-w-2xl text-sm text-[#627f7c]">Search by student ID, review recorded cases, then approve or notify the student to clear their case.</p>
            </div>
            <div class="inline-flex items-center gap-2 rounded-full bg-[#E6FAF8] px-4 py-3 text-sm text-[#084A48] shadow-sm">
                <i class="fas fa-clock"></i>
                {{ $pendingApprovals->count() }} pending requests
            </div>
        </div>
    </div>

    <div class="surface-card p-6 shadow-lg">
        <form method="GET" action="{{ route('department.approvals.index') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-[#001722] mb-2">Search by Student ID</label>
                <input type="text" name="student_id" value="{{ $searchStudentId }}" placeholder="e.g. SAL/2024/001" class="form-input w-full">
            </div>
            <button type="submit" class="btn-secondary px-5 py-3 text-sm">Search</button>
            @if($searchStudentId)
                <a href="{{ route('department.approvals.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#084A48]/10 px-5 py-3 text-sm font-semibold text-[#084A48] hover:bg-[#F5FFFE] transition">Clear</a>
            @endif
        </form>
    </div>

    @if($pendingApprovals->count() > 0)
        <div class="grid gap-6">
            @foreach($pendingApprovals as $approval)
                @php
                    $studentDbId = $approval->request->student_id;
                    $openCases = $openCasesByStudent->get($studentDbId, collect());
                    $hasOpenCases = $openCases->isNotEmpty();
                @endphp
                <div class="surface-card overflow-hidden shadow-lg transition hover:-translate-y-1 {{ $hasOpenCases ? 'ring-2 ring-[#FE580B]/30' : '' }}">
                    <div class="p-6">
                        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                            <div class="flex-1">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-[#084A48] text-white text-xl font-semibold shadow-sm">
                                        {{ strtoupper(substr($approval->request->student->full_name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-[#001722]">{{ $approval->request->student->full_name }}</h3>
                                        <p class="mt-1 text-sm text-[#627f7c]">Student ID: {{ $approval->request->student->student_id }}</p>
                                        @if($hasOpenCases)
                                            <span class="mt-2 inline-flex items-center gap-2 rounded-full border border-[#FE580B]/15 bg-[#FE580B]/10 px-3 py-1 text-xs font-semibold text-[#7f3f08]">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                {{ $openCases->count() }} open case(s) on record
                                            </span>
                                        @else
                                            <span class="mt-2 inline-flex items-center gap-2 rounded-full border border-[#084A48]/15 bg-[#084A48]/10 px-3 py-1 text-xs font-semibold text-[#084A48]">
                                                <i class="fas fa-check-circle"></i>
                                                No open cases
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    <div class="rounded-[20px] bg-[#F5FFFE] p-4 border border-[#084A48]/10">
                                        <p class="text-xs uppercase tracking-[0.28em] text-[#084A48]">Reference</p>
                                        <p class="mt-2 text-sm font-semibold text-[#001722]">{{ $approval->request->reference_no }}</p>
                                    </div>
                                    <div class="rounded-[20px] bg-[#F5FFFE] p-4 border border-[#084A48]/10">
                                        <p class="text-xs uppercase tracking-[0.28em] text-[#084A48]">Type</p>
                                        <p class="mt-2 text-sm font-semibold text-[#001722]">{{ ucfirst(str_replace('_', ' ', $approval->request->type)) }}</p>
                                    </div>
                                    <div class="rounded-[20px] bg-[#F5FFFE] p-4 border border-[#084A48]/10">
                                        <p class="text-xs uppercase tracking-[0.28em] text-[#084A48]">Submitted</p>
                                        <p class="mt-2 text-sm text-[#627f7c]">{{ $approval->request->created_at->format('F d, Y H:i') }}</p>
                                    </div>
                                </div>

                                @if($hasOpenCases)
                                    <div class="mt-6 rounded-[22px] border border-[#FE580B]/20 bg-[#FFF5EA] p-5">
                                        <p class="text-xs uppercase tracking-[0.28em] text-[#FE580B]">Recorded cases blocking approval</p>
                                        <ul class="mt-3 space-y-3">
                                            @foreach($openCases as $case)
                                                <li class="rounded-xl bg-white/80 p-4">
                                                    <p class="font-semibold text-[#001722]">{{ $case->title }}</p>
                                                    @if($case->description)
                                                        <p class="mt-1 text-sm text-[#627f7c]">{{ $case->description }}</p>
                                                    @endif
                                                    <p class="mt-2 text-xs text-[#627f7c]">Recorded {{ $case->created_at->format('M d, Y') }}</p>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <a href="{{ route('department.cases.index', ['student_id' => $approval->request->student->student_id, 'status' => 'open']) }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-[#084A48] hover:text-[#001722]">
                                            Manage cases →
                                        </a>
                                    </div>
                                @endif

                                @if($approval->status === 'on_hold' && $approval->remarks)
                                    <div class="mt-6 rounded-[22px] border border-[#FE580B]/20 bg-[#FFF5EA] p-5">
                                        <p class="text-xs uppercase tracking-[0.28em] text-[#FE580B]">Your comment to student</p>
                                        <p class="mt-2 text-sm text-[#001722]">{{ $approval->remarks }}</p>
                                    </div>
                                @endif

                                @if($approval->request->reason)
                                    <div class="mt-6 rounded-[22px] border border-[#084A48]/10 bg-[#E8F9F6] p-5">
                                        <p class="text-xs uppercase tracking-[0.28em] text-[#084A48]">Student's reason</p>
                                        <p class="mt-2 text-sm text-[#001722]">{{ $approval->request->reason }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="flex w-full flex-col items-start gap-4 sm:w-auto sm:items-end">
                                @if($hasOpenCases)
                                    <button type="button" onclick="showFlagModal({{ $approval->id }})" class="btn-accent w-full px-5 py-3 text-sm">
                                        Notify student to clear case
                                    </button>
                                    <p class="text-xs text-[#627f7c] text-right max-w-xs">Clear the case(s) in Student Cases, then approve.</p>
                                @else
                                    <form action="{{ route('department.approvals.approve', $approval->id) }}" method="POST" class="w-full sm:w-auto">
                                        @csrf
                                        <button type="submit" class="btn-primary w-full px-5 py-3 text-sm">Approve</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="surface-card p-12 text-center shadow-xl">
            <div class="mx-auto mb-4 inline-flex h-20 w-20 items-center justify-center rounded-full bg-[#E6FAF8] text-[#084A48] shadow-sm">
                <i class="fas fa-check-circle text-4xl"></i>
            </div>
            <h3 class="text-2xl font-semibold text-[#001722] mb-2">No pending approvals</h3>
            <p class="text-sm text-[#627f7c]">
                @if($searchStudentId)
                    No pending requests found for student ID "{{ $searchStudentId }}".
                @else
                    Your department has cleared all current requests.
                @endif
            </p>
        </div>
    @endif
</div>

<div id="flagModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="surface-card max-w-lg w-full mx-4 rounded-[28px] shadow-2xl overflow-hidden">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-[#001722] mb-2">Notify student to clear case</h3>
            <p class="text-sm text-[#627f7c] mb-4">Tell the student what they must do before you can approve their clearance.</p>
            <form id="flagForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#001722] mb-2">Comment to student <span class="text-[#FE580B]">*</span></label>
                    <textarea name="remarks" rows="4" class="form-input w-full" placeholder="e.g. Please return the borrowed textbook to the library front desk." required></textarea>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <button type="button" onclick="hideFlagModal()" class="inline-flex items-center justify-center rounded-full border border-[#084A48]/10 px-5 py-3 text-sm font-semibold text-[#084A48] hover:bg-[#F5FFFE] transition">Cancel</button>
                    <button type="submit" class="btn-accent inline-flex items-center justify-center rounded-full px-5 py-3 text-sm">Send notification</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showFlagModal(approvalId) {
        document.getElementById('flagForm').action = '/department/approvals/' + approvalId + '/flag-case';
        document.getElementById('flagModal').classList.remove('hidden');
        document.getElementById('flagModal').classList.add('flex');
    }

    function hideFlagModal() {
        document.getElementById('flagModal').classList.add('hidden');
        document.getElementById('flagModal').classList.remove('flex');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideFlagModal();
        }
    });
</script>
@endpush
@endsection
