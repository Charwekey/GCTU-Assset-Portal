<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::withCount(['assets', 'procurements'])->paginate(10);
        return view('admin.vendors.index', compact('vendors'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        $vendor = Vendor::create($validated);

        AuditLog::log('vendor_creation', "Created vendor record: {$vendor->name}.");

        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully.');
    }

    public function update(Request $request, Vendor $vendor)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        $vendor->update($validated);

        AuditLog::log('vendor_update', "Updated vendor record: {$vendor->name}.");

        return redirect()->route('vendors.index')->with('success', 'Vendor details updated.');
    }

    public function destroy(Vendor $vendor)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $name = $vendor->name;
        $vendor->delete();

        AuditLog::log('vendor_deletion', "Deleted vendor record: {$name}.");

        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully.');
    }
}
