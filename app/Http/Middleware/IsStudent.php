<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsStudent
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

        if (!Auth::user()->hasRole('student')) {
            abort(403, 'Access denied. Student access required.');
        }

        // Check if student profile exists
        if (!Auth::user()->student) {
            abort(403, 'Student profile not found. Please contact administrator.');
        }

        return $next($request);
    }
}