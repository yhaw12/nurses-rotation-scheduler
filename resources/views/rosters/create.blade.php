@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center">Create Roster for {{ ucwords(str_replace('-', ' ', $discipline)) }}</h1>

        <form action="{{ route('rosters.store') }}" method="POST" id="rosterForm" class="space-y-6">
            @csrf
            <input type="hidden" name="discipline" value="{{ $discipline }}">
            <input type="hidden" name="student_names" id="student_names_hidden">

            <!-- Student Names -->
            <div>
                <label for="student_name_input" class="block text-sm font-medium text-gray-700">Student Names</label>
                <input type="text" name="student_name_input" id="student_name_input" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Type a student name and press Enter">
                <div id="student_list" class="mt-2 space-y-2"></div>
                @error('student_names')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Start Date -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('start_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- End Date -->
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ date('Y-m-d', strtotime('+1 year')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('end_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Schedule Button -->
            <div class="text-center">
                <button type="submit" class="bg-blue-600 text-white py-3 px-6 rounded-md hover:bg-blue-700 transition duration-200 font-semibold">
                    Schedule
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('student_name_input');
        const list = document.getElementById('student_list');
        const hiddenInput = document.getElementById('student_names_hidden');

        input.addEventListener('keypress', function (e) {
            if (e.key === 'Enter' && input.value.trim() !== '') {
                e.preventDefault();
                const name = input.value.trim();
                addStudentName(name);
                input.value = '';
            }
        });

        function addStudentName(name) {
            const div = document.createElement('div');
            div.className = 'flex items-center justify-between bg-gray-100 p-2 rounded';
            div.innerHTML = `
                <span>${name}</span>
                <button type="button" class="ml-2 text-red-500 hover:text-red-700" onclick="removeStudentName(this, '${name}')">×</button>
            `;
            list.appendChild(div);

            // Update hidden input with all names
            const names = Array.from(list.getElementsByTagName('span')).map(span => span.textContent);
            hiddenInput.value = names.join('\n');
        }

        window.removeStudentName = function (button, name) {
            const div = button.parentElement;
            div.remove();
            const names = Array.from(list.getElementsByTagName('span')).map(span => span.textContent);
            hiddenInput.value = names.join('\n');
        };
    });
</script>
@endsection