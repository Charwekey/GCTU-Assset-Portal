<x-app-layout>
    <x-slot name="header">
        {{ __('System Settings & Configurations') }}
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/30">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white font-heading">Global Settings</h3>
                <p class="text-xs text-gray-500 mt-1">Configure GCTU system settings and threshold variables. Changes are applied system-wide.</p>
            </div>

            <form method="POST" action="{{ route('settings.update') }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- App Name -->
                <div>
                    <label for="app_name" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Application Portal Title <span class="text-red-500">*</span></label>
                    <input type="text" name="app_name" id="app_name" value="{{ old('app_name', $settings['app_name'] ?? 'GCTU Asset Hub') }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('app_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Currency -->
                <div>
                    <label for="currency" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">System Currency Symbol <span class="text-red-500">*</span></label>
                    <input type="text" name="currency" id="currency" value="{{ old('currency', $settings['currency'] ?? 'USD') }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('currency')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Maintenance Warning Days -->
                <div>
                    <label for="maintenance_warning_days" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Maintenance Warning Threshold (Days) <span class="text-red-500">*</span></label>
                    <input type="number" name="maintenance_warning_days" id="maintenance_warning_days" value="{{ old('maintenance_warning_days', $settings['maintenance_warning_days'] ?? '30') }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('maintenance_warning_days')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-[10px] text-gray-400 mt-1.5">Triggers warning flags for assets whose warranty is expiring within this many days.</p>
                </div>

                <!-- Action Button -->
                <div class="flex justify-end pt-6 border-t border-gray-100 dark:border-slate-800">
                    <button type="submit" class="text-xs font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3 px-6 rounded-xl shadow-md transition">
                        Update Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
