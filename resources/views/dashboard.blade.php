@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 space-y-12 bg-gray-100 dark:bg-[#0a0a23] dark:text-white">
    <!-- Date Filter -->
    

    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
    <p>Welcome, {{ Auth::user()->name }}!</p>
    <div class="space-y-4">
        <a href="{{ route('rosters.create') }}" class="block bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 w-max">Create New Roster</a>
        @if (Auth::user()->is_admin)
            <a href="{{ route('disciplines.index') }}" class="block bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 w-max">Manage Disciplines</a>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let eggChart, feedChart, salesChart;

    function updateEggChart() {
        const ctx = document.getElementById('eggTrend').getContext('2d');
        if (eggChart) eggChart.destroy();
        eggChart = new Chart(ctx, {
            type: document.getElementById('eggChartType').value,
            data: {
                labels: @json($eggTrend->pluck('date')),
                datasets: [{ label: 'Egg Crates', data: @json($eggTrend->pluck('value')), fill: false, borderColor: '#10b981', backgroundColor: '#10b981' }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                plugins: { title: { display: true, text: 'Egg Production Trend' } }
            }
        });
    }

    function updateFeedChart() {
        const ctx = document.getElementById('feedTrend').getContext('2d');
        if (feedChart) feedChart.destroy();
        feedChart = new Chart(ctx, {
            type: document.getElementById('feedChartType').value,
            data: {
                labels: @json($feedTrend->pluck('date')),
                datasets: [{ label: 'Feed (kg)', data: @json($feedTrend->pluck('value')), fill: false, borderColor: '#f97316', backgroundColor: '#f97316' }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                plugins: { title: { display: true, text: 'Feed Consumption Trend' } }
            }
        });
    }

    function updateSalesChart() {
        const ctx = document.getElementById('salesTrend').getContext('2d');
        if (salesChart) salesChart.destroy();
        salesChart = new Chart(ctx, {
            type: document.getElementById('salesChartType').value,
            data: {
                labels: @json($salesTrend->pluck('date')),
                datasets: [{ label: 'Sales ($)', data: @json($salesTrend->pluck('value')), fill: false, borderColor: '#3b82f6', backgroundColor: '#3b82f6' }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                plugins: { title: { display: true, text: 'Sales Trend' } }
            }
        });
    }

    // Initial draws
    @if (isset($eggTrend) && $eggTrend->isNotEmpty())
        updateEggChart();
    @else
        document.getElementById('eggTrend').parentElement.insertAdjacentHTML('beforeend', '<p class="text-gray-600 dark:text-gray-400 text-center py-4">No egg data available.</p>');
    @endif

    @if (isset($feedTrend) && $feedTrend->isNotEmpty())
        updateFeedChart();
    @else
        document.getElementById('feedTrend').parentElement.insertAdjacentHTML('beforeend', '<p class="text-gray-600 dark:text-gray-400 text-center py-4">No feed data available.</p>');
    @endif

    @if (isset($salesTrend) && $salesTrend->isNotEmpty())
        updateSalesChart();
    @else
        document.getElementById('salesTrend').parentElement.insertAdjacentHTML('beforeend', '<p class="text-gray-600 dark:text-gray-400 text-center py-4">No sales data available.</p>');
    @endif

    // Dismiss All Alerts
    document.getElementById('dismiss-all-alerts')?.addEventListener('click', function() {
        const section = document.getElementById('alerts-section');
        const errorDiv = document.getElementById('alert-error');
        if (section && errorDiv) {
            section.style.display = 'none'; // Hide the alerts section
            // Send AJAX request to mark all alerts as read
            fetch('{{ route('alerts.dismiss-all') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response Status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Invalid JSON:', text);
                        throw new Error('Invalid JSON response');
                    }
                });
            })
            .then(data => {
                console.log('Response Data:', data);
                if (data.success) {
                    console.log('All alerts marked as read successfully.');
                } else {
                    console.error('Failed to dismiss alerts:', data.message);
                    section.style.display = 'block'; // Re-show section
                    errorDiv.classList.remove('hidden');
                    errorDiv.textContent = data.message || 'Failed to dismiss alerts.';
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error.message);
                section.style.display = 'block'; // Re-show section
                errorDiv.classList.remove('hidden');
                errorDiv.textContent = 'An error occurred while dismissing alerts: ' + error.message;
            });
        }
    });
</script>
@endsection