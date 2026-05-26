<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('procurements.index') }}" class="text-gray-400 hover:text-gray-500">Procurements</a>
            <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="text-slate-800 dark:text-white font-semibold">New Procurement Request</span>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/30">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Submit Procurement Request</h3>
                <p class="text-xs text-gray-500 mt-1">Submit request for hardware, software, or utilities. Approvals are checked against departmental budget cap limits.</p>
            </div>

            <form method="POST" action="{{ route('procurements.store') }}" class="p-6 space-y-6">
                @csrf

                <!-- Title / Item Name -->
                <div>
                    <label for="title" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Request Title / Item Name <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="e.g. 15 Dell Latitude Laptops for CS Lab" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-300 ring-red-300 @enderror">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    
                    <!-- Budget Allocated -->
                    <div>
                        <label for="budget_allocated" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Estimated Cost (USD) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="budget_allocated" id="budget_allocated" value="{{ old('budget_allocated') }}" placeholder="0.00" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500 @error('budget_allocated') border-red-300 ring-red-300 @enderror">
                        @error('budget_allocated')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department_id" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Department <span class="text-red-500">*</span></label>
                        @if(Auth::user()->isAdmin())
                            <select name="department_id" id="department_id" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" value="{{ Auth::user()->department->name }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-100 dark:bg-slate-800 text-gray-500 cursor-not-allowed" readonly>
                            <input type="hidden" name="department_id" value="{{ Auth::user()->department_id }}">
                        @endif
                        @error('department_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vendor -->
                    <div class="sm:col-span-2">
                        <label for="vendor_id" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Proposed Vendor / Supplier</label>
                        <select name="vendor_id" id="vendor_id" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Choose a Vendor (Optional)</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <!-- Footer Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-slate-800">
                    <a href="{{ route('procurements.index') }}" class="text-xs font-bold bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 py-3 px-5 rounded-xl transition">
                        Cancel
                    </a>
                    <button type="submit" class="text-xs font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3 px-6 rounded-xl shadow-md transition">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
