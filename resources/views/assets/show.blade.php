<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm">
                <a href="{{ route('assets.index') }}" class="text-gray-400 hover:text-gray-500">Assets</a>
                <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-slate-800 dark:text-white font-semibold">{{ $asset->asset_code }}</span>
            </div>
            
            <div class="flex items-center gap-3">
                @can('update', $asset)
                    <a href="{{ route('assets.edit', $asset->id) }}" class="inline-flex items-center gap-1 text-xs font-bold bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 px-3.5 py-2 rounded-xl shadow-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Edit
                    </a>
                @endcan
                @can('delete', $asset)
                    <form method="POST" action="{{ route('assets.destroy', $asset->id) }}" onsubmit="return confirm('Are you sure you want to delete this asset? This will wipe all maintenance logs.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1 text-xs font-bold bg-rose-50 hover:bg-rose-100 text-rose-700 dark:bg-rose-950/30 dark:hover:bg-rose-900/40 dark:text-rose-400 px-3.5 py-2 rounded-xl transition border border-rose-100 dark:border-rose-900/30">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        
        <!-- Left: Asset Card Profiler -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/30 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 dark:text-white">{{ $asset->asset_name }}</h2>
                        <span class="text-xs text-gray-500 font-mono mt-1 block">{{ $asset->asset_code }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @php
                            $statusColor = match($asset->status) {
                                'active' => 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20',
                                'maintenance' => 'bg-amber-500/10 text-amber-500 border border-amber-500/20',
                                'disposed' => 'bg-rose-500/10 text-rose-500 border border-rose-500/20',
                                default => 'bg-gray-500/10 text-gray-500'
                            };
                        @endphp
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider {{ $statusColor }}">
                            {{ $asset->status }}
                        </span>
                    </div>
                </div>

                <!-- Parameters list -->
                <div class="p-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Category</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $asset->category->name }}</span>
                    </div>
                    
                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Department</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $asset->department->name }}</span>
                    </div>

                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Assigned Officer</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $asset->assignee?->name ?? 'Unassigned (In Storage)' }}</span>
                    </div>

                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Vendor / Supplier</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $asset->vendor?->name ?? 'N/A' }}</span>
                    </div>

                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Purchase Date</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $asset->purchase_date->format('F d, Y') }}</span>
                    </div>

                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Warranty Expiry</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                            @if($asset->warranty_expiry)
                                {{ $asset->warranty_expiry->format('F d, Y') }}
                                @if($asset->warranty_expiry->isPast())
                                    <span class="text-rose-500 text-xxs font-bold">(Expired)</span>
                                @else
                                    <span class="text-emerald-500 text-xxs font-bold">({{ $asset->warranty_expiry->diffForHumans() }})</span>
                                @endif
                            @else
                                No Warranty
                            @endif
                        </span>
                    </div>

                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Initial Purchase Cost</span>
                        <span class="text-lg font-black text-slate-800 dark:text-white">${{ number_format($asset->purchase_cost, 2) }}</span>
                    </div>

                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Condition Status</span>
                        @php
                            $condColor = match($asset->condition) {
                                'new' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400',
                                'good' => 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400',
                                'fair' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400',
                                'poor' => 'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400',
                                'disposed' => 'bg-gray-50 text-gray-700 dark:bg-slate-800 dark:text-slate-400',
                                default => 'bg-gray-50 text-gray-700 dark:bg-slate-800 dark:text-slate-400'
                            };
                        @endphp
                        <span class="text-xs font-bold px-2.5 py-1.5 rounded-lg uppercase tracking-tight inline-block mt-1 {{ $condColor }}">
                            {{ $asset->condition }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Maintenance History Table -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/30 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">Maintenance History</h3>
                        <p class="text-xs text-gray-500 mt-1">Previous repairs, services, and calibrations logged.</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xxs font-bold text-gray-400 uppercase tracking-wider block">Total Service Cost</span>
                        <span class="text-sm font-extrabold text-slate-800 dark:text-white">${{ number_format($asset->maintenanceRecords->sum('cost'), 2) }}</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-800">
                        <thead>
                            <tr class="text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                <th class="py-3 px-6">Date</th>
                                <th class="py-3 px-6">Performed By</th>
                                <th class="py-3 px-6">Details</th>
                                <th class="py-3 px-6 text-right">Cost</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-sm">
                            @forelse($asset->maintenanceRecords as $record)
                                <tr>
                                    <td class="py-4 px-6 font-medium text-slate-800 dark:text-slate-200 whitespace-nowrap">
                                        {{ $record->maintenance_date->format('M d, Y') }}
                                    </td>
                                    <td class="py-4 px-6 text-gray-500 whitespace-nowrap">{{ $record->performed_by }}</td>
                                    <td class="py-4 px-6 text-gray-600 dark:text-slate-400 max-w-xs truncate" title="{{ $record->description }}">{{ $record->description }}</td>
                                    <td class="py-4 px-6 text-right font-bold text-slate-800 dark:text-white whitespace-nowrap">
                                        ${{ number_format($record->cost, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-xs text-gray-400 dark:text-slate-500">
                                        No maintenance activities have been registered for this asset.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Sidebar: Log New Maintenance -->
        <div>
            @can('logMaintenance', $asset)
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm p-6 sticky top-24">
                    <h3 class="font-bold text-slate-800 dark:text-white border-b border-gray-100 dark:border-slate-800 pb-3 flex items-center gap-2">
                        <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        </svg>
                        Log Maintenance
                    </h3>
                    
                    <form method="POST" action="{{ route('assets.maintenance', $asset->id) }}" class="space-y-4 mt-4">
                        @csrf
                        
                        <!-- Maintenance Date -->
                        <div>
                            <label for="maintenance_date" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Service Date <span class="text-red-500">*</span></label>
                            <input type="date" name="maintenance_date" id="maintenance_date" value="{{ date('Y-m-d') }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Maintenance Cost -->
                        <div>
                            <label for="cost" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Repair Cost (USD) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="cost" id="cost" placeholder="0.00" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Performed By -->
                        <div>
                            <label for="performed_by" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Service Contractor <span class="text-red-500">*</span></label>
                            <input type="text" name="performed_by" id="performed_by" placeholder="e.g. Dell Service Hub" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Service Description <span class="text-red-500">*</span></label>
                            <textarea name="description" id="description" rows="3" placeholder="Explain the maintenance performed..." class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <!-- Option to Reactivate Asset -->
                        @if($asset->status === 'maintenance')
                            <div class="flex items-center gap-2 py-2">
                                <input type="checkbox" name="update_status" id="update_status" value="active" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="update_status" class="text-xs font-semibold text-slate-850 dark:text-slate-350">Re-activate asset status to "Active" and set condition to "Good"</label>
                            </div>
                        @endif

                        <button type="submit" class="w-full text-xs font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3 rounded-xl shadow-md transition">
                            Log Maintenance Entry
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm p-6 text-center text-xs text-gray-400">
                    <svg class="h-8 w-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    You do not have permissions to log maintenance operations on this asset.
                </div>
            @endcan
        </div>

    </div>
</x-app-layout>
