<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('officer')
            ->orderBy('priority_order')
            ->paginate(15);
        
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        $officers = User::role('department_officer')->get();
        return view('admin.departments.create', compact('officers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'slug' => 'required|string|max:255|unique:departments',
            'officer_user_id' => 'nullable|exists:users,id',
            'priority_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['priority_order'] = $validated['priority_order'] ?? (Department::max('priority_order') + 1);

        Department::create($validated);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        $officers = User::role('department_officer')->get();
        return view('admin.departments.edit', compact('department', 'officers'));
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $id,
            'slug' => 'required|string|max:255|unique:departments,slug,' . $id,
            'officer_user_id' => 'nullable|exists:users,id',
            'priority_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['priority_order'] = $validated['priority_order'] ?? $department->priority_order;

        $department->update($validated);
        
        return redirect()->route('admin.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        
        // Check if department has any approvals
        if ($department->approvals()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete department with existing clearance approvals.');
        }
        
        $department->delete();
        
        return redirect()->route('admin.departments.index')
            ->with('success', 'Department deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'departments' => 'required|array',
            'departments.*' => 'exists:departments,id',
        ]);
        
        foreach ($request->departments as $index => $departmentId) {
            Department::where('id', $departmentId)->update(['priority_order' => $index + 1]);
        }
        
        return response()->json(['success' => true]);
    }

    public function toggleStatus($id)
    {
        $department = Department::findOrFail($id);
        $department->update(['is_active' => !$department->is_active]);
        
        $status = $department->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Department {$status} successfully.");
    }

    public function assignOfficer(Request $request, $id)
    {
        $request->validate([
            'officer_user_id' => 'required|exists:users,id',
        ]);
        
        $department = Department::findOrFail($id);
        $department->update(['officer_user_id' => $request->officer_user_id]);
        
        return redirect()->back()
            ->with('success', 'Department officer assigned successfully.');
    }
}