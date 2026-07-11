@extends('layouts.app')

@section('sidebar')
    <a href="{{ route('department.dashboard') }}" class="sidebar-link {{ request()->routeIs('department.dashboard') ? 'is-active' : '' }}">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('department.cases.index') }}" class="sidebar-link {{ request()->routeIs('department.cases.*') ? 'is-active' : '' }}">
        <i class="fas fa-folder-open"></i>
        <span>Student Cases</span>
    </a>

    <a href="{{ route('department.approvals.index') }}" class="sidebar-link {{ request()->routeIs('department.approvals.*') ? 'is-active' : '' }}">
        <i class="fas fa-clock"></i>
        <span>Pending Approvals</span>
        @if(isset($pendingCount) && $pendingCount > 0)
            <span class="ml-auto rounded-full bg-[#FE580B] px-2 py-0.5 text-[11px] font-semibold text-white">{{ $pendingCount }}</span>
        @endif
    </a>

    <a href="{{ route('department.history') }}" class="sidebar-link {{ request()->routeIs('department.history') ? 'is-active' : '' }}">
        <i class="fas fa-history"></i>
        <span>History</span>
    </a>
@endsection
