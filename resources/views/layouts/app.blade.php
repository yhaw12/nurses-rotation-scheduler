<!DOCTYPE html>
<html lang="en" class="{{ session('darkMode', false) ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Roster System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/print.css') }}" media="print">
    <style>
        @media (max-width: 768px) {
            .sidebar.expanded {
                transform: translateX(0);
                z-index: 30;
            }
            .sidebar {
                z-index: 30;
            }
        }
        @media print {
            .a4-container {
                width: 1084px !important;
                height: 760px !important;
                margin: 0 40px !important;
                padding: 0 !important;
                overflow: visible !important;
                transform-origin: top left !important;
            }
            header, aside, .sidebar, #sidebar-overlay, #mobile-menu-button, #theme-toggle {
                display: none !important;
            }
            main {
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
                overflow: visible !important;
            }
            body {
                overflow: hidden !important;
                margin: 0 !important;
            }
            .print-content {
                width: 100% !important;
                height: auto !important;
                overflow: visible !important;
            }
            .print-content table {
                width: 100% !important;
                border-collapse: collapse !important;
            }
            .print-content th, .print-content td {
                border: 1px solid #000 !important;
                padding: 1px !important;
                font-size: 8pt !important;
                vertical-align: top !important;
                line-height: 1.0 !important;
            }
            .print-content .text-center {
                text-align: center !important;
            }
            .print-content th:nth-child(1), .print-content td:nth-child(1) { width: 20% !important; }
            .print-content th:nth-child(2), .print-content td:nth-child(2) { width: 8% !important; }
            .print-content th:nth-child(3), .print-content td:nth-child(3) { width: 8% !important; }
            .print-content th:nth-child(4), .print-content td:nth-child(4) { width: 8% !important; }
            .print-content th:nth-child(5), .print-content td:nth-child(5) { width: 16% !important; }
            .print-content th:nth-child(6), .print-content td:nth-child(6) { width: 10% !important; }
            .print-content th:nth-child(7), .print-content td:nth-child(7) { width: 30% !important; }
            .print-content table table {
                width: 100% !important;
                border: none !important;
            }
            .print-content table table td {
                border: none !important;
                padding: 0 !important;
                font-size: 8pt !important;
                line-height: 1.0 !important;
            }
            h2 {
                font-size: 10pt !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .print-content .text-lg {
                font-size: 9pt !important;
                margin: 0 !important;
            }
            .print-content .text-sm {
                font-size: 7pt !important;
                margin: 0 !important;
            }
            .print-content .mb-1 {
                margin-bottom: 1px !important;
            }
            .h-5 {
                height: 18px !important;
            }
            @page { size: A4 landscape; margin: 0.2cm; }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-white font-sans">
    <!-- Loading Overlay -->
    {{-- <div id="loading-overlay" class="fixed inset-0 bg-white dark:bg-gray-900 flex items-center justify-center z-50">
        <div class="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div> --}}

    @auth
        <div class="flex h-screen relative">
            <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden"></div>
            <aside class="sidebar w-64 bg-gray-100 dark:bg-gray-800 shadow-md h-screen fixed top-0 left-0 transform -translate-x-full md:translate-x-0 md:static z-30 transition-transform duration-300 ease-in-out">
                @include('partials.sidebar')
            </aside>
            <div class="flex-1 flex flex-col">
                <header class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-10">
                    <nav class="p-4">
                        <div class="container mx-auto flex items-center gap-6">
                            <button id="mobile-menu-button" class="md:hidden p-2 text-gray-700 dark:text-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('disciplines.index') }}" class="hidden md:block text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold">Disciplines</a>
                            @endif
                            <div class="flex-1"></div>
                            <button id="theme-toggle" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition" aria-label="Toggle dark mode">
                                <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700 dark:text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.293 13.293A8 8 0 116.707 2.707a6 6 0 1010.586 10.586z"/>
                                </svg>
                                <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5 text-gray-700 dark:text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4.22 2.03a1 1 0 10-1.44 1.44l.7.7a1 1 0 101.44-1.44l-.7-.7zM18 9a1 1 0 110 2h-1a1 1 0 110-2h1zM14.22 15.97a1 1 0 00-1.44-1.44l-.7.7a1 1 0 101.44 1.44l.7-.7zM11 18a1 1 0 10-2 0v1zM6.78 15.97l-.7-.7a1 1 0 10-1.44 1.44l.7.7a1 1 0 001.44-1.44zM4 9a1 1 0 100 2H3a1 1 0 100-2h1zM6.78 4.03l.7.7a1 1 0 101.44-1.44l-.7-.7a1 1 0 00-1.44 1.44zM10 5a5 5 0 100 10A5 5 0 0010 5z"/>
                                </svg>
                            </button>
                            <div class="text-gray-600 dark:text-gray-200 font-medium text-sm flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Logged in as: <span class="text-blue-800 dark:text-blue-400 font-semibold">{{ auth()->user()->is_admin ? 'Admin' : 'User' }}</span>
                            </div>
                        </div>
                    </nav>
                </header>
                <main class="flex-1 overflow-y-auto container mx-auto px-4 py-6 bg-gray-100 dark:bg-gray-900">
                    @yield('content')
                </main>
            </div>
        </div>
    @endauth

    @guest
        <main class="flex-1 overflow-y-auto container mx-auto px-4 py-6 bg-gray-100 dark:bg-gray-900">
            @yield('content')
        </main>
    @endguest

    <!-- Scripts -->
    <script src="{{ asset('js/chart.min.js') }}"></script> 

    @auth
        <script>
            (function() {
                const themeToggle = document.getElementById('theme-toggle');
                const iconMoon = document.getElementById('icon-moon');
                const iconSun = document.getElementById('icon-sun');
                const root = document.documentElement;
                const stored = localStorage.getItem('darkMode');
                const mobileBtn = document.getElementById('mobile-menu-button');
                const sidebar = document.querySelector('.sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                const loadingOverlay = document.getElementById('loading-overlay'); // Define loadingOverlay
                let isDark = stored === 'true' ? true : (stored === 'false' ? false : window.matchMedia('(prefers-color-scheme: dark)').matches);

                const applyTheme = () => {
                    if (isDark) {
                        root.classList.add('dark');
                        iconMoon.classList.add('hidden');
                        iconSun.classList.remove('hidden');
                    } else {
                        root.classList.remove('dark');
                        iconSun.classList.add('hidden');
                        iconMoon.classList.remove('hidden');
                    }
                };

                applyTheme();

                if (themeToggle) {
                    themeToggle.addEventListener('click', () => {
                        isDark = !isDark;
                        localStorage.setItem('darkMode', isDark);
                        applyTheme();
                    });
                }

                if (mobileBtn && sidebar && overlay) {
                    mobileBtn.addEventListener('click', () => {
                        sidebar.classList.toggle('expanded');
                        overlay.classList.toggle('hidden');
                    });
                    overlay.addEventListener('click', () => {
                        sidebar.classList.remove('expanded');
                        overlay.classList.add('hidden');
                    });
                }

                // Hide loading overlay after page load
                if (loadingOverlay) {
                    window.addEventListener('load', () => {
                        loadingOverlay.style.display = 'none';
                    });
                }
            })();
        </script>
    @endauth
    @stack('scripts')
</body>
</html>