<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <span>{{ __('Asset Registry') }}</span>
            <div class="flex items-center gap-3">
                <a href="{{ route('assets.export') }}" class="inline-flex items-center gap-1.5 text-xs font-bold bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 px-3 py-2 rounded-xl shadow-sm transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export CSV
                </a>
                @can('create', App\Models\Asset::class)
                    <a href="{{ route('assets.create') }}" class="inline-flex items-center gap-1.5 text-xs font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-4 py-2 rounded-xl shadow-md shadow-blue-500/10 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Register Asset
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <!-- Filters & Search Panel -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 mb-8 shadow-sm">
        <form method="GET" action="{{ route('assets.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-6">
            <!-- Search -->
            <div class="md:col-span-2">
                <label for="search" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Search Name/Code</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="e.g. Server, AST-..." class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Categories -->
            <div>
                <label for="category_id" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Category</label>
                <select name="category_id" id="category_id" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Condition -->
            <div>
                <label for="condition" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Condition</label>
                <select name="condition" id="condition" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Conditions</option>
                    <option value="new" {{ request('condition') === 'new' ? 'selected' : '' }}>New</option>
                    <option value="good" {{ request('condition') === 'good' ? 'selected' : '' }}>Good</option>
                    <option value="fair" {{ request('condition') === 'fair' ? 'selected' : '' }}>Fair</option>
                    <option value="poor" {{ request('condition') === 'poor' ? 'selected' : '' }}>Poor</option>
                    <option value="disposed" {{ request('condition') === 'disposed' ? 'selected' : '' }}>Disposed</option>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Status</label>
                <select name="status" id="status" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="disposed" {{ request('status') === 'disposed' ? 'selected' : '' }}>Disposed</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl shadow-sm transition text-center justify-center">
                    Filter
                </button>
                <a href="{{ route('assets.index') }}" class="text-xs font-bold bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 py-3 px-4 rounded-xl shadow-sm transition text-center">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Asset Table Card -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-800">
                <thead class="bg-gray-50 dark:bg-slate-900/50">
                    <tr class="text-left text-xs font-extrabold text-gray-400 dark:text-slate-500 uppercase tracking-wider">
                        <th class="py-4 px-6">Asset Code</th>
                        <th class="py-4 px-6">Asset Name</th>
                        <th class="py-4 px-6">Category</th>
                        <th class="py-4 px-6">Department</th>
                        <th class="py-4 px-6">Condition</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-right">Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-sm">
                    @forelse($assets as $asset)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition">
                            <td class="py-4 px-6 font-bold text-blue-600 dark:text-blue-400">
                                <a href="{{ route('assets.show', $asset->id) }}" class="hover:underline">{{ $asset->asset_code }}</a>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-800 dark:text-slate-200">
                                <a href="{{ route('assets.show', $asset->id) }}" class="hover:underline">{{ $asset->asset_name }}</a>
                            </td>
                            <td class="py-4 px-6 text-gray-500">{{ $asset->category->name }}</td>
                            <td class="py-4 px-6 text-gray-500 font-medium">{{ $asset->department->name }}</td>
                            <td class="py-4 px-6">
                                @php
                                    $condColor = match($asset->condition) {
                                        'new' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30',
                                        'good' => 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-100 dark:border-blue-900/30',
                                        'fair' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30',
                                        'poor' => 'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30',
                                        'disposed' => 'bg-gray-50 text-gray-700 dark:bg-slate-800 dark:text-slate-400 border border-gray-100 dark:border-slate-800',
                                        default => 'bg-gray-50 text-gray-700 dark:bg-slate-800 dark:text-slate-400'
                                    };
                                @endphp
                                <span class="text-xs font-bold px-2 py-1 rounded-lg uppercase tracking-tight {{ $condColor }}">
                                    {{ $asset->condition }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $statusColor = match($asset->status) {
                                        'active' => 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20',
                                        'maintenance' => 'bg-amber-500/10 text-amber-500 border border-amber-500/20',
                                        'disposed' => 'bg-rose-500/10 text-rose-500 border border-rose-500/20',
                                        default => 'bg-gray-500/10 text-gray-500'
                                    };
                                @endphp
                                <span class="text-xs font-semibold px-2 py-1 rounded-full uppercase tracking-wider {{ $statusColor }}">
                                    {{ $asset->status }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right font-bold text-slate-800 dark:text-white">
                                ${{ number_format($asset->purchase_cost, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <span>No assets found matching the filter criteria.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Footer -->
        @if($assets->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50">
                {{ $assets->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
