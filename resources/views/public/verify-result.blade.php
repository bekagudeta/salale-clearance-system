<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Result &mdash; {{ config('app.name', 'Salale University') }}</title>
    <style>
        :root { --teal:#084A48; --dark:#001722; --gold:#C9A227; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(160deg, #001722 0%, #084A48 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 24px; color: #1f2a2e;
        }
        .card { width: 100%; max-width: 560px; background: #fff; border-radius: 18px; overflow: hidden; box-shadow: 0 24px 60px rgba(0,0,0,.35); }
        .banner { padding: 30px 28px; text-align: center; color: #fff; }
        .banner.ok { background: #0f766e; }
        .banner.basic { background: #1d4ed8; }
        .banner.warn { background: #b45309; }
        .banner.bad { background: #b91c1c; }
        .icon { width: 56px; height: 56px; border-radius: 50%; background: rgba(255,255,255,.18); margin: 0 auto 12px; line-height: 56px; font-size: 30px; font-weight: 700; }
        .banner h1 { font-size: 21px; font-weight: 700; }
        .banner p { margin-top: 6px; font-size: 13px; opacity: .92; }
        .body { padding: 26px 28px; }
        .row { display: flex; justify-content: space-between; padding: 11px 0; border-bottom: 1px solid #eef2f2; font-size: 14px; }
        .row:last-child { border-bottom: 0; }
        .row .k { color: #6b7280; font-weight: 600; }
        .row .v { color: #1f2a2e; text-align: right; }
        .mono { font-family: monospace; }
        .pill { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
        .pill.ok { background: #d1fae5; color: #065f46; }
        .section { margin-top: 20px; }
        .section h2 { font-size: 12px; text-transform: uppercase; letter-spacing: .5px; color: var(--teal); margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: var(--teal); color: #fff; text-align: left; padding: 8px 10px; font-size: 11px; text-transform: uppercase; }
        td { padding: 8px 10px; border-bottom: 1px solid #eef2f2; font-size: 13px; }
        .info { margin-top: 18px; padding: 12px 14px; border-radius: 10px; font-size: 13px; line-height: 1.5; }
        .info.tip { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
        .info.muted { background: #f9fafb; color: #4b5563; border: 1px solid #e5e7eb; }
        .actions { margin-top: 22px; text-align: center; }
        .btn { display: inline-block; padding: 11px 22px; background: var(--teal); color: #fff; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="card">
        @php
            $banner = ['verified'=>'ok','basic'=>'basic','expired'=>'warn','invalid'=>'bad'][$state] ?? 'bad';
            $glyph  = ['verified'=>'✓','basic'=>'i','expired'=>'!','invalid'=>'✕'][$state] ?? '✕';
        @endphp

        <div class="banner {{ $banner }}">
            <div class="icon">{{ $glyph }}</div>
            @switch($state)
                @case('verified')
                    <h1>Certificate Verified</h1>
                    <p>This clearance certificate is authentic and valid.</p>
                    @break
                @case('basic')
                    <h1>Certificate Found</h1>
                    <p>A completed certificate exists for this reference number.</p>
                    @break
                @case('expired')
                    <h1>Certificate Expired</h1>
                    <p>This certificate was authentic but is no longer valid.</p>
                    @break
                @default
                    <h1>Not Verified</h1>
                    <p>This certificate could not be verified.</p>
            @endswitch
        </div>

        <div class="body">
            @if($state === 'verified')
                <div class="row"><span class="k">Reference Number</span><span class="v mono">{{ $clearance->reference_no }}</span></div>
                <div class="row"><span class="k">Student Name</span><span class="v">{{ $clearance->student->full_name }}</span></div>
                <div class="row"><span class="k">Student ID</span><span class="v">{{ $clearance->student->student_id }}</span></div>
                <div class="row"><span class="k">Clearance Type</span><span class="v">{{ ucfirst(str_replace('_', ' ', $clearance->type)) }}</span></div>
                <div class="row"><span class="k">Completed</span><span class="v">{{ $clearance->completed_at ? $clearance->completed_at->format('F d, Y') : '—' }}</span></div>
                <div class="row"><span class="k">Status</span><span class="v"><span class="pill ok">Verified</span></span></div>

                <div class="section">
                    <h2>Department Approvals</h2>
                    <table>
                        <thead><tr><th>Department</th><th>Date</th></tr></thead>
                        <tbody>
                            @foreach($clearance->approvals->where('status', 'approved') as $a)
                                <tr><td>{{ $a->department->name }}</td><td>{{ $a->approved_at ? $a->approved_at->format('M d, Y') : '—' }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @elseif($state === 'basic')
                <div class="row"><span class="k">Reference Number</span><span class="v mono">{{ $clearance->reference_no }}</span></div>
                <div class="row"><span class="k">Student</span><span class="v">{{ $maskedName }}</span></div>
                <div class="row"><span class="k">Clearance Type</span><span class="v">{{ ucfirst(str_replace('_', ' ', $clearance->type)) }}</span></div>
                <div class="row"><span class="k">Completed</span><span class="v">{{ $clearance->completed_at ? $clearance->completed_at->format('F d, Y') : '—' }}</span></div>
                <div class="info tip">
                    For privacy, full details are shown only with the security code. Enter the security code printed on the
                    certificate, or scan its QR code, for complete verification.
                </div>

            @else
                <div class="info muted">{{ $message }}</div>
            @endif

            <div class="actions">
                <a class="btn" href="{{ route('verify') }}">Verify Another</a>
            </div>
        </div>
    </div>
</body>
</html>
