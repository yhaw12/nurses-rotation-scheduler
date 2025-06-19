<nav class="flex flex-col h-full p-4">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-lg font-bold text-blue-600 hover:text-blue-800">Roster System</a>
    </div>
    <ul class="space-y-2 flex-1">
        <li>
            <a href="{{ route('dashboard') }}" class="block p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 {{ Route::is('dashboard') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                Dashboard
            </a>
        </li>
        @if(auth()->user()->is_admin)
            <li>
                <a href="{{ route('disciplines.index') }}" class="block p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 {{ Route::is('disciplines.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                    Manage Disciplines
                </a>
            </li>
        @endif
    </ul>
    <div class="mt-auto">
        <form action="{{ route('logout') }}" method="POST" class="block p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
            @csrf
            <button type="submit" class="w-full text-left">Logout</button>
        </form>
    </div>
</nav>