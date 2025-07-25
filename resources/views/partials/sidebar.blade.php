<aside id="sidebar" class="fixed top-0 left-0 w-64 h-screen bg-white dark:bg-gray-900 shadow-md transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-30 bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 flex flex-col">
    <div class="h-16 flex items-center justify-between p-4 border-b dark:border-gray-700 bg-white dark:bg-gray-900">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white tracking-tight">Roster System</h2>
        <button id="sidebar-toggle" class="md:hidden text-gray-500 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-1" aria-label="Close sidebar">
            <span class="icon-container">
                <svg class="w-4 h-4 menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg class="w-4 h-4 close-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </span>
        </button>
    </div>

    <nav class="flex-1 p-4 space-y-2 text-xl font-medium text-gray-600 dark:text-gray-300 overflow-y-auto">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-4 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 hover:scale-105 transition-all duration-200 ease-in-out {{ Route::is('dashboard') ? 'bg-blue-50 dark:bg-blue-900 text-blue-600 dark:text-blue-300' : '' }}" aria-current="{{ Route::is('dashboard') ? 'page' : 'false' }}">
            <span class="icon-container mr-2">
                <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18m-9 5h9"/>
                </svg>
            </span>
            Dashboard
        </a>

        <!-- Make A Roster -->
        <div>
            <button class="submenu-toggle flex items-center justify-between w-full px-4 py-4 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 hover:scale-105 transition-all duration-200 ease-in-out" data-target="make-roster-submenu" aria-expanded="false" aria-label="Toggle Make A Roster submenu">
                <div class="flex items-center">
                    <span class="icon-container mr-2">
                        <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </span>
                    Make A Roster
                </div>
                <span class="icon-container">
                    <svg class="w-4 h-4 transform transition-transform duration-200 text-gray-500 dark:text-gray-400" data-arrow-for="make-roster-submenu" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
            </button>
            <div id="make-roster-submenu" class="submenu hidden mt-2 ml-6 space-y-1">
                @if(\App\Models\Discipline::whereIn('name', ['Registered General Nurses (RGN)', 'Midwives', 'Public Health Nurses'])->count() === 0)
                    <span class="block px-4 py-4 text-gray-400 dark:text-gray-500 text-xs">No disciplines available</span>
                @else
                    @foreach(\App\Models\Discipline::whereIn('name', ['Registered General Nurses (RGN)', 'Midwives', 'Public Health Nurses'])->get() as $discipline)
                        <a href="{{ route('rosters.create', ['discipline' => $discipline->name === 'Registered General Nurses (RGN)' ? 'rgn' : Str::slug($discipline->name)]) }}"
                           class="flex items-center px-4 py-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 hover:scale-105 transition-all duration-200 ease-in-out {{ request()->routeIs('disciplines.units.*') && request()->route('discipline')?->id === $discipline->id ? 'bg-blue-50 dark:bg-blue-900 text-blue-600 dark:text-blue-300' : '' }}">
                            <span class="icon-container mr-2">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            {{ $discipline->name }}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

        @if(auth()->user()->is_admin)
            <!-- Manage Disciplines -->
            <div>
                <button class="submenu-toggle flex items-center justify-between w-full px-2 py-4 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 hover:scale-105 transition-all duration-200 ease-in-out" data-target="manage-disciplines-submenu" aria-expanded="false" aria-label="Toggle Manage Disciplines submenu">
                    <div class="flex items-center">
                        <span class="icon-container">
                            <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </span>
                        Manage Discipline
                    </div>
                    <span class="icon-container">
                        <svg class="w-4 h-4 transform transition-transform duration-200 text-gray-500 dark:text-gray-400" data-arrow-for="manage-disciplines-submenu" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </span>
                </button>
                <div id="manage-disciplines-submenu" class="submenu hidden mt-2 ml-6 space-y-1">
                    @if(\App\Models\Discipline::whereIn('name', ['Registered General Nurses (RGN)', 'Midwives', 'Public Health Nurses'])->count() === 0)
                        <span class="block px-4 py-4 text-gray-400 dark:text-gray-500 text-xs">No disciplines available</span>
                    @else
                        @foreach(\App\Models\Discipline::whereIn('name', ['Registered General Nurses (RGN)', 'Midwives', 'Public Health Nurses'])->get() as $discipline)
                            <a href="{{ route('disciplines.units.index', $discipline) }}"
                               class="flex items-center px-4 py-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 hover:scale-105 transition-all duration-200 ease-in-out {{ request()->routeIs('disciplines.units.*') && request()->route('discipline')?->id === $discipline->id ? 'bg-blue-50 dark:bg-blue-900 text-blue-600 dark:text-blue-300' : '' }}">
                                <span class="icon-container mr-2">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>
                                {{ $discipline->name }}
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        @endif
    </nav>

    <!-- Logout -->
    <div class="p-4 mt-auto font-semibold text-lg" style="margin-bottom: 2rem;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" aria-label="Logout" class="flex items-center w-full px-4 py-4 rounded-lg text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/50 hover:scale-105 transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500">
                <span class="icon-container mr-2">
                    <svg class="w-4 h-4 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 24 24" width="16" height="16">
                        <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zM4 5h8V3H4a1 1 0 00-1 1v16a1 1 0 001 1h8v-2H4V5z" />
                    </svg>
                </span>
                Logout
            </button>
        </form>
    </div>
