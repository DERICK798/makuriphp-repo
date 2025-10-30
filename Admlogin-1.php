<?php
session_start();
include "db.php"; // connect to DB

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $user, $hashed_pass, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_pass)) {
            // Save session
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $user;
            $_SESSION["role"] = $role;

            if ($role === "admin") {
                header("Location: dashboard.php"); 
                exit();
            } else {
                $error = "❌ Access denied. Admins only.";
            }
        } else {
            $error = "⛔ Access denied. You are not authorized to view the admin dashboard.";
        }
    } else {
        $error = "❌ User not found.";
    }
    if ($role === "admin") {
    header("Location: Dashboard.php"); 
    exit();
} else {
    header("Location: index.html"); // or any other page for normal users
    exit();
}

}
?>