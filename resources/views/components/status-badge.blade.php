@php
    $statusColors = [
        'pending' => 'badge-accent',
        'in_progress' => 'badge-info',
        'approved' => 'badge-teal',
        'rejected' => 'badge-danger',
        'returned' => 'badge-warning',
        'on_hold' => 'badge-warning',
        'completed' => 'badge-success',
        'cancelled' => 'badge-muted',
    ];
    $icons = [
        'pending' => 'fa-clock',
        'in_progress' => 'fa-spinner',
        'approved' => 'fa-check-circle',
        'rejected' => 'fa-times-circle',
        'returned' => 'fa-reply',
        'on_hold' => 'fa-pause-circle',
        'completed' => 'fa-check-double',
        'cancelled' => 'fa-ban',
    ];
@endphp

<span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ $statusColors[$status] ?? 'badge-muted' }}">
    <i class="fas {{ $icons[$status] ?? 'fa-circle' }} mr-1 text-xs"></i>
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>
