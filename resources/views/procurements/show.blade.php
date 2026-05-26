<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm">
                <a href="{{ route('procurements.index') }}" class="text-gray-400 hover:text-gray-500">Procurements</a>
                <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-slate-800 dark:text-white font-semibold">{{ $procurement->procurement_code }}</span>
            </div>
            
            <!-- Quick Cancel Button -->
            @can('cancel', $procurement)
                <form method="POST" action="{{ route('procurements.cancel', $procurement->id) }}" onsubmit="return confirm('Are you sure you want to cancel/reject this procurement request?');">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-bold bg-rose-50 hover:bg-rose-100 text-rose-700 dark:bg-rose-950/30 dark:hover:bg-rose-900/40 dark:text-rose-400 px-3.5 py-2 rounded-xl transition border border-rose-100 dark:border-rose-900/30">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel Request
                    </button>
                </form>
            @endcan
        </div>
    </x-slot>

    <!-- Budget Overrun Warning Banner -->
    @if($procurement->status === 'pending')
        @if($isOverrunRisk)
            <div class="mb-6 rounded-2xl bg-rose-50 dark:bg-rose-950/20 p-5 border border-rose-200 dark:border-rose-900/50 flex items-start gap-3.5 shadow-sm">
                <span class="p-2 rounded-xl bg-rose-100 text-rose-800 dark:bg-rose-900 dark:text-rose-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </span>
                <div>
                    <h4 class="font-extrabold text-sm text-rose-800 dark:text-rose-300">Budget Limit Safety Warning</h4>
                    <p class="text-xs text-rose-700 dark:text-rose-400 mt-1 leading-relaxed">
                        Approving this procurement of <strong>${{ number_format($procurement->budget_allocated, 2) }}</strong> will exceed the department's budget cap limit of <strong>${{ number_format($budgetLimit, 2) }}</strong>!
                        The remaining department budget headroom is currently <strong>${{ number_format($headroom, 2) }}</strong>.
                    </p>
                </div>
            </div>
        @else
            <div class="mb-6 rounded-2xl bg-blue-50 dark:bg-blue-950/20 p-5 border border-blue-200 dark:border-blue-900/50 flex items-start gap-3.5 shadow-sm">
                <span class="p-2 rounded-xl bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                <div>
                    <h4 class="font-extrabold text-sm text-blue-800 dark:text-blue-300">Budget Headroom Check</h4>
                    <p class="text-xs text-blue-700 dark:text-blue-400 mt-1 leading-relaxed">
                        This request fits safely within the department's limits. Remaining headroom is <strong>${{ number_format($headroom, 2) }}</strong>, and this request consumes <strong>{{ number_format(($procurement->budget_allocated / $budgetLimit) * 100, 1) }}%</strong> of the overall budget limit.
                    </p>
                </div>
            </div>
        @endif
    @endif

    <!-- Main Workspace Grid -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        
        <!-- Left: Procurement Profile Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <!-- Header -->
                <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/30 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 dark:text-white">{{ $procurement->title }}</h2>
                        <span class="text-xs text-gray-500 font-mono mt-1 block">{{ $procurement->procurement_code }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @php
                            $statusColor = match($procurement->status) {
                                'pending' => 'bg-amber-500/10 text-amber-500 border border-amber-500/20',
                                'approved' => 'bg-blue-500/10 text-blue-500 border border-blue-500/20',
                                'in_progress' => 'bg-indigo-500/10 text-indigo-500 border border-indigo-500/20',
                                'completed' => 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20',
                                'cancelled' => 'bg-rose-500/10 text-rose-500 border border-rose-500/20',
                                default => 'bg-gray-500/10 text-gray-500'
                            };
                        @endphp
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider {{ $statusColor }}">
                            {{ str_replace('_', ' ', $procurement->status) }}
                        </span>
                    </div>
                </div>

                <!-- Parameters list -->
                <div class="p-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Department</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $procurement->department->name }}</span>
                    </div>

                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Proposed Vendor</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $procurement->vendor?->name ?? 'N/A' }}</span>
                    </div>

                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Initiated By</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $procurement->initiator?->name ?? 'System' }}</span>
                    </div>

                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Approved By</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $procurement->approver?->name ?? 'Not Yet Approved' }}</span>
                    </div>

                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Start Date</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $procurement->start_date ? $procurement->start_date->format('F d, Y') : 'Not Started' }}</span>
                    </div>

                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Completion Date</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $procurement->completion_date ? $procurement->completion_date->format('F d, Y') : 'Incomplete' }}</span>
                    </div>

                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Estimated Budget</span>
                        <span class="text-lg font-black text-slate-850 dark:text-slate-200">${{ number_format($procurement->budget_allocated, 2) }}</span>
                    </div>

                    <div>
                        <span class="text-xxs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider block">Actual Expenditure Cost</span>
                        <span class="text-lg font-black text-slate-800 dark:text-white">
                            {{ $procurement->actual_cost ? '$' . number_format($procurement->actual_cost, 2) : '—' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar: Workflow Action Panel -->
        <div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm p-6 sticky top-24">
                <h3 class="font-bold text-slate-800 dark:text-white border-b border-gray-100 dark:border-slate-800 pb-3 flex items-center gap-2">
                    <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Workflow Management
                </h3>
                
                <div class="space-y-4 mt-4">
                    
                    <!-- 1. Pending -> Approve (For Managers / Admins) -->
                    @if($procurement->status === 'pending')
                        @can('approve', $procurement)
                            <form method="POST" action="{{ route('procurements.approve', $procurement->id) }}">
                                @csrf
                                <button type="submit" class="w-full text-xs font-bold bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white py-3 rounded-xl shadow-md transition text-center">
                                    Approve Procurement Request
                                </button>
                            </form>
                        @else
                            <div class="text-xs text-gray-400 text-center py-4 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                Awaiting Approval. You do not have permissions to approve this request.
                            </div>
                        @endcan

                    <!-- 2. Approved -> Start (For Initiators / Managers / Admins) -->
                    @elseif($procurement->status === 'approved')
                        @can('update', $procurement)
                            <form method="POST" action="{{ route('procurements.start', $procurement->id) }}">
                                @csrf
                                <button type="submit" class="w-full text-xs font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3 rounded-xl shadow-md transition text-center">
                                    Mark as In Progress / Ordered
                                </button>
                            </form>
                        @else
                            <div class="text-xs text-gray-400 text-center py-4 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                Request is approved. Awaiting order dispatch.
                            </div>
                        @endcan

                    <!-- 3. In Progress -> Complete (With Option to Register Asset) -->
                    @elseif($procurement->status === 'in_progress')
                        @can('update', $procurement)
                            <div x-data="{ regAsset: true }">
                                <form method="POST" action="{{ route('procurements.complete', $procurement->id) }}" class="space-y-4">
                                    @csrf
                                    
                                    <!-- Actual Cost Input -->
                                    <div>
                                        <label for="actual_cost" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Actual Purchase Cost (USD) <span class="text-red-500">*</span></label>
                                        <input type="number" step="0.01" name="actual_cost" id="actual_cost" value="{{ $procurement->budget_allocated }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <!-- Auto Register Asset Toggles -->
                                    <div class="flex items-center gap-2 py-1">
                                        <input type="checkbox" name="register_as_asset" id="register_as_asset" value="1" x-model="regAsset" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <label for="register_as_asset" class="text-xs font-semibold text-slate-800 dark:text-slate-300">Auto-register as asset in registry</label>
                                    </div>

                                    <!-- Asset Category Select (Hidden if toggle false) -->
                                    <div x-show="regAsset" style="display: none;">
                                        <label for="category_id" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Select Asset Category <span class="text-red-500">*</span></label>
                                        <select name="category_id" id="category_id" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <button type="submit" class="w-full text-xs font-bold bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white py-3 rounded-xl shadow-md transition text-center">
                                        Complete Procurement
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="text-xs text-gray-400 text-center py-4 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                Procurement is in progress. Awaiting completion logs.
                            </div>
                        @endcan

                    <!-- 4. Complete / Cancelled Status -->
                    @else
                        <div class="text-center py-6 bg-gray-50 dark:bg-slate-800 rounded-2xl">
                            <span class="text-xs font-bold text-gray-500 dark:text-slate-400 block uppercase tracking-widest">Workflow Closed</span>
                            <span class="text-[10px] text-gray-400 mt-1 block">Request status: {{ strtoupper($procurement->status) }}</span>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</x-app-layout>
