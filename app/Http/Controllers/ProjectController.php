<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Project::with('department');

        // Scope by department if not Admin or Auditor
        if (!$user->isAdmin() && !$user->isAuditor()) {
            $query->where('department_id', $user->department_id);
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('project_name', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('project_status', $request->input('status'));
        }

        if ($request->filled('department_id') && ($user->isAdmin() || $user->isAuditor())) {
            $query->where('department_id', $request->input('department_id'));
        }

        $projects = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $departments = Department::all();

        return view('projects.index', compact('projects', 'departments'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        Gate::authorize('create', Project::class);

        $user = auth()->user();
        $departments = $user->isAdmin() ? Department::all() : Department::where('id', $user->department_id)->get();

        return view('projects.create', compact('departments'));
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Project::class);

        $user = auth()->user();

        $rules = [
            'project_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'allocated_budget' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'expected_completion' => 'required|date|after_or_equal:start_date',
        ];

        if (!$user->isAdmin()) {
            $request->merge(['department_id' => $user->department_id]);
        }

        $validated = $request->validate($rules);
        $validated['project_status'] = 'planned';
        $validated['actual_spending'] = 0.00;
        $validated['progress_percentage'] = 0;

        $project = Project::create($validated);

        // Audit Log
        AuditLog::log('project_creation', "Created new project: {$project->project_name} for department {$project->department->name} with budget {$project->allocated_budget}.");

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Display project details.
     */
    public function show(Project $project)
    {
        Gate::authorize('view', $project);
        $project->load('department');
        return view('projects.show', compact('project'));
    }

    /**
     * Show edit form.
     */
    public function edit(Project $project)
    {
        Gate::authorize('update', $project);

        $user = auth()->user();
        $departments = $user->isAdmin() ? Department::all() : Department::where('id', $user->department_id)->get();

        return view('projects.edit', compact('project', 'departments'));
    }

    /**
     * Update project parameters.
     */
    public function update(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $user = auth()->user();

        $rules = [
            'project_name' => 'required|string|max:255',
            'allocated_budget' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'expected_completion' => 'required|date|after_or_equal:start_date',
            'project_status' => 'required|in:planned,ongoing,completed,on_hold,cancelled',
        ];

        if ($user->isAdmin()) {
            $rules['department_id'] = 'required|exists:departments,id';
        }

        $validated = $request->validate($rules);
        
        // If status completed and completion date not set
        if ($validated['project_status'] === 'completed' && !$project->completion_date) {
            $validated['completion_date'] = now();
            $validated['progress_percentage'] = 100;
        }

        $project->update($validated);

        // Audit Log
        AuditLog::log('project_modification', "Modified project: {$project->project_name} ({$project->id}).");

        return redirect()->route('projects.show', $project->id)->with('success', 'Project details updated.');
    }

    /**
     * Quick-update project progress and spendings (accessible to Officers too).
     */
    public function updateProgress(Request $request, Project $project)
    {
        Gate::authorize('updateProgress', $project);

        $validated = $request->validate([
            'progress_percentage' => 'required|integer|min:0|max:100',
            'actual_spending' => 'required|numeric|min:0',
            'project_status' => 'required|in:planned,ongoing,completed,on_hold,cancelled',
        ]);

        if ($validated['project_status'] === 'completed' && $project->project_status !== 'completed') {
            $validated['completion_date'] = now();
            $validated['progress_percentage'] = 100;
        }

        $project->update($validated);

        // Audit Log
        AuditLog::log('project_progress_update', "Updated progress on project '{$project->project_name}' to {$project->progress_percentage}% with spending {$project->actual_spending}.");

        return redirect()->route('projects.show', $project->id)->with('success', 'Project progress updated.');
    }

    /**
     * Delete a project.
     */
    public function destroy(Project $project)
    {
        Gate::authorize('delete', $project);

        $name = $project->project_name;
        $project->delete();

        // Audit Log
        AuditLog::log('project_deletion', "Deleted project: {$name}.");

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    /**
     * Export projects to CSV.
     */
    public function export()
    {
        $user = auth()->user();
        $query = Project::with('department');

        if (!$user->isAdmin() && !$user->isAuditor()) {
            $query->where('department_id', $user->department_id);
        }

        $projects = $query->orderBy('project_name', 'asc')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=projects_report_" . date('Ymd_His') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Project Name', 'Department', 'Status', 'Allocated Budget', 'Actual Spending', 'Start Date', 'Expected Completion', 'Completion Date', 'Progress %'];

        $callback = function() use($projects, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($projects as $proj) {
                fputcsv($file, [
                    $proj->project_name,
                    $proj->department?->name ?? 'N/A',
                    ucfirst($proj->project_status),
                    $proj->allocated_budget,
                    $proj->actual_spending,
                    $proj->start_date->format('Y-m-d'),
                    $proj->expected_completion->format('Y-m-d'),
                    $proj->completion_date ? $proj->completion_date->format('Y-m-d') : 'N/A',
                    $proj->progress_percentage
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
