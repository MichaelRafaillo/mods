<?php
// Function to output CSV data
function outputCSV($data)
{
    $output = fopen("php://output", "w");
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
}

// Collect data from the HTML form or other sources
$data1 = array(
    array('Category', 'Total'),
    // ... Add rows with data
);

$data2 = array(
    array('Category', 'Total'),
    // ... Add rows with data
);

$data3 = array(
    array('Branch', 'Delivered', 'Paid', 'Cancelled', 'Percentage'),
    // ... Add rows with data
);

// Output CSV data
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=exported_data.csv");
header("Pragma: no-cache");
header("Expires: 0");

outputCSV($data1);
echo "\n\n";
outputCSV($data2);
echo "\n\n";
outputCSV($data3);
