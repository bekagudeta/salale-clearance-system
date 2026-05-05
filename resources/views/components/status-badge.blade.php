@php
    $statusColors = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'in_progress' => 'bg-blue-100 text-blue-800',
        'approved' => 'bg-green-100 text-green-800',
        'rejected' => 'bg-red-100 text-red-800',
        'completed' => 'bg-purple-100 text-purple-800',
        'cancelled' => 'bg-gray-100 text-gray-800',
    ];
    $icons = [
        'pending' => 'fa-clock',
        'in_progress' => 'fa-spinner',
        'approved' => 'fa-check-circle',
        'rejected' => 'fa-times-circle',
        'completed' => 'fa-check-double',
        'cancelled' => 'fa-ban',
    ];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800' }}">
    <i class="fas {{ $icons[$status] ?? 'fa-circle' }} mr-1 text-xs"></i>
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>