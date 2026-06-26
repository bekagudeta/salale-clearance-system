<?php

namespace App\Services;

use App\Models\DepartmentStudentCase;
use App\Models\Student;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\DB;

class StudentCaseService
{
    use LogsActivity;

    public function findStudentByStudentId(string $studentId): ?Student
    {
        return Student::where('student_id', $studentId)->first();
    }

    public function recordCase(int $departmentId, int $studentId, array $data, int $recordedBy): DepartmentStudentCase
    {
        return DB::transaction(function () use ($departmentId, $studentId, $data, $recordedBy) {
            $case = DepartmentStudentCase::create([
                'student_id' => $studentId,
                'department_id' => $departmentId,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'status' => 'open',
                'recorded_by' => $recordedBy,
            ]);

            $this->logActivity(
                'record_student_case',
                'department_student_cases',
                $case->id,
                "Recorded case for student #{$studentId}: {$case->title}"
            );

            return $case;
        });
    }

    public function clearCase(int $caseId, int $clearedBy): DepartmentStudentCase
    {
        return DB::transaction(function () use ($caseId, $clearedBy) {
            $case = DepartmentStudentCase::with('student')->findOrFail($caseId);

            if (! $case->isOpen()) {
                throw new \RuntimeException('This case has already been cleared.');
            }

            $case->update([
                'status' => 'cleared',
                'cleared_by' => $clearedBy,
                'cleared_at' => now(),
            ]);

            $this->logActivity(
                'clear_student_case',
                'department_student_cases',
                $case->id,
                "Cleared case: {$case->title}"
            );

            return $case;
        });
    }

    public function hasOpenCases(int $departmentId, int $studentId): bool
    {
        return DepartmentStudentCase::where('department_id', $departmentId)
            ->where('student_id', $studentId)
            ->open()
            ->exists();
    }

    public function getOpenCases(int $departmentId, int $studentId)
    {
        return DepartmentStudentCase::where('department_id', $departmentId)
            ->where('student_id', $studentId)
            ->open()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getDepartmentCases(int $departmentId, array $filters = [])
    {
        $query = DepartmentStudentCase::where('department_id', $departmentId)
            ->with(['student', 'recorder', 'clearer']);

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['student_id'])) {
            $student = $this->findStudentByStudentId($filters['student_id']);
            if ($student) {
                $query->where('student_id', $student->id);
            } else {
                $query->whereRaw('0 = 1');
            }
        }

        return $query->orderByDesc('created_at')
            ->paginate($filters['per_page'] ?? 15);
    }

    public function getOpenCasesGroupedByStudent(int $departmentId, array $studentIds)
    {
        if (empty($studentIds)) {
            return collect();
        }

        return DepartmentStudentCase::where('department_id', $departmentId)
            ->whereIn('student_id', $studentIds)
            ->open()
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('student_id');
    }
}
