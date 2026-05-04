<?php

namespace App\Traits;

use App\Models\ClearanceRequest;

trait GeneratesReference
{
    protected function generateReferenceNumber()
    {
        $year = date('Y');
        $month = date('m');
        $count = ClearanceRequest::whereYear('created_at', $year)->count() + 1;
        
        return sprintf('SAL/%s/%s/%05d', $year, $month, $count);
    }
}