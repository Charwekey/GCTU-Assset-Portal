<x-app-layout>
    <x-slot name="header">
        {{ __('Manage Vendors & Suppliers') }}
    </x-slot>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <!-- Left: Vendors Table -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-800">
                    <thead class="bg-gray-50 dark:bg-slate-900/50">
                        <tr class="text-left text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-6">Vendor Name</th>
                            <th class="py-4 px-6">Contact Info</th>
                            <th class="py-4 px-6 text-center">Assets / PRs</th>
                            @if(Auth::user()->isAdmin())
                                <th class="py-4 px-6 text-right">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-sm">
                        @forelse($vendors as $vendor)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition">
                                <td class="py-4 px-6 font-semibold text-slate-800 dark:text-slate-200">
                                    {{ $vendor->name }}
                                    <span class="text-xxs text-gray-400 dark:text-slate-500 block truncate max-w-xs" title="{{ $vendor->address }}">{{ $vendor->address ?? 'No address' }}</span>
                                </td>
                                <td class="py-4 px-6 text-gray-500">
                                    <span class="block text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $vendor->email ?? 'No email' }}</span>
                                    <span class="block text-xxs text-gray-400 mt-0.5">{{ $vendor->phone ?? 'No phone' }}</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                        {{ $vendor->assets_count }} / {{ $vendor->procurements_count }}
                                    </span>
                                </td>
                                @if(Auth::user()->isAdmin())
                                    <td class="py-4 px-6 text-right flex items-center justify-end gap-2">
                                        <a href="{{ route('vendors.index', ['edit_id' => $vendor->id]) }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                                        <form method="POST" action="{{ route('vendors.destroy', $vendor->id) }}" onsubmit="return confirm('Are you sure you want to delete this vendor? Associated assets and procurements will disconnect.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-red-500 hover:underline ml-2">Delete</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-400 dark:text-slate-500">No vendors defined.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($vendors->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50">
                    {{ $vendors->links() }}
                </div>
            @endif
        </div>

        <!-- Right: Create/Edit Vendor -->
        <div>
            @if(Auth::user()->isAdmin())
                @php
                    $editing = null;
                    if(request()->filled('edit_id')) {
                        $editing = \App\Models\Vendor::find(request('edit_id'));
                    }
                @endphp

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm p-6">
                    <h3 class="font-bold text-slate-800 dark:text-white border-b border-gray-100 dark:border-slate-800 pb-3 flex items-center gap-2">
                        <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                        {{ $editing ? 'Modify Vendor' : 'Add Vendor' }}
                    </h3>

                    <form method="POST" action="{{ $editing ? route('vendors.update', $editing->id) : route('vendors.store') }}" class="space-y-4 mt-4">
                        @csrf
                        @if($editing)
                            @method('PUT')
                        @endif

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Vendor Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $editing?->name) }}" placeholder="e.g. Cisco Networks Ltd" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-300 ring-red-300 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $editing?->email) }}" placeholder="e.g. sales@cisco.com" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Phone Line</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $editing?->phone) }}" placeholder="e.g. +233 30 221 000" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Physical Location Address</label>
                            <textarea name="address" id="address" rows="3" placeholder="Vendor location details..." class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $editing?->address) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            @if($editing)
                                <a href="{{ route('vendors.index') }}" class="text-xs font-bold bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 py-3 px-4 rounded-xl transition text-center flex-1">
                                    Cancel
                                </a>
                            @endif
                            <button type="submit" class="text-xs font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3 px-4 rounded-xl shadow-md transition flex-1">
                                {{ $editing ? 'Save Changes' : 'Add Vendor' }}
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm p-6 text-center text-xs text-gray-400">
                    <svg class="h-8 w-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Only system administrators are permitted to manage vendor files.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
