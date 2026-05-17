<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 16mm 12mm;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #1f2937;
            padding: 16px;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 6px;
            letter-spacing: 0.02em;
            color: #111827;
        }
        .meta {
            font-size: 11px;
            color: #6b7280;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
            background: white;
            table-layout: fixed;
            word-break: break-word;
        }
        .table th,
        .table td {
            border: 1px solid #e5e7eb;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
            font-size: 10px;
            line-height: 1.35;
            white-space: normal;
            overflow-wrap: anywhere;
        }
        .table th {
            background: #1f2937;
            color: #f9fafb;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .table tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        .no-data {
            margin-top: 24px;
            padding: 18px;
            background: #eef2ff;
            color: #1e293b;
            border-radius: 12px;
            border: 1px solid #c7d2fe;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $title }}</div>
        <div class="meta">Generated on {{ $generatedDate }}</div>
    </div>

    @if(empty($data) || count($data) === 0)
        <div class="no-data">No records were found for this report.</div>
    @else
        @php
            $normalizeRow = function($row) {
                if (is_object($row)) {
                    if (method_exists($row, 'toArray')) {
                        return $row->toArray();
                    }
                    if (method_exists($row, 'getAttributes')) {
                        return $row->getAttributes();
                    }
                    return json_decode(json_encode($row), true) ?: [];
                }
                return (array) $row;
            };

            $firstRow = $normalizeRow($data->first());
            $headers = array_keys($firstRow);
        @endphp

        <table class="table">
            <thead>
                <tr>
                    @foreach($headers as $column)
                        <th>{{ ucwords(str_replace(['_', '-'], ' ', $column)) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    @php $rowData = $normalizeRow($row); @endphp
                    <tr>
                        @foreach($headers as $header)
                            @php $value = $rowData[$header] ?? null; @endphp
                            <td>{{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) : (is_object($value) ? json_encode($value) : $value) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
