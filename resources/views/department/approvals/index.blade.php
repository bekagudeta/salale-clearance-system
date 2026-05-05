@extends('layouts.officer')

@section('title', 'Pending Approvals - Department')
@section('page-title', 'Pending Approvals')

@section('content')
<div class="space-y-6">
    @if($pendingApprovals->count() > 0)
        @foreach($pendingApprovals as $approval)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden fade-in">
            <div class="p-6">
                <div class="flex flex-wrap justify-between items-start gap-4">
                    <!-- Student Info -->
                    <div class="flex-1">
                        <div class="flex items-center mb-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($approval->request->student->full_name, 0, 2) }}
                            </div>
                            <div class="ml-3">
                                <h3 class="font-semibold text-gray-800 text-lg">{{ $approval->request->student->full_name }}</h3>
                                <p class="text-sm text-gray-500">Student ID: {{ $approval->request->student->student_id }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <p class="text-xs text-gray-500">Reference Number</p>
                                <p class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ $approval->request->reference_no }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Clearance Type</p>
                                <p class="text-sm font-semibold">{{ ucfirst(str_replace('_', ' ', $approval->request->type)) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Faculty</p>
                                <p class="text-sm">{{ $approval->request->student->faculty }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Department</p>
                                <p class="text-sm">{{ $approval->request->student->department }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Year / Semester</p>
                                <p class="text-sm">Year {{ $approval->request->student->year }} - {{ $approval->request->student->semester }} Semester</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Submitted Date</p>
                                <p class="text-sm">{{ $approval->request->created_at->format('F d, Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($approval->request->reason)
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Reason / Additional Notes</p>
                            <p class="text-sm text-gray-700">{{ $approval->request->reason }}</p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="w-full md:w-auto">
                        <form action="{{ route('department.approvals.approve', $approval->id) }}" method="POST" class="inline-block mr-2">
                            @csrf
                            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-md">
                                <i class="fas fa-check-circle mr-2"></i> Approve
                            </button>
                        </form>
                        
                        <button onclick="showRejectModal({{ $approval->id }})" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-md">
                            <i class="fas fa-times-circle mr-2"></i> Reject
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">No Pending Approvals</h3>
            <p class="text-gray-500">All clearance requests have been processed. Great job!</p>
        </div>
    @endif
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Reject Clearance Request</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Rejection <span class="text-red-500">*</span></label>
                    <textarea name="remarks" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Please provide a detailed reason for rejection..." required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideRejectModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showRejectModal(approvalId) {
        document.getElementById('rejectForm').action = '/department/approvals/' + approvalId + '/reject';
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
    }
    
    function hideRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('flex');
    }
    
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideRejectModal();
        }
    });
</script>
@endpush
@endsection