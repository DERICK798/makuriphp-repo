<?php
// alter_add_phone.php
// One-off script to add `phone` column to `Registered` table if it doesn't exist.

include 'db.php';
$table = 'Registered';
$column = 'phone';
$dbname = $dbname ?? 'usersdb'; // fallback if not set

// Check if column exists
$sql = "SELECT COUNT(*) AS cnt FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $dbname, $table, $column);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row && $row['cnt'] > 0) {
    echo "Column '$column' already exists in table '$table'.\n";
    exit;
}

// Run ALTER
$alterSql = "ALTER TABLE `$table` ADD COLUMN `$column` VARCHAR(20) AFTER `name`";
if ($conn->query($alterSql) === TRUE) {
    echo "Successfully added column '$column' to table '$table'.\n";
} else {
    echo "Error adding column: " . $conn->error . "\n";
}

?>