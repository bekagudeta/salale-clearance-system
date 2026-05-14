@extends('layouts.app')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition group {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white' : '' }}">
        <i class="fas fa-tachometer-alt w-5 mr-3"></i>
        <span>Dashboard</span>
    </a>
    
    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition group {{ request()->routeIs('admin.users.*') ? 'bg-gray-700 text-white' : '' }}">
        <i class="fas fa-users w-5 mr-3"></i>
        <span>Users</span>
    </a>
    
    <a href="{{ route('admin.departments.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition group {{ request()->routeIs('admin.departments.*') ? 'bg-gray-700 text-white' : '' }}">
        <i class="fas fa-building w-5 mr-3"></i>
        <span>Departments</span>
    </a>
    
    <a href="{{ route('admin.roles.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition group {{ request()->routeIs('admin.roles.*') ? 'bg-gray-700 text-white' : '' }}">
        <i class="fas fa-user-tag w-5 mr-3"></i>
        <span>Roles</span>
    </a>
    
    <a href="{{ route('admin.logs.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition group {{ request()->routeIs('admin.logs.*') ? 'bg-gray-700 text-white' : '' }}">
        <i class="fas fa-history w-5 mr-3"></i>
        <span>Activity Logs</span>
    </a>
    
    <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition group {{ request()->routeIs('admin.settings.*') ? 'bg-gray-700 text-white' : '' }}">
        <i class="fas fa-cog w-5 mr-3"></i>
        <span>Settings</span>
    </a>
@endsection

@section('profile-link', route('admin.settings.index'))
@section('settings-link', route('admin.settings.index'))

@section('notifications-dropdown')
    <div class="p-4 text-sm text-gray-600">
        <p class="font-semibold text-gray-800">No notifications available</p>
        <p class="mt-2">Review your activity logs for recent admin actions.</p>
        <a href="{{ route('admin.logs.index') }}" class="mt-3 inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
            <i class="fas fa-history mr-2"></i> View Activity Logs
        </a>
    </div>
@endsection