<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Clearance Certificate &mdash; {{ config('app.name', 'Salale University') }}</title>
    <style>
        :root { --teal:#084A48; --dark:#001722; --gold:#C9A227; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(160deg, #001722 0%, #084A48 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 24px; color: #1f2a2e;
        }
        .card { width: 100%; max-width: 440px; background: #fff; border-radius: 18px; overflow: hidden; box-shadow: 0 24px 60px rgba(0,0,0,.35); }
        .card-head { background: var(--teal); padding: 34px 28px; text-align: center; color: #fff; }
        .seal { width: 58px; height: 58px; line-height: 54px; margin: 0 auto 12px; border: 2px solid var(--gold); border-radius: 50%; font-size: 24px; font-weight: 700; }
        .card-head h1 { font-size: 21px; font-weight: 700; }
        .card-head p { color: #b9d4d2; font-size: 13px; margin-top: 6px; }
        .card-body { padding: 28px; }
        label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .hint { font-size: 12px; color: #9ca3af; font-weight: 400; }
        input { width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px; font-family: monospace; }
        input:focus { outline: none; border-color: var(--teal); box-shadow: 0 0 0 3px rgba(8,74,72,.15); }
        .field { margin-bottom: 18px; }
        .err { color: #dc2626; font-size: 12px; margin-top: 6px; }
        .flash { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; padding: 10px 12px; border-radius: 10px; font-size: 13px; margin-bottom: 18px; }
        button { width: 100%; background: var(--teal); color: #fff; border: 0; padding: 13px; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; transition: background .15s; }
        button:hover { background: #06605d; }
        .back { display: block; text-align: center; margin-top: 18px; color: var(--teal); text-decoration: none; font-size: 13px; }
        .note { margin-top: 18px; font-size: 12px; color: #6b7280; text-align: center; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-head">
            <div class="seal">{{ strtoupper(substr(config('app.name', 'Salale University'), 0, 1)) }}</div>
            <h1>Verify Clearance Certificate</h1>
            <p>Confirm the authenticity of an issued certificate</p>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="flash">{{ session('error') }}</div>
            @endif

            <form action="{{ route('verify.check') }}" method="POST">
                @csrf
                <div class="field">
                    <label for="reference_no">Reference Number</label>
                    <input id="reference_no" type="text" name="reference_no" value="{{ old('reference_no') }}" required placeholder="SAL/2024/01/00001">
                    @error('reference_no') <p class="err">{{ $message }}</p> @enderror
                </div>
                <div class="field">
                    <label for="security_code">Security Code <span class="hint">(optional &mdash; for full verification)</span></label>
                    <input id="security_code" type="text" name="security_code" value="{{ old('security_code') }}" placeholder="Found on the certificate">
                </div>
                <button type="submit">Verify Certificate</button>
            </form>

            <p class="note">Tip: scanning the QR code on the certificate verifies it instantly with full details.</p>
            <a class="back" href="{{ route('home') }}">&larr; Back to Home</a>
        </div>
    </div>
</body>
</html>
