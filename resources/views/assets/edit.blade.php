<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('assets.index') }}" class="text-gray-400 hover:text-gray-500">Assets</a>
            <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <a href="{{ route('assets.show', $asset->id) }}" class="text-gray-400 hover:text-gray-500">{{ $asset->asset_code }}</a>
            <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="text-slate-800 dark:text-white font-semibold">Modify Asset</span>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/30">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Modify Asset Details</h3>
                <p class="text-xs text-gray-500 mt-1">Make changes to asset parameters. All edits will be logged under the admin audit logs.</p>
            </div>

            <form method="POST" action="{{ route('assets.update', $asset->id) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    
                    <!-- Asset Code (Readonly for non-admin) -->
                    <div>
                        <label for="asset_code" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Asset Code <span class="text-red-500">*</span></label>
                        @if(Auth::user()->isAdmin())
                            <input type="text" name="asset_code" id="asset_code" value="{{ old('asset_code', $asset->asset_code) }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500 @error('asset_code') border-red-300 ring-red-300 @enderror">
                        @else
                            <input type="text" value="{{ $asset->asset_code }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-100 dark:bg-slate-800 text-gray-500 cursor-not-allowed" readonly>
                        @endif
                        @error('asset_code')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Asset Name -->
                    <div>
                        <label for="asset_name" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Asset Name <span class="text-red-500">*</span></label>
                        <input type="text" name="asset_name" id="asset_name" value="{{ old('asset_name', $asset->asset_name) }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500 @error('asset_name') border-red-300 ring-red-300 @enderror">
                        @error('asset_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Category <span class="text-red-500">*</span></label>
                        <select name="category_id" id="category_id" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $asset->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Department (Readonly for non-admin) -->
                    <div>
                        <label for="department_id" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Department <span class="text-red-500">*</span></label>
                        @if(Auth::user()->isAdmin())
                            <select name="department_id" id="department_id" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $asset->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" value="{{ $asset->department->name }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-100 dark:bg-slate-800 text-gray-500 cursor-not-allowed" readonly>
                        @endif
                        @error('department_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Purchase Date -->
                    <div>
                        <label for="purchase_date" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Purchase Date <span class="text-red-500">*</span></label>
                        <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date', $asset->purchase_date->format('Y-m-d')) }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('purchase_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Purchase Cost -->
                    <div>
                        <label for="purchase_cost" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Purchase Cost (USD) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="purchase_cost" id="purchase_cost" value="{{ old('purchase_cost', $asset->purchase_cost) }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
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
                                <option value="{{ $vendor->id }}" {{ old('vendor_id', $asset->vendor_id) == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Warranty Expiry -->
                    <div>
                        <label for="warranty_expiry" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Warranty Expiration</label>
                        <input type="date" name="warranty_expiry" id="warranty_expiry" value="{{ old('warranty_expiry', $asset->warranty_expiry ? $asset->warranty_expiry->format('Y-m-d') : '') }}" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Condition -->
                    <div>
                        <label for="condition" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Condition <span class="text-red-500">*</span></label>
                        <select name="condition" id="condition" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="new" {{ old('condition', $asset->condition) === 'new' ? 'selected' : '' }}>New</option>
                            <option value="good" {{ old('condition', $asset->condition) === 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ old('condition', $asset->condition) === 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="poor" {{ old('condition', $asset->condition) === 'poor' ? 'selected' : '' }}>Poor</option>
                            <option value="disposed" {{ old('condition', $asset->condition) === 'disposed' ? 'selected' : '' }}>Disposed</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="active" {{ old('status', $asset->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="maintenance" {{ old('status', $asset->status) === 'maintenance' ? 'selected' : '' }}>In Maintenance</option>
                            <option value="disposed" {{ old('status', $asset->status) === 'disposed' ? 'selected' : '' }}>Disposed</option>
                        </select>
                    </div>

                    <!-- Assigned To Officer -->
                    <div class="sm:col-span-2">
                        <label for="assigned_to" class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2">Assign Officer</label>
                        <select name="assigned_to" id="assigned_to" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Keep Unassigned / In Storage</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ old('assigned_to', $asset->assigned_to) == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ ucfirst($u->role) }})</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <!-- Footer Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-slate-800">
                    <a href="{{ route('assets.show', $asset->id) }}" class="text-xs font-bold bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 py-3 px-5 rounded-xl transition">
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
