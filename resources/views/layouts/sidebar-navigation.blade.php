<nav class="flex flex-1 flex-col mt-4">
    <ul role="list" class="flex flex-1 flex-col gap-y-7">
        <li>
            <ul role="list" class="-mx-2 space-y-1">
                
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('dashboard') }}" class="group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                        <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-slate-600 dark:group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Dashboard
                    </a>
                </li>

                <!-- Assets -->
                <li>
                    <a href="{{ route('assets.index') }}" class="group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition {{ request()->routeIs('assets*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                        <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('assets*') ? 'text-white' : 'text-gray-400 group-hover:text-slate-600 dark:group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                        Asset Registry
                    </a>
                </li>

                <!-- Procurements -->
                <li>
                    <a href="{{ route('procurements.index') }}" class="group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition {{ request()->routeIs('procurements*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                        <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('procurements*') ? 'text-white' : 'text-gray-400 group-hover:text-slate-600 dark:group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                        Procurements
                    </a>
                </li>

                <!-- Projects -->
                <li>
                    <a href="{{ route('projects.index') }}" class="group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition {{ request()->routeIs('projects*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                        <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('projects*') ? 'text-white' : 'text-gray-400 group-hover:text-slate-600 dark:group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.375M9 18h3.375m2.21-10.084a3 3 0 00-4.243 0l-5.657 5.656a3 3 0 000 4.243l5.657 5.656a3 3 0 004.243 0l5.657-5.656a3 3 0 000-4.243l-5.657-5.656z" />
                        </svg>
                        Projects Tracker
                    </a>
                </li>

            </ul>
        </li>

        <!-- Admin Scoped Links -->
        @if(Auth::user()->isAdmin() || Auth::user()->isAuditor())
            <li>
                <div class="text-xs font-semibold leading-6 text-gray-400 dark:text-slate-500 uppercase tracking-wider">Administration</div>
                <ul role="list" class="-mx-2 mt-2 space-y-1">
                    
                    <!-- Departments -->
                    <li>
                        <a href="{{ route('departments.index') }}" class="group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition {{ request()->routeIs('*departments*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('*departments*') ? 'text-white' : 'text-gray-400 group-hover:text-slate-600 dark:group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h18v3H3V3z" />
                            </svg>
                            Departments
                        </a>
                    </li>

                    <!-- Categories -->
                    <li>
                        <a href="{{ route('categories.index') }}" class="group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition {{ request()->routeIs('*categories*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('*categories*') ? 'text-white' : 'text-gray-400 group-hover:text-slate-600 dark:group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a2.25 2.25 0 003.182 0l4.318-4.318a2.25 2.25 0 000-3.182L11.16 3.659A2.25 2.25 0 009.568 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5h.008v.008H6V7.5z" />
                            </svg>
                            Asset Categories
                        </a>
                    </li>

                    <!-- Vendors -->
                    <li>
                        <a href="{{ route('vendors.index') }}" class="group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition {{ request()->routeIs('*vendors*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('*vendors*') ? 'text-white' : 'text-gray-400 group-hover:text-slate-600 dark:group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                            Vendors
                        </a>
                    </li>

                    <!-- Audit Trail -->
                    <li>
                        <a href="{{ route('audit-logs.index') }}" class="group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition {{ request()->routeIs('*audit-logs*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('*audit-logs*') ? 'text-white' : 'text-gray-400 group-hover:text-slate-600 dark:group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                            Audit Trail
                        </a>
                    </li>

                    <!-- System Settings -->
                    @if(Auth::user()->isAdmin())
                        <li>
                            <a href="{{ route('settings.index') }}" class="group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition {{ request()->routeIs('*settings*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                                <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('*settings*') ? 'text-white' : 'text-gray-400 group-hover:text-slate-600 dark:group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.991l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.645-.869l.213-1.28z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                System Settings
                            </a>
                        </li>
                    @endif

                </ul>
            </li>
        @endif
    </ul>
</nav>
