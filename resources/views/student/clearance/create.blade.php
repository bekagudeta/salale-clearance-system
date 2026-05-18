@extends('layouts.student')

@section('title', 'New Clearance Request - Salale University')
@section('page-title', 'New Clearance Request')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="surface-card overflow-hidden rounded-[28px] border border-[#084A48]/10 shadow-xl">
        <div class="bg-gradient-to-r from-[#084A48] via-[#6BCFCB] to-[#001722] px-6 py-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-2xl font-semibold text-white">Clearance Application Form</h3>
                    <p class="mt-1 text-sm text-[#E5FCF9]">Please complete the fields below to submit your clearance request.</p>
                </div>
                <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm font-semibold text-white shadow-sm backdrop-blur-sm">
                    <i class="fas fa-file-signature"></i>
                    New Request
                </div>
            </div>
        </div>

        <form action="{{ route('student.clearance.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Student Information -->
            <div class="rounded-[22px] border border-[#6BCFCB]/15 bg-[#F5FFFE] p-5 shadow-sm">
                <div class="flex items-center gap-3 text-[#001722] mb-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-3xl bg-[#6BCFCB]/15 text-[#084A48] text-xl shadow-inner">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-[#084A48]">Student Information</p>
                        <p class="text-xs text-slate-500">Read-only details are automatically filled from your profile.</p>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-1">
                        <p class="text-xs uppercase tracking-[0.2em] text-[#084A48]">Full Name</p>
                        <p class="text-sm font-semibold text-[#001722]">{{ auth()->user()->name }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs uppercase tracking-[0.2em] text-[#084A48]">Student ID</p>
                        <p class="text-sm font-semibold text-[#001722]">{{ auth()->user()->student->student_id }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs uppercase tracking-[0.2em] text-[#084A48]">Faculty</p>
                        <p class="text-sm font-semibold text-[#001722]">{{ auth()->user()->student->faculty }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs uppercase tracking-[0.2em] text-[#084A48]">Department</p>
                        <p class="text-sm font-semibold text-[#001722]">{{ auth()->user()->student->department }}</p>
                    </div>
                </div>
            </div>

            <!-- Clearance Details -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-[#001722] mb-2">Clearance Type <span class="text-[#FE580B]">*</span></label>
                    <select name="type" required class="w-full rounded-[18px] border border-[#084A48]/15 bg-white px-4 py-3 text-sm text-[#001722] outline-none transition focus:border-[#6BCFCB] focus:ring-2 focus:ring-[#6BCFCB]/30">
                        <option value="">Select clearance type</option>
                        <option value="graduation">Graduation</option>
                        <option value="withdrawal">Withdrawal</option>
                        <option value="transfer">Transfer</option>
                        <option value="dismissal">Dismissal</option>
                        <option value="temporary_leave">Temporary Leave</option>
                        <option value="semester_completion">Semester Completion</option>
                    </select>
                    @error('type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#001722] mb-2">Reason for Clearance</label>
                    <textarea name="reason" rows="5" class="w-full rounded-[18px] border border-[#084A48]/15 bg-white px-4 py-3 text-sm text-[#001722] outline-none transition focus:border-[#6BCFCB] focus:ring-2 focus:ring-[#6BCFCB]/30" placeholder="Please provide any additional information or reason for this clearance request..."></textarea>
                    @error('reason')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Important Notice -->
            <div class="rounded-[20px] border border-[#FE580B]/20 bg-[#FE580B]/10 p-4">
                <div class="flex items-start gap-3">
                    <div class="mt-1 flex h-10 w-10 items-center justify-center rounded-2xl bg-[#FE580B]/15 text-[#FE580B]">
                        <i class="fas fa-info"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-[#001722]">Important Notice</p>
                        <p class="mt-2 text-sm text-[#33403d]">Once submitted, your clearance request will be routed to the relevant departments for approval. You will be notified as each department updates your request.</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('student.dashboard') }}" class="inline-flex items-center justify-center rounded-full border border-[#084A48]/15 px-5 py-3 text-sm font-semibold text-[#084A48] transition hover:bg-[#084A48]/10">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-[#FE580B] to-[#084A48] px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-[#084A48]/20 transition hover:brightness-105">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection