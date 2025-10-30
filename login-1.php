<?php
// Example credentials
$valid_username = "admin";
$valid_password = "12345";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $valid_username && $password === $valid_password) {
        // Redirect to index.html
        header("Location: index.html");
        exit(); // Always exit after redirect
    } else {
        echo "❌ Invalid username or password.";
    }
}
?>
