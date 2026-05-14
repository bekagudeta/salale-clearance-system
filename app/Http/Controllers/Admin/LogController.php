<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');
        
        // Filter by action
        if ($request->action && $request->action != 'all') {
            $query->where('action', $request->action);
        }
        
        // Filter by table
        if ($request->table_name) {
            $query->where('table_name', $request->table_name);
        }
        
        // Filter by user
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter by date range
        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('description', 'like', "%{$request->search}%")
                  ->orWhere('ip_address', 'like', "%{$request->search}%");
            });
        }
        
        $logs = $query->orderBy('created_at', 'desc')
            ->paginate(50);
        
        // Get filter options
        $actions = ActivityLog::select('action')->distinct()->orderBy('action')->pluck('action');
        $tables = ActivityLog::select('table_name')->distinct()->orderBy('table_name')->pluck('table_name');
        $users = ActivityLog::with('user')->get()->pluck('user')->filter()->unique('id')->sortBy('name');
        
        // Statistics
        $stats = [
            'total_logs' => ActivityLog::count(),
            'today_logs' => ActivityLog::whereDate('created_at', today())->count(),
            'unique_users' => ActivityLog::distinct('user_id')->count('user_id'),
            'most_active_action' => ActivityLog::select('action', DB::raw('count(*) as total'))
                ->groupBy('action')
                ->orderBy('total', 'desc')
                ->first(),
        ];
        
        return view('admin.logs.index', compact('logs', 'actions', 'tables', 'users', 'stats', 'request'));
    }

    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);
        return view('admin.logs.show', compact('log'));
    }

    public function clear(Request $request)
    {
        $request->validate([
            'days' => 'nullable|integer|min:1',
        ]);
        
        $days = $request->days ?? 90;
        $date = now()->subDays($days);
        
        $deleted = ActivityLog::where('created_at', '<', $date)->delete();
        
        return redirect()->route('admin.logs.index')
            ->with('success', "Deleted {$deleted} log records older than {$days} days.");
    }

    public function export(Request $request)
    {
        $query = ActivityLog::with('user');
        
        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->get();
        
        $csvData = [];
        $csvData[] = ['ID', 'User', 'Action', 'Table', 'Record ID', 'Description', 'IP Address', 'Timestamp'];
        
        foreach ($logs as $log) {
            $csvData[] = [
                $log->id,
                $log->user ? $log->user->name : 'System',
                $log->action,
                $log->table_name,
                $log->record_id,
                $log->description,
                $log->ip_address,
                $log->created_at,
            ];
        }
        
        $filename = "activity_logs_" . date('Y-m-d_His') . ".csv";
        $handle = fopen('php://temp', 'w');
        
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);
        
        return response($content, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }
}