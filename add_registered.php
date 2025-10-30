<?php
// add_member.php
include 'db.php'; // your db connection file

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $status = $_POST['status'];

    if (!empty($name) && !empty($phone)) {
        $stmt = $conn->prepare("INSERT INTO registered (name, phone, status) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $phone, $status);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error: " . $conn->error;
        }
    } else {
        echo "error: missing fields";
    }
}
?>
