<?php

namespace App\Helpers;

use App\Models\ClearanceRequest;

class ReferenceHelper
{
    public static function generateReferenceNumber()
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
    
    public static function validateReferenceNumber($reference)
    {
        $pattern = '/^SAL\/\d{4}\/\d{2}\/\d{5}$/';
        return preg_match($pattern, $reference);
    }
    
    public static function extractReferenceParts($reference)
    {
        if (!self::validateReferenceNumber($reference)) {
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