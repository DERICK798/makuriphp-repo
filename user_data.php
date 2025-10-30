<?php
include 'db.php';

// Cache file
$cacheFile = __DIR__ . '/cache_user_data.json';
$cacheTime = 30; // seconds

// If cache exists and is still fresh, use it
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
    header('Content-Type: application/json');
    echo file_get_contents($cacheFile);
    exit;
}

// Query database
$sql = "SELECT YEAR(created_at) as year, COUNT(*) as total 
        FROM members 
        GROUP BY YEAR(created_at) 
        ORDER BY year ASC 
        LIMIT 10";

$result = $conn->query($sql);

$years = [];
$totals = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $years[] = $row['year'];
        $totals[] = $row['total'];
    }
}

// Free result
if ($result) {
    $result->free();
}

$conn->close();

// Prepare response
$response = json_encode([
    "years" => $years,
    "totals" => $totals
]);

// Save to cache
file_put_contents($cacheFile, $response);

// Output JSON
header('Content-Type: application/json');
echo $response;
?>
