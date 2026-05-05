@extends('layouts.app')

@section('sidebar')
    <a href="{{ route('department.dashboard') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition group {{ request()->routeIs('department.dashboard') ? 'bg-gray-700 text-white' : '' }}">
        <i class="fas fa-tachometer-alt w-5 mr-3"></i>
        <span>Dashboard</span>
    </a>
    
    <a href="{{ route('department.approvals.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition group {{ request()->routeIs('department.approvals.*') ? 'bg-gray-700 text-white' : '' }}">
        <i class="fas fa-clock w-5 mr-3"></i>
        <span>Pending Approvals</span>
        @if(isset($pendingCount) && $pendingCount > 0)
            <span class="ml-auto bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
        @endif
    </a>
    
    <a href="{{ route('department.history') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition group {{ request()->routeIs('department.history') ? 'bg-gray-700 text-white' : '' }}">
        <i class="fas fa-history w-5 mr-3"></i>
        <span>History</span>
    </a>
@endsection