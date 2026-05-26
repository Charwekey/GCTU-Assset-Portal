<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('projects.index') }}" class="text-gray-400 hover:text-gray-500">Projects</a>
            <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <a href="{{ route('projects.show', $project->id) }}" class="text-gray-400 hover:text-gray-500">{{ $project->project_name }}</a>
            <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="text-slate-800 dark:text-white font-semibold">Modify Project</span>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/30">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Modify Project Parameters</h3>
                <p class="text-xs text-gray-500 mt-1">Make adjustments to project values and status mappings.</p>
            </div>

            <form method="POST" action="{{ route('projects.update', $project->id) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Project Name -->
                <div>
                    <label for="project_name" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Project Name <span class="text-red-500">*</span></label>
                    <input type="text" name="project_name" id="project_name" value="{{ old('project_name', $project->project_name) }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500 @error('project_name') border-red-300 ring-red-300 @enderror">
                    @error('project_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    
                    <!-- Budget -->
                    <div>
                        <label for="allocated_budget" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Allocated Budget (USD) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="allocated_budget" id="allocated_budget" value="{{ old('allocated_budget', $project->allocated_budget) }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500 @error('allocated_budget') border-red-300 ring-red-300 @enderror">
                        @error('allocated_budget')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department_id" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Department <span class="text-red-500">*</span></label>
                        @if(Auth::user()->isAdmin())
                            <select name="department_id" id="department_id" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $project->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" value="{{ $project->department->name }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-100 dark:bg-slate-800 text-gray-500 cursor-not-allowed" readonly>
                        @endif
                        @error('department_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $project->start_date->format('Y-m-d')) }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('start_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expected Completion -->
                    <div>
                        <label for="expected_completion" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Expected Completion Date <span class="text-red-500">*</span></label>
                        <input type="date" name="expected_completion" id="expected_completion" value="{{ old('expected_completion', $project->expected_completion->format('Y-m-d')) }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('expected_completion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Project Status -->
                    <div class="sm:col-span-2">
                        <label for="project_status" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Project Status <span class="text-red-500">*</span></label>
                        <select name="project_status" id="project_status" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="planned" {{ old('project_status', $project->project_status) === 'planned' ? 'selected' : '' }}>Planned</option>
                            <option value="ongoing" {{ old('project_status', $project->project_status) === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ old('project_status', $project->project_status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="on_hold" {{ old('project_status', $project->project_status) === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                            <option value="cancelled" {{ old('project_status', $project->project_status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                </div>

                <!-- Footer Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-slate-800">
                    <a href="{{ route('projects.show', $project->id) }}" class="text-xs font-bold bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 py-3 px-5 rounded-xl transition">
                        Cancel
                    </a>
                    <button type="submit" class="text-xs font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3 px-6 rounded-xl shadow-md transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
