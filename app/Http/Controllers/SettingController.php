<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $settings = SystemSetting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'currency' => 'required|string|max:10',
            'maintenance_warning_days' => 'required|integer|min:1',
        ]);

        foreach ($validated as $key => $value) {
            SystemSetting::set($key, $value);
        }

        AuditLog::log('settings_update', "Updated global application configurations.");

        return redirect()->route('settings.index')->with('success', 'System settings updated.');
    }
}
