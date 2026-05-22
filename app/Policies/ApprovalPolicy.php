<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ClearanceApproval;

class ApprovalPolicy
{
    public function approve(User $user, ClearanceApproval $approval)
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        
        if ($user->hasRole('department_officer')) {
            $hasAssigned = $user->assignedDepartments->contains($approval->department_id)
                || $user->departments()->wherePivot('can_approve', true)
                    ->where('departments.id', $approval->department_id)
                    ->exists();

            return $hasAssigned;
        }

        if ($user->hasRole('registrar')) {
            return $approval->department->slug === 'registrar-office';
        }
        
        return false;
    }

    public function reject(User $user, ClearanceApproval $approval)
    {
        return $this->approve($user, $approval);
    }

    public function view(User $user, ClearanceApproval $approval)
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        
        if ($user->hasRole('student') && $approval->request->student->user_id === $user->id) {
            return true;
        }
        
        if ($user->hasRole('registrar')) {
            return true;
        }
        
        if ($user->hasRole('department_officer')) {
            $hasAssigned = $user->assignedDepartments->contains($approval->department_id)
                || $user->departments()->wherePivot('can_approve', true)
                    ->where('departments.id', $approval->department_id)
                    ->exists();

            return $hasAssigned;
        }
        
        return false;
    }
}