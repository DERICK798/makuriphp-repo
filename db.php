<?php
$servername = "localhost";   // XAMPP default
$username   = "root";        // default username
$password   = "";            // default password (empty in XAMPP)
$dbname     = "usersdb";     // your actual database name
$Port        = 3306;       // match this to your MySQL port
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname,);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
?>