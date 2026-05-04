@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">Student Dashboard</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                    <div class="bg-blue-100 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold">Total Requests</h3>
                        <p class="text-3xl font-bold">{{ $stats['total'] }}</p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold">Pending</h3>
                        <p class="text-3xl font-bold">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold">Approved</h3>
                        <p class="text-3xl font-bold">{{ $stats['approved'] }}</p>
                    </div>
                    <div class="bg-red-100 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold">Rejected</h3>
                        <p class="text-3xl font-bold">{{ $stats['rejected'] }}</p>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold">Completed</h3>
                        <p class="text-3xl font-bold">{{ $stats['completed'] }}</p>
                    </div>
                </div>
                
                <div class="mt-8">
                    <a href="{{ route('student.clearance.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        New Clearance Request
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection