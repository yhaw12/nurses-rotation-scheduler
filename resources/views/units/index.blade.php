@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Manage Units for {{ $discipline->name }}</h1>
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center">
            <span class="icon-container mr-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7" />
                </svg>
            </span>
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

    @if($units->isEmpty())
        <div class="bg-gradient-to-r from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-lg shadow-lg p-6 text-center">
            <p class="text-gray-600 dark:text-gray-300">No units found for this discipline. Add a new unit to get started.</p>
            <a href="{{ route('disciplines.units.create', $discipline) }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition-all hover:scale-105">Add Unit</a>
        </div>
    @else
        <div class="bg-gradient-to-r from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-lg shadow-lg p-6">
            <!-- Tabs -->
            <div class="border-b dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-gray-600 dark:text-gray-300" role="tablist">
                    @foreach($units as $unit)
                        <li class="mr-2" role="presentation">
                            <button class="inline-flex items-center px-4 py-2 rounded-t-lg border-b-2 {{ $loop->first ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent hover:text-blue-600 hover:border-blue-500 dark:hover:text-blue-400 dark:hover:border-blue-400' }} transition-all"
                                    id="unit-tab-{{ $unit->id }}"
                                    data-tabs-target="unit-panel-{{ $unit->id }}"
                                    type="button"
                                    role="tab"
                                    aria-controls="unit-panel-{{ $unit->id }}"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                <span class="icon-container mr-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                </span>
                                {{ $unit->name }}
                            </button>
                        </li>
                    @endforeach
                    <li role="presentation">
                        <button class="inline-flex items-center px-4 py-2 rounded-t-lg border-b-2 border-transparent hover:text-blue-600 hover:border-blue-500 dark:hover:text-blue-400 dark:hover:border-blue-400 transition-all"
                                id="add-unit-tab"
                                data-tabs-target="add-unit-panel"
                                type="button"
                                role="tab"
                                aria-controls="add-unit-panel"
                                aria-selected="false">
                            <span class="icon-container mr-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                                </svg>
                            </span>
                            Add Unit
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Tab Panels -->
            <div class="mt-4">
                @foreach($units as $unit)
                    <div class="{{ $loop->first ? '' : 'hidden' }} p-4 rounded-lg bg-white/80 dark:bg-gray-800/80" id="unit-panel-{{ $unit->id }}" role="tabpanel" aria-labelledby="unit-tab-{{ $unit->id }}">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">{{ $unit->name }}</h2>
                            <div class="flex space-x-4">
                                <a href="{{ route('units.edit', [$discipline, $unit]) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center">
                                    <span class="icon-container mr-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </span>
                                    Edit
                                </a>
                                <form action="{{ route('units.destroy', [$discipline, $unit]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this unit and its subunits?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 flex items-center">
                                        <span class="icon-container mr-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0h4m-7 4h10" />
                                            </svg>
                                        </span>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Subunits Management -->
                        <div class="border-t dark:border-gray-700 pt-4">
                            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-200 mb-4">Subunits</h3>
                            <form action="{{ route('disciplines.units.subunits.update', [$discipline, $unit]) }}" method="POST" class="space-y-4" id="subunit-form-{{ $unit->id }}">
                                @csrf
                                @method('PATCH')
                                <div class="grid grid-cols-1 gap-4" id="subunits-{{ $unit->id }}">
                                    @foreach($unit->subunits as $subunit)
                                        <div class="subunit-row bg-white dark:bg-gray-700 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-600 animate-fade-in">
                                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                                                <input type="hidden" name="subunits[{{ $loop->index }}][id]" value="{{ $subunit->id }}">
                                                <div class="sm:col-span-6">
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subunit Name</label>
                                                    <input type="text" name="subunits[{{ $loop->index }}][name]" value="{{ $subunit->name }}" required class="mt-1 w-full p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all subunit-input" data-original-value="{{ $subunit->name }}">
                                                    @error("subunits.{$loop->index}.name")
                                                        <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="sm:col-span-3">
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (Weeks)</label>
                                                    <input type="number" name="subunits[{{ $loop->index }}][duration_weeks]" value="{{ $subunit->duration_weeks }}" required min="1" class="mt-1 w-full p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all subunit-input" data-original-value="{{ $subunit->duration_weeks }}">
                                                    @error("subunits.{$loop->index}.duration_weeks")
                                                        <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="sm:col-span-3 flex items-end justify-end">
                                                    <button type="button" onclick="deleteSubunit(this, {{ $subunit->id }}, {{ $discipline->id }}, {{ $unit->id }})" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 flex items-center" aria-label="Delete subunit {{ $subunit->name }}">
                                                        <span class="icon-container mr-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0h4m-7 4h10" />
                                                            </svg>
                                                        </span>
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="delete_subunit_id" id="delete-subunit-id-{{ $unit->id }}">
                                <div class="flex space-x-4 mt-6">
                                    <button type="button" onclick="addSubunitRow({{ $unit->id }})" class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition-all hover:scale-105 flex items-center">
                                        <span class="icon-container mr-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </span>
                                        Add Subunit
                                    </button>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition-all hover:scale-105">Save Subunits</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
                <div class="hidden p-4 rounded-lg bg-white/80 dark:bg-gray-800/80" id="add-unit-panel" role="tabpanel" aria-labelledby="add-unit-tab">
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
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function addSubunitRow(unitId) {
    const container = document.getElementById(`subunits-${unitId}`);
    const index = container.querySelectorAll('.subunit-row').length;
    const newRow = document.createElement('div');
    newRow.className = 'subunit-row bg-white dark:bg-gray-700 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-600 animate-slide-in';
    newRow.innerHTML = `
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
            <div class="sm:col-span-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subunit Name</label>
                <input type="text" name="subunits[${index}][name]" required class="mt-1 w-full p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all subunit-input" placeholder="Enter subunit name">
            </div>
            <div class="sm:col-span-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (Weeks)</label>
                <input type="number" name="subunits[${index}][duration_weeks]" required min="1" class="mt-1 w-full p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all subunit-input" placeholder="Weeks">
            </div>
            <div class="sm:col-span-3 flex items-end justify-end space-x-2">
                <button type="button" onclick="this.closest('.subunit-row').remove()" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300 flex items-center" aria-label="Cancel new subunit">
                    <span class="icon-container mr-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </span>
                    Cancel
                </button>
            </div>
        </div>
    `;
    container.appendChild(newRow);
}

