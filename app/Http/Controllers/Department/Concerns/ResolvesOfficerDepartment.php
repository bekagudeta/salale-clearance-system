<?php

namespace App\Http\Controllers\Department\Concerns;

use App\Models\Department;

trait ResolvesOfficerDepartment
{
    protected function officerDepartment(): ?Department
    {
        $user = auth()->user();

        return $user->assignedDepartments->first()
            ?? $user->departments()->wherePivot('can_approve', true)->first();
    }
}
