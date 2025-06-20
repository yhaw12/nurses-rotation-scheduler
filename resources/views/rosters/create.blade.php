@extends('layouts.app')

@section('content')
<script src="https://unpkg.com/alpinejs" defer></script>

<div class="container mx-auto px-4 py-8" x-data="rosterForm()">
  <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6 text-center">
      Create Roster for {{ ucwords(str_replace('-', ' ', $discipline)) }}
    </h1>

    <form 
      action="{{ route('rosters.store') }}" 
      method="POST" 
      @submit="prepareNames()" 
      class="space-y-6"
    >
      @csrf
      <input type="hidden" name="discipline" value="{{ $discipline }}">

      {{-- Student Names as tags --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Student Names</label>
        <div class="mt-1 flex flex-wrap gap-2">
          <template x-for="(name, idx) in names" :key="idx">
            <span class="flex items-center bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
              <span x-text="name"></span>
              <button type="button" class="ml-1 text-blue-600 hover:text-blue-900" @click="removeName(idx)">×</button>
            </span>
          </template>
          <input
            x-ref="nameInput"
            x-model="newName"
            @keydown.enter.prevent="addName()"
            placeholder="Type a name and press Enter"
            class="flex-1 border rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-300"
          />
        </div>
        @error('student_names')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <textarea name="student_names" x-model="namesString" class="hidden"></textarea>
      </div>

      {{-- Dates --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Start Date</label>
          <input
            type="date"
            name="start_date"
            x-model="startDate"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500"
          />
          @error('start_date')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">End Date</label>
          <input
            type="date"
            name="end_date"
            x-model="endDate"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500"
          />
          @error('end_date')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>
      </div>

      {{-- Submit Button --}}
      <div class="flex justify-end">
        <button
          type="submit"
          class="bg-blue-600 text-white py-2 px-6 rounded hover:bg-blue-700"
        >
          Schedule
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function rosterForm() {
    return {
      names: [],
      newName: '',
      startDate: '{{ old("start_date", date("Y-m-d")) }}',
      endDate: '{{ old("end_date", date("Y-m-d", strtotime("+1 year"))) }}',
      get namesString() {
        return this.names.join("\n");
      },
      addName() {
        let n = this.newName.trim();
        if (!n) return;
        if (!this.names.includes(n)) {
          this.names.push(n);
        }
        this.newName = '';
        this.$refs.nameInput.focus();
      },
      removeName(i) {
        this.names.splice(i, 1);
      },
      prepareNames() {
        if (this.names.length === 0) {
          alert("Please add at least one student name.");
          event.preventDefault();
        }
      }
    }
  }
</script>
@endsection