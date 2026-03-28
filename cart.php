<?php
include "includes/db.php";
include "includes/header.php";

// Ensure user is logged in
// if (!isset($_SESSION['user_id'])) {
//     echo "<p class='message'>Please <a href='login.php'>log in</a> to use the cart.</p>";
//     exit();
// }

// Initialize cart for the logged-in user
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

// Handle: Add to cart
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['photo_id']) && isset($_POST['title'])) {
    $photo_id = $_POST['photo_id'];
    $title = $_POST['title'];
    $price = $_POST['price'];
    $image_path = $_POST['image_path'];
    $quantity = $_POST['quantity'];
    $print_size = $_POST['print_size'];
    $print_type = $_POST['print_type'];

      // Check if the same product with same print option is already in cart
    $check = mysqli_query($conn, "SELECT * FROM cart WHERE photo_id = '$photo_id' AND user_id = '$user_id' AND print_size = '$print_size' AND print_type = '$print_type'");

    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE photo_id = '$photo_id' AND user_id = '$user_id' AND print_size = '$print_size' AND print_type = '$print_type'");
    } else {
        $stmt = $conn->prepare("INSERT INTO cart (photo_id, user_id, quantity, print_size, print_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss", $photo_id, $user_id, $quantity, $print_size, $print_type);
        $stmt->execute();
        $stmt->close();
    }
}

// Handle: Update quantity
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_quantity'])) {
    $photo_id = $_POST['photo_id'];
    $quantity = max(1, intval($_POST['quantity']));
    mysqli_query($conn, "UPDATE cart SET quantity = '$quantity' WHERE photo_id = '$photo_id' AND user_id = '$user_id'");
}

// Handle: Remove item
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['remove_item'])) {
    $photo_id = $_POST['photo_id'];
    mysqli_query($conn, "DELETE FROM cart WHERE photo_id = '$photo_id' AND user_id = '$user_id'");
}

// Fetch cart items

$cartQuery = "SELECT p.id, p.title, p.price, c.print_size, c.print_type, c.quantity, p.image_path, p.stock
    FROM photos p 
    JOIN cart c ON p.id = c.photo_id 
    WHERE c.user_id = $user_id";

$result = mysqli_query($conn, $cartQuery);
?>
<h2 class="heading" style="text-align:center; margin: 30px 0;">Your Cart</h2>

<div class="cart-table-wrapper">
<?php
$grand_total = 0;

if (mysqli_num_rows($result) > 0): ?>
    <table class="cart-table">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Title</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Quantity</th>
                <!-- <th>Final Price</th> -->
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)):
            // base price
            $base_price = $row['price'];

            // extra based on print size
            $size_extra = 0;
            switch ($row['print_size']) {
                case '5x7': $size_extra = 20; break;
                case 'A4': $size_extra = 50; break;
                case 'A3': $size_extra = 100; break;
            }

            // extra based on print type
            $type_extra = 0;
            switch ($row['print_type']) {
                case 'Matte': $type_extra = 20; break;
                case 'Canvas': $type_extra = 50; break;
            }

            $final_price = $base_price + $size_extra + $type_extra;
            $subtotal = $final_price * $row['quantity'];
            $grand_total += $subtotal;

        ?>
            <tr>
                <td><img src="<?php echo $row['image_path']; ?>" alt="photo" class="cart-img"></td>
                <td>
                    <?php echo htmlspecialchars($row['title']); ?>
                    <small>Size: <?php echo $row['print_size']; ?> | Type: <?php echo $row['print_type']; ?></small>
                </td>
                <td>Rs. <?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo $row['stock']; ?></td>
                <td>
                    <form method="POST" action="" class="inline-form">
                        <input type="hidden" name="photo_id" value="<?php echo $row['id']; ?>">
                        <div class="form-row">
                            <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1" max="<?php echo $row['stock']; ?>" required class="qty-input">
                            <button type="submit" name="update_quantity" class="qty-btn">Update</button>
                        </div>
                    </form>
                </td>

                <!-- <td>Rs. <?php echo number_format($final_price, 2); ?></td> -->
                <td>Rs. <?php echo number_format($subtotal, 2); ?></td>

                <td>
                    <form method="POST" class="inline-form">
                        <input type="hidden" name="photo_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="remove_item" class="remove-btn">Remove</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align:right;"><strong>Grand Total:</strong></td>
                <td colspan="2"><strong>Rs. <?php echo number_format($grand_total, 2); ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <div style="text-align:right; margin-top: 20px;">
        <a href="gallery.php" class="btn" stye="margin-bottom: 40px;">Continue Browsing</a>
        <a href="checkout.php" class="btn" stye="margin-bottom: 40px;">Proceed to Checkout</a>
    </div>
<?php else: ?>
    <p class="message" style="text-align:center;">Your cart is empty.</p>
<?php endif; ?>
</div>
