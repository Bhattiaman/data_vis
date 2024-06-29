<?php
$csvFile = './read_csv.csv';
$handle = fopen($csvFile, 'r');
if ($handle !== false) {
    // Skip the header row
    fgetcsv($handle);

    $row_count = 0;
    $output = '';

    // Loop through each row in the CSV file
    while (($data = fgetcsv($handle)) !== false) {
        if ($row_count >= 5 && $row_count < 10) { // Change 5 and 10 accordingly for subsequent rows
            $output .= "<tr>";
            foreach ($data as $value) {
                $output .= "<td>$value</td>";
            }
            $output .= "</tr>";
        }
        $row_count++;
    }

    fclose($handle);

    // Return the HTML output
    echo $output;
} else {
    echo "Error opening CSV file.";
}
?>
