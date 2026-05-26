<x-app-layout>
    <x-slot name="header">
        {{ __('Manage Asset Categories') }}
    </x-slot>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <!-- Left: Categories Table -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-800">
                    <thead class="bg-gray-50 dark:bg-slate-900/50">
                        <tr class="text-left text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-6">Category Name</th>
                            <th class="py-4 px-6">Description</th>
                            <th class="py-4 px-6 text-center">Assets Count</th>
                            @if(Auth::user()->isAdmin())
                                <th class="py-4 px-6 text-right">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-sm">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition">
                                <td class="py-4 px-6 font-semibold text-slate-800 dark:text-slate-200">
                                    {{ $cat->name }}
                                </td>
                                <td class="py-4 px-6 text-gray-500 max-w-xs truncate" title="{{ $cat->description }}">
                                    {{ $cat->description ?? 'No description' }}
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-slate-800 dark:text-white">
                                    {{ $cat->assets_count }}
                                </td>
                                @if(Auth::user()->isAdmin())
                                    <td class="py-4 px-6 text-right flex items-center justify-end gap-2">
                                        <a href="{{ route('categories.index', ['edit_id' => $cat->id]) }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                                        <form method="POST" action="{{ route('categories.destroy', $cat->id) }}" onsubmit="return confirm('Are you sure you want to delete this category? Linked assets will be orphaned/deleted.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-red-500 hover:underline ml-2">Delete</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-400 dark:text-slate-500">No categories defined.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>

        <!-- Right: Create/Edit Category -->
        <div>
            @if(Auth::user()->isAdmin())
                @php
                    $editing = null;
                    if(request()->filled('edit_id')) {
                        $editing = \App\Models\Category::find(request('edit_id'));
                    }
                @endphp

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm p-6">
                    <h3 class="font-bold text-slate-800 dark:text-white border-b border-gray-100 dark:border-slate-800 pb-3 flex items-center gap-2">
                        <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a2.25 2.25 0 003.182 0l4.318-4.318a2.25 2.25 0 000-3.182L11.16 3.659A2.25 2.25 0 009.568 3z" />
                        </svg>
                        {{ $editing ? 'Modify Category' : 'Add Category' }}
                    </h3>

                    <form method="POST" action="{{ $editing ? route('categories.update', $editing->id) : route('categories.store') }}" class="space-y-4 mt-4">
                        @csrf
                        @if($editing)
                            @method('PUT')
                        @endif

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Category Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $editing?->name) }}" placeholder="e.g. IT Equipment" class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-300 ring-red-300 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Description</label>
                            <textarea name="description" id="description" rows="3" placeholder="Category description..." class="w-full text-sm rounded-xl border-gray-200 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-950 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $editing?->description) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            @if($editing)
                                <a href="{{ route('categories.index') }}" class="text-xs font-bold bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 py-3 px-4 rounded-xl transition text-center flex-1">
                                    Cancel
                                </a>
                            @endif
                            <button type="submit" class="text-xs font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3 px-4 rounded-xl shadow-md transition flex-1">
                                {{ $editing ? 'Save Changes' : 'Add Category' }}
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm p-6 text-center text-xs text-gray-400">
                    <svg class="h-8 w-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Only system administrators are permitted to manage asset categories.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
