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
            return $user->assignedDepartments->contains($approval->department_id);
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
            return $user->assignedDepartments->contains($approval->department_id);
        }
        
        return false;
    }
}