<?php
// Include database connection
require_once 'dashboard.php';

// Relative path to your CSV file (adjust as needed)
$csvFile = './read_csv.csv';

// Check if the CSV file exists
if (!file_exists($csvFile)) {
    die("CSV file does not exist.");
}

// Initialize arrays to store values for the chart
$endYears = [];
$startYears = [];
$topics = [];

// Read the CSV file
if (($handle = fopen($csvFile, 'r')) !== false) {
    // Skip the header row
    fgetcsv($handle);

    // Loop through each row in the CSV file
    while (($data = fgetcsv($handle)) !== false) {
        $endYears[] = $data[0];
        $startYears[] = $data[10];
        $topics[] = $data[5];
    }

    // Close CSV file
    fclose($handle);
} else {
    die("Error opening CSV file.");
}

// Calculate frequencies
$endYearCounts = array_count_values($endYears);
$startYearCounts = array_count_values($startYears);
$topicCounts = array_count_values($topics);

// Start HTML output
echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Data from CSV</title>";
echo "<style>";
echo "body {";
echo "  font-family: Arial, sans-serif;";
echo "  background-color: #f0f0f0;";
echo "  margin: 0;";
echo "  padding: 0;";
echo "}";
echo "table {";
echo "  border-collapse: collapse;";
echo "  width: 100%;";
echo "  margin-top: 20px;";
echo "  background-color: #ffffff;";
echo "  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);";
echo "}";
echo "th, td {";
echo "  border: 1px solid #dddddd;";
echo "  padding: 8px;";
echo "}";
echo "th {";
echo "  background-color: #f2f2f2;";
echo "}";
echo "tr:nth-child(even) {";
echo "  background-color: #f9f9f9;";
echo "}";
echo "tr:hover {";
echo "  background-color: #f1f1f1;";
echo "}";
echo ".data-row {";
echo "  width: 100%;"; // Adjust as needed
echo "}";
echo "#see-more, #show-pie-chart {";
echo "  display: block;";
echo "  margin: 20px auto;";
echo "  padding: 10px 20px;";
echo "  font-size: 16px;";
echo "  border-radius: 5px;";
echo "  cursor: pointer;";
echo "  border: none;";
echo "  background-color: #4CAF50;";
echo "  color: white;";
echo "}";
echo "#see-more:hover, #show-pie-chart:hover {";
echo "  background-color: #45a049;";
echo "}";
echo "#pie-chart-container {";
echo "  margin-top: -30%;";
echo "  text-align: center;";
echo "}";
echo "#pie-chart {";
echo "  max-width: 400px;";
echo "  margin: 0 auto;";
echo "}";
echo "</style>";
echo "</head>";
echo "<body>";

// Button to show 3D Pie Chart (moved to the top)
echo "<div style='text-align: center; margin-top: 20px;'>";
echo "<button id='show-pie-chart'>Show 3D Pie Chart</button>";
echo "</div>";

// Display header row
echo "<h1 style='text-align: center; margin-top: 20px;'>Data from CSV:</h1>";
echo "<table>";
echo "<thead><tr><th style='width: 5%;'>end_year</th><th style='width: 10%;'>citylng</th><th style='width: 10%;'>citylat</th><th style='width: 10%;'>intensity</th><th style='width: 10%;'>sector</th><th style='width: 10%;'>topic</th><th style='width: 10%;'>insight</th><th style='width: 5%;'>swot</th><th style='width: 10%;'>url</th><th style='width: 5%;'>region</th><th style='width: 5%;'>start_year</th><th style='width: 5%;'>impact</th><th style='width: 5%;'>added</th><th style='width: 5%;'>published</th><th style='width: 10%;'>city</th><th style='width: 5%;'>country</th><th style='width: 5%;'>relevance</th><th style='width: 5%;'>pestle</th><th style='width: 10%;'>source</th><th style='width: 10%;'>title</th><th style='width: 5%;'>likelihood</th></tr></thead>";
echo "<tbody id='data-body'>";

// Loop through each row in the CSV file again to display first five rows
if (($handle = fopen($csvFile, 'r')) !== false) {
    fgetcsv($handle); // Skip the header row again
    $row_count = 0;

    while (($data = fgetcsv($handle)) !== false) {
        if ($row_count < 1) {
            echo "<tr class='data-row'>";
            foreach ($data as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        $row_count++;
    }

    fclose($handle);
}

echo "</tbody>";
echo "</table>";

// If there are more rows to display, show the "See More" button
if ($row_count > 1) {
    echo "<button id='see-more'>See More</button>";
}

// Include details section for charts
include 'details_section.php';

// Container for the pie chart
echo "<div id='pie-chart-container' style='display:none;'>";
echo "<canvas id='pie-chart'></canvas>";
echo "</div>";

echo "</body>";
echo "</html>";

// Close MySQL connection (from dashboard.php)
$mysqli->close();
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to generate random colors for pie chart slices
    function generateColors(numColors) {
        const colors = [];
        for (let i = 0; i < numColors; i++) {
            colors.push(`rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 0.6)`);
        }
        return colors;
    }

    // Event listener for Show 3D Pie Chart button
    const pieChartButton = document.getElementById('show-pie-chart');
    pieChartButton.addEventListener('click', function() {
        // Show pie chart container
        document.getElementById('pie-chart-container').style.display = 'block';

        // Create pie chart
        new Chart('pie-chart', {
            type: 'pie',
            data: {
                labels: ['End Year', 'Start Year', 'Topic'],
                datasets: [{
                    data: [
                        <?php echo array_sum($endYearCounts); ?>,
                        <?php echo array_sum($startYearCounts); ?>,
                        <?php echo array_sum($topicCounts); ?>
                    ],
                    backgroundColor: generateColors(3),
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return `${tooltipItem.label}: ${tooltipItem.raw}`;
                            }
                        }
                    }
                }
            }
        });
    });

    // Event listener for See More button
    const seeMoreButton = document.getElementById('see-more');
    if (seeMoreButton) {
        seeMoreButton.addEventListener('click', function() {
            fetchNextRows();
        });
    }

    function fetchNextRows() {
        fetch('fetch_next_rows.php') // Adjust the path as needed
        .then(response => response.text())
        .then(data => {
            // Append the fetched rows to the table
            document.getElementById('data-body').insertAdjacentHTML('beforeend', data);
        })
        .catch(error => console.error('Error fetching next rows:', error));
    }
});
</script>
    