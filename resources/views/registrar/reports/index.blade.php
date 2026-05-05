@extends('layouts.registrar')

@section('title', 'Reports - Registrar')
@section('page-title', 'Reports & Analytics')
@section('page-subtitle', 'Generate and export clearance reports')

@section('content')
<div class="space-y-6">
    <!-- Report Generation Form -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Generate Report</h3>
        
        <form action="{{ route('registrar.reports.generate') }}" method="POST" target="_blank">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Type <span class="text-red-500">*</span></label>
                    <select name="report_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">Select Report Type</option>
                        <option value="cleared_students">Cleared Students by Month</option>
                        <option value="rejected_requests">Rejected Requests Report</option>
                        <option value="department_delays">Department Delays Report</option>
                        <option value="graduation_stats">Graduation Statistics</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Format <span class="text-red-500">*</span></label>
                    <select name="format" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="html">HTML Preview</option>
                        <option value="pdf">PDF Download</option>
                    </select>
                </div>
                
                <div class="date-range-fields" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                    <input type="date" name="from_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                
                <div class="date-range-fields" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                    <input type="date" name="to_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                
                <div class="month-fields" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                    <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ $i }}" {{ $i == date('m') ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$i,1)) }}</option>
                        @endfor
                    </select>
                </div>
                
                <div class="year-fields" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                    <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        @for($i=2020; $i<=date('Y'); $i++)
                            <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            
            <div class="mt-6">
                <button type="submit" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-lg hover:from-purple-700 hover:to-pink-700 transition">
                    <i class="fas fa-chart-line mr-2"></i> Generate Report
                </button>
            </div>
        </form>
    </div>
    
    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">This Month</h3>
                <i class="fas fa-calendar-alt text-purple-600 text-2xl"></i>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Clearances:</span>
                    <span class="font-semibold">{{ $thisMonthStats['total'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Completed:</span>
                    <span class="text-green-600 font-semibold">{{ $thisMonthStats['completed'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Rejected:</span>
                    <span class="text-red-600 font-semibold">{{ $thisMonthStats['rejected'] ?? 0 }}</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">This Year</h3>
                <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Clearances:</span>
                    <span class="font-semibold">{{ $thisYearStats['total'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Completion Rate:</span>
                    <span class="text-green-600 font-semibold">{{ $thisYearStats['completion_rate'] ?? 0 }}%</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Department Performance</h3>
                <i class="fas fa-building text-purple-600 text-2xl"></i>
            </div>
            <div class="space-y-2 max-h-32 overflow-y-auto">
                @foreach($topDepartments ?? [] as $dept)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">{{ $dept->name }}</span>
                    <span class="font-semibold">{{ $dept->approved_count }} approved</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Export Options -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Quick Export</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('registrar.reports.export', ['type' => 'clearances', 'format' => 'csv']) }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                <i class="fas fa-file-csv text-green-600 text-2xl mr-3"></i>
                <div>
                    <p class="font-semibold text-gray-800">Export Clearances</p>
                    <p class="text-xs text-gray-500">CSV format</p>
                </div>
            </a>
            
            <a href="{{ route('registrar.reports.export', ['type' => 'students', 'format' => 'csv']) }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                <i class="fas fa-users text-blue-600 text-2xl mr-3"></i>
                <div>
                    <p class="font-semibold text-gray-800">Export Students</p>
                    <p class="text-xs text-gray-500">CSV format</p>
                </div>
            </a>
            
            <a href="{{ route('registrar.reports.export', ['type' => 'departments', 'format' => 'csv']) }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                <i class="fas fa-building text-purple-600 text-2xl mr-3"></i>
                <div>
                    <p class="font-semibold text-gray-800">Department Summary</p>
                    <p class="text-xs text-gray-500">CSV format</p>
                </div>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelector('select[name="report_type"]').addEventListener('change', function() {
        const type = this.value;
        const dateRangeFields = document.querySelectorAll('.date-range-fields');
        const monthFields = document.querySelectorAll('.month-fields');
        const yearFields = document.querySelectorAll('.year-fields');
        
        dateRangeFields.forEach(field => field.style.display = 'none');
        monthFields.forEach(field => field.style.display = 'none');
        yearFields.forEach(field => field.style.display = 'none');
        
        if (type === 'rejected_requests') {
            dateRangeFields.forEach(field => field.style.display = 'block');
        } else if (type === 'cleared_students') {
            monthFields.forEach(field => field.style.display = 'block');
            yearFields.forEach(field => field.style.display = 'block');
        } else if (type === 'graduation_stats') {
            yearFields.forEach(field => field.style.display = 'block');
        }
    });
</script>
@endpush
@endsection