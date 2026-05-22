<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsOfficer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->hasRole('department_officer')) {
            abort(403, 'Access denied. Department officer access required.');
        }

        // Check if officer is assigned to a department (direct assignment or via pivot with can_approve)
        $hasAssignment = Auth::user()->assignedDepartments->isNotEmpty()
            || Auth::user()->departments()->wherePivot('can_approve', true)->exists();

        if (! $hasAssignment) {
            abort(403, 'No department assigned. Please contact administrator.');
        }

        return $next($request);
    }
}