<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include "includes/db.php";
include 'includes/header.php'; 

// Handle delete user
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];

    // Step 1: Get all order IDs of this user
    $order_ids = [];
    $stmt = $conn->prepare("SELECT id FROM orders WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $order_ids[] = $row['id'];
    }

    // Step 2: Delete order_items for each order
    foreach ($order_ids as $order_id) {
        $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
    }

    // Step 3: Delete the orders
    $stmt = $conn->prepare("DELETE FROM orders WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Step 4: Finally, delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    header("Location: manage_users.php");
    exit();
}


// Fetch users
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - StreetGraphs</title>
    <link rel="stylesheet" href="css/admin-style.css">
</head>
<body>
<div class="admin-container">
    <h2>Manage Users</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td>
                    <a href="edit-user.php?id=<?= $row['id']; ?>" class="edit-btn">Edit</a> 
                    <a href="manage_users.php?delete=<?= $row['id']; ?>" class="delete-btn" onclick="return confirm('Orders are pending!! Are you sure you want to delete this user?');">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <!-- <a href="dashboard.php" class="back-btn">← Back to Dashboard</a> -->
</div>
</body>
</html>
