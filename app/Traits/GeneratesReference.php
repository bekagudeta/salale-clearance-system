<?php

namespace App\Traits;

use App\Models\ClearanceRequest;
use Illuminate\Support\Facades\DB;

trait GeneratesReference
{
    /**
     * Generate unique reference number for clearance
     * Format: SAL/YYYY/MM/XXXXX
     */
    protected function generateReferenceNumber()
    {
        $year = date('Y');
        $month = date('m');
        $prefix = 'SAL';

        $maxAttempts = 5;
        $attempt = 0;

        while (true) {
            try {
                return DB::transaction(function () use ($year, $month, $prefix) {
                    $lastRequest = DB::table('clearance_requests')
                        ->whereYear('created_at', $year)
                        ->orderBy('id', 'desc')
                        ->lockForUpdate()
                        ->first();

                    if ($lastRequest && isset($lastRequest->reference_no) && preg_match('/(\d{5})$/', $lastRequest->reference_no, $m)) {
                        $lastNumber = intval($m[1]);
                        $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
                    } else {
                        $newNumber = '00001';
                    }

                    return "{$prefix}/{$year}/{$month}/{$newNumber}";
                });
            } catch (\Throwable $e) {
                $attempt++;
                if ($attempt >= $maxAttempts) {
                    // Fallback: produce a low-collision random suffix to avoid blocking the request
                    $rand = strtoupper(substr(md5(uniqid((string) microtime(true), true)), 0, 5));
                    return "{$prefix}/{$year}/{$month}/{$rand}";
                }

                // Exponential-ish backoff (in microseconds)
                usleep(100000 * $attempt);
                continue;
            }
        }
    }

    /**
     * Generate sequential number
     */
    protected function generateSequentialNumber($prefix, $length = 5)
    {
        $maxAttempts = 5;
        $attempt = 0;

        while (true) {
            try {
                return DB::transaction(function () use ($prefix, $length) {
                    $lastRecord = DB::table('clearance_requests')->lockForUpdate()->orderBy('id', 'desc')->first();
                    $lastId = $lastRecord ? $lastRecord->id : 0;
                    $newId = $lastId + 1;

                    return $prefix . str_pad($newId, $length, '0', STR_PAD_LEFT);
                });
            } catch (\Throwable $e) {
                $attempt++;
                if ($attempt >= $maxAttempts) {
                    // Fallback to a random-seeming identifier
                    $rand = strtoupper(substr(md5(uniqid((string) microtime(true), true)), 0, $length));
                    return $prefix . $rand;
                }

                usleep(100000 * $attempt);
                continue;
            }
        }
    }

    /**
     * Generate random alpha-numeric code
     */
    protected function generateRandomCode($length = 8)
    {
        return strtoupper(substr(md5(uniqid() . time()), 0, $length));
    }

    /**
     * Generate tracking ID
     */
    protected function generateTrackingId()
    {
        return 'TRK-' . strtoupper(uniqid());
    }

    /**
     * Validate reference number format
     */
    protected function validateReferenceFormat($reference)
    {
        $pattern = '/^SAL\/\d{4}\/\d{2}\/\d{5}$/';
        return preg_match($pattern, $reference);
    }

    /**
     * Extract components from reference number
     */
    protected function parseReference($reference)
    {
        if (!$this->validateReferenceFormat($reference)) {
            return null;
        }
        
        $parts = explode('/', $reference);
        
        return [
            'prefix' => $parts[0],
            'year' => $parts[1],
            'month' => $parts[2],
            'sequence' => $parts[3],
        ];
    }
}