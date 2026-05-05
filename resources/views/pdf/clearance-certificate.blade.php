<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Clearance Certificate - {{ $clearance->reference_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            padding: 40px;
            background: white;
        }
        .certificate {
            border: 3px solid #1e3a8a;
            padding: 30px;
            position: relative;
            background: linear-gradient(135deg, #fff 0%, #f0f9ff 100%);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 20px;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }
        .university-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .certificate-title {
            font-size: 28px;
            font-weight: bold;
            color: #dc2626;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .student-info {
            background: #f3f4f6;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .info-row {
            margin: 10px 0;
            display: flex;
        }
        .info-label {
            width: 150px;
            font-weight: bold;
            color: #4b5563;
        }
        .info-value {
            flex: 1;
            color: #1f2937;
        }
        .approvals-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .approvals-table th {
            background: #1e3a8a;
            color: white;
            padding: 10px;
            text-align: left;
        }
        .approvals-table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .status-approved {
            color: #16a34a;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #d1d5db;
            display: flex;
            justify-content: space-between;
        }
        .qr-code {
            text-align: center;
        }
        .signature {
            text-align: center;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 10px;
        }
        .verification-text {
            font-size: 10px;
            color: #6b7280;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">
            <div class="university-name">SALALE UNIVERSITY</div>
            <div class="certificate-title">CLEARANCE CERTIFICATE</div>
            <div>Reference No: {{ $clearance->reference_no }}</div>
        </div>
        
        <div class="student-info">
            <div class="info-row">
                <div class="info-label">Student Name:</div>
                <div class="info-value">{{ $student->full_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Student ID:</div>
                <div class="info-value">{{ $student->student_id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Faculty:</div>
                <div class="info-value">{{ $student->faculty }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Department:</div>
                <div class="info-value">{{ $student->department }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Year of Study:</div>
                <div class="info-value">Year {{ $student->year }} - {{ $student->semester }} Semester</div>
            </div>
            <div class="info-row">
                <div class="info-label">Clearance Type:</div>
                <div class="info-value">{{ ucfirst(str_replace('_', ' ', $clearance->type)) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date:</div>
                <div class="info-value">{{ $generated_date }}</div>
            </div>
        </div>
        
        <h3 style="margin: 20px 0 10px 0;">Department Approvals</h3>
        <table class="approvals-table">
            <thead>
                <tr>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Date</th>
                 </tr>
            </thead>
            <tbody>
                @foreach($approvals->where('status', 'approved') as $approval)
                <tr>
                    <td>{{ $approval->department->name }}</td>
                    <td class="status-approved">✓ Approved</td>
                    <td>{{ $approval->approved_at ? $approval->approved_at->format('M d, Y') : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="footer">
            <div class="qr-code">
                @if(isset($qrCode))
                    <img src="data:image/png;base64,{{ $qrCode }}" width="100" height="100" alt="QR Code">
                @endif
                <p class="verification-text">Scan to verify</p>
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <p>Registrar's Signature</p>
                <p class="verification-text">Salale University</p>
            </div>
        </div>
        
        <div class="verification-text">
            This is an electronically generated certificate. Valid for verification on the university portal.
            <br>
            Verify at: {{ url('/verify') }}/{{ $clearance->reference_no }}
        </div>
    </div>
</body>
</html>