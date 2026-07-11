@extends('layouts.app')

@section('sidebar')
    <a href="{{ route('registrar.dashboard') }}" class="sidebar-link {{ request()->routeIs('registrar.dashboard') ? 'is-active' : '' }}">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
    
    <a href="{{ route('registrar.clearance.index') }}" class="sidebar-link {{ request()->routeIs('registrar.clearance.*') ? 'is-active' : '' }}">
        <i class="fas fa-file-alt"></i>
        <span>All Clearances</span>
    </a>
    
    <a href="{{ route('registrar.reports.index') }}" class="sidebar-link {{ request()->routeIs('registrar.reports.*') ? 'is-active' : '' }}">
        <i class="fas fa-chart-line"></i>
        <span>Reports</span>
    </a>
    
    <a href="{{ route('registrar.certificates.index') }}" class="sidebar-link {{ request()->routeIs('registrar.certificates.*') ? 'is-active' : '' }}">
        <i class="fas fa-certificate"></i>
        <span>Certificates</span>
    </a>
@endsection

@section('profile-link', route('registrar.profile.edit'))
@section('settings-link', route('registrar.settings.edit'))
