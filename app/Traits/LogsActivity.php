<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    /**
     * Log an activity
     */
    protected function logActivity($action, $tableName = null, $recordId = null, $description = null)
    {
        try {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'table_name' => $tableName,
                'record_id' => $recordId,
                'description' => $description,
                'ip_address' => Request::ip(),
            ]);
        } catch (\Exception $e) {
            // Silent fail - don't let logging break the application
            \Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }

    /**
     * Log user login activity
     */
    protected function logLogin($user)
    {
        $this->logActivity(
            'login',
            'users',
            $user->id,
            "User {$user->email} logged in successfully"
        );
    }

    /**
     * Log user logout activity
     */
    protected function logLogout($user)
    {
        $this->logActivity(
            'logout',
            'users',
            $user->id,
            "User {$user->email} logged out"
        );
    }

    /**
     * Log failed login attempt
     */
    protected function logFailedLogin($email, $ip = null)
    {
        try {
            ActivityLog::create([
                'user_id' => null,
                'action' => 'failed_login',
                'table_name' => 'users',
                'record_id' => null,
                'description' => "Failed login attempt for email: {$email}",
                'ip_address' => $ip ?? Request::ip(),
            ]);
        } catch (\Exception $e) {
            // Silent fail
        }
    }

    /**
     * Log model created event
     */
    protected function logCreated($model)
    {
        $this->logActivity(
            'created',
            $model->getTable(),
            $model->id,
            "Created new record in " . $model->getTable()
        );
    }

    /**
     * Log model updated event
     */
    protected function logUpdated($model, $changes = null)
    {
        $description = "Updated record in " . $model->getTable();
        if ($changes) {
            $description .= ": " . json_encode($changes);
        }
        
        $this->logActivity(
            'updated',
            $model->getTable(),
            $model->id,
            $description
        );
    }

    /**
     * Log model deleted event
     */
    protected function logDeleted($model)
    {
        $this->logActivity(
            'deleted',
            $model->getTable(),
            $model->id,
            "Deleted record from " . $model->getTable()
        );
    }

    /**
     * Log data export
     */
    protected function logExport($tableName, $format, $recordCount)
    {
        $this->logActivity(
            'export',
            $tableName,
            null,
            "Exported {$recordCount} records from {$tableName} as {$format}"
        );
    }

    /**
     * Log bulk operation
     */
    protected function logBulkOperation($action, $tableName, $recordIds, $description = null)
    {
        $this->logActivity(
            "bulk_{$action}",
            $tableName,
            null,
            $description ?? "Bulk {$action} on records: " . implode(', ', $recordIds)
        );
    }

    /**
     * Log system error
     */
    protected function logSystemError($error, $context = null)
    {
        $this->logActivity(
            'system_error',
            null,
            null,
            "System error: {$error}" . ($context ? " Context: " . json_encode($context) : "")
        );
    }

    /**
     * Get user activity summary
     */
    protected function getUserActivitySummary($userId, $days = 30)
    {
        return ActivityLog::where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays($days))
            ->select('action', \DB::raw('COUNT(*) as count'))
            ->groupBy('action')
            ->get();
    }
}