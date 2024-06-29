<!-- details_section.php -->
<div class="chart-container">
    <canvas id="year-chart"></canvas>
</div>
<div class="chart-container">
    <canvas id="topic-chart"></canvas>
</div>
<div class="chart-container">
    <canvas id="start-year-chart"></canvas>
</div>
<div class="chart-container">
    <canvas id="sector-chart"></canvas>
</div>
<div class="chart-container">
    <canvas id="likelihood-chart"></canvas>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('dashboard.php')
        .then(response => response.json())
        .then(data => {
            // Extract data for each chart
            const years = data.map(item => item.year);
            const topics = data.map(item => item.topic);
            const startYears = data.map(item => item.start_year);
            const sectors = data.map(item => item.sector);
            const likelihoods = data.map(item => item.likelihood);

            // Update charts
            updateChart('year-chart', 'Years', years);
            updateChart('topic-chart', 'Topics', topics);
            updateChart('start-year-chart', 'Start Years', startYears);
            updateChart('sector-chart', 'Sectors', sectors);
            updateChart('likelihood-chart', 'Likelihoods', likelihoods);
        })
        .catch(error => console.error('Error fetching data:', error));

    function updateChart(chartId, label, data) {
        const ctx = document.getElementById(chartId).getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: label
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    }
});
</script>
