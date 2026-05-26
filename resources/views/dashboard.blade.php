<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <!-- Top Summary Metrics Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Total Assets -->
        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-900 p-6 border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-md transition duration-300">
            <dt class="text-sm font-semibold text-gray-500 dark:text-slate-400">Total Registered Assets</dt>
            <dd class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white">{{ $totalAssets }}</span>
                <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 dark:bg-indigo-950/30 dark:text-indigo-400 px-2 py-1 rounded-full">
                    Valued at ${{ number_format($totalAssetValue, 2) }}
                </span>
            </dd>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
        </div>

        <!-- Active Projects -->
        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-900 p-6 border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-md transition duration-300">
            <dt class="text-sm font-semibold text-gray-500 dark:text-slate-400">Active Projects</dt>
            <dd class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white">{{ $activeProjects }}</span>
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/30 dark:text-emerald-400 px-2 py-1 rounded-full">
                    Budget: ${{ number_format($totalProjectBudget, 2) }}
                </span>
            </dd>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
        </div>

        <!-- Active Procurements -->
        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-900 p-6 border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-md transition duration-300">
            <dt class="text-sm font-semibold text-gray-500 dark:text-slate-400">Active Procurements</dt>
            <dd class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white">{{ $activeProcurements }}</span>
                <span class="text-xs font-semibold text-amber-600 bg-amber-50 dark:bg-amber-950/30 dark:text-amber-400 px-2 py-1 rounded-full">
                    In Progress / Approved
                </span>
            </dd>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-orange-500"></div>
        </div>

        <!-- Pending Approvals -->
        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-900 p-6 border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-md transition duration-300">
            <dt class="text-sm font-semibold text-gray-500 dark:text-slate-400">Pending Approvals</dt>
            <dd class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white">{{ $pendingProcurements }}</span>
                <span class="text-xs font-semibold text-rose-600 bg-rose-50 dark:bg-rose-950/30 dark:text-rose-400 px-2 py-1 rounded-full">
                    Awaiting Action
                </span>
            </dd>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-500 to-pink-500"></div>
        </div>
    </div>

    <!-- Analytics & Budget Charts Grid -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3 mb-8">
        
        <!-- Department Budget & Spending Utilization Card -->
        <div class="lg:col-span-2 rounded-2xl bg-white dark:bg-slate-900 p-6 border border-gray-100 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 dark:border-slate-800 pb-3">
                <h3 class="font-extrabold text-base text-slate-800 dark:text-white flex items-center gap-2">
                    <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                    </svg>
                    Department Budgets & Expenditures
                </h3>
                <span class="text-xs text-gray-400">Real-time Utilization</span>
            </div>
            
            <div class="space-y-5">
                @foreach($budgetStats as $stat)
                    @php
                        // Scoping display to user department if not admin/auditor
                        if(!Auth::user()->isAdmin() && !Auth::user()->isAuditor() && Auth::user()->department_id !== $stat['id']) {
                            continue;
                        }
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $stat['name'] }}</span>
                                <span class="text-xs bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 px-1.5 py-0.5 rounded uppercase font-bold">{{ $stat['code'] }}</span>
                            </div>
                            <div class="text-xs text-gray-500">
                                <span class="font-semibold text-slate-800 dark:text-slate-200">${{ number_format($stat['total_spent'], 2) }}</span> 
                                of ${{ number_format($stat['budget_limit'], 2) }}
                            </div>
                        </div>

                        <!-- Progress Bar Container -->
                        <div class="relative w-full h-3 bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 {{ $stat['percentage'] > 90 ? 'bg-gradient-to-r from-red-500 to-rose-600' : ($stat['percentage'] > 75 ? 'bg-gradient-to-r from-amber-500 to-orange-500' : 'bg-gradient-to-r from-blue-500 to-indigo-500') }}" 
                                 style="width: {{ $stat['percentage'] }}%;"></div>
                        </div>
                        
                        @if($stat['has_overrun'])
                            <div class="mt-1 flex items-center gap-1 text-red-500 text-xxs font-semibold">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Department is exceeding its allocated budget limit!
                            </div>
                        @elseif($stat['percentage'] > 75)
                            <div class="mt-1 flex items-center gap-1 text-amber-500 text-xxs font-semibold">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Budget utilization has crossed 75% limit warning.
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Asset Condition Breakdown Card (Chart.js) -->
        <div class="rounded-2xl bg-white dark:bg-slate-900 p-6 border border-gray-100 dark:border-slate-800 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 border-b border-gray-100 dark:border-slate-800 pb-3">
                    <h3 class="font-extrabold text-base text-slate-800 dark:text-white flex items-center gap-2">
                        <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                        Asset Condition
                    </h3>
                </div>
                
                <div class="relative flex items-center justify-center p-2 h-44">
                    <canvas id="conditionChart"></canvas>
                </div>
            </div>

            <!-- List Legend -->
            <div class="grid grid-cols-3 gap-2 text-xxs mt-4 border-t border-gray-100 dark:border-slate-800 pt-3">
                <div class="flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    <span class="text-gray-500 truncate">New/Good ({{ $conditions['new'] + $conditions['good'] }})</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                    <span class="text-gray-500 truncate">Fair ({{ $conditions['fair'] }})</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-red-500"></span>
                    <span class="text-gray-500 truncate">Poor ({{ $conditions['poor'] }})</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Lists: Maintenance & Audits Grid -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Assets under Maintenance -->
        <div class="rounded-2xl bg-white dark:bg-slate-900 p-6 border border-gray-100 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 dark:border-slate-800 pb-3">
                <h3 class="font-extrabold text-base text-slate-800 dark:text-white flex items-center gap-2">
                    <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Currently Under Maintenance
                </h3>
                <span class="text-xs bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-400 px-2 py-0.5 rounded-full font-bold">Needs Action</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-800">
                    <thead>
                        <tr class="text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <th class="py-2.5">Asset Code</th>
                            <th class="py-2.5">Name</th>
                            <th class="py-2.5">Department</th>
                            <th class="py-2.5 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-sm">
                        @forelse($maintenanceAssets as $mAsset)
                            <tr>
                                <td class="py-3 font-semibold text-blue-600 dark:text-blue-400">
                                    <a href="{{ route('assets.show', $mAsset->id) }}" class="hover:underline">{{ $mAsset->asset_code }}</a>
                                </td>
                                <td class="py-3 font-medium text-slate-800 dark:text-slate-200">{{ $mAsset->asset_name }}</td>
                                <td class="py-3 text-xs text-gray-500">{{ $mAsset->department->name }}</td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('assets.show', $mAsset->id) }}" class="text-xs font-bold bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:hover:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 px-2.5 py-1.5 rounded-lg transition border border-indigo-100 dark:border-indigo-900/30">
                                        View Log
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-xs text-gray-400 dark:text-slate-500">
                                    No assets currently flagged in maintenance status.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent System Activities (Audit Logs) -->
        <div class="rounded-2xl bg-white dark:bg-slate-900 p-6 border border-gray-100 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 dark:border-slate-800 pb-3">
                <h3 class="font-extrabold text-base text-slate-800 dark:text-white flex items-center gap-2">
                    <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Recent System Activities
                </h3>
                @if(Auth::user()->isAdmin() || Auth::user()->isAuditor())
                    <a href="{{ route('audit-logs.index') }}" class="text-xs text-blue-600 dark:text-blue-400 font-bold hover:underline">View All Logs</a>
                @endif
            </div>
            
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @forelse($recentLogs as $log)
                        <li>
                            <div class="relative pb-8">
                                @if (!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-100 dark:bg-slate-800" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-slate-900 bg-blue-100 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-200">
                                                {{ $log->description }}
                                            </p>
                                            <p class="text-xxs text-gray-400 mt-0.5">
                                                By {{ $log->user?->name ?? 'System' }} ({{ ucfirst($log->user?->role ?? 'admin') }})
                                            </p>
                                        </div>
                                        <div class="whitespace-nowrap text-right text-xxs text-gray-400">
                                            <time datetime="{{ $log->created_at }}">{{ $log->created_at->diffForHumans() }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="py-8 text-center text-xs text-gray-400 dark:text-slate-500">
                            No activities logged yet.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Chart.js initialization script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('conditionChart').getContext('2d');
            
            const conditionData = {
                labels: ['New', 'Good', 'Fair', 'Poor', 'Disposed'],
                datasets: [{
                    data: [
                        {{ $conditions['new'] }},
                        {{ $conditions['good'] }},
                        {{ $conditions['fair'] }},
                        {{ $conditions['poor'] }},
                        {{ $conditions['disposed'] }}
                    ],
                    backgroundColor: [
                        '#10B981', // Emerald 500 (New)
                        '#3B82F6', // Blue 500 (Good)
                        '#F59E0B', // Amber 500 (Fair)
                        '#EF4444', // Red 500 (Poor)
                        '#6B7280'  // Gray 500 (Disposed)
                    ],
                    borderWidth: 0
                }]
            };

            new Chart(ctx, {
                type: 'doughnut',
                data: conditionData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    cutout: '75%'
                }
            });
        });
    </script>
</x-app-layout>
