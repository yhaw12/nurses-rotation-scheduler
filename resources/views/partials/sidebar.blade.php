<aside id="sidebar" class="fixed top-0 left-0 w-64 h-screen bg-white dark:bg-gray-900 shadow-md transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-30 bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 flex flex-col">
    <div class="h-16 flex items-center justify-between p-4 border-b dark:border-gray-700 bg-white dark:bg-gray-900">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white tracking-tight">Roster System</h2>
        <button id="sidebar-toggle" class="md:hidden text-gray-500 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-1">
            <span class="icon-container">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" class="menu-icon" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" class="close-icon hidden" />
                </svg>
            </span>
        </button>
    </div>

    <nav class="relative flex-1 p-4 space-y-2 text-xl font-medium text-gray-600 dark:text-gray-300 overflow-y-auto flex flex-col border-red-500 ">
        <!-- Dashboard -->

       <div>
        <a href="{{ route('dashboard') }}"   
           class="flex items-center px-3 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 hover:scale-105 transition-all duration-200 ease-in-out {{ Route::is('dashboard') ? 'bg-blue-50 dark:bg-blue-900 text-blue-600 dark:text-blue-300' : '' }}"
           aria-current="{{ Route::is('dashboard') ? 'page' : 'false' }}">
            <span class="icon-container mr-2">
                <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24" width="16" height="16">
                    <path d="M12 2a10 10 0 110 20 10 10 0 010-20zm0 2a8 8 0 100 16 8 8 0 000-16zm0 3a1 1 0 011 1v4a1 1 0 01-2 0V8a1 1 0 011-1zm0 8a1 1 0 110 2 1 1 0 010-2z" />
                </svg>
            </span>
            Dashboard
        </a>

        <!-- MAKE A ROSTER -->
        <div>
            <button class="submenu-toggle flex items-center justify-between w-full px-3 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 hover:scale-105 transition-all duration-200 ease-in-out"
                    data-target="make-roster-submenu" aria-expanded="false">
                <div class="flex items-center hover:bg-gray-200  dark:hover:bg-blue-900">
                    <span class="icon-container mr-2">
                        <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path d="M17 3a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2h10zm-1 3H8a1 1 0 00-1 1v10a1 1 0 001 1h8a1 1 0 001-1V7a1 1 0 00-1-1zm-4 7h-2v2a1 1 0 01-2 0v-2H6a1 1 0 010-2h2V9a1 1 0 012 0v2h2a1 1 0 010 2z" />
                        </svg>
                    </span>
                    Make A Roster
                </div>
                <span class="icon-container">
                    <svg class="w-4 h-4 transform transition-transform duration-200 text-gray-500 dark:text-gray-400" data-arrow-for="make-roster-submenu" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="12" height="12">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </span>
            </button>
            <div id="make-roster-submenu" class="submenu hidden mt-2 ml-6 space-y-1 opacity-0 transition-opacity duration-300">
                @if(\App\Models\Discipline::whereIn('name', ['Registered General Nurses (RGN)', 'Midwives', 'Public Health Nurses'])->count() === 0)
                    <span class="block px-3 py-3 text-gray-400 dark:text-gray-500 text-xs">No disciplines available</span>
                @else
                    @foreach(\App\Models\Discipline::whereIn('name', ['Registered General Nurses (RGN)', 'Midwives', 'Public Health Nurses'])->get() as $discipline)
                        <a href="{{ route('rosters.create', ['discipline' => $discipline->name === 'Registered General Nurses (RGN)' ? 'rgn' : Str::slug($discipline->name)]) }}"
                           class="flex items-center px-3 py-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 hover:scale-105 transition-all duration-200 ease-in-out {{ request()->routeIs('disciplines.units.*') && request()->route('discipline')?->id === $discipline->id ? 'bg-blue-50 dark:bg-blue-900 text-blue-600 dark:text-blue-300' : '' }}">
                            <span class="icon-container mr-2">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                    <path d="M15 5a4 4 0 11-8 0 4 4 0 018 0zM3 20v-1a6 6 0 0112 0v1H3zm18 0v-1a6 6 0 00-9-5.197V20h9z" />
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
                <button class="submenu-toggle flex items-center justify-between w-full px-3 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 hover:scale-105 transition-all duration-200 ease-in-out"
                        data-target="manage-disciplines-submenu" aria-expanded="false">
                    <div class="flex items-center">
                        <span class="icon-container mr-2">
                            <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                <path d="M12 15.5a3.5 3.5 0 100-7 3.5 3.5 0 000 7zm0 2a5.5 5.5 0 110-11 5.5 5.5 0 010 11zm8.793-1.793l-1.414-1.414a1 1 0 010-1.414l1.414-1.414a1 1 0 011.414 0l1.414 1.414a1 1 0 010 1.414l-1.414 1.414a1 1 0 01-1.414 0zm-17.586 0a1 1 0 010-1.414l1.414-1.414a1 1 0 011.414 0l1.414 1.414a1 1 0 010 1.414l-1.414 1.414a1 1 0 01-1.414 0l-1.414-1.414z" />
                            </svg>
                        </span>
                        Manage Disciplines
                    </div>
                    <span class="icon-container">
                        <svg class="w-4 h-4 transform transition-transform duration-200 text-gray-500 dark:text-gray-400" data-arrow-for="manage-disciplines-submenu" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="12" height="12">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </button>
                <div id="manage-disciplines-submenu" class="submenu hidden mt-2 ml-6 space-y-1 opacity-0 transition-opacity duration-300">
                    @if(\App\Models\Discipline::whereIn('name', ['Registered General Nurses (RGN)', 'Midwives', 'Public Health Nurses'])->count() === 0)
                        <span class="block px-3 py-3 text-gray-400 dark:text-gray-500 text-xs">No disciplines available</span>
                    @else
                        @foreach(\App\Models\Discipline::whereIn('name', ['Registered General Nurses (RGN)', 'Midwives', 'Public Health Nurses'])->get() as $discipline)
                            <a href="{{ route('disciplines.units.index', $discipline) }}"
                               class="flex items-center px-3 py-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 hover:scale-105 transition-all duration-200 ease-in-out {{ request()->routeIs('disciplines.units.*') && request()->route('discipline')?->id === $discipline->id ? 'bg-blue-50 dark:bg-blue-900 text-blue-600 dark:text-blue-300' : '' }}">
                                <span class="icon-container mr-2">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                        <path d="M15 5a4 4 0 11-8 0 4 4 0 018 0zM3 20v-1a6 6 0 0112 0v1H3zm18 0v-1a6 6 0 00-9-5.197V20h9z" />
                                    </svg>
                                </span>
                                {{ $discipline->name }}
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        @endif
       </div>

       <div class="mt-12 bottom-0 absolute">
        <!-- Logout -->      
        <form method="POST" action="{{ route('logout') }}" class=" w-full border-t dark:border-gray-700 pt-4">
            @csrf    
            <button type="submit"
                    class="flex items-center w-full px-3 py-3 rounded-lg text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/50 hover:scale-105 transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500">
                <span class="icon-container mr-2">
                    <svg class="w-4 h-4 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 24 24" width="16" height="16">
                        <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zM4 5h8V3H4a1 1 0 00-1 1v16a1 1 0 001 1h8v-2H4V5z" />
                    </svg>
                </span>
                Logout
            </button>
        </form>
       </div>
    </nav>
