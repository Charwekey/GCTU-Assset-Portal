<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount(['assets', 'projects', 'procurements'])->paginate(10);
        return view('admin.departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:departments,code|max:20',
            'budget_limit' => 'required|numeric|min:0',
        ]);

        $dept = Department::create($validated);

        AuditLog::log('department_creation', "Created department: {$dept->name} ({$dept->code}) with budget {$dept->budget_limit}.");

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function update(Request $request, Department $department)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:departments,code,' . $department->id,
            'budget_limit' => 'required|numeric|min:0',
        ]);

        $department->update($validated);

        AuditLog::log('department_update', "Updated department: {$department->name} to budget limit {$department->budget_limit}.");

        return redirect()->route('departments.index')->with('success', 'Department details updated.');
    }

    public function destroy(Department $department)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $name = $department->name;
        $department->delete();

        AuditLog::log('department_deletion', "Deleted department: {$name}.");

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}
