<aside id="sidebar" class="fixed top-0 left-0 w-64 h-screen bg-white dark:bg-gray-900 shadow-md transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-30 bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800">
    <div class="flex items-center justify-between p-4 border-b dark:border-gray-700 bg-white dark:bg-gray-900 sticky top-0 z-20">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white tracking-tight">Roster System</h2>
        <button id="sidebar-toggle" class="md:hidden text-gray-500 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav class="p-4 space-y-2 text-sm font-medium text-gray-600 dark:text-gray-300 overflow-y-auto h-[calc(100vh-4rem)] relative">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 hover:scale-105 transition-all duration-200 ease-in-out {{ Route::is('dashboard') ? 'bg-blue-50 dark:bg-blue-900 text-blue-600 dark:text-blue-300' : '' }}"
           aria-current="{{ Route::is('dashboard') ? 'page' : 'false' }}">
            <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3M9 21v-6a1 1 0 011-1h4a1 1 0 011 1v6" />
            </svg>
            Dashboard
        </a>

        @if(auth()->user()->is_admin)
            <!-- Manage Disciplines -->
            <div>
                <button class="submenu-toggle flex items-center justify-between w-full px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 hover:scale-105 transition-all duration-200 ease-in-out"
                        data-target="disciplines-submenu" aria-expanded="false">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        Manage Disciplines
                    </div>
                    <svg class="w-3 h-3 transform transition-transform duration-200 text-gray-500 dark:text-gray-400" data-arrow-for="disciplines-submenu" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="disciplines-submenu" class="submenu hidden mt-2 ml-6 space-y-1 opacity-0 transition-opacity duration-300">
                    @if(\App\Models\Discipline::whereIn('name', ['Registered General Nurses (RGN)', 'Midwives', 'Public Health Nurses'])->count() === 0)
                        <span class="block px-3 py-2 text-gray-400 dark:text-gray-500 text-xs">No disciplines available</span>
                    @else
                        @foreach(\App\Models\Discipline::whereIn('name', ['Registered General Nurses (RGN)', 'Midwives', 'Public Health Nurses'])->get() as $discipline)
                            <a href="{{ route('disciplines.units.index', $discipline) }}"
                               class="block px-3 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 hover:scale-105 transition-all duration-200 ease-in-out {{ request()->routeIs('disciplines.units.*') && request()->route('discipline')?->id === $discipline->id ? 'bg-blue-50 dark:bg-blue-900 text-blue-600 dark:text-blue-300' : '' }}">
                                {{ $discipline->name }}
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            
        @endif

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="absolute bottom-4 w-full">
            @csrf
            <button type="submit"
                    class="flex items-center w-full px-3 py-2 rounded-lg text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/50 hover:scale-105 transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500">
                <svg class="w-4 h-4 mr-2 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>
    </nav>
</aside>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const mobileBtn = document.getElementById('mobile-menu-toggle');
    const overlay = document.getElementById('sidebar-overlay');
    let openSubmenuId = null;

    const initializeSidebar = () => {
        if (window.innerWidth >= 768) {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            overlay?.classList.add('hidden');
            sidebar.style.opacity = '1';
        } else {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            overlay?.classList.add('hidden');
            sidebar.style.opacity = '1';
        }
    };

    initializeSidebar();

    window.addEventListener('resize', () => {
        initializeSidebar();
        if (window.innerWidth >= 768 && openSubmenuId) {
            const prevSubmenu = document.getElementById(openSubmenuId);
            prevSubmenu.classList.remove('open', 'opacity-100');
            prevSubmenu.classList.add('hidden', 'opacity-0');
            const prevArrow = document.querySelector(`[data-arrow-for="${openSubmenuId}"]`);
            prevArrow.classList.remove('rotate-180');
            const prevToggle = document.querySelector(`[data-target="${openSubmenuId}"]`);
            prevToggle.setAttribute('aria-expanded', 'false');
            openSubmenuId = null;
        }
    });

    const toggleSubmenu = (btn, targetId) => {
        const submenu = document.getElementById(targetId);
        const arrow = document.querySelector(`[data-arrow-for="${targetId}"]`);
        const isOpen = submenu.classList.contains('open');

        if (openSubmenuId && openSubmenuId !== targetId) {
            const prevSubmenu = document.getElementById(openSubmenuId);
            prevSubmenu.classList.remove('open', 'opacity-100');
            prevSubmenu.classList.add('hidden', 'opacity-0');
            const prevArrow = document.querySelector(`[data-arrow-for="${openSubmenuId}"]`);
            prevArrow.classList.remove('rotate-180');
            const prevToggle = document.querySelector(`[data-target="${openSubmenuId}"]`);
            prevToggle.setAttribute('aria-expanded', 'false');
        }

        if (isOpen) {
            submenu.classList.remove('open', 'opacity-100');
            submenu.classList.add('hidden', 'opacity-0');
            arrow.classList.remove('rotate-180');
            btn.setAttribute('aria-expanded', 'false');
            openSubmenuId = null;
        } else {
            submenu.classList.add('open');
            submenu.classList.remove('hidden');
            setTimeout(() => submenu.classList.add('opacity-100'), 10);
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

    const toggleSidebar = () => {
        const isOpen = sidebar.classList.contains('translate-x-0');
        if (isOpen) {
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');
            overlay?.classList.add('hidden');
            if (openSubmenuId) {
                const prevSubmenu = document.getElementById(openSubmenuId);
                prevSubmenu.classList.remove('open', 'opacity-100');
                prevSubmenu.classList.add('hidden', 'opacity-0');
                const prevArrow = document.querySelector(`[data-arrow-for="${openSubmenuId}"]`);
                prevArrow.classList.remove('rotate-180');
                const prevToggle = document.querySelector(`[data-target="${openSubmenuId}"]`);
                prevToggle.setAttribute('aria-expanded', 'false');
                openSubmenuId = null;
            }
        } else {
            sidebar.classList.add('translate-x-0');
            sidebar.classList.remove('-translate-x-full');
            overlay?.classList.remove('hidden');
            sidebar.style.opacity = '1';
        }
    };

    if (sidebarToggle && sidebar && overlay) {
        sidebarToggle.addEventListener('click', toggleSidebar);
        mobileBtn?.addEventListener('click', toggleSidebar);
        overlay?.addEventListener('click', toggleSidebar);
        document.querySelectorAll('nav a, nav button[type="submit"]').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    toggleSidebar();
                }
            });
        });
    }

    document.querySelectorAll('.submenu').forEach(submenu => {
        submenu.classList.add('hidden', 'opacity-0');
    });
});
</script>