</aside>

<style>
    /* Prevent SVGs from scaling with parent transforms */
    .icon-container {
        display: inline-block;
        transform: none !important;
    }
    /* Subtle icon hover animation */
    .icon-container svg {
        transition: transform 0.2s ease-in-out;
    }
    .flex.items-center:hover .icon-container svg {
        transform: scale(1.2);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const mobileBtn = document.getElementById('mobile-menu-button');
    const overlay = document.getElementById('sidebar-overlay');
    let openSubmenuId = null;

    const initializeSidebar = () => {
        if (window.innerWidth >= 768) {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            overlay?.classList.add('hidden');
            sidebar.style.opacity = '1';
            document.querySelector('.menu-icon')?.classList.remove('hidden');
            document.querySelector('.close-icon')?.classList.add('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            overlay?.classList.add('hidden');
            sidebar.style.opacity = '1';
            document.querySelector('.menu-icon')?.classList.remove('hidden');
            document.querySelector('.close-icon')?.classList.add('hidden');
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

    if (sidebarToggle && sidebar && overlay) {
        sidebarToggle.addEventListener('click', () => {
            const isOpen = sidebar.classList.contains('translate-x-0');
            if (isOpen) {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                overlay?.classList.add('hidden');
                document.querySelector('.menu-icon')?.classList.remove('hidden');
                document.querySelector('.close-icon')?.classList.add('hidden');
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
                document.querySelector('.menu-icon')?.classList.add('hidden');
                document.querySelector('.close-icon')?.classList.remove('hidden');
                sidebar.style.opacity = '1';
            }
        });
        mobileBtn?.addEventListener('click', () => {
            sidebar.classList.add('translate-x-0');
            sidebar.classList.remove('-translate-x-full');
            overlay?.classList.remove('hidden');
            document.querySelector('.menu-icon')?.classList.add('hidden');
            document.querySelector('.close-icon')?.classList.remove('hidden');
            sidebar.style.opacity = '1';
        });
        overlay?.addEventListener('click', () => {
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.querySelector('.menu-icon')?.classList.remove('hidden');
            document.querySelector('.close-icon')?.classList.add('hidden');
        });
        document.querySelectorAll('nav a, nav button[type="submit"]').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    sidebar.classList.remove('translate-x-0');
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                    document.querySelector('.menu-icon')?.classList.remove('hidden');
                    document.querySelector('.close-icon')?.classList.add('hidden');
                }
            });
        });
    }

    document.querySelectorAll('.submenu').forEach(submenu => {
        submenu.classList.add('hidden', 'opacity-0');
    });
});
</script>