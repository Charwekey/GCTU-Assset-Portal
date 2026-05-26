<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Procurement;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Scoped metrics based on role
        if ($user->isAdmin() || $user->isAuditor()) {
            $totalAssets = Asset::count();
            $totalAssetValue = Asset::sum('purchase_cost');
            $activeProjects = Project::whereIn('project_status', ['planned', 'ongoing', 'on_hold'])->count();
            $totalProjectBudget = Project::sum('allocated_budget');
            $pendingProcurements = Procurement::where('status', 'pending')->count();
            $activeProcurements = Procurement::whereIn('status', ['approved', 'in_progress'])->count();
            
            // Recent audit logs
            $recentLogs = AuditLog::with('user')->orderBy('created_at', 'desc')->limit(5)->get();
        } else {
            // Manager / Officer: scope to their department
            $deptId = $user->department_id;
            
            $totalAssets = Asset::where('department_id', $deptId)->count();
            $totalAssetValue = Asset::where('department_id', $deptId)->sum('purchase_cost');
            $activeProjects = Project::where('department_id', $deptId)->whereIn('project_status', ['planned', 'ongoing', 'on_hold'])->count();
            $totalProjectBudget = Project::where('department_id', $deptId)->sum('allocated_budget');
            $pendingProcurements = Procurement::where('department_id', $deptId)->where('status', 'pending')->count();
            $activeProcurements = Procurement::where('department_id', $deptId)->whereIn('status', ['approved', 'in_progress'])->count();
            
            // Recent logs scoped to department users
            $recentLogs = AuditLog::with('user')
                ->whereHas('user', function ($query) use ($deptId) {
                    $query->where('department_id', $deptId);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        // 2. Department-wise budget utilization statistics (For Charts/KPIs)
        $departments = Department::with(['projects', 'procurements'])->get();
        $budgetStats = [];

        foreach ($departments as $dept) {
            // Committed spend = project actual spending + completed/approved procurement costs
            $projectSpend = $dept->projects->sum('actual_spending');
            
            $procurementSpend = $dept->procurements->where('status', 'completed')->sum('actual_cost') 
                + $dept->procurements->whereIn('status', ['approved', 'in_progress'])->sum('budget_allocated');

            $totalSpent = $projectSpend + $procurementSpend;
            $budgetLimit = $dept->budget_limit;
            $percentage = $budgetLimit > 0 ? min(round(($totalSpent / $budgetLimit) * 100, 1), 100) : 0;
            $hasOverrun = $totalSpent > $budgetLimit;

            $budgetStats[] = [
                'id' => $dept->id,
                'name' => $dept->name,
                'code' => $dept->code,
                'budget_limit' => $budgetLimit,
                'total_spent' => $totalSpent,
                'percentage' => $percentage,
                'has_overrun' => $hasOverrun,
            ];
        }

        // 3. Asset condition counts (for visual pie charts)
        $conditionStats = Asset::select('condition', DB::raw('count(*) as total'))
            ->when(!$user->isAdmin() && !$user->isAuditor(), function ($q) use ($user) {
                return $q->where('department_id', $user->department_id);
            })
            ->groupBy('condition')
            ->pluck('total', 'condition')
            ->toArray();

        $conditions = ['new' => 0, 'good' => 0, 'fair' => 0, 'poor' => 0, 'disposed' => 0];
        foreach ($conditionStats as $cond => $count) {
            $conditions[$cond] = $count;
        }

        // 4. Overdue or pending maintenance assets
        $maintenanceAssets = Asset::where('status', 'maintenance')
            ->when(!$user->isAdmin() && !$user->isAuditor(), function ($q) use ($user) {
                return $q->where('department_id', $user->department_id);
            })
            ->with(['department', 'maintenanceRecords' => function ($q) {
                $q->orderBy('maintenance_date', 'desc');
            }])
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalAssets',
            'totalAssetValue',
            'activeProjects',
            'totalProjectBudget',
            'pendingProcurements',
            'activeProcurements',
            'recentLogs',
            'budgetStats',
            'conditions',
            'maintenanceAssets'
        ));
    }
}