function deleteSubunit(button, subunitId, disciplineId, unitId) {
    if (confirm('Are you sure you want to delete this subunit?')) {
        const form = button.closest('form');
        const deleteInput = document.getElementById(`delete-subunit-id-${unitId}`);
        deleteInput.value = subunitId;
        form.action = `/disciplines/${disciplineId}/units/${unitId}/subunits/${subunitId}`;
        form.innerHTML += `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        form.submit();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('[role="tab"]');
    const panels = document.querySelectorAll('[role="tabpanel"]');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => {
                t.classList.remove('border-blue-500', 'text-blue-600', 'dark:border-blue-400', 'dark:text-blue-400');
                t.classList.add('border-transparent', 'hover:text-blue-600', 'hover:border-blue-500', 'dark:hover:text-blue-400', 'dark:hover:border-blue-400');
                t.setAttribute('aria-selected', 'false');
            });
            panels.forEach(p => p.classList.add('hidden'));

            tab.classList.add('border-blue-500', 'text-blue-600', 'dark:border-blue-400', 'dark:text-blue-400');
            tab.classList.remove('border-transparent', 'hover:text-blue-600', 'hover:border-blue-500', 'dark:hover:text-blue-400', 'dark:hover:border-blue-400');
            tab.setAttribute('aria-selected', 'true');
            const panelId = tab.getAttribute('data-tabs-target');
            document.getElementById(panelId).classList.remove('hidden');
        });
    });

    // Highlight modified inputs
    document.querySelectorAll('.subunit-input').forEach(input => {
        input.addEventListener('input', () => {
            const originalValue = input.getAttribute('data-original-value');
            if (input.value !== originalValue) {
                input.classList.add('border-yellow-500', 'dark:border-yellow-400');
            } else {
                input.classList.remove('border-yellow-500', 'dark:border-yellow-400');
            }
        });
    });
});
</script>
<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-in {
        animation: slideIn 0.3s ease-in-out;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .icon-container {
        display: inline-block;
        transform: none !important;
    }
    svg {
        width: inherit !important;
        height: inherit !important;
        min-width: 0 !important;
        min-height: 0 !important;
        max-width: 100% !important;
        max-height: 100% !important;
    }
</style>
@endpush
@endsection