<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Roster System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/print.css') }}" media="print">
    <style>
        @media (max-width: 768px) {
            .sidebar.expanded { transform: translateX(0); }
        }
        @media print {
            header, aside, .sidebar, #sidebar-overlay { display: none !important; }
            main { width: 100% !important; padding: 0 !important; overflow: hidden !important; }
            body { overflow: hidden !important; }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 dark:bg-[#0a0a23] dark:text-white font-sans">
    @auth
        <div class="flex h-screen relative">
            <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden md:hidden"></div>
            <aside class="sidebar w-64 bg-gray-100 dark:bg-gray-900 shadow-md h-screen fixed top-0 left-0 transform -translate-x-full md:translate-x-0 md:static z-20 transition-transform duration-300 ease-in-out">
                @include('partials.sidebar')
            </aside>
            <div class="flex-1 flex flex-col">
                <header class="bg-white shadow-md dark:bg-[#0a0a23] dark:text-white">
                    <nav class="p-4">
                        <div class="container mx-auto flex items-center gap-6">
                            <button id="mobile-menu-button" class="md:hidden p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('disciplines.index') }}" class="hidden md:block text-blue-600 hover:text-blue-800 font-semibold">Disciplines</a>
                            @endif
                            <div class="flex-1"></div>
                            <button id="theme-toggle" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition" aria-label="Toggle dark mode">
                                <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.293 13.293A8 8 0 116.707 2.707a6 6 0 1010.586 10.586z"/>
                                </svg>
                                <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4.22 2.03a1 1 0 10-1.44 1.44l.7.7a1 1 0 101.44-1.44l-.7-.7zM18 9a1 1 0 110 2h-1a1 1 0 110-2h1zM14.22 15.97a1 1 0 00-1.44-1.44l-.7.7a1 1 0 101.44 1.44l.7-.7zM11 18a1 1 0 10-2 0v-1a1 1 0 102 0v1zM6.78 15.97l-.7-.7a1 1 0 10-1.44 1.44l.7.7a1 1 0 001.44-1.44zM4 9a1 1 0 100 2H3a1 1 0 100-2h1zM6.78 4.03l.7.7a1 1 0 101.44-1.44l-.7-.7a1 1 0 00-1.44 1.44zM10 5a5 5 0 100 10A5 5 0 0010 5z"/>
                                </svg>
                            </button>
                            <div class="text-gray-600 font-medium text-sm flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Logged in as: <span class="text-blue-800 font-semibold">{{ auth()->user()->is_admin ? 'Admin' : 'User' }}</span>
                            </div>
                        </div>
                    </nav>
                </header>
                <main class="flex-1 overflow-y-auto container mx-auto px-4 py-6">
                    @yield('content')
                </main>
            </div>
        </div>
    @endauth
    @guest
        <main class="min-h-screen flex items-center justify-center">
            @yield('content')
        </main>
    @endguest
    <script src="{{ asset('js/chart.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.onerror = function(message, source, lineno, colno, error) { console.error(`Error: ${message} at ${source}:${lineno}:${colno}`, error); };
        (function() {
            const themeToggle = document.getElementById('theme-toggle');
            const iconMoon = document.getElementById('icon-moon');
            const iconSun = document.getElementById('icon-sun');
            const root = document.documentElement;
            const stored = localStorage.getItem('darkMode');
            const mobileBtn = document.getElementById('mobile-menu-button');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            let isDark = stored === 'true' ? true : (stored === 'false' ? false : window.matchMedia('(prefers-color-scheme: dark)').matches);
            const applyTheme = () => { if (isDark) { root.classList.add('dark'); iconMoon.classList.add('hidden'); iconSun.classList.remove('hidden'); } else { root.classList.remove('dark'); iconSun.classList.add('hidden'); iconMoon.classList.remove('hidden'); } };
            applyTheme();
            if (themeToggle) themeToggle.addEventListener('click', () => { isDark = !isDark; localStorage.setItem('darkMode', isDark); applyTheme(); });
            if (mobileBtn && sidebar && overlay) { mobileBtn.addEventListener('click', () => { sidebar.classList.toggle('expanded'); overlay.classList.toggle('hidden'); }); overlay.addEventListener('click', () => { sidebar.classList.remove('expanded'); overlay.classList.add('hidden'); if (window.expandedSubmenu) { const prevSubmenu = document.getElementById(window.expandedSubmenu); const prevBtn = document.querySelector(`[data-target="${window.expandedSubmenu}"]`); if (prevSubmenu && prevBtn) { prevSubmenu.classList.add('hidden'); prevBtn.querySelector('.plus-icon')?.classList.remove('hidden'); prevBtn.querySelector('.minus-icon')?.classList.add('hidden'); window.expandedSubmenu = null; } } }); }
        })();
    </script>
</body>
</html>