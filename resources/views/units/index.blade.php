@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 space-y-8">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Manage Units and Subunits for {{ $discipline->name }}</h1>
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 p-4 rounded-lg mb-6 animate-fade-in">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-200 p-4 rounded-lg mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Add New Unit Form -->
    <div class="bg-gradient-to-r from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Add New Unit</h2>
        <form action="{{ route('disciplines.units.store', $discipline) }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Enter unit name">
                    @error('name')
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="duration_weeks" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (Weeks)</label>
                    <input type="number" name="duration_weeks" id="duration_weeks" value="{{ old('duration_weeks') }}" required min="1" class="mt-1 w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Weeks">
                    @error('duration_weeks')
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order') }}" required min="0" class="mt-1 w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Order">
                    @error('sort_order')
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition-all hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500">Add Unit</button>
        </form>
    </div>

    <!-- Units and Subunits List -->
    @if($units->isEmpty())
        <div class="bg-gradient-to-r from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-lg shadow-lg p-6 text-center">
            <p class="text-gray-600 dark:text-gray-300">No units found for this discipline. Add a new unit to get started.</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach($units as $unit)
                <div class="bg-gradient-to-r from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-lg shadow-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">{{ $unit->name }}</h2>
                        <div class="flex space-x-4">
                            <a href="{{ route('units.edit', [$discipline, $unit]) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('units.destroy', [$discipline, $unit]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this unit and its subunits?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0h4m-7 4h10" />
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Subunits Management -->
                    <div class="border-t dark:border-gray-700 pt-4">
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-200 mb-4">Subunits</h3>
                        <form action="{{ route('disciplines.units.subunits.update', [$discipline, $unit]) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            <div class="space-y-4" id="subunits-{{ $unit->id }}">
                                @foreach($unit->subunits as $subunit)
                                    <div class="flex flex-col sm:flex-row gap-4 subunit-row">
                                        <input type="hidden" name="subunits[{{ $loop->index }}][id]" value="{{ $subunit->id }}">
                                        <div class="flex-1">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subunit Name</label>
                                            <input type="text" name="subunits[{{ $loop->index }}][name]" value="{{ $subunit->name }}" required class="mt-1 w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                                            @error("subunits.{$loop->index}.name")
                                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="w-full sm:w-1/4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (Weeks)</label>
                                            <input type="number" name="subunits[{{ $loop->index }}][duration_weeks]" value="{{ $subunit->duration_weeks }}" required min="1" class="mt-1 w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                                            @error("subunits.{$loop->index}.duration_weeks")
                                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="w-full sm:w-1/6 flex items-end">
                                            <form action="{{ route('disciplines.units.subunits.destroy', [$discipline, $unit, $subunit]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this subunit?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0h4m-7 4h10" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex space-x-4">
                                <button type="button" onclick="addSubunitRow({{ $unit->id }})" class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white font-semibold py-2 px-6 rounded-lg transition-all hover:scale-105">Add Subunit</button>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition-all hover:scale-105">Save Subunits</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script>
function addSubunitRow(unitId) {
    const container = document.getElementById(`subunits-${unitId}`);
    const index = container.querySelectorAll('.subunit-row').length;
    const newRow = document.createElement('div');
    newRow.className = 'flex flex-col sm:flex-row gap-4 subunit-row animate-fade-in';
    newRow.innerHTML = `
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subunit Name</label>
            <input type="text" name="subunits[${index}][name]" required class="mt-1 w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Enter subunit name">
        </div>
        <div class="w-full sm:w-1/4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (Weeks)</label>
            <input type="number" name="subunits[${index}][duration_weeks]" required min="1" class="mt-1 w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Weeks">
        </div>
        <div class="w-full sm:w-1/6 flex items-end">
            <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0h4m-7 4h10" />
                </svg>
                Remove
            </button>
        </div>
    `;
    container.appendChild(newRow);
}
</script>
<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush
@endsection