<x-app-layout>
    <x-slot name="header">
        {{ __('Manage Departments & Budget Caps') }}
    </x-slot>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <!-- Left: Departments Table -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-800">
                    <thead class="bg-gray-50 dark:bg-slate-900/50">
                        <tr class="text-left text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-6">Dept Code</th>
                            <th class="py-4 px-6">Name</th>
                            <th class="py-4 px-6 text-right">Budget Limit</th>
                            @if(Auth::user()->isAdmin())
                                <th class="py-4 px-6 text-right">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-sm">
                        @forelse($departments as $dept)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition">
                                <td class="py-4 px-6 font-mono font-bold text-slate-800 dark:text-slate-200">
                                    {{ $dept->code }}
                                </td>
                                <td class="py-4 px-6 font-semibold text-slate-800 dark:text-slate-200">
                                    {{ $dept->name }}
                                </td>
                                <td class="py-4 px-6 text-right font-bold text-slate-800 dark:text-white">
                                    ${{ number_format($dept->budget_limit, 2) }}
                                </td>
                                @if(Auth::user()->isAdmin())
                                    <td class="py-4 px-6 text-right flex items-center justify-end gap-2">
                                        <a href="{{ route('departments.index', ['edit_id' => $dept->id]) }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                                        <form method="POST" action="{{ route('departments.destroy', $dept->id) }}" onsubmit="return confirm('Deleting a department will delete associated assets, projects, and procurements. Proceed?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-red-500 hover:underline ml-2">Delete</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-400 dark:text-slate-500">No departments defined.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($departments->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50">
                    {{ $departments->links() }}
                </div>
            @endif
        </div>

        <!-- Right Sidebar: Add/Edit Department -->
        <div>
            @if(Auth::user()->isAdmin())
                @php
                    $editing = null;
                    if(request()->filled('edit_id')) {
                        $editing = \App\Models\Department::find(request('edit_id'));
                    }
                @endphp
                
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm p-6">
                    <h3 class="font-bold text-slate-800 dark:text-white border-b border-gray-100 dark:border-slate-800 pb-3 flex items-center gap-2">
                        <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        {{ $editing ? 'Modify Department' : 'Add Department' }}
                    </h3>
                    
                    <form method="POST" action="{{ $editing ? route('departments.update', $editing->id) : route('departments.store') }}" class="space-y-4 mt-4">
                        @csrf
                        @if($editing)
                            @method('PUT')
                        @endif

                        <!-- Dept Code -->
                        <div>
                            <label for="code" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Department Code <span class="text-red-500">*</span></label>
                            <input type="text" name="code" id="code" value="{{ old('code', $editing?->code) }}" placeholder="e.g. CS-IT" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500 @error('code') border-red-300 ring-red-300 @enderror">
                            @error('code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dept Name -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Department Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $editing?->name) }}" placeholder="e.g. Computer Science & IT" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-300 ring-red-300 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Budget Limit -->
                        <div>
                            <label for="budget_limit" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Budget Spending Cap (USD) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="budget_limit" id="budget_limit" value="{{ old('budget_limit', $editing?->budget_limit ?? 0.00) }}" placeholder="0.00" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500 @error('budget_limit') border-red-300 ring-red-300 @enderror">
                            @error('budget_limit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            @if($editing)
                                <a href="{{ route('departments.index') }}" class="text-xs font-bold bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 py-3 px-4 rounded-xl transition text-center flex-1">
                                    Cancel
                                </a>
                            @endif
                            <button type="submit" class="text-xs font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3 px-4 rounded-xl shadow-md transition flex-1">
                                {{ $editing ? 'Save Changes' : 'Add Department' }}
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm p-6 text-center text-xs text-gray-400">
                    <svg class="h-8 w-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Only system administrators are permitted to register new departments and change budget spending limits.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
