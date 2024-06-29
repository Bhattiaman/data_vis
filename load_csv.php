<?php
include 'connection.php';

$file_path = 'read_csv.csv';

if (($handle = fopen($file_path, 'r')) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        // Check if the row has exactly 12 columns (adjust the number as needed)
        if (count($data) == 12) {
            $sql = "INSERT INTO data (end_year, citylng, citylat, intensity, sector, topic, insight, swot, url, region, start_year, impact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            // Assuming your CSV columns match this order, adjust if needed
            $stmt->bind_param('dddsissssssi', $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11]);
            $stmt->execute();
        } else {
            // Handle the case where the row does not have exactly 12 columns
            echo "Error: CSV row does not have exactly 12 elements. Skipping row.";
        }
    }
    fclose($handle);
    echo "Data imported successfully!";
} else {
    echo "Error opening the file.";
}

$conn->close();
?>
