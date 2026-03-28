<?php
session_start(); 
include 'includes/header.php'; 

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
?>

    <h1>Admin Dashboard</h1>
    <div class="dashboard-cards">
        <div class="card">
            <h2>Users</h2>
            <p>View and manage users</p>
            <a href="manage_users.php" class="btn">Go</a>
        </div>
        <div class="card">
            <h2>Photos</h2>
            <p>Manage gallery photos</p>
            <a href="manage_photos.php" class="btn">Go</a>
        </div>
        <div class="card">
            <h2>Orders</h2>
            <p>Review customer orders</p>
            <a href="manage_orders.php" class="btn">Go</a>
        </div>
    </div>
<?php include 'includes/footer.php'; ?>
