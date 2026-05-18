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
                <p class="mt-2 max-w-2xl text-sm text-[#627f7c]">Review and act on requests from students in your department.</p>
            </div>
            <div class="inline-flex items-center gap-2 rounded-full bg-[#E6FAF8] px-4 py-3 text-sm text-[#084A48] shadow-sm">
                <i class="fas fa-clock"></i>
                {{ $pendingApprovals->count() }} pending requests
            </div>
        </div>
    </div>

    @if($pendingApprovals->count() > 0)
        <div class="grid gap-6">
            @foreach($pendingApprovals as $approval)
                <div class="surface-card overflow-hidden shadow-lg transition hover:-translate-y-1">
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

                                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                    <div class="rounded-[20px] bg-[#F5FFFE] p-4 border border-[#084A48]/10">
                                        <p class="text-xs uppercase tracking-[0.28em] text-[#084A48]">Faculty</p>
                                        <p class="mt-2 text-sm text-[#627f7c]">{{ $approval->request->student->faculty }}</p>
                                    </div>
                                    <div class="rounded-[20px] bg-[#F5FFFE] p-4 border border-[#084A48]/10">
                                        <p class="text-xs uppercase tracking-[0.28em] text-[#084A48]">Department</p>
                                        <p class="mt-2 text-sm text-[#627f7c]">{{ $approval->request->student->department }}</p>
                                    </div>
                                </div>

                                @if($approval->request->reason)
                                    <div class="mt-6 rounded-[22px] border border-[#084A48]/10 bg-[#E8F9F6] p-5">
                                        <p class="text-xs uppercase tracking-[0.28em] text-[#084A48]">Reason / Notes</p>
                                        <p class="mt-2 text-sm text-[#001722]">{{ $approval->request->reason }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="flex w-full flex-col items-start gap-4 sm:w-auto sm:items-end">
                                <form action="{{ route('department.approvals.approve', $approval->id) }}" method="POST" class="w-full sm:w-auto">
                                    @csrf
                                    <button type="submit" class="btn-primary w-full px-5 py-3 text-sm">Approve</button>
                                </form>
                                <button onclick="showRejectModal({{ $approval->id }})" class="btn-accent w-full px-5 py-3 text-sm">Reject</button>
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
            <p class="text-sm text-[#627f7c]">Your department has cleared all current requests.</p>
        </div>
    @endif
</div>

<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="surface-card max-w-lg w-full mx-4 rounded-[28px] shadow-2xl overflow-hidden">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-[#001722] mb-4">Reject Clearance Request</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#001722] mb-2">Reason for Rejection <span class="text-[#FE580B]">*</span></label>
                    <textarea name="remarks" rows="4" class="form-input w-full" placeholder="Please provide a detailed reason for rejection..." required></textarea>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <button type="button" onclick="hideRejectModal()" class="inline-flex items-center justify-center rounded-full border border-[#084A48]/10 px-5 py-3 text-sm font-semibold text-[#084A48] hover:bg-[#F5FFFE] transition">Cancel</button>
                    <button type="submit" class="btn-accent inline-flex items-center justify-center rounded-full px-5 py-3 text-sm">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showRejectModal(approvalId) {
        document.getElementById('rejectForm').action = '/department/approvals/' + approvalId + '/reject';
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
    }

    function hideRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('flex');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideRejectModal();
        }
    });
</script>
@endpush
@endsection