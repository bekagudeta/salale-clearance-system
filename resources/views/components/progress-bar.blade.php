@php
    $value = $value ?? 0;
    $max = $max ?? 0;
    $percentage = $max > 0 ? ($value / $max) * 100 : 0;
@endphp

<div class="w-full">
    <div class="mb-2 flex justify-between gap-3">
        <span class="text-sm font-semibold text-[#102A32]">{{ $label }}</span>
        <span class="text-sm font-semibold text-[#0E7490]">{{ $value }}/{{ $max }} ({{ round($percentage) }}%)</span>
    </div>
    <div class="h-3 w-full overflow-hidden rounded-full bg-[#EAF7F6]">
        <div class="h-3 rounded-full bg-gradient-to-r {{ $color ?? 'from-[#1BA3C6] to-[#22C55E]' }} transition-all duration-500" style="width: {{ $percentage }}%"></div>
    </div>
</div>
