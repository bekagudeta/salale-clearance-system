<?php

namespace Tests\Feature;

use App\Models\ClearanceApproval;
use App\Models\Department;
use App\Models\Student;
use App\Services\ClearanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ClearanceSubmissionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_only_one_pending_approval_per_active_department()
    {
        $student = Student::factory()->create();
        $activeDepartments = Department::factory()->count(3)->create();
        Department::factory()->inactive()->create();

        $clearance = app(ClearanceService::class)->createClearance([
            'type' => 'semester_completion',
            'reason' => 'Semester completed.',
        ], $student->id);

        $this->assertSame($activeDepartments->count(), $clearance->approvals()->count());

        foreach ($activeDepartments as $department) {
            $this->assertSame(1, ClearanceApproval::where([
                'clearance_request_id' => $clearance->id,
                'department_id' => $department->id,
                'status' => 'pending',
            ])->count());
        }
    }
}
