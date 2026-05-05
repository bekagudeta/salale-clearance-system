<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Result - Salale University</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-900 to-purple-900 min-h-screen">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="bg-gradient-to-r {{ $isValid ? 'from-green-600 to-teal-600' : 'from-red-600 to-pink-600' }} px-6 py-8 text-center">
                    @if($isValid)
                        <i class="fas fa-check-circle text-white text-5xl mb-3"></i>
                        <h1 class="text-2xl font-bold text-white">Valid Certificate</h1>
                        <p class="text-green-100 mt-2">This clearance certificate is authentic and valid</p>
                    @else
                        <i class="fas fa-times-circle text-white text-5xl mb-3"></i>
                        <h1 class="text-2xl font-bold text-white">Invalid Certificate</h1>
                        <p class="text-red-100 mt-2">This clearance certificate is not valid</p>
                    @endif
                </div>
                
                <div class="p-8">
                    @if($isValid)
                        <div class="space-y-4">
                            <div class="flex justify-between pb-3 border-b">
                                <span class="font-semibold text-gray-600">Reference Number:</span>
                                <span class="font-mono text-gray-800">{{ $clearance->reference_no }}</span>
                            </div>
                            <div class="flex justify-between pb-3 border-b">
                                <span class="font-semibold text-gray-600">Student Name:</span>
                                <span class="text-gray-800">{{ $clearance->student->full_name }}</span>
                            </div>
                            <div class="flex justify-between pb-3 border-b">
                                <span class="font-semibold text-gray-600">Student ID:</span>
                                <span class="text-gray-800">{{ $clearance->student->student_id }}</span>
                            </div>
                            <div class="flex justify-between pb-3 border-b">
                                <span class="font-semibold text-gray-600">Clearance Type:</span>
                                <span class="text-gray-800">{{ ucfirst(str_replace('_', ' ', $clearance->type)) }}</span>
                            </div>
                            <div class="flex justify-between pb-3 border-b">
                                <span class="font-semibold text-gray-600">Completed Date:</span>
                                <span class="text-gray-800">{{ $clearance->completed_at ? $clearance->completed_at->format('F d, Y') : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-600">Status:</span>
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Verified ✓</span>
                            </div>
                        </div>
                        
                        <div class="mt-6 p-4 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt text-green-600 mr-3"></i>
                                <p class="text-sm text-green-800">This certificate has been digitally verified and is authentic.</p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-red-500 text-6xl mb-4"></i>
                            <p class="text-gray-600">The certificate you are trying to verify does not exist in our system or has been revoked.</p>
                        </div>
                    @endif
                    
                    <div class="mt-6 text-center">
                        <a href="{{ route('verify') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-redo mr-2"></i> Verify Another
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>