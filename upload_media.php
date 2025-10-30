<?php
// TEMP DEBUGGING upload.php - revert to original after debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

include 'db.php';

$response = ['status' => 'error', 'message' => 'Unknown error', 'debug' => []];

// Dump incoming method and headers
$response['debug']['method'] = $_SERVER['REQUEST_METHOD'];
$response['debug']['content_type'] = $_SERVER['CONTENT_TYPE'] ?? 'n/a';

// Dump $_POST and $_FILES
$response['debug']['_POST'] = $_POST;
$response['debug']['_FILES'] = $_FILES;

// Basic checks
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Request method is not POST';
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}

if (!isset($_FILES['files'])) {
    $response['message'] = 'No files key in $_FILES';
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}

// check upload errors per file
$errors = [];
foreach ($_FILES['files']['error'] as $k => $err) {
    $errors[$k] = $err;
}
$response['debug']['file_errors'] = $errors;

// Ensure upload dir exists
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        $response['message'] = 'Failed to create uploads directory';
        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }
}

$savedFiles = [];
foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
    $origName = $_FILES['files']['name'][$key] ?? '';
    $error = $_FILES['files']['error'][$key] ?? 1;
    if ($error !== UPLOAD_ERR_OK) {
        $response['debug']['skipped'][] = [
            'index' => $key,
            'orig' => $origName,
            'error_code' => $error
        ];
        continue;
    }
    if (!is_uploaded_file($tmp_name)) {
        $response['debug']['skipped'][] = ['index'=>$key,'orig'=>$origName,'reason'=>'not_uploaded_file'];
        continue;
    }
    $ext = pathinfo($origName, PATHINFO_EXTENSION);
    $filename = uniqid('media_') . ($ext ? '.' . $ext : '');
    $filePath = $uploadDir . $filename;
    if (move_uploaded_file($tmp_name, $filePath)) {
        $savedFiles[] = $filename;
    } else {
        $response['debug']['move_failed'][] = ['index'=>$key,'orig'=>$origName];
    }
}

$response['debug']['savedFiles'] = $savedFiles;

if (!empty($savedFiles)) {
    $title = $_POST['title'] ?? 'Untitled';
    $mediaJson = json_encode($savedFiles);
    $sql = "INSERT INTO gallery (title, media_files) VALUES ('" . mysqli_real_escape_string($conn,$title) . "', '" . mysqli_real_escape_string($conn,$mediaJson) . "')";
    if (mysqli_query($conn, $sql)) {
        $response['status'] = 'success';
        $response['message'] = 'Files uploaded and DB saved';
    } else {
        $response['message'] = 'DB insert failed: ' . mysqli_error($conn);
    }
} else {
    $response['message'] = 'No files moved to uploads folder';
}

echo json_encode($response, JSON_PRETTY_PRINT);
