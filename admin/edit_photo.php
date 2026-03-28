<?php
// edit_photo.php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_photos.php");
    exit();
}

$photo_id = (int) $_GET['id'];
$message = '';

$query = "SELECT * FROM photos WHERE id = $photo_id";
$result = mysqli_query($conn, $query);
$photo = mysqli_fetch_assoc($result);

if (!$photo) {
    die("Photo not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim(mysqli_real_escape_string($conn, $_POST['title']));
    $category = trim(mysqli_real_escape_string($conn, $_POST['category']));
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];

    $image_path = $photo['image_path']; // default to current image
    $errors = [];

    // Server-side validation
    if (empty($title)) {
        $errors[] = "Title is required.";
    }

    if (!preg_match("/^[A-Za-z\s]{3,}$/", $title)) {
        $errors[] = "Title must contain only letters and spaces and be at least 3 characters.";
    }

    if (empty($category)) {
        $errors[] = "Category is required.";
    }
    if ($price <= 0) {
        $errors[] = "Price shouldn't be a negative number or zero.";
    }
    if ($stock < 0 || $stock > 15) {
        $errors[] = "Stock must be between 0 and 15.";
    }

    // If no errors, proceed
    if (empty($errors)) {
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            $filename = time() . '_' . basename($_FILES['photo']['name']);
            $target_file = $upload_dir . $filename;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                // Optional: delete old image if needed
                if (file_exists('../' . $photo['image_path'])) {
                    unlink('../' . $photo['image_path']);
                }
                $image_path = 'uploads/' . $filename;
            } else {
                $errors[] = "Failed to upload new image.";
            }
        }

        if (empty($errors)) {
            $update = "UPDATE photos SET 
                        title = '$title', 
                        category = '$category', 
                        price = $price, 
                        stock = $stock, 
                        image_path = '$image_path'
                       WHERE id = $photo_id";

            if (mysqli_query($conn, $update)) {
                header("Location: manage_photos.php?updated=1");
                exit();
            } else {
                $message = "Error: " . mysqli_error($conn);
            }
        } else {
            $message = implode('<br>', $errors);
        }
    } else {
        $message = implode('<br>', $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Photo</title>
    <link rel="stylesheet" href="css/admin-style.css">
    <script>
        function validateForm() {
            const title = document.forms[0]["title"].value.trim();
            const category = document.forms[0]["category"].value.trim();
            const price = parseFloat(document.forms[0]["price"].value);
            const stock = parseInt(document.forms[0]["stock"].value);

            const titlePattern = /^[A-Za-z\s]{3,}$/;
            if (!titlePattern.test(title)) {
                alert("Title must contain only letters and spaces and be at least 3 characters long.");
                return false;
            }

            if (title === "" || category === "") {
                alert("Title and Category cannot be empty.");
                return false;
            }

            if (price <= 0) {
                alert("Price shouldn't be negative number or zero.");
                return false;
            }

            if (stock < 0 || stock > 15) {
                alert("Stock must be between 0 and 15.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="admin-container-edit">
        <h2 style="margin-bottom:15px">Edit Photo</h2>
        <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <label>Title:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($photo['title']) ?>" required><br><br>

            <label>Category:</label>
            <select name="category" required>
                <option value="animal" <?= $photo['category'] === 'animal' ? 'selected' : '' ?>>Animal</option>
                <option value="people" <?= $photo['category'] === 'people' ? 'selected' : '' ?>>People</option>
                <option value="nature" <?= $photo['category'] === 'nature' ? 'selected' : '' ?>>Nature</option>
                <option value="heritage" <?= $photo['category'] === 'heritage' ? 'selected' : '' ?>>Heritage</option>
                <option value="street" <?= $photo['category'] === 'street' ? 'selected' : '' ?>>Street</option>
            </select><br><br>


            <label>Price (Rs.):</label>
            <input type="number" step="0.01" name="price" value="<?= $photo['price'] ?>" required><br><br>

            <label>Stock:</label>
            <input type="number" name="stock" value="<?= $photo['stock'] ?>" required min="0" max="15"><br><br>

            <label>Current Photo:</label><br>
            <img src="../<?= $photo['image_path'] ?>" width="150" alt="Photo"><br><br>

            <label>Change Photo (optional):</label>
            <input type="file" name="photo" accept="image/*"><br><br>

            <input type="submit" value="Update Photo" class="edit-btn" style="padding: 10px 20px;">
            <a href="manage_photos.php" class="back-btn">Cancel</a>
        </form>

        <?php if (!empty($message)): ?>
            <p style="color:red;"><?= $message ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
