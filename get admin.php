<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {
    echo json_encode([
        "loggedIn" => true,
        "username" => $_SESSION['username'],
        "role" => $_SESSION['role']
    ]);
} else {
    echo json_encode(["loggedIn" => false]);
}
?>
