<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CertificateAudit;
use App\Models\ClearanceRequest;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function index()
    {
        return view('public.verify-form');
    }

    /**
     * Manual verification via the form. A security code is optional:
     *  - reference only            → basic confirmation (no personal details)
     *  - reference + valid code    → full verified details
     */
    public function check(Request $request)
    {
        $request->validate([
            'reference_no' => 'required|string',
            'security_code' => 'nullable|string',
        ]);

        return $this->resolve($request->reference_no, $request->security_code);
    }

    /**
     * QR / link verification. The QR code carries ?code=<security_code>, so a
     * scan lands here with full proof; a hand-typed reference shows basic info.
     */
    public function show($reference, Request $request)
    {
        return $this->resolve($reference, $request->query('code'));
    }

    public function apiVerify($reference, Request $request)
    {
        $result = $this->evaluate($reference, $request->query('code'));

        if ($result['state'] === 'invalid') {
            return response()->json([
                'valid' => false,
                'message' => $result['message'],
            ], $result['clearance'] ? 400 : 404);
        }

        if ($result['state'] === 'expired') {
            return response()->json([
                'valid' => false,
                'message' => 'This certificate has expired.',
                'reference_no' => $reference,
            ], 410);
        }

        $clearance = $result['clearance'];

        // Basic (reference-only) responses omit personal data to prevent harvesting
        // by enumerating reference numbers.
        if ($result['state'] === 'basic') {
            return response()->json([
                'valid' => true,
                'verification_level' => 'basic',
                'message' => 'A completed clearance certificate exists for this reference. Provide the security code (or scan the QR code) for full verification.',
                'reference_no' => $clearance->reference_no,
                'type' => ucfirst(str_replace('_', ' ', $clearance->type)),
                'completed_date' => $clearance->completed_at?->toDateString(),
            ], 200);
        }

        return response()->json([
            'valid' => true,
            'verification_level' => 'full',
            'reference_no' => $clearance->reference_no,
            'student_name' => $clearance->student->full_name,
            'student_id' => $clearance->student->student_id,
            'type' => ucfirst(str_replace('_', ' ', $clearance->type)),
            'status' => ucfirst($clearance->status),
            'completed_date' => $clearance->completed_at?->toDateString(),
            'approvals' => $clearance->approvals->where('status', 'approved')->values()->map(function ($approval) {
                return [
                    'department' => $approval->department->name,
                    'status' => ucfirst($approval->status),
                    'date' => $approval->approved_at?->toDateString(),
                ];
            }),
        ], 200);
    }

    /**
     * Resolve a verification request to a rendered result view.
     */
    protected function resolve($reference, $securityCode)
    {
        $result = $this->evaluate($reference, $securityCode);

        return view('public.verify-result', [
            'clearance' => $result['clearance'],
            'state' => $result['state'],
            'message' => $result['message'],
            'maskedName' => $result['clearance'] ? $this->maskName($result['clearance']->student->full_name) : null,
        ]);
    }

    /**
     * Core verification logic shared by the web and API endpoints.
     *
     * @return array{state:string,clearance:?ClearanceRequest,message:?string}
     */
    protected function evaluate($reference, $securityCode): array
    {
        $clearance = ClearanceRequest::where('reference_no', $reference)
            ->with(['student', 'approvals.department'])
            ->first();

        if (! $clearance || $clearance->status !== 'completed') {
            return [
                'state' => 'invalid',
                'clearance' => $clearance,
                'message' => 'No valid completed certificate exists for this reference number.',
            ];
        }

        // A security code was supplied (QR scan or typed): it must match an audit row.
        if (! empty($securityCode)) {
            $audit = CertificateAudit::where('clearance_id', $clearance->id)
                ->where('security_code', $securityCode)
                ->first();

            if (! $audit) {
                return [
                    'state' => 'invalid',
                    'clearance' => $clearance,
                    'message' => 'The security code does not match our records. This certificate may be altered or counterfeit.',
                ];
            }

            if ($audit->validity_date && $audit->validity_date->isPast()) {
                return [
                    'state' => 'expired',
                    'clearance' => $clearance,
                    'message' => 'This certificate was authentic but expired on ' . $audit->validity_date->format('F d, Y') . '.',
                ];
            }

            return ['state' => 'verified', 'clearance' => $clearance, 'message' => null];
        }

        // No code: confirm existence only, without exposing personal details.
        return ['state' => 'basic', 'clearance' => $clearance, 'message' => null];
    }

    /**
     * Mask a name for basic verification, e.g. "Abel Tesfaye" -> "A**** T*****".
     */
    protected function maskName(?string $name): string
    {
        if (! $name) {
            return '—';
        }

        return collect(explode(' ', trim($name)))
            ->filter()
            ->map(fn ($part) => mb_substr($part, 0, 1) . str_repeat('*', max(1, mb_strlen($part) - 1)))
            ->implode(' ');
    }
}
