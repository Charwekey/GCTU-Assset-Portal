<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('assets.index') }}" class="text-gray-400 hover:text-gray-500">Assets</a>
            <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="text-slate-800 dark:text-white font-semibold">Register New Asset</span>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/30">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Asset Details</h3>
                <p class="text-xs text-gray-500 mt-1">Please provide accurate purchase records and assignment status for GCTU audit trail compliance.</p>
            </div>

            <form method="POST" action="{{ route('assets.store') }}" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    
                    <!-- Asset Code -->
                    <div>
                        <label for="asset_code" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Asset Code <span class="text-red-500">*</span></label>
                        <input type="text" name="asset_code" id="asset_code" value="{{ old('asset_code') }}" placeholder="e.g. AST-CS-005" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500 @error('asset_code') border-red-300 ring-red-300 @enderror">
                        @error('asset_code')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Asset Name -->
                    <div>
                        <label for="asset_name" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Asset Name <span class="text-red-500">*</span></label>
                        <input type="text" name="asset_name" id="asset_name" value="{{ old('asset_name') }}" placeholder="e.g. Dell PowerEdge Server" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500 @error('asset_name') border-red-300 ring-red-300 @enderror">
                        @error('asset_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Category <span class="text-red-500">*</span></label>
                        <select name="category_id" id="category_id" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department_id" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Assign to Department <span class="text-red-500">*</span></label>
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

                    <!-- Purchase Date -->
                    <div>
                        <label for="purchase_date" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Purchase Date <span class="text-red-500">*</span></label>
                        <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('purchase_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Purchase Cost -->
                    <div>
                        <label for="purchase_cost" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Purchase Cost (USD) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="purchase_cost" id="purchase_cost" value="{{ old('purchase_cost') }}" placeholder="0.00" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('purchase_cost')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vendor -->
                    <div>
                        <label for="vendor_id" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Vendor / Supplier</label>
                        <select name="vendor_id" id="vendor_id" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">N/A (No Vendor linked)</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Warranty Expiry -->
                    <div>
                        <label for="warranty_expiry" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Warranty Expiration</label>
                        <input type="date" name="warranty_expiry" id="warranty_expiry" value="{{ old('warranty_expiry') }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Condition -->
                    <div>
                        <label for="condition" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Condition <span class="text-red-500">*</span></label>
                        <select name="condition" id="condition" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="new" {{ old('condition') === 'new' ? 'selected' : '' }}>New</option>
                            <option value="good" {{ old('condition', 'good') === 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ old('condition') === 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="poor" {{ old('condition') === 'poor' ? 'selected' : '' }}>Poor</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>In Maintenance</option>
                            <option value="disposed" {{ old('status') === 'disposed' ? 'selected' : '' }}>Disposed</option>
                        </select>
                    </div>

                    <!-- Assigned To Officer -->
                    <div class="sm:col-span-2">
                        <label for="assigned_to" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Assign Officer (Optional)</label>
                        <select name="assigned_to" id="assigned_to" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Keep Unassigned / In Storage</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ old('assigned_to') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ ucfirst($u->role) }})</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <!-- Footer Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-slate-800">
                    <a href="{{ route('assets.index') }}" class="text-xs font-bold bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 py-3 px-5 rounded-xl transition">
                        Cancel
                    </a>
                    <button type="submit" class="text-xs font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3 px-6 rounded-xl shadow-md transition">
                        Register Asset
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
