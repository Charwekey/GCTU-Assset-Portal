<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm">
                <a href="{{ route('projects.index') }}" class="text-gray-400 hover:text-gray-500">Projects</a>
                <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-slate-800 dark:text-white font-semibold">{{ $project->project_name }}</span>
            </div>
            
            <div class="flex items-center gap-3">
                @can('update', $project)
                    <a href="{{ route('projects.edit', $project->id) }}" class="inline-flex items-center gap-1 text-xs font-bold bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 px-3.5 py-2 rounded-xl shadow-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Edit Details
                    </a>
                @endcan
                @can('delete', $project)
                    <form method="POST" action="{{ route('projects.destroy', $project->id) }}" onsubmit="return confirm('Are you sure you want to delete this project?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-bold bg-rose-50 hover:bg-rose-100 text-rose-700 dark:bg-rose-950/30 dark:hover:bg-rose-900/40 dark:text-rose-400 px-3.5 py-2 rounded-xl transition border border-rose-100 dark:border-rose-900/30">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Project
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <!-- Main Workspace Grid -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        
        <!-- Left: Project Profile Card -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/30 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 dark:text-white">{{ $project->project_name }}</h2>
                        <span class="text-xs text-gray-500 mt-1 block">Department: {{ $project->department->name }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @php
                            $statusColor = match($project->project_status) {
                                'planned' => 'bg-blue-500/10 text-blue-500 border border-blue-500/20',
                                'ongoing' => 'bg-indigo-500/10 text-indigo-500 border border-indigo-500/20',
                                'completed' => 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20',
                                'on_hold' => 'bg-amber-500/10 text-amber-500 border border-amber-500/20',
                                'cancelled' => 'bg-rose-500/10 text-rose-500 border border-rose-500/20',
                                default => 'bg-gray-500/10 text-gray-500'
                            };
                        @endphp
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider {{ $statusColor }}">
                            {{ $project->project_status }}
                        </span>
                    </div>
                </div>

                <!-- Parameters list -->
                <div class="p-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Allocated Budget</span>
                        <span class="text-lg font-black text-slate-850 dark:text-slate-200">${{ number_format($project->allocated_budget, 2) }}</span>
                    </div>

                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Actual Spendings</span>
                        <span class="text-lg font-black text-slate-800 dark:text-white">${{ number_format($project->actual_spending, 2) }}</span>
                    </div>

                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Start Date</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $project->start_date->format('F d, Y') }}</span>
                    </div>

                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Expected Completion</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $project->expected_completion->format('F d, Y') }}</span>
                    </div>

                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Completed Date</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $project->completion_date ? $project->completion_date->format('F d, Y') : 'Ongoing' }}</span>
                    </div>

                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Budget Balance Left</span>
                        @php
                            $bal = $project->allocated_budget - $project->actual_spending;
                        @endphp
                        <span class="text-sm font-bold {{ $bal < 0 ? 'text-rose-500' : 'text-slate-800 dark:text-slate-200' }}">
                            ${{ number_format($bal, 2) }}
                            @if($bal < 0)
                                <span class="text-xxs font-extrabold">(Overrun)</span>
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Large Progress Tracker -->
                <div class="p-6 border-t border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/10">
                    <div class="flex items-center justify-between text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-2">
                        <span>Project Completion Progress</span>
                        <span>{{ $project->progress_percentage }}%</span>
                    </div>
                    <div class="relative w-full h-4 bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full transition-all duration-500" style="width: {{ $project->progress_percentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar: Report Progress (Available to Officers & Managers) -->
        <div>
            @can('updateProgress', $project)
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm p-6 sticky top-24">
                    <h3 class="font-bold text-slate-800 dark:text-white border-b border-gray-100 dark:border-slate-800 pb-3 flex items-center gap-2">
                        <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z" />
                        </svg>
                        Report Project Progress
                    </h3>
                    
                    <form method="POST" action="{{ route('projects.progress', $project->id) }}" class="space-y-4 mt-4">
                        @csrf
                        
                        <!-- Progress Slider -->
                        <div x-data="{ pct: {{ $project->progress_percentage }} }">
                            <label for="progress_percentage" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Completion Progress (<span x-text="pct"></span>%)</label>
                            <input type="range" name="progress_percentage" id="progress_percentage" min="0" max="100" x-model="pct" class="w-full h-2 bg-gray-200 dark:bg-slate-800 rounded-lg appearance-none cursor-pointer accent-indigo-600 focus:outline-none">
                        </div>

                        <!-- Actual Spending -->
                        <div>
                            <label for="actual_spending" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Committed Spending Cost (USD) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="actual_spending" id="actual_spending" value="{{ $project->actual_spending }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Project Status -->
                        <div>
                            <label for="project_status" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Current Status <span class="text-red-500">*</span></label>
                            <select name="project_status" id="project_status" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="planned" {{ $project->project_status === 'planned' ? 'selected' : '' }}>Planned</option>
                                <option value="ongoing" {{ $project->project_status === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ $project->project_status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="on_hold" {{ $project->project_status === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                <option value="cancelled" {{ $project->project_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full text-xs font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3 rounded-xl shadow-md transition">
                            Submit Progress Update
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm p-6 text-center text-xs text-gray-400">
                    <svg class="h-8 w-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    You do not have permissions to report project progress updates.
                </div>
            @endcan
        </div>

    </div>
</x-app-layout>
