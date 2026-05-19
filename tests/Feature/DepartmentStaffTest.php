<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DepartmentStaffTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_assigns_multiple_staff_to_department()
    {
        // Department has 3 staff members
        $department = Department::factory()->create();
        $staff = User::factory()->count(3)->create();

        foreach ($staff as $index => $member) {
            $position = $index === 0 ? 'head' : 'staff';
            $department->staff()->attach($member->id, [
                'position' => $position,
                'can_approve' => true,
            ]);
        }

        $this->assertCount(3, $department->fresh()->staff);
        $this->assertEquals('head', $department->staff->first()->pivot->position);
    }

    #[Test]
    public function it_gets_all_approvable_staff_in_department()
    {
        $department = Department::factory()->create();
        $approvableStaff = User::factory()->count(2)->create();
        $nonApprovableStaff = User::factory()->create();

        foreach ($approvableStaff as $index => $member) {
            $department->staff()->attach($member->id, [
                'position' => $index === 0 ? 'head' : 'staff',
                'can_approve' => true,
            ]);
        }

        $department->staff()->attach($nonApprovableStaff->id, [
            'position' => 'viewer',
            'can_approve' => false,
        ]);

        $this->assertCount(2, $department->fresh()->allStaff());
    }

    #[Test]
    public function it_identifies_staff_by_department()
    {
        $dept1 = Department::factory()->create();
        $dept2 = Department::factory()->create();

        $staffUser = User::factory()->create();

        // Same staff in multiple departments
        $dept1->staff()->attach($staffUser->id, ['position' => 'head', 'can_approve' => true]);
        $dept2->staff()->attach($staffUser->id, ['position' => 'staff', 'can_approve' => true]);

        $this->assertTrue($staffUser->departments->contains($dept1));
        $this->assertTrue($staffUser->departments->contains($dept2));
        $this->assertEquals('head', $staffUser->departments->find($dept1->id)->pivot->position);
        $this->assertEquals('staff', $staffUser->departments->find($dept2->id)->pivot->position);
    }
}
