<?php

namespace App\Traits;

use App\Models\ClearanceRequest;

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
        
        $lastRequest = ClearanceRequest::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastRequest) {
            $lastNumber = intval(substr($lastRequest->reference_no, -5));
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }
        
        return "{$prefix}/{$year}/{$month}/{$newNumber}";
    }

    /**
     * Generate sequential number
     */
    protected function generateSequentialNumber($prefix, $length = 5)
    {
        $lastRecord = ClearanceRequest::orderBy('id', 'desc')->first();
        $lastId = $lastRecord ? $lastRecord->id : 0;
        $newId = $lastId + 1;
        
        return $prefix . str_pad($newId, $length, '0', STR_PAD_LEFT);
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