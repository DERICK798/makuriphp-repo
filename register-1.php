<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

   
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        header("Location:index.html "); // redirect to login after signup
        exit();
    } else {
        echo "❌ Error: " . $stmt->error;
    }
    $stmt->close();

    $conn->close();
}
?>
