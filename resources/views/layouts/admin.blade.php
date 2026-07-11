@extends('layouts.app')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
    
    <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}">
        <i class="fas fa-users"></i>
        <span>Users</span>
    </a>
    
    <a href="{{ route('admin.departments.index') }}" class="sidebar-link {{ request()->routeIs('admin.departments.*') ? 'is-active' : '' }}">
        <i class="fas fa-building"></i>
        <span>Departments</span>
    </a>
    
    <a href="{{ route('admin.roles.index') }}" class="sidebar-link {{ request()->routeIs('admin.roles.*') ? 'is-active' : '' }}">
        <i class="fas fa-user-tag"></i>
        <span>Roles</span>
    </a>
    
    <a href="{{ route('admin.logs.index') }}" class="sidebar-link {{ request()->routeIs('admin.logs.*') ? 'is-active' : '' }}">
        <i class="fas fa-history"></i>
        <span>Activity Logs</span>
    </a>
    
    <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'is-active' : '' }}">
        <i class="fas fa-cog"></i>
        <span>Settings</span>
    </a>
@endsection

@section('profile-link', route('admin.settings.index'))
@section('settings-link', route('admin.settings.index'))

@section('notifications-dropdown')
    <div class="p-4 text-sm text-gray-600">
        <p class="font-semibold text-gray-800">No notifications available</p>
        <p class="mt-2">Review your activity logs for recent admin actions.</p>
        <a href="{{ route('admin.logs.index') }}" class="mt-3 inline-flex items-center px-3 py-2 bg-[#FE580B] text-white text-sm rounded hover:bg-[#d85a12] shadow-sm transition">
            <i class="fas fa-history mr-2"></i> View Activity Logs
        </a>
    </div>
@endsection
