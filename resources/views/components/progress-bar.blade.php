@php
    $value = $value ?? 0;
    $max = $max ?? 0;
    $percentage = $max > 0 ? ($value / $max) * 100 : 0;
@endphp

<div class="w-full">
    <div class="flex justify-between mb-1">
        <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
        <span class="text-sm font-medium text-gray-700">{{ $value }}/{{ $max }} ({{ round($percentage) }}%)</span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-2.5">
        <div class="bg-gradient-to-r {{ $color ?? 'from-blue-600 to-purple-600' }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
    </div>
</div>