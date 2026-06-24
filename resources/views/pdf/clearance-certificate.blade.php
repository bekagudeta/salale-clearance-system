<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Clearance Certificate - {{ $clearance->reference_no }}</title>
    <style>
        @page { margin: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #1f2a2e;
            font-size: 11px;
        }

        .page { padding: 18px; }

        /* Double frame */
        .frame {
            border: 2px solid #084A48;
            padding: 3px;
        }
        .frame-inner {
            border: 1px solid #C9A227;
            padding: 16px 26px;
            position: relative;
        }

        .watermark {
            position: absolute;
            top: 300px; left: 0; right: 0;
            text-align: center;
            font-size: 90px;
            font-weight: bold;
            color: #084A48;
            opacity: 0.05;
            letter-spacing: 12px;
            transform: rotate(-8deg);
        }

        /* Header */
        .header { text-align: center; padding-bottom: 10px; border-bottom: 2px solid #084A48; }
        .logo { height: 52px; margin-bottom: 6px; }
        .monogram {
            width: 50px; height: 50px; line-height: 50px;
            margin: 0 auto 6px auto;
            border: 2px solid #084A48; border-radius: 50%;
            color: #084A48; font-size: 20px; font-weight: bold;
        }
        .uni-name { font-size: 20px; font-weight: bold; color: #001722; letter-spacing: 1px; }
        .uni-sub { font-size: 10px; color: #6b7280; letter-spacing: 2px; text-transform: uppercase; margin-top: 2px; }

        .cert-title {
            text-align: center;
            font-size: 20px; font-weight: bold; color: #084A48;
            letter-spacing: 4px; text-transform: uppercase;
            margin: 12px 0 2px 0;
        }
        .cert-ref { text-align: center; font-size: 10px; color: #6b7280; margin-bottom: 10px; }
        .cert-ref strong { color: #1f2a2e; font-family: 'DejaVu Sans Mono', monospace; }

        /* Certifying statement */
        .statement { text-align: center; font-family: 'DejaVu Serif', serif; line-height: 1.5; margin: 2px 26px 12px 26px; }
        .statement .lead { font-size: 11px; color: #4b5563; }
        .student-name {
            font-size: 20px; font-weight: bold; color: #001722;
            margin: 5px 0; border-bottom: 1px solid #C9A227; display: inline-block; padding: 0 16px 3px 16px;
        }
        .statement .body { font-size: 11px; color: #374151; }
        .statement .body strong { color: #084A48; }

        /* Details */
        .details { width: 100%; border-collapse: collapse; margin: 4px 0 10px 0; }
        .details td { padding: 4px 10px; font-size: 11px; vertical-align: top; width: 50%; }
        .details .k { color: #6b7280; text-transform: uppercase; font-size: 9px; letter-spacing: 0.5px; }
        .details .v { color: #1f2a2e; font-weight: bold; font-size: 11px; }
        .details tr:nth-child(odd) { background: #f4f7f7; }

        .section-label { font-size: 11px; font-weight: bold; color: #084A48; text-transform: uppercase; letter-spacing: 1px; margin: 2px 0 4px 0; }

        /* Approvals */
        .approvals { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .approvals th {
            background: #084A48; color: #fff; padding: 5px 8px; text-align: left; font-size: 9px;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .approvals td { padding: 3.5px 8px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        .approvals .ok { color: #0f766e; font-weight: bold; }
        .approvals .by { color: #1f2a2e; }

        /* Footer (QR + signature) */
        .footer { width: 100%; margin-top: 4px; }
        .footer td { vertical-align: bottom; }
        .qr img { width: 80px; height: 80px; }
        .qr .cap { font-size: 9px; color: #6b7280; margin-top: 3px; }
        .sign { text-align: center; }
        .sign-line { border-top: 1px solid #1f2a2e; width: 190px; margin: 22px 0 0 auto; padding-top: 5px; }
        .sign .role { font-size: 11px; font-weight: bold; color: #001722; }
        .sign .org { font-size: 10px; color: #6b7280; }

        /* Security strip */
        .security {
            margin-top: 10px; border-top: 1px solid #d1d5db; padding-top: 7px;
            font-size: 9px; color: #6b7280;
        }
        .security .row { margin: 2px 0; }
        .security .mono { font-family: 'DejaVu Sans Mono', monospace; color: #374151; }
        .verify-line { text-align: center; font-size: 9.5px; color: #4b5563; margin-top: 8px; }
        .verify-line a, .verify-url { color: #084A48; font-weight: bold; }
        .legal { text-align: center; font-size: 8px; color: #9ca3af; font-style: italic; margin-top: 6px; }
    </style>
</head>
<body>
<div class="page">
    <div class="frame">
        <div class="frame-inner">
            <div class="watermark">OFFICIAL</div>

            <div class="header">
                @if(!empty($logo_path))
                    <img class="logo" src="{{ $logo_path }}" alt="Logo">
                @else
                    <div class="monogram">{{ strtoupper(substr($university_name, 0, 1)) }}</div>
                @endif
                <div class="uni-name">{{ strtoupper($university_name) }}</div>
                <div class="uni-sub">Office of the Registrar</div>
            </div>

            <div class="cert-title">Certificate of Clearance</div>
            <div class="cert-ref">Reference No: <strong>{{ $clearance->reference_no }}</strong></div>

            <div class="statement">
                <div class="lead">This is to certify that</div>
                <div class="student-name">{{ $student->full_name }}</div>
                <div class="body">
                    bearing Student ID <strong>{{ $student->student_id }}</strong>,
                    of the Faculty of {{ $student->faculty }}, Department of {{ $student->department }},
                    has satisfactorily completed all institutional clearance requirements for
                    <strong>{{ ucfirst(str_replace('_', ' ', $clearance->type)) }}</strong>
                    as confirmed by the departments listed below.
                </div>
            </div>

            <table class="details">
                <tr>
                    <td><div class="k">Student ID</div><div class="v">{{ $student->student_id }}</div></td>
                    <td><div class="k">Clearance Type</div><div class="v">{{ ucfirst(str_replace('_', ' ', $clearance->type)) }}</div></td>
                </tr>
                <tr>
                    <td><div class="k">Faculty</div><div class="v">{{ $student->faculty }}</div></td>
                    <td><div class="k">Department</div><div class="v">{{ $student->department }}</div></td>
                </tr>
                <tr>
                    <td><div class="k">Year / Semester</div><div class="v">Year {{ $student->year }} &middot; {{ $student->semester }} Semester</div></td>
                    <td><div class="k">Date of Issue</div><div class="v">{{ $generated_date }}</div></td>
                </tr>
            </table>

            <div class="section-label">Department Approvals</div>
            <table class="approvals">
                <thead>
                    <tr>
                        <th style="width:34%">Department</th>
                        <th style="width:14%">Status</th>
                        <th style="width:32%">Approved By</th>
                        <th style="width:20%">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($approvals->where('status', 'approved') as $approval)
                        <tr>
                            <td>{{ $approval->department->name }}</td>
                            <td class="ok">Approved</td>
                            <td class="by">{{ $approval->officer->name ?? '—' }}</td>
                            <td>{{ $approval->approved_at ? $approval->approved_at->format('M d, Y') : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="footer">
                <tr>
                    <td class="qr" style="width:35%">
                        @if(isset($qrCode))
                            <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="Verification QR">
                        @endif
                        <div class="cap">Scan to verify authenticity</div>
                    </td>
                    <td style="width:30%"></td>
                    <td class="sign" style="width:35%">
                        <div class="sign-line"></div>
                        <div class="role">Registrar</div>
                        <div class="org">{{ $university_name }}</div>
                    </td>
                </tr>
            </table>

            <div class="security">
                <div class="row"><strong>Security Code:</strong> <span class="mono">{{ $security_code }}</span></div>
                <div class="row"><strong>Document Hash:</strong> <span class="mono">{{ $document_hash }}</span></div>
                <div class="row"><strong>Issued:</strong> {{ $issued_datetime }} &nbsp;|&nbsp; <strong>Valid Until:</strong> {{ $validity_date }} (4 years)</div>
            </div>

            <div class="verify-line">
                Verify this certificate online at <span class="verify-url">{{ $verify_url ?? url('/verify/' . $clearance->reference_no) }}</span>
            </div>
            <div class="legal">
                Any unauthorized alteration, forgery, or duplication of this certificate is illegal and subject to prosecution.
                For verification queries: registrar@salale.edu.et
            </div>
        </div>
    </div>
</div>
</body>
</html>
