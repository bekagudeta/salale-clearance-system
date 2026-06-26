<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Department\Concerns\ResolvesOfficerDepartment;
use App\Http\Requests\Department\StoreStudentCaseRequest;
use App\Services\StudentCaseService;
use Illuminate\Http\Request;

class StudentCaseController extends Controller
{
    use ResolvesOfficerDepartment;

    public function __construct(protected StudentCaseService $studentCaseService)
    {
    }

    public function index(Request $request)
    {
        $department = $this->officerDepartment();
        $cases = $this->studentCaseService->getDepartmentCases($department->id, [
            'status' => $request->query('status'),
            'student_id' => $request->query('student_id'),
        ]);

        $openCount = $department->studentCases()->open()->count();

        return view('department.cases.index', compact('department', 'cases', 'openCount'));
    }

    public function create()
    {
        $department = $this->officerDepartment();

        return view('department.cases.create', compact('department'));
    }

    public function store(StoreStudentCaseRequest $request)
    {
        $department = $this->officerDepartment();
        $student = $this->studentCaseService->findStudentByStudentId($request->student_id);

        if (! $student) {
            return back()
                ->withInput()
                ->withErrors(['student_id' => 'No student found with that ID. Please check and try again.']);
        }

        $this->studentCaseService->recordCase(
            $department->id,
            $student->id,
            $request->only('title', 'description'),
            auth()->id()
        );

        return redirect()
            ->route('department.cases.index')
            ->with('success', "Case recorded for {$student->full_name} ({$student->student_id}).");
    }

    public function clear($id)
    {
        $department = $this->officerDepartment();
        $case = $department->studentCases()->findOrFail($id);

        $this->studentCaseService->clearCase($case->id, auth()->id());

        return redirect()
            ->back()
            ->with('success', 'Case marked as cleared.');
    }

    public function lookup(Request $request)
    {
        $request->validate(['student_id' => 'required|string|max:50']);
        $department = $this->officerDepartment();

        $student = $this->studentCaseService->findStudentByStudentId(trim($request->student_id));

        if (! $student) {
            return response()->json(['found' => false]);
        }

        // Check if student belongs to the officer's department
        if ($student->department_id !== $department->id) {
            return response()->json(['found' => false, 'message' => 'Student not found in your department.']);
        }

        $openCases = $this->studentCaseService->getOpenCases($department->id, $student->id);

        return response()->json([
            'found' => true,
            'student' => [
                'id' => $student->id,
                'student_id' => $student->student_id,
                'full_name' => $student->full_name,
                'faculty' => $student->faculty,
                'department' => $student->department,
            ],
            'open_cases' => $openCases->map(fn ($case) => [
                'id' => $case->id,
                'title' => $case->title,
                'description' => $case->description,
                'recorded_at' => $case->created_at->format('M d, Y'),
            ]),
            'has_open_cases' => $openCases->isNotEmpty(),
        ]);
    }
}
