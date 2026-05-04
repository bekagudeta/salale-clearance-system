@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Salale University Clearance System
            </h1>
            <p class="text-xl text-gray-600 mb-8">
                Streamlined clearance process for students and staff
            </p>
        </div>

        @if(auth()->check())
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                    Welcome back, {{ auth()->user()->name }}!
                </h2>
                <p class="text-gray-600 mb-6">
                    Navigate to your dashboard to manage your clearance process.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if(auth()->user()->hasRole('student'))
                        <a href="{{ route('student.dashboard') }}" 
                           class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors text-center">
                            Student Dashboard
                        </a>
                    @endif
                    
                    @if(auth()->user()->hasRole('department_officer'))
                        <a href="{{ route('department.dashboard') }}" 
                           class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors text-center">
                            Department Dashboard
                        </a>
                    @endif
                    
                    @if(auth()->user()->hasRole('registrar'))
                        <a href="{{ route('registrar.dashboard') }}" 
                           class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors text-center">
                            Registrar Dashboard
                        </a>
                    @endif
                    
                    @if(auth()->user()->hasRole('super_admin'))
                        <a href="{{ route('admin.dashboard') }}" 
                           class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors text-center">
                            Admin Dashboard
                        </a>
                    @endif
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                        Student Portal
                    </h2>
                    <p class="text-gray-600 mb-6">
                        Apply for clearance, track your status, and download your clearance certificate.
                    </p>
                    <a href="{{ route('login') }}" 
                       class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors inline-block">
                        Student Login
                    </a>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                        Staff Portal
                    </h2>
                    <p class="text-gray-600 mb-6">
                        Review and approve clearance requests for your department.
                    </p>
                    <a href="{{ route('login') }}" 
                       class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors inline-block">
                        Staff Login
                    </a>
                </div>
            </div>
        @endif

        <div class="mt-12 bg-white rounded-lg shadow-lg p-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Clearance Departments</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 text-sm">
                <div class="text-center">
                    <div class="bg-blue-100 rounded-lg p-3 mb-2">
                        <i class="fas fa-university text-blue-600 text-2xl"></i>
                    </div>
                    <span class="text-gray-700">School/Dept</span>
                </div>
                <div class="text-center">
                    <div class="bg-green-100 rounded-lg p-3 mb-2">
                        <i class="fas fa-book text-green-600 text-2xl"></i>
                    </div>
                    <span class="text-gray-700">Library</span>
                </div>
                <div class="text-center">
                    <div class="bg-yellow-100 rounded-lg p-3 mb-2">
                        <i class="fas fa-utensils text-yellow-600 text-2xl"></i>
                    </div>
                    <span class="text-gray-700">Food Service</span>
                </div>
                <div class="text-center">
                    <div class="bg-purple-100 rounded-lg p-3 mb-2">
                        <i class="fas fa-shield-alt text-purple-600 text-2xl"></i>
                    </div>
                    <span class="text-gray-700">Security</span>
                </div>
                <div class="text-center">
                    <div class="bg-red-100 rounded-lg p-3 mb-2">
                        <i class="fas fa-graduation-cap text-red-600 text-2xl"></i>
                    </div>
                    <span class="text-gray-700">Registrar</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush
@endsection
