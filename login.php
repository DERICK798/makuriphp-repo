<?php
session_start();
include 'db.php'; // your db.php must connect to "usersdb"
$error = ""; // initialize
// Only handle POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Prepare SQL query
    $sql = "SELECT id, username, password FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("❌ SQL prepare failed: " . $conn->error);
    }

    // Bind username and run query
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($row = $result->fetch_assoc()) {
        // Verify hashed password
        if (password_verify($password, $row['password'])) {
            // ✅ Save user in session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // Redirect to dashboard/home page
            header("Location: index.html");
            exit();
        } else {
            $error = "❌ Wrong password.";
        }
    } else {
        $error = "❌ User not found.";
    }

    $stmt->close();
    $conn->close();
}
?>