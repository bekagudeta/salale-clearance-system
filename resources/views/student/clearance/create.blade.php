@extends('layouts.student')

@section('title', 'New Clearance Request - Salale University')
@section('page-title', 'New Clearance Request')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
            <h3 class="text-white font-semibold text-lg">Clearance Application Form</h3>
            <p class="text-blue-100 text-sm">Please fill out all required information</p>
        </div>
        
        <form action="{{ route('student.clearance.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Student Information (Read-only) -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-user-graduate mr-2 text-blue-600"></i>
                    Student Information
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <p class="mt-1 text-gray-900">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Student ID</label>
                        <p class="mt-1 text-gray-900">{{ auth()->user()->student->student_id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Faculty</label>
                        <p class="mt-1 text-gray-900">{{ auth()->user()->student->faculty }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Department</label>
                        <p class="mt-1 text-gray-900">{{ auth()->user()->student->department }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Clearance Details -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Clearance Type <span class="text-red-500">*</span>
                </label>
                <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select clearance type</option>
                    <option value="graduation">🎓 Graduation</option>
                    <option value="withdrawal">🚪 Withdrawal</option>
                    <option value="transfer">🔄 Transfer</option>
                    <option value="dismissal">⚠️ Dismissal</option>
                    <option value="temporary_leave">📚 Temporary Leave</option>
                    <option value="semester_completion">✅ Semester Completion</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Clearance</label>
                <textarea name="reason" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Please provide any additional information or reason for this clearance request..."></textarea>
                @error('reason')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Important Notice -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Important Notice:</strong> Once submitted, your clearance request will be sent to all departments for approval. 
                            You will receive notifications as each department processes your request.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('student.dashboard') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition shadow-md">
                    <i class="fas fa-paper-plane mr-2"></i> Submit Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection