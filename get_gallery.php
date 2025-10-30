<?php
// Allow access from your frontend
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'db.php'; // connect to DB

// Query latest uploads first
$sql = "SELECT id, title, media_files, uploaded_at FROM gallery ORDER BY uploaded_at DESC";
$result = mysqli_query($conn, $sql);

$gallery = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Decode the stored JSON list of files
        $mediaFiles = json_decode($row['media_files'], true);

        // Convert filenames into full URLs or paths
        $fullPaths = [];
        foreach ($mediaFiles as $file) {
            $fullPaths[] = "uploads/" . $file; // e.g., uploads/media_abc123.jpg
        }

        $gallery[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'media_files' => $fullPaths,
            'uploaded_at' => $row['uploaded_at']
        ];
    }

    echo json_encode($gallery, JSON_PRETTY_PRINT);
} else {
    echo json_encode([]);
}
?>
