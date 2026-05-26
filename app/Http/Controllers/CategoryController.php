<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('assets')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $cat = Category::create($validated);

        AuditLog::log('category_creation', "Created asset category: {$cat->name}.");

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        AuditLog::log('category_update', "Updated asset category: {$category->name}.");

        return redirect()->route('categories.index')->with('success', 'Category details updated.');
    }

    public function destroy(Category $category)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $name = $category->name;
        $category->delete();

        AuditLog::log('category_deletion', "Deleted asset category: {$name}.");

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
