<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Department;
use App\Models\Procurement;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class ProcurementController extends Controller
{
    /**
     * Display a listing of procurements.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Procurement::with(['department', 'vendor', 'initiator', 'approver']);

        // Scope by department if not Admin or Auditor
        if (!$user->isAdmin() && !$user->isAuditor()) {
            $query->where('department_id', $user->department_id);
        }

        // Search & Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('procurement_code', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('department_id') && ($user->isAdmin() || $user->isAuditor())) {
            $query->where('department_id', $request->input('department_id'));
        }

        $procurements = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $departments = Department::all();

        return view('procurements.index', compact('procurements', 'departments'));
    }

    /**
     * Show the form for creating a new procurement.
     */
    public function create()
    {
        Gate::authorize('create', Procurement::class);

        $user = auth()->user();
        $departments = $user->isAdmin() ? Department::all() : Department::where('id', $user->department_id)->get();
        $vendors = Vendor::all();

        return view('procurements.create', compact('departments', 'vendors'));
    }

    /**
     * Store a newly created procurement in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Procurement::class);

        $user = auth()->user();

        $rules = [
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'budget_allocated' => 'required|numeric|min:0',
            'vendor_id' => 'nullable|exists:vendors,id',
        ];

        if (!$user->isAdmin()) {
            $request->merge(['department_id' => $user->department_id]);
        }

        $validated = $request->validate($rules);

        // Generate Code: PRC-YYYY-XXXX
        $count = Procurement::whereYear('created_at', date('Y'))->count() + 1;
        $validated['procurement_code'] = 'PRC-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        $validated['status'] = 'pending';
        $validated['initiated_by'] = $user->id;

        $procurement = Procurement::create($validated);

        // Audit Log
        AuditLog::log('procurement_request', "Submitted procurement request: {$procurement->title} ({$procurement->procurement_code}) requesting {$procurement->budget_allocated}.");

        return redirect()->route('procurements.index')->with('success', 'Procurement request submitted successfully.');
    }

    /**
     * Display details of a procurement.
     */
    public function show(Procurement $procurement)
    {
        Gate::authorize('view', $procurement);
        
        $procurement->load(['department', 'vendor', 'initiator', 'approver']);

        // Check budget limits for department
        $dept = $procurement->department;
        $projectSpend = $dept->projects->sum('actual_spending');
        $procurementSpend = $dept->procurements->where('status', 'completed')->sum('actual_cost') 
            + $dept->procurements->whereIn('status', ['approved', 'in_progress'])->sum('budget_allocated');
        
        $currentCommitted = $projectSpend + $procurementSpend;
        $budgetLimit = $dept->budget_limit;
        
        // Check if approving this would overrun budget
        $headroom = $budgetLimit - $currentCommitted;
        $isOverrunRisk = ($currentCommitted + $procurement->budget_allocated) > $budgetLimit;
        
        // Also check if category list is needed for the "auto-convert to asset" modal
        $categories = Category::all();

        return view('procurements.show', compact('procurement', 'headroom', 'isOverrunRisk', 'budgetLimit', 'categories'));
    }

    /**
     * Approve the procurement.
     */
    public function approve(Procurement $procurement)
    {
        Gate::authorize('approve', $procurement);

        $procurement->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'start_date' => now(),
        ]);

        // Audit Log
        AuditLog::log('procurement_approval', "Approved procurement request: {$procurement->title} ({$procurement->procurement_code}).");

        return redirect()->route('procurements.show', $procurement->id)->with('success', 'Procurement request approved.');
    }

    /**
     * Mark the procurement in progress.
     */
    public function start(Procurement $procurement)
    {
        Gate::authorize('update', $procurement);

        $procurement->update(['status' => 'in_progress']);

        // Audit Log
        AuditLog::log('procurement_update', "Marked procurement as in progress: {$procurement->procurement_code}.");

        return redirect()->route('procurements.show', $procurement->id)->with('success', 'Procurement is now in progress.');
    }

    /**
     * Complete the procurement.
     */
    public function complete(Request $request, Procurement $procurement)
    {
        Gate::authorize('update', $procurement);

        $validated = $request->validate([
            'actual_cost' => 'required|numeric|min:0',
            'register_as_asset' => 'nullable|boolean',
            'category_id' => 'required_if:register_as_asset,1|nullable|exists:categories,id',
        ]);

        $procurement->update([
            'status' => 'completed',
            'actual_cost' => $validated['actual_cost'],
            'completion_date' => now(),
        ]);

        $msg = 'Procurement request marked as completed.';

        // Register Asset Automatically if checked
        if ($request->input('register_as_asset') == 1) {
            $assetCode = 'AST-' . $procurement->department->code . '-' . str_pad(Asset::count() + 1, 4, '0', STR_PAD_LEFT);
            
            $asset = Asset::create([
                'asset_code' => $assetCode,
                'asset_name' => $procurement->title,
                'category_id' => $validated['category_id'],
                'department_id' => $procurement->department_id,
                'purchase_date' => now(),
                'purchase_cost' => $validated['actual_cost'],
                'vendor_id' => $procurement->vendor_id,
                'condition' => 'new',
                'status' => 'active',
            ]);

            $msg .= " Asset automatically registered as code: {$assetCode}.";
            AuditLog::log('asset_registration', "Auto-registered asset {$assetCode} from completed procurement {$procurement->procurement_code}.");
        }

        // Audit Log
        AuditLog::log('procurement_completion', "Completed procurement: {$procurement->title} ({$procurement->procurement_code}) at actual cost {$validated['actual_cost']}.");

        return redirect()->route('procurements.show', $procurement->id)->with('success', $msg);
    }

    /**
     * Cancel/Reject the procurement.
     */
    public function cancel(Procurement $procurement)
    {
        Gate::authorize('cancel', $procurement);

        $procurement->update(['status' => 'cancelled']);

        // Audit Log
        AuditLog::log('procurement_cancellation', "Cancelled/Rejected procurement: {$procurement->procurement_code}.");

        return redirect()->route('procurements.show', $procurement->id)->with('success', 'Procurement cancelled.');
    }

    /**
     * Export procurements.
     */
    public function export()
    {
        $user = auth()->user();
        $query = Procurement::with(['department', 'vendor', 'initiator', 'approver']);

        if (!$user->isAdmin() && !$user->isAuditor()) {
            $query->where('department_id', $user->department_id);
        }

        $procurements = $query->orderBy('procurement_code', 'asc')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=procurements_report_" . date('Ymd_His') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Procurement Code', 'Title', 'Department', 'Budget Allocated', 'Actual Cost', 'Vendor', 'Status', 'Initiated By', 'Approved By', 'Start Date', 'Completion Date'];

        $callback = function() use($procurements, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($procurements as $prc) {
                fputcsv($file, [
                    $prc->procurement_code,
                    $prc->title,
                    $prc->department?->name ?? 'N/A',
                    $prc->budget_allocated,
                    $prc->actual_cost ?? 'N/A',
                    $prc->vendor?->name ?? 'N/A',
                    ucfirst($prc->status),
                    $prc->initiator?->name ?? 'N/A',
                    $prc->approver?->name ?? 'N/A',
                    $prc->start_date ? $prc->start_date->format('Y-m-d') : 'N/A',
                    $prc->completion_date ? $prc->completion_date->format('Y-m-d') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
