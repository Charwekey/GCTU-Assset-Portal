<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Department;
use App\Models\MaintenanceRecord;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class AssetController extends Controller
{
    /**
     * Display a listing of the assets.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Asset::with(['category', 'department', 'vendor', 'assignee']);

        // Scope by department if not Admin or Auditor
        if (!$user->isAdmin() && !$user->isAuditor()) {
            $query->where('department_id', $user->department_id);
        }

        // Apply Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('asset_name', 'like', "%{$search}%")
                  ->orWhere('asset_code', 'like', "%{$search}%");
            });
        }

        // Apply Filters
        if ($request->filled('department_id') && ($user->isAdmin() || $user->isAuditor())) {
            $query->where('department_id', $request->input('department_id'));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->input('condition'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $assets = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $departments = Department::all();
        $categories = Category::all();

        return view('assets.index', compact('assets', 'departments', 'categories'));
    }

    /**
     * Show the form for creating a new asset.
     */
    public function create()
    {
        Gate::authorize('create', Asset::class);

        $user = auth()->user();
        $departments = $user->isAdmin() ? Department::all() : Department::where('id', $user->department_id)->get();
        $categories = Category::all();
        $vendors = Vendor::all();
        $users = User::when(!$user->isAdmin(), function ($q) use ($user) {
            return $q->where('department_id', $user->department_id);
        })->get();

        return view('assets.create', compact('departments', 'categories', 'vendors', 'users'));
    }

    /**
     * Store a newly created asset in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Asset::class);

        $user = auth()->user();
        
        $rules = [
            'asset_code' => 'required|string|unique:assets,asset_code',
            'asset_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'department_id' => 'required|exists:departments,id',
            'purchase_date' => 'required|date',
            'purchase_cost' => 'required|numeric|min:0',
            'vendor_id' => 'nullable|exists:vendors,id',
            'condition' => 'required|in:new,good,fair,poor,disposed',
            'status' => 'required|in:active,maintenance,disposed',
            'assigned_to' => 'nullable|exists:users,id',
            'warranty_expiry' => 'nullable|date|after_or_equal:purchase_date',
        ];

        // Managers cannot assign assets to another department
        if (!$user->isAdmin()) {
            $request->merge(['department_id' => $user->department_id]);
        }

        $validated = $request->validate($rules);
        $asset = Asset::create($validated);

        // Audit Log
        AuditLog::log('asset_registration', "Registered a new asset: {$asset->asset_name} ({$asset->asset_code}) with value {$asset->purchase_cost}.");

        return redirect()->route('assets.index')->with('success', 'Asset registered successfully.');
    }

    /**
     * Display the specified asset.
     */
    public function show(Asset $asset)
    {
        Gate::authorize('view', $asset);
        $asset->load(['category', 'department', 'vendor', 'assignee', 'maintenanceRecords']);
        return view('assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified asset.
     */
    public function edit(Asset $asset)
    {
        Gate::authorize('update', $asset);

        $user = auth()->user();
        $departments = $user->isAdmin() ? Department::all() : Department::where('id', $user->department_id)->get();
        $categories = Category::all();
        $vendors = Vendor::all();
        $users = User::when(!$user->isAdmin(), function ($q) use ($user) {
            return $q->where('department_id', $user->department_id);
        })->get();

        return view('assets.edit', compact('asset', 'departments', 'categories', 'vendors', 'users'));
    }

    /**
     * Update the specified asset in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        Gate::authorize('update', $asset);

        $user = auth()->user();

        $rules = [
            'asset_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'purchase_date' => 'required|date',
            'purchase_cost' => 'required|numeric|min:0',
            'vendor_id' => 'nullable|exists:vendors,id',
            'condition' => 'required|in:new,good,fair,poor,disposed',
            'status' => 'required|in:active,maintenance,disposed',
            'assigned_to' => 'nullable|exists:users,id',
            'warranty_expiry' => 'nullable|date|after_or_equal:purchase_date',
        ];

        if ($user->isAdmin()) {
            $rules['asset_code'] = 'required|string|unique:assets,asset_code,' . $asset->id;
            $rules['department_id'] = 'required|exists:departments,id';
        }

        $validated = $request->validate($rules);
        $asset->update($validated);

        // Audit Log
        AuditLog::log('asset_modification', "Modified asset: {$asset->asset_name} ({$asset->asset_code}).");

        return redirect()->route('assets.show', $asset->id)->with('success', 'Asset updated successfully.');
    }

    /**
     * Remove the specified asset from storage.
     */
    public function destroy(Asset $asset)
    {
        Gate::authorize('delete', $asset);

        $assetCode = $asset->asset_code;
        $assetName = $asset->asset_name;
        $asset->delete();

        // Audit Log
        AuditLog::log('asset_deletion', "Deleted asset: {$assetName} ({$assetCode}).");

        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully.');
    }

    /**
     * Store a maintenance record for this asset.
     */
    public function logMaintenance(Request $request, Asset $asset)
    {
        Gate::authorize('logMaintenance', $asset);

        $validated = $request->validate([
            'maintenance_date' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'description' => 'required|string',
            'performed_by' => 'required|string|max:255',
        ]);

        $record = $asset->maintenanceRecords()->create($validated);

        // Auto update status if requested or change condition if poor
        if ($request->has('update_status') && $request->input('update_status') === 'active') {
            $asset->update(['status' => 'active', 'condition' => 'good']);
        }

        // Audit Log
        AuditLog::log('asset_maintenance', "Logged maintenance for: {$asset->asset_name} costing {$record->cost}.");

        return redirect()->route('assets.show', $asset->id)->with('success', 'Maintenance record added successfully.');
    }

    /**
     * Export assets to CSV.
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        $query = Asset::with(['category', 'department', 'vendor', 'assignee']);

        // Scope by department if not Admin or Auditor
        if (!$user->isAdmin() && !$user->isAuditor()) {
            $query->where('department_id', $user->department_id);
        }

        $assets = $query->orderBy('asset_code', 'asc')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=assets_report_" . date('Ymd_His') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Asset Code', 'Asset Name', 'Category', 'Department', 'Purchase Date', 'Cost', 'Vendor', 'Condition', 'Status', 'Assigned To', 'Warranty Expiry'];

        $callback = function() use($assets, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($assets as $asset) {
                fputcsv($file, [
                    $asset->asset_code,
                    $asset->asset_name,
                    $asset->category?->name ?? 'N/A',
                    $asset->department?->name ?? 'N/A',
                    $asset->purchase_date->format('Y-m-d'),
                    $asset->purchase_cost,
                    $asset->vendor?->name ?? 'N/A',
                    ucfirst($asset->condition),
                    ucfirst($asset->status),
                    $asset->assignee?->name ?? 'Unassigned',
                    $asset->warranty_expiry ? $asset->warranty_expiry->format('Y-m-d') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
