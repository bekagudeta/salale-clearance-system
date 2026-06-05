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
            position: relative;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(30, 58, 138, 0.08);
            z-index: 0;
            pointer-events: none;
            font-weight: bold;
            letter-spacing: 20px;
        }
        .certificate {
            border: 3px solid #1e3a8a;
            padding: 30px;
            position: relative;
            background: linear-gradient(135deg, #fff 0%, #f0f9ff 100%);
            z-index: 10;
            box-shadow: 0 0 20px rgba(30, 58, 138, 0.15);
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
        .security-banner {
            background: linear-gradient(90deg, #1e3a8a, #2d5a96);
            color: white;
            padding: 12px;
            border-radius: 5px;
            font-size: 11px;
            text-align: center;
            margin: 15px 0;
            font-weight: bold;
            letter-spacing: 1px;
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
            font-weight: 500;
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
        .security-info {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            padding: 12px;
            margin: 15px 0;
            font-size: 9px;
        }
        .security-info-label {
            font-weight: bold;
            color: #059669;
            margin-bottom: 5px;
        }
        .security-info-value {
            color: #047857;
            font-family: monospace;
            word-break: break-all;
        }
        .validity-section {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            padding: 12px;
            border-radius: 5px;
            margin: 15px 0;
            font-size: 10px;
        }
        .validity-section strong {
            color: #92400e;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #d1d5db;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
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
            font-size: 9px;
            color: #6b7280;
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background: #f3f4f6;
            border-radius: 5px;
            border: 1px solid #d1d5db;
        }
        .verification-link {
            color: #1e3a8a;
            text-decoration: underline;
            font-weight: bold;
        }
        .timestamp {
            font-size: 8px;
            color: #9ca3af;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="watermark">OFFICIAL CERTIFICATE</div>
    
    <div class="certificate">
        <div class="header">
            <div class="university-name">SALALE UNIVERSITY</div>
            <div class="certificate-title">CLEARANCE CERTIFICATE</div>
            <div style="font-size: 12px; color: #4b5563; margin-top: 8px;">Reference No: <strong>{{ $clearance->reference_no }}</strong></div>
        </div>
        
        <div class="security-banner">
            🔒 SECURE OFFICIAL DOCUMENT - DIGITALLY VERIFIED
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
        
        <div class="security-info">
            <div class="security-info-label">🔐 SECURITY INFORMATION</div>
            <div style="margin: 5px 0;">
                <strong>Security Code:</strong> <span class="security-info-value">{{ $security_code }}</span>
            </div>
            <div style="margin: 5px 0;">
                <strong>Document Hash:</strong> <span class="security-info-value">{{ $document_hash }}</span>
            </div>
            <div style="margin: 5px 0;">
                <strong>Issued:</strong> {{ $issued_datetime }}
            </div>
        </div>
        
        <div class="validity-section">
            <strong>⏰ VALIDITY PERIOD</strong><br>
            Issued: {{ $generated_date }}<br>
            Valid Until: {{ $validity_date }}<br>
            <span style="color: #dc2626;">This certificate is valid for 4 years from the date of issue.</span>
        </div>
        
        <div class="footer">
            <div class="qr-code">
                @if(isset($qrCode))
                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="100" height="100" alt="QR Code">
                @endif
                <p class="verification-text">Scan QR Code to Verify</p>
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <p style="font-size: 11px; font-weight: bold;">Registrar's Signature</p>
                <p style="font-size: 10px;">Salale University</p>
                <p style="font-size: 9px; color: #6b7280;">Date: {{ $generated_date }}</p>
            </div>
        </div>
        
        <div class="verification-text">
            ✓ This is an officially issued digital certificate from Salale University.<br>
            <strong>Verify this certificate online:</strong><br>
            <span class="verification-link">{{ url('/verify') }}/{{ $clearance->reference_no }}</span><br>
            <br>
            <strong>Security Code for Verification:</strong> {{ $security_code }}<br>
            <br>
            <strong>For verification queries:</strong> registrar@salale.edu.et<br>
            <br>
            <em>Any unauthorized alteration, forgery, or duplication of this certificate is illegal and subject to prosecution.</em>
        </div>
        
        <div class="timestamp">
            Generated on {{ $issued_datetime }} | Certificate ID: {{ $security_code }}
        </div>
    </div>
</body>
</html>