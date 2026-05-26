<x-app-layout>
    <x-slot name="header">
        {{ __('System Audit Logs') }}
    </x-slot>

    <!-- Filters -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 mb-8 shadow-sm">
        <form method="GET" action="{{ route('audit-logs.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label for="search" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Search Logs Description/User</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search details or user name..." class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Action Type -->
            <div>
                <label for="action_type" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Action Type</label>
                <select name="action_type" id="action_type" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Actions</option>
                    @foreach($actionTypes as $type)
                        <option value="{{ $type }}" {{ request('action_type') === $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl shadow-sm transition text-center justify-center">
                    Search
                </button>
                <a href="{{ route('audit-logs.index') }}" class="text-xs font-bold bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 py-3 px-4 rounded-xl shadow-sm transition text-center">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-800">
                <thead class="bg-gray-50 dark:bg-slate-900/50">
                    <tr class="text-left text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">
                        <th class="py-4 px-6">Timestamp</th>
                        <th class="py-4 px-6">User / Actor</th>
                        <th class="py-4 px-6">Action</th>
                        <th class="py-4 px-6">Details</th>
                        <th class="py-4 px-6 text-right">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-sm">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition">
                            <td class="py-4 px-6 text-gray-500 whitespace-nowrap">
                                {{ $log->created_at->format('M d, Y H:i:s') }}
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="font-bold text-slate-800 dark:text-slate-200 block">{{ $log->user?->name ?? 'System' }}</span>
                                <span class="text-xxs text-gray-400 block">{{ ucfirst($log->user?->role ?? 'system') }}</span>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="text-xs font-bold px-2.5 py-1 rounded-lg uppercase bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-400 border border-slate-200 dark:border-slate-700/50">
                                    {{ str_replace('_', ' ', $log->action) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-gray-650 dark:text-slate-350">{{ $log->description }}</td>
                            <td class="py-4 px-6 text-right font-mono text-xs text-gray-400 whitespace-nowrap">
                                {{ $log->ip_address ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-400 dark:text-slate-500">
                                No logs matching criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
