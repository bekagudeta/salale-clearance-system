@extends('layouts.registrar')

@section('title', 'Registrar Dashboard - Salale University')
@section('page-title', 'Registrar Dashboard')
@section('page-subtitle', 'Overview of clearance activities')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="surface-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2 text-slate-900">Welcome, {{ auth()->user()->name }}!</h1>
                <p class="text-slate-500">Registrar Office - Manage and finalize student clearances</p>
            </div>
            <div class="text-right">
                <div class="text-4xl font-bold text-slate-900">{{ date('F j, Y') }}</div>
                <div class="text-slate-500">{{ date('l') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Total Requests</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $stats['total_requests'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#6BCFCB] bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-alt text-[#6BCFCB] text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Pending Review</p>
                    <p class="text-3xl font-bold text-[#FE580B]">{{ $stats['pending'] + $stats['in_progress'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#FE580B] bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-[#FE580B] text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Awaiting Final</p>
                    <p class="text-3xl font-bold text-[#084A48]">{{ $stats['awaiting_final'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#084A48] bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-double text-[#084A48] text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="surface-card p-6 card-hover transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Completed</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['completed'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Monthly Trends Chart -->
    <div class="surface-card p-6">
        <h3 class="font-semibold text-slate-900 mb-4">Monthly Clearance Trends</h3>
        <canvas id="monthlyChart" height="100"></canvas>
    </div>
    
    <!-- Recent Requests -->
    <div class="surface-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
            <h3 class="font-semibold text-slate-900">Recent Clearance Requests</h3>
            <a href="{{ route('registrar.clearance.index') }}" class="text-sm text-[#084A48] hover:text-[#001722]">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase">Ref No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase">Submitted</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($recentRequests as $request)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-sm font-mono text-slate-900">{{ $request->reference_no }}</td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-sm font-medium text-slate-900">{{ $request->student->full_name }}</p>
                                <p class="text-xs text-slate-500">{{ $request->student->student_id }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ ucfirst(str_replace('_', ' ', $request->type)) }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $request->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            @include('components.status-badge', ['status' => $request->status])
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('registrar.clearance.show', $request->id) }}" class="text-[#084A48] hover:text-[#001722]">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No clearance requests found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Graduation Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="surface-card p-6">
            <h3 class="font-semibold text-slate-900 mb-4">Graduation Statistics {{ date('Y') }}</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Graduations</span>
                    <span class="font-semibold text-gray-800">{{ $graduationStats['total_graduations'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Completed</span>
                    <span class="font-semibold text-green-600">{{ $graduationStats['completed_graduations'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Rejected</span>
                    <span class="font-semibold text-red-600">{{ $graduationStats['rejected_graduations'] }}</span>
                </div>
                <div class="mt-4">
                    @include('components.progress-bar', [
                        'label' => 'Completion Rate',
                        'value' => $graduationStats['completed_graduations'],
                        'max' => $graduationStats['total_graduations'],
                        'color' => 'from-green-600 to-teal-600'
                    ])
                </div>
            </div>
        </div>
        
        <div class="surface-card p-6">
            <h3 class="font-semibold text-slate-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('registrar.reports.index') }}" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                    <i class="fas fa-chart-line text-blue-600 text-xl mr-3"></i>
                    <div>
                        <p class="font-semibold text-gray-800">Generate Reports</p>
                        <p class="text-xs text-gray-500">View detailed analytics and reports</p>
                    </div>
                </a>
                <a href="{{ route('registrar.certificates.index') }}" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                    <i class="fas fa-certificate text-purple-600 text-xl mr-3"></i>
                    <div>
                        <p class="font-semibold text-gray-800">Manage Certificates</p>
                        <p class="text-xs text-gray-500">View and regenerate certificates</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyData = @json($monthlyStats);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month_name),
            datasets: [{
                label: 'Total Requests',
                data: monthlyData.map(item => item.total),
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Completed',
                data: monthlyData.map(item => item.completed),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
@endsection