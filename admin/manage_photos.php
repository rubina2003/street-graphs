<?php
// manage_photos.php
session_start();
include '../includes/db.php';
include 'includes/header.php'; 

// Optional: check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_query = "DELETE FROM photos WHERE id = $id";
    mysqli_query($conn, $delete_query);
    header("Location: manage_photos.php");
    exit();
}


$query = "SELECT * FROM photos ORDER BY uploaded_at ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Photos</title>
    <link rel="stylesheet" href="admin-style.css">

</head>
    <div class="admin-container">
        <h2>Manage Photos</h2>
        <a href="add_photo.php" class="add-btn">+ Add New Photo</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Preview</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><img src="../<?= $row['image_path'] ?>" width="80" height="60" alt="Photo"></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td>Rs. <?= number_format($row['price'], 2) ?></td>
                        <td><?= $row['stock'] ?></td>
                        <td>
                            <a href="edit_photo.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
                            <!-- <a  class="delete-btn" href="viewProducts.php?delete=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a> -->
                            <a href="manage_photos.php?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this photo?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
