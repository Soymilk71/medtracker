<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicatie</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    <main>

        <h1>Medicatie check</h1>

        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif

        <form action="/medscheck" method="POST">
            @csrf  <!-- Laravel CSRF bescherming -->

            <label for="meds_taken">Medicatie ingenomen: </label>
            <input type="checkbox" name="meds_taken" id="meds_taken" required>
            <button type="submit">Verzenden</button>
        </form>
        <br>

        @if ($last_dose && $next_dose)
            <p>Laatste dosis: {{ \Carbon\Carbon::parse($last_dose)->format('d-m H:i') }}</p>
            <p>Volgende dosis: {{ \Carbon\Carbon::parse($next_dose)->format('d-m H:i') }}</p>
        @else
            <p>Er zijn nog geen medicatie-informatie beschikbaar.</p>
        @endif

        <!-- Canvas for Graphs -->
        <div class=" grid grid-cols-2  mb-4 justify-center ">
            <div>
                <canvas class="chart"id="firstDoseChart" ></canvas>
            </div>
            <div>
                <canvas class="chart" id="combinedChart"></canvas>
            </div>
            <div>
                <canvas class="chart" id="secondDoseChart" ></canvas>
            </div>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Function to render the line chart using Chart.js
    function renderGraph(chartId, dates, firstDoses, secondDoses, label1, label2) {
        const ctx = document.getElementById(chartId).getContext('2d');
        const chartData = {
            labels: dates,
            datasets: [
                {
                    label: label1,
                    data: firstDoses,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1,
                    fill: false
                },
                {
                    label: label2,
                    data: secondDoses,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    tension: 0.1,
                    fill: false
                }
            ]
        };

        new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return formatTime(value); // Convert minutes back to HH:MM
                            }
                        }
                    }
                }
            }
        });
    }

    // Helper function to convert time from minutes (decimal) to HH:MM format
    function formatTime(minutes) {
        const hours = Math.floor(minutes);
        const mins = Math.round((minutes - hours) * 60);
        return `${hours}:${mins.toString().padStart(2, '0')}`;
    }

    // Fetch data from your backend (using /graph-data route)
    fetch('/graph-data')
        .then(response => response.json())
        .then(data => {
            const dates = data.map(entry => entry.date);
            const firstDoses = data.map(entry => entry.first_dose ? new Date(entry.first_dose).getHours() + new Date(entry.first_dose).getMinutes() / 60 : null);
            const secondDoses = data.map(entry => entry.second_dose ? new Date(entry.second_dose).getHours() + new Date(entry.second_dose).getMinutes() / 60 : null);

            // Render first and second dose charts separately
            renderGraph('firstDoseChart', dates, firstDoses, [], 'First Dose Time', ''); // Only first dose data
            renderGraph('secondDoseChart', dates, secondDoses, [], 'Second Dose Time', ''); // Only second dose data
            
            // Combine both first and second dose data in one chart
            renderGraph('combinedChart', dates, firstDoses, secondDoses, 'First Dose Time', 'Second Dose Time'); 
        });
</script>

<style>
    /* Control the size of the charts using CSS */
    .chart {
        width: 75%;  /* Full width of the container */
        max-width: 600px;  /* Maximum width */
        max-height: 300px;  /* Fixed height */
        margin: 0;  /* Center the chart */
    }
</style>