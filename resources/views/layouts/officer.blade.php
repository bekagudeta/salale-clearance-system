@extends('layouts.app')

@section('sidebar')
    <a href="{{ route('department.dashboard') }}" class="flex items-center px-4 py-3 gap-3 rounded-2xl transition {{ request()->routeIs('department.dashboard') ? 'bg-current text-white shadow-lg' : 'text-[#d4f3ee] hover:bg-[#6BCFCB]/10 hover:text-white' }}">
        <i class="fas fa-tachometer-alt w-5"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('department.cases.index') }}" class="flex items-center px-4 py-3 gap-3 rounded-2xl transition {{ request()->routeIs('department.cases.*') ? 'bg-current text-white shadow-lg' : 'text-[#d4f3ee] hover:bg-[#6BCFCB]/10 hover:text-white' }}">
        <i class="fas fa-folder-open w-5"></i>
        <span>Student Cases</span>
    </a>

    <a href="{{ route('department.approvals.index') }}" class="flex items-center px-4 py-3 gap-3 rounded-2xl transition {{ request()->routeIs('department.approvals.*') ? 'bg-current text-white shadow-lg' : 'text-[#d4f3ee] hover:bg-[#6BCFCB]/10 hover:text-white' }}">
        <i class="fas fa-clock w-5"></i>
        <span>Pending Approvals</span>
        @if(isset($pendingCount) && $pendingCount > 0)
            <span class="ml-auto bg-[#FE580B] text-white text-[11px] font-semibold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
        @endif
    </a>

    <a href="{{ route('department.history') }}" class="flex items-center px-4 py-3 gap-3 rounded-2xl transition {{ request()->routeIs('department.history') ? 'bg-current text-white shadow-lg' : 'text-[#d4f3ee] hover:bg-[#6BCFCB]/10 hover:text-white' }}">
        <i class="fas fa-history w-5"></i>
        <span>History</span>
    </a>
@endsection