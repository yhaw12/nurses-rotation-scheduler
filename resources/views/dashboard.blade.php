@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 space-y-8 h-full overflow-auto">
    <h1 class="text-4xl font-bold text-center text-gray-800 dark:text-gray-100 mb-6">Roster System Dashboard</h1>




    {{-- CARDS FOR DISCIPLINES --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('rosters.create', ['discipline' => 'rgn']) }}" class="block bg-white/80 dark:bg-gray-800/80 p-6 rounded-lg shadow-lg backdrop-blur-sm hover:bg-blue-100 dark:hover:bg-gray-700 transition duration-200 transform hover:-translate-y-1">
            <div class="flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600 dark:text-blue-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">RGN</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300 text-center">Create roster for Registered General Nurses</p>
            </div>
        </a>
        <a href="{{ route('rosters.create', ['discipline' => 'midwives']) }}" class="block bg-white/80 dark:bg-gray-800/80 p-6 rounded-lg shadow-lg backdrop-blur-sm hover:bg-blue-100 dark:hover:bg-gray-700 transition duration-200 transform hover:-translate-y-1">
            <div class="flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600 dark:text-blue-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Midwives</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300 text-center">Create roster for Midwives</p>
            </div>
        </a>
        <a href="{{ route('rosters.create', ['discipline' => 'public-health-nurses']) }}" class="block bg-white/80 dark:bg-gray-800/80 p-6 rounded-lg shadow-lg backdrop-blur-sm hover:bg-blue-100 dark:hover:bg-gray-700 transition duration-200 transform hover:-translate-y-1">
            <div class="flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600 dark:text-blue-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Public Health Nurses</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300 text-center">Create roster for Public Health Nurses</p>
            </div>
        </a>
    </div>



    {{-- INFO --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white/80 dark:bg-gray-800/80 p-6 rounded-lg shadow-lg backdrop-blur-sm transition duration-200 hover:shadow-xl">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Total Rosters</h2>
            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $rosters->count() ?? 0 }}</p>
        </div>
        <div class="bg-white/80 dark:bg-gray-800/80 p-6 rounded-lg shadow-lg backdrop-blur-sm transition duration-200 hover:shadow-xl">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Disciplines</h2>
            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ \App\Models\Discipline::count() }}</p>
        </div>
        <div class="bg-white/80 dark:bg-gray-800/80 p-6 rounded-lg shadow-lg backdrop-blur-sm transition duration-200 hover:shadow-xl">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Active Students</h2>
            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $activeStudents ?? 0 }}</p>
        </div>
    </div>

    
    {{-- FILTER --}}
    <div class="bg-white/80 dark:bg-gray-800/80 p-6 rounded-lg shadow-lg backdrop-blur-sm mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Filter Rosters</h2>
        <div class="flex flex-col sm:flex-row gap-4">
            <input type="text" id="searchInput" placeholder="Search by discipline, creator, or student..." class="w-full sm:w-1/3 p-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Search rosters">
            <select id="disciplineFilter" class="w-full sm:w-1/3 p-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Filter by discipline">
                <option value="">All Disciplines</option>
                <option value="Registered General Nurses (RGN)">RGN</option>
                <option value="Midwives">Midwives</option>
                <option value="Public Health Nurses">Public Health Nurses</option>
            </select>
            <select id="dateFilter" class="w-full sm:w-1/3 p-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Filter by date">
                <option value="">All Dates</option>
                <option value="active">Active Rosters</option>
                <option value="expired">Expired Rosters</option>
            </select>
        </div>
    </div>
    
    
    {{-- TABLE --}}
    <div class="bg-white/80 dark:bg-gray-800/80 p-6 rounded-lg shadow-lg backdrop-blur-sm">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Existing Rosters</h2>
            <div class="flex space-x-2">
                @if(auth()->user()->is_admin)
                    <a href="{{ route('disciplines.index') }}" class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Manage Disciplines</a>
                @endif
                <button id="exportCsv" class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Export CSV</button>
            </div>
        </div>
        @if(empty($rosters) || $rosters->isEmpty())
            <p class="text-gray-600 dark:text-gray-300 text-center">No rosters found. Create a new roster to get started.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm" id="rosterTable">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700">
                            <th class="border p-2 text-left cursor-pointer" onclick="sortTable(0)">ID <span class="sort-icon">↕</span></th>
                            <th class="border p-2 text-left cursor-pointer" onclick="sortTable(1)">Discipline <span class="sort-icon">↕</span></th>
                            <th class="border p-2 text-left cursor-pointer" onclick="sortTable(2)">Date Range <span class="sort-icon">↕</span></th>
                            <th class="border p-2 text-left cursor-pointer" onclick="sortTable(3)">Created By <span class="sort-icon">↕</span></th>
                            <th class="border p-2 text-left">Status</th>
                            <th class="border p-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rosters->take(10) as $roster)
                            <tr class="hover:bg-blue-50 dark:hover:bg-gray-600 transition duration-200">
                                <td class="border p-2 text-gray-800 dark:text-gray-200">{{ $roster->id }}</td>
                                <td class="border p-2 text-gray-800 dark:text-gray-200">{{ $roster->discipline->name ?? 'Unknown' }}</td>
                                <td class="border p-2 text-gray-800 dark:text-gray-200">
                                    {{ \Carbon\Carbon::parse($roster->start_date)->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($roster->end_date)->format('d/m/Y') }}
                                </td>
                                <td class="border p-2 text-gray-800 dark:text-gray-200">{{ $roster->createdBy->name ?? 'Unknown' }}</td>
                                <td class="border p-2">
                                    @php
                                        $today = \Carbon\Carbon::today();
                                        $endDate = \Carbon\Carbon::parse($roster->end_date);
                                        $status = $endDate->isPast() ? 'Expired' : 'Active';
                                    @endphp
                                    <span class="{{ $status === 'Active' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">{{ $status }}</span>
                                </td>
                                <td class="border p-2">
                                    <a href="{{ route('rosters.show', $roster) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($rosters->count() > 10)
                    <div class="mt-4 flex justify-center">
                        <button id="showMore" class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Show More</button>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script defer>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const disciplineFilter = document.getElementById('disciplineFilter');
    const dateFilter = document.getElementById('dateFilter');
    const rosterTable = document.getElementById('rosterTable');

    let debounceTimeout;
    function filterTable() {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            const searchTerm = searchInput?.value.toLowerCase().trim() || '';
            const discipline = disciplineFilter?.value.toLowerCase() || '';
            const date = dateFilter?.value || '';
            const rows = rosterTable?.querySelectorAll('tbody tr') || [];
            const today = new Date();
            const rosterStudents = allRows.reduce((acc, roster) => {
                acc[roster.id] = roster.student_names?.map(name => name.toLowerCase().trim()) || [];
                return acc;
            }, {});

            rows.forEach(row => {
                const rosterId = row.cells[0]?.textContent.trim() || '';
                const disciplineText = row.cells[1]?.textContent.toLowerCase().trim() || '';
                const creator = row.cells[3]?.textContent.toLowerCase().trim() || '';
                const dateRange = row.cells[2]?.textContent || '';
                const endDateParts = dateRange.split(' - ')[1]?.split('/') || [];
                const endDate = endDateParts.length === 3 ? new Date(`${endDateParts[2]}-${endDateParts[1]}-${endDateParts[0]}`) : null;
                let show = true;

                if (searchTerm) {
                    const students = rosterStudents[rosterId] || [];
                    if (!disciplineText.includes(searchTerm) && !creator.includes(searchTerm) && !students.some(student => student.includes(searchTerm))) {
                        show = false;
                    }
                }
                if (discipline && !disciplineText.includes(discipline)) {
                    show = false;
                }
                if (date === 'active' && endDate && endDate < today) {
                    show = false;
                }
                if (date === 'expired' && endDate && endDate >= today) {
                    show = false;
                }

                row.style.display = show ? '' : 'none';
            });
        }, 300);
    }

    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (disciplineFilter) disciplineFilter.addEventListener('change', filterTable);
    if (dateFilter) dateFilter.addEventListener('change', filterTable);

    let sortDirection = {};
    function sortTable(colIndex) {
        if (!rosterTable) return;
        const tbody = rosterTable.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const key = colIndex === 0 ? 'id' : colIndex === 1 ? 'discipline' : colIndex === 2 ? 'date' : 'creator';
        sortDirection[key] = !sortDirection[key];

        rows.sort((a, b) => {
            let aValue = a.cells[colIndex]?.textContent.trim() || '';
            let bValue = b.cells[colIndex]?.textContent.trim() || '';
            if (colIndex === 0) {
                aValue = parseInt(aValue) || 0;
                bValue = parseInt(bValue) || 0;
            } else if (colIndex === 2) {
                const aDate = aValue.split(' - ')[0]?.split('/') || [];
                const bDate = bValue.split(' - ')[0]?.split('/') || [];
                aValue = aDate.length === 3 ? new Date(`${aDate[2]}-${aDate[1]}-${aDate[0]}`) : new Date(0);
                bValue = bDate.length === 3 ? new Date(`${bDate[2]}-${bDate[1]}-${bDate[0]}`) : new Date(0);
            }
            if (sortDirection[key]) {
                return aValue > bValue ? 1 : -1;
            } else {
                return aValue < bValue ? 1 : -1;
            }
        });

        tbody.innerHTML = '';
        rows.forEach(row => tbody.appendChild(row));

        document.querySelectorAll('.sort-icon')?.forEach(icon => icon.textContent = '↕');
        const sortIcon = document.querySelector(`th:nth-child(${colIndex + 1}) .sort-icon`);
        if (sortIcon) sortIcon.textContent = sortDirection[key] ? '↑' : '↓';
    }

    document.querySelectorAll('#rosterTable th:not(:last-child):not(:nth-child(5))').forEach((th, index) => {
        th.classList.add('sortable');
        th.addEventListener('click', () => sortTable(index));
    });

    function exportToCSV() {
        const rows = rosterTable?.querySelectorAll('tr') || [];
        let csvContent = 'data:text/csv;charset=utf-8,';
        csvContent += 'ID,Discipline,Date Range,Created By,Status\n';
        
        rows.forEach((row, index) => {
            if (index === 0) return;
            const cols = row.querySelectorAll('td');
            if (cols.length < 5) return;
            const rowData = [
                cols[0].textContent,
                `"${cols[1].textContent}"`,
                `"${cols[2].textContent}"`,
                `"${cols[3].textContent}"`,
                `"${cols[4].textContent}"`
            ].join(',');
            csvContent += rowData + '\n';
        });

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', 'rosters.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    document.getElementById('exportCsv')?.addEventListener('click', exportToCSV);

    let visibleRows = 10;
    const allRows = @json($allRows);
    const showMoreBtn = document.getElementById('showMore');
    if (showMoreBtn) {
        showMoreBtn.addEventListener('click', () => {
            visibleRows += 10;
            const tbody = rosterTable?.querySelector('tbody');
            if (!tbody) return;
            tbody.innerHTML = '';
            allRows.slice(0, visibleRows).forEach(roster => {
                const endDate = new Date(roster.end_date);
                const today = new Date();
                const status = endDate < today ? 'Expired' : 'Active';
                const row = document.createElement('tr');
                row.className = 'hover:bg-blue-50 dark:hover:bg-gray-600 transition duration-200';
                row.innerHTML = `
                    <td class="border p-2 text-gray-800 dark:text-gray-200">${roster.id}</td>
                    <td class="border p-2 text-gray-800 dark:text-gray-200">${roster.discipline_name || 'Unknown'}</td>
                    <td class="border p-2 text-gray-800 dark:text-gray-200">${new Date(roster.start_date).toLocaleDateString('en-GB')} - ${new Date(roster.end_date).toLocaleDateString('en-GB')}</td>
                    <td class="border p-2 text-gray-800 dark:text-gray-200">${roster.created_by_name || 'Unknown'}</td>
                    <td class="border p-2"><span class="${status === 'Active' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}">${status}</span></td>
                    <td class="border p-2"><a href="/rosters/${roster.id}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">View</a></td>
                `;
                tbody.appendChild(row);
            });
            if (visibleRows >= allRows.length) {
                showMoreBtn.style.display = 'none';
            }
        });
    }
});
</script>
@endpush
@endsection