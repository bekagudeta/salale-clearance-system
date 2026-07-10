<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Throwable;

class HomeController extends Controller
{
    public function index()
    {
        return view('public.home', [
            'stats' => $this->liveStats(),
        ]);
    }

    public function about()
    {
        return view('public.about');
    }

    /**
     * Real, verifiable system figures for the public landing page.
     *
     * These are structural facts about the clearance workflow (how many
     * departments are wired into the chain, how many roles exist), not
     * marketing numbers — so they stay honest as the system grows. Cached
     * briefly to keep the public page fast, and wrapped so a database hiccup
     * never takes the landing page down.
     */
    private function liveStats(): array
    {
        $fallback = [
            'departments' => 16,
            'academic'    => 6,
            'service'     => 10,
            'roles'       => 4,
        ];

        try {
            return Cache::remember('public.home.stats', now()->addMinutes(10), function () use ($fallback) {
                if (! Schema::hasTable('departments')) {
                    return $fallback;
                }

                $active = Department::where('is_active', true);

                return [
                    'departments' => (clone $active)->count(),
                    'academic'    => (clone $active)->where('category', 'academic')->count(),
                    'service'     => (clone $active)->where('category', 'service')->count(),
                    'roles'       => Schema::hasTable('roles') ? Role::count() : $fallback['roles'],
                ];
            });
        } catch (Throwable $e) {
            return $fallback;
        }
    }
}
