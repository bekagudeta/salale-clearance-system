@extends('layouts.registrar')

@section('title', 'Certificates - Registrar')
@section('page-title', 'Clearance Certificates')
@section('page-subtitle', 'Manage and verify student certificates')

@section('content')
<div class="space-y-6">
    <!-- Search -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <form method="GET" action="{{ route('registrar.certificates.index') }}" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" placeholder="Search by Reference No, Student Name or ID..." 
                    value="{{ request('search') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
            </div>
            <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">
                <i class="fas fa-search mr-2"></i> Search
            </button>
        </form>
    </div>
    
    <!-- Certificates Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ref No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clearance Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Completed Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Certificate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($certificates as $certificate)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm">{{ $certificate->reference_no }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $certificate->student->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $certificate->student->student_id }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ ucfirst(str_replace('_', ' ', $certificate->type)) }}</td>
                        <td class="px-6 py-4 text-sm">{{ $certificate->completed_at?->format('M d, Y') ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($certificate->certificate_path)
                                <span class="text-green-600"><i class="fas fa-check-circle"></i> Generated</span>
                            @else
                                <span class="text-yellow-600"><i class="fas fa-clock"></i> Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-3">
                                @if($certificate->certificate_path)
                                    <a href="{{ Storage::url($certificate->certificate_path) }}" target="_blank" 
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                @endif
                                <button onclick="regenerateCertificate({{ $certificate->id }})" 
                                        class="text-purple-600 hover:text-purple-800">
                                    <i class="fas fa-sync-alt"></i> Regenerate
                                </button>
                                <button onclick="verifyCertificate('{{ $certificate->reference_no }}')" 
                                        class="text-green-600 hover:text-green-800">
                                    <i class="fas fa-qrcode"></i> Verify
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-certificate text-4xl mb-2"></i>
                            <p>No certificates found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($certificates, 'links'))
            <div class="px-6 py-4 border-t">
                {{ $certificates->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Verify Modal -->
<div id="verifyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Verify Certificate</h3>
                <button onclick="closeVerifyModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="verificationResult" class="text-center">
                <div class="animate-pulse">
                    <i class="fas fa-spinner fa-spin text-4xl text-purple-600 mb-3"></i>
                    <p>Verifying...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function regenerateCertificate(id) {
        if (confirm('Regenerate certificate? This will create a new PDF.')) {
            window.location.href = '/registrar/certificates/' + id + '/regenerate';
        }
    }
    
    async function verifyCertificate(referenceNo) {
        const modal = document.getElementById('verifyModal');
        const resultDiv = document.getElementById('verificationResult');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        resultDiv.innerHTML = '<div class="animate-pulse"><i class="fas fa-spinner fa-spin text-4xl text-purple-600 mb-3"></i><p>Verifying...</p></div>';
        
        try {
            const response = await fetch('/api/verify/' + referenceNo);
            const data = await response.json();
            
            if (data.valid) {
                resultDiv.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-check-circle text-green-500 text-5xl mb-3"></i>
                        <h4 class="font-semibold text-green-800 mb-2">Valid Certificate</h4>
                        <p class="text-gray-600 text-sm">This certificate is authentic and valid.</p>
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg text-left">
                            <p class="text-sm"><strong>Student:</strong> ${data.student}</p>
                            <p class="text-sm"><strong>Reference:</strong> ${data.reference_no}</p>
                            <p class="text-sm"><strong>Completed:</strong> ${data.completed_date}</p>
                        </div>
                        <button onclick="closeVerifyModal()" class="mt-4 px-4 py-2 bg-purple-600 text-white rounded-lg">Close</button>
                    </div>
                `;
            } else {
                resultDiv.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-times-circle text-red-500 text-5xl mb-3"></i>
                        <h4 class="font-semibold text-red-800 mb-2">Invalid Certificate</h4>
                        <p class="text-gray-600 text-sm">This certificate is not valid or has been revoked.</p>
                        <button onclick="closeVerifyModal()" class="mt-4 px-4 py-2 bg-purple-600 text-white rounded-lg">Close</button>
                    </div>
                `;
            }
        } catch (error) {
            resultDiv.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-5xl mb-3"></i>
                    <h4 class="font-semibold text-red-800 mb-2">Verification Failed</h4>
                    <p class="text-gray-600 text-sm">Unable to verify certificate. Please try again.</p>
                    <button onclick="closeVerifyModal()" class="mt-4 px-4 py-2 bg-purple-600 text-white rounded-lg">Close</button>
                </div>
            `;
        }
    }
    
    function closeVerifyModal() {
        const modal = document.getElementById('verifyModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endpush
@endsection