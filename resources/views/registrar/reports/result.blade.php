@extends('layouts.registrar')

@section('title', 'Report Preview - Registrar')
@section('page-title', 'Report Preview')
@section('page-subtitle', $title ?? 'Generated report result')

@section('content')
<div class="space-y-6">
    <div class="surface-card p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-semibold text-gray-800">{{ $title ?? 'Report Preview' }}</h3>
                <p class="text-sm text-gray-600">HTML preview of the generated report.</p>
            </div>
            <a href="{{ route('registrar.reports.index') }}" class="btn-secondary px-4 py-2">Back to Reports</a>
        </div>

        @if (empty($data) || (is_countable($data) && count($data) === 0))
            <div class="p-6 bg-yellow-50 rounded-lg border border-yellow-200 text-yellow-800">
                No report data was found for the selected criteria.
            </div>
        @else
            @php
                $rows = collect($data)->map(function ($item) {
                    if (is_array($item)) {
                        return $item;
                    }
                    if (is_object($item)) {
                        return method_exists($item, 'toArray') ? $item->toArray() : (array) $item;
                    }
                    return ['value' => $item];
                });
                $headers = $rows->first() ? array_keys($rows->first()) : [];
            @endphp

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach ($headers as $header)
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ ucwords(str_replace(['_', '-'], ' ', $header)) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($rows as $row)
                            <tr>
                                @foreach ($headers as $header)
                                    @php
                                        $cell = data_get($row, $header, '');
                                        if (is_array($cell) || is_object($cell)) {
                                            $cell = json_encode($cell, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                                        }
                                    @endphp
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $cell }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
