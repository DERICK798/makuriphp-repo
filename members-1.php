<?php
session_start();

// Protect members page
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

include 'db.php'; // database connection

// Fetch members from database
$sql = "SELECT id, name, phone FROM registered ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Members</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Link your CSS -->
  <link rel="stylesheet" href="Dashboard.css">
</head>
<body>
  <div class="members-page">
    <h1>Members</h1>

    <!-- Search & Filter -->
    <div class="search-filter">
      <input type="search" id="searchInput" placeholder="Search members..">
      <select id="statusFilter">
        <option value="all">All members</option>
        <option value="active">Active members</option>
        <option value="inactive">Inactive</option>
      </select>
      <button id="searchBtn">🔍 Search</button>
    </div>

    <!-- Members Table -->
    <table id="membersTable">
      <thead>
        <tr>
          <th>Name</th>
          <th>Phone</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['phone']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td>
              <a href="edit_member.php?id=<?php echo $row['id']; ?>">✏️ Edit</a> |
              <a href="delete_member.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

<form action="delete_member.php" method="POST" onsubmit="return confirm('Are you sure?')">
  <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
  <button type="submit">🗑️ Delete</button>
</form>

    <!-- Add Member Form -->
    <form action="add_Registered.php" method="POST" id="add member-form">
      <input type="text" name="name" placeholder="Name" required>
      <input type="number" name="phone" placeholder="Phone" required>
      <select name="status">
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
      </select>
      <button type="submit" id="add-member-btn">Add Member</button>
    </form>
  </div>

  <!-- Link your JavaScript (for search/filter client side) -->
  <script src="Dashboard.js"></script>
</body>
</html>