@extends('layouts.app')

@section('content')
<!-- Alpine only to manage the tag list, not to hijack the submit -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>

<div class="container mx-auto px-4 py-8" x-data="rosterForm()">
  <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-white">
      Create Roster for {{ $displayDiscipline }}
    </h1>

    <!-- **Plain** form: no @submit.prevent here -->
    <form action="{{ route('rosters.store') }}" method="POST" class="space-y-6">
      @csrf
      <input type="hidden" name="discipline" value="{{ $discipline }}">

      <!-- Student Tags -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Student Names</label>
        <div class="mt-1 flex flex-wrap gap-2">
          <template x-for="(name, i) in names" :key="i">
            <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-2 py-1 rounded-full flex items-center">
              <span x-text="name"></span>
              <button type="button" class="ml-1 text-blue-800 dark:text-blue-200 hover:text-blue-600 dark:hover:text-blue-400" @click="removeName(i)">×</button>
            </span>
          </template>
          <input
            x-ref="nameInput"
            x-model="newName"
            @keydown.enter.prevent="addName()"
            placeholder="Type a name + Enter"
            class="flex-1 border rounded px-2 py-1 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 dark:focus:ring-blue-400"
          />
        </div>
        @error('student_names') <p class="text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

        <!-- Hidden textarea for POST -->
        <textarea name="student_names" x-model="namesString" class="hidden"></textarea>
      </div>

      <!-- Dates -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
          <input
            type="date"
            name="start_date"
            x-model="startDate"
            required
            class="mt-1 block w-full border rounded p-1 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 dark:focus:ring-blue-400"
          />
          @error('start_date') <p class="text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
          <input
            type="date"
            name="end_date"
            x-model="endDate"
            required
            class="mt-1 block w-full border rounded p-1 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 dark:focus:ring-blue-400"
          />
          @error('end_date') <p class="text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>
      </div>

      <!-- Submit -->
      <div class="text-right">
        <button
          type="submit"
          class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 disabled:bg-gray-400 dark:disabled:bg-gray-600"
          :disabled="names.length === 0"
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
    names: [], newName: '',
    startDate: '{{ old("start_date", date("Y-m-d")) }}',
    endDate:   '{{ old("end_date", date("Y-m-d", strtotime("+1 year"))) }}',
    get namesString() { return this.names.join("\n"); },
    addName() {
      let n = this.newName.trim();
      if (n && !this.names.includes(n)) this.names.push(n);
      this.newName = '';
      this.$refs.nameInput.focus();
    },
    removeName(i) { this.names.splice(i,1) }
  }
}
</script>
@endsection