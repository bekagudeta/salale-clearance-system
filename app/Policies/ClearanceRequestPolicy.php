<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ClearanceRequest;

class ClearanceRequestPolicy
{
    public function view(User $user, ClearanceRequest $clearance)
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        
        if ($user->hasRole('student') && $user->student && $user->student->id === $clearance->student_id) {
            return true;
        }
        
        if ($user->hasRole('registrar')) {
            return true;
        }
        
        if ($user->hasRole('department_officer')) {
            $assigned = $user->assignedDepartments->pluck('id')->toArray();
            $pivot = $user->departments()->wherePivot('can_approve', true)->pluck('departments.id')->toArray();
            $departments = array_unique(array_merge($assigned, $pivot));

            return $clearance->approvals()->whereIn('department_id', $departments)->exists();
        }
        
        return false;
    }

    public function create(User $user)
    {
        return $user->hasRole('student');
    }

    public function update(User $user, ClearanceRequest $clearance)
    {
        return $user->hasRole('super_admin') || $user->hasRole('registrar');
    }

    public function delete(User $user, ClearanceRequest $clearance)
    {
        return $user->hasRole('super_admin');
    }
}