<?php
// add_photo.php
session_start();
include '../includes/db.php';


if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];

    $image_path = '';

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $filename = time() . '_' . basename($_FILES['photo']['name']);
        $target_file = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $image_path = 'uploads/' . $filename;
        } else {
            $message = "Failed to upload image.";
        }
    }

    if ($image_path) {
        $query = "INSERT INTO photos (title, category, price, image_path, stock) 
                  VALUES ('$title', '$category', $price, '$image_path', $stock)";
        if (mysqli_query($conn, $query)) {
            header("Location: manage_photos.php?success=1");
            exit();
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Photo</title>
    <link rel="stylesheet" href="css/admin-style.css">
    
</head>
<body>
    <div class="admin-container-edit">
        <h2 style="margin-bottom:30px ;">Add New Photo</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <label>Title:</label>
            <input type="text" name="title" required><br><br>

           <label>Category:</label>
            <select name="category" required>
                <option value="">Select Category</option>
                <option value="animal">Animal</option>
                <option value="nature">Nature</option>
                <option value="people">People</option>
                <option value="heritage">Heritage</option>
                <option value="street">Street</option>
            </select><br><br>

            <label>Price (Rs.):</label>
            <input type="number" step="0.01" name="price" required><br><br>

            <label>Stock:</label>
            <input type="number" name="stock" value="0" min="0" max="15" required><br><br>

            <label>Photo:</label>
            <input type="file" name="photo" accept="image/*" required><br><br>

            <input type="submit" value="Add Photo" class="edit-btn" style="background-color: #4CAF50; color: white; padding: 10px 20px;">
            <a href="manage_photos.php" class="back-btn">Cancel</a>
        </form>

        <?php if (!empty($message)): ?>
            <p style="color: red;"><?= $message ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
