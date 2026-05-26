<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <span>{{ __('Projects Tracker') }}</span>
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.export') }}" class="inline-flex items-center gap-1.5 text-xs font-bold bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 px-3 py-2 rounded-xl shadow-sm transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export CSV
                </a>
                @can('create', App\Models\Project::class)
                    <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-1.5 text-xs font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-4 py-2 rounded-xl shadow-md shadow-blue-500/10 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Initiate Project
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 mb-8 shadow-sm">
        <form method="GET" action="{{ route('projects.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-5">
            <!-- Search -->
            <div class="md:col-span-2">
                <label for="search" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Search Project Name</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="e.g. Wi-Fi expansion..." class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Status</label>
                <select name="status" id="status" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="planned" {{ request('status') === 'planned' ? 'selected' : '' }}>Planned</option>
                    <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="on_hold" {{ request('status') === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <!-- Department -->
            <div>
                <label for="department_id" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Department</label>
                <select name="department_id" id="department_id" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500" {{ !Auth::user()->isAdmin() && !Auth::user()->isAuditor() ? 'disabled' : '' }}>
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl shadow-sm transition text-center justify-center">
                    Filter
                </button>
                <a href="{{ route('projects.index') }}" class="text-xs font-bold bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 py-3 px-4 rounded-xl shadow-sm transition text-center">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Table registry -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-800">
                <thead class="bg-gray-50 dark:bg-slate-900/50">
                    <tr class="text-left text-xs font-extrabold text-gray-400 dark:text-slate-500 uppercase tracking-wider">
                        <th class="py-4 px-6">Project Name</th>
                        <th class="py-4 px-6">Department</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Progress</th>
                        <th class="py-4 px-6 text-right">Budget Limit</th>
                        <th class="py-4 px-6 text-right">Spending</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-sm">
                    @forelse($projects as $proj)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition">
                            <td class="py-4 px-6 font-bold text-slate-800 dark:text-slate-200">
                                <a href="{{ route('projects.show', $proj->id) }}" class="hover:underline">{{ $proj->project_name }}</a>
                            </td>
                            <td class="py-4 px-6 text-gray-500 font-medium">{{ $proj->department->name }}</td>
                            <td class="py-4 px-6">
                                @php
                                    $statusColor = match($proj->project_status) {
                                        'planned' => 'bg-blue-500/10 text-blue-500 border border-blue-500/20',
                                        'ongoing' => 'bg-indigo-500/10 text-indigo-500 border border-indigo-500/20',
                                        'completed' => 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20',
                                        'on_hold' => 'bg-amber-500/10 text-amber-500 border border-amber-500/20',
                                        'cancelled' => 'bg-rose-500/10 text-rose-500 border border-rose-500/20',
                                        default => 'bg-gray-500/10 text-gray-500'
                                    };
                                @endphp
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full uppercase tracking-wider {{ $statusColor }}">
                                    {{ $proj->project_status }}
                                </span>
                            </td>
                            <td class="py-4 px-6 min-w-[150px]">
                                <div class="flex items-center gap-2">
                                    <div class="relative w-24 h-2 bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $proj->progress_percentage }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $proj->progress_percentage }}%</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right font-semibold text-slate-850 dark:text-slate-200">
                                ${{ number_format($proj->allocated_budget, 2) }}
                            </td>
                            <td class="py-4 px-6 text-right font-bold text-slate-800 dark:text-white">
                                ${{ number_format($proj->actual_spending, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span>No projects matching criteria found.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($projects->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50">
                {{ $projects->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
