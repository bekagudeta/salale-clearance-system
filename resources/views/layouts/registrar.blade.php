@extends('layouts.app')

@section('sidebar')
    <a href="{{ route('registrar.dashboard') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition group {{ request()->routeIs('registrar.dashboard') ? 'bg-gray-700 text-white' : '' }}">
        <i class="fas fa-tachometer-alt w-5 mr-3"></i>
        <span>Dashboard</span>
    </a>
    
    <a href="{{ route('registrar.clearance.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition group {{ request()->routeIs('registrar.clearance.*') ? 'bg-gray-700 text-white' : '' }}">
        <i class="fas fa-file-alt w-5 mr-3"></i>
        <span>All Clearances</span>
    </a>
    
    <a href="{{ route('registrar.reports.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition group {{ request()->routeIs('registrar.reports.*') ? 'bg-gray-700 text-white' : '' }}">
        <i class="fas fa-chart-line w-5 mr-3"></i>
        <span>Reports</span>
    </a>
    
    <a href="{{ route('registrar.certificates.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition group {{ request()->routeIs('registrar.certificates.*') ? 'bg-gray-700 text-white' : '' }}">
        <i class="fas fa-certificate w-5 mr-3"></i>
        <span>Certificates</span>
    </a>
@endsection

@section('profile-link', route('registrar.profile.edit'))
@section('settings-link', route('registrar.settings.edit'))
