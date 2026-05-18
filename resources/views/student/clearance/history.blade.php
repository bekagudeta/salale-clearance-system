@extends('layouts.student')

@section('title', 'My Clearance History - Salale University')
@section('page-title', 'My Clearance History')

@section('content')
<div class="space-y-6">
    <div class="surface-card overflow-hidden shadow-xl p-6">
        <div class="grid gap-6 lg:grid-cols-[2fr_1fr] lg:items-center">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-[#6BCFCB]">Clearance history</p>
                <h1 class="mt-3 text-3xl font-extrabold text-[#001722]">All clearance requests</h1>
                <p class="mt-3 max-w-2xl text-sm text-[#627f7c]">Track every request with clear status labels, completion dates, and progress at a glance.</p>
            </div>
            <div class="flex items-center justify-end">
                <a href="{{ route('student.clearance.create') }}" class="btn-accent inline-flex items-center gap-2 text-sm">
                    <i class="fas fa-plus"></i>
                    New Request
                </a>
            </div>
        </div>
    </div>

    @if($clearances->isEmpty())
        <div class="surface-card rounded-[28px] border border-dashed border-[#084A48]/15 bg-[#F5FFFE] p-12 text-center shadow-sm">
            <div class="mx-auto mb-4 inline-flex h-20 w-20 items-center justify-center rounded-full bg-[#E6FAF8] text-[#084A48] shadow-sm">
                <i class="fas fa-inbox text-3xl"></i>
            </div>
            <p class="text-2xl font-semibold text-[#001722] mb-2">No clearance requests found</p>
            <p class="max-w-xl mx-auto text-sm text-[#627f7c]">Submit your first clearance request and start monitoring its progress right here.</p>
            <a href="{{ route('student.clearance.create') }}" class="mt-6 inline-flex items-center justify-center rounded-full bg-[#6BCFCB] px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#5bc0b5]">
                <i class="fas fa-plus mr-2"></i> Create Request
            </a>
        </div>
    @else
        <div class="grid gap-6">
            @foreach($clearances as $clearance)
                @php
                    $total = $clearance->approvals->count();
                    $approved = $clearance->approvals->where('status', 'approved')->count();
                    $progress = $total > 0 ? round(($approved / $total) * 100) : 0;
                    $statusColors = [
                        'pending' => 'bg-[#FE580B]/15 text-[#7f3f08]',
                        'in_progress' => 'bg-[#6BCFCB]/15 text-[#084A48]',
                        'approved' => 'bg-[#084A48]/15 text-[#084A48] hover:bg-[#084A48]/90 hover:text-white',
                        'rejected' => 'bg-[#FF4D4D]/15 text-[#B52B2B]',
                        'completed' => 'bg-[#001722]/10 text-[#001722]',
                    ];
                @endphp
                <div class="surface-card rounded-[28px] border border-[#E5F7F6] p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-6">
                                <div class="rounded-3xl bg-[#E6FAF8] px-4 py-3 text-sm font-semibold text-[#084A48] shadow-sm">{{ $clearance->reference_no }}</div>
                                <div>
                                    <p class="text-sm uppercase tracking-[0.24em] text-[#627f7c]">{{ ucfirst(str_replace('_', ' ', $clearance->type)) }}</p>
                                    <p class="mt-1 text-lg font-semibold text-[#001722]">Submitted {{ $clearance->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-3 lg:flex lg:items-center lg:gap-4">
                            <div class="rounded-2xl bg-[#F5FFFE] px-4 py-3 text-sm text-[#627f7c] shadow-sm">
                                <p class="text-[0.72rem] uppercase tracking-[0.3em]">Completed</p>
                                <p class="mt-2 font-semibold text-[#001722]">{{ $clearance->completed_at ? $clearance->completed_at->format('M d, Y') : '—' }}</p>
                            </div>
                            <div class="rounded-2xl bg-[#F5FFFE] px-4 py-3 text-sm text-[#627f7c] shadow-sm">
                                <p class="text-[0.72rem] uppercase tracking-[0.3em]">Status</p>
                                <span class="inline-flex mt-2 rounded-full px-3 py-1 text-xs font-semibold {{ $statusColors[$clearance->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst(str_replace('_', ' ', $clearance->status)) }}
                                </span>
                            </div>
                            <div class="rounded-2xl bg-[#F5FFFE] px-4 py-3 text-sm text-[#627f7c] shadow-sm">
                                <p class="text-[0.72rem] uppercase tracking-[0.3em]">Progress</p>
                                <p class="mt-2 font-semibold text-[#001722]">{{ $progress }}%</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('student.clearance.show', $clearance->id) }}" class="inline-flex items-center justify-center rounded-full bg-[#6BCFCB] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#5bc0b5]">
                                <i class="fas fa-eye mr-2"></i> View
                            </a>
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="h-2 overflow-hidden rounded-full bg-[#d9f3f0]">
                            <div class="h-full rounded-full bg-gradient-to-r from-[#6BCFCB] to-[#084A48]" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if(method_exists($clearances, 'links'))
            <div class="mt-6">
                {{ $clearances->links() }}
            </div>
        @endif
    @endif
</div>
@endsection