</aside>

<style>
    .icon-container {
        display: inline-block;
        transform: none !important;
    }
    .icon-container svg {
        transition: transform 0.2s ease-in-out;
    }
    .flex.items-center:hover .icon-container svg {
        transform: scale(1.2);
    }
    .submenu {
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    .submenu.open {
        opacity: 1;
        transform: translateY(0);
    }
    #sidebar-overlay {
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    #sidebar-overlay.open {
        opacity: 1;
        pointer-events: auto;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const mobileBtn = document.getElementById('mobile-menu-button');
        const overlay = document.getElementById('sidebar-overlay');
        let openSubmenuId = null;
        let isSidebarOpen = false;

        const initializeSidebar = () => {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                overlay.classList.remove('open');
                isSidebarOpen = false;
            } else {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                overlay.classList.remove('open');
                isSidebarOpen = false;
            }
        };

        initializeSidebar();

        window.addEventListener('resize', initializeSidebar);

        const toggleSubmenu = (btn, targetId) => {
            const submenu = document.getElementById(targetId);
            const arrow = document.querySelector(`[data-arrow-for="${targetId}"]`);
            const isOpen = submenu.classList.contains('open');

            if (openSubmenuId && openSubmenuId !== targetId) {
                const prevSubmenu = document.getElementById(openSubmenuId);
                prevSubmenu.classList.remove('open');
                setTimeout(() => {
                    if (!prevSubmenu.classList.contains('open')) {
                        prevSubmenu.classList.add('hidden');
                    }
                }, 300);
                const prevArrow = document.querySelector(`[data-arrow-for="${openSubmenuId}"]`);
                prevArrow.classList.remove('rotate-180');
                const prevToggle = document.querySelector(`[data-target="${openSubmenuId}"]`);
                prevToggle.setAttribute('aria-expanded', 'false');
            }

            if (isOpen) {
                submenu.classList.remove('open');
                setTimeout(() => {
                    if (!submenu.classList.contains('open')) {
                        submenu.classList.add('hidden');
                    }
                }, 300);
                arrow.classList.remove('rotate-180');
                btn.setAttribute('aria-expanded', 'false');
                openSubmenuId = null;
            } else {
                submenu.classList.remove('hidden');
                setTimeout(() => submenu.classList.add('open'), 10);
                arrow.classList.add('rotate-180');
                btn.setAttribute('aria-expanded', 'true');
                openSubmenuId = targetId;
            }
        };

        document.querySelectorAll('.submenu-toggle').forEach(btn => {
            const targetId = btn.getAttribute('data-target');
            btn.addEventListener('click', () => toggleSubmenu(btn, targetId));
            btn.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleSubmenu(btn, targetId);
                }
            });
        });

        const closeSidebar = () => {
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');
            overlay.classList.remove('open');
            document.querySelector('.menu-icon')?.classList.remove('hidden');
            document.querySelector('.close-icon')?.classList.add('hidden');
            isSidebarOpen = false;
            if (openSubmenuId) {
                const prevSubmenu = document.getElementById(openSubmenuId);
                prevSubmenu.classList.remove('open');
                setTimeout(() => {
                    if (!prevSubmenu.classList.contains('open')) {
                        prevSubmenu.classList.add('hidden');
                    }
                }, 300);
                const prevArrow = document.querySelector(`[data-arrow-for="${openSubmenuId}"]`);
                prevArrow.classList.remove('rotate-180');
                const prevToggle = document.querySelector(`[data-target="${openSubmenuId}"]`);
                prevToggle.setAttribute('aria-expanded', 'false');
                openSubmenuId = null;
            }
        };

        sidebarToggle.addEventListener('click', closeSidebar);
        mobileBtn.addEventListener('click', () => {
            sidebar.classList.add('translate-x-0');
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.add('open');
            document.querySelector('.menu-icon')?.classList.add('hidden');
            document.querySelector('.close-icon')?.classList.remove('hidden');
            isSidebarOpen = true;
        });
        overlay.addEventListener('click', closeSidebar);

        document.querySelectorAll('nav a, nav button[type="submit"]').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) closeSidebar();
            });
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && (isSidebarOpen || openSubmenuId)) {
                closeSidebar();
            }
        });
    });
</script>