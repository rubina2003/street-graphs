<?php
include "includes/db.php";
include "includes/header.php";

// Check user login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$delivery_charge = 100;
$subtotal = 0;
$items = [];

$cart = mysqli_query($conn, "SELECT c.*, p.title, p.price FROM cart c JOIN photos p ON c.photo_id = p.id WHERE c.user_id = '$user_id'");
while ($row = mysqli_fetch_assoc($cart)) {
    $size_extra = match ($row['print_size']) {
        '5x7' => 20,
        'A4' => 50,
        'A3' => 100,
        default => 0
    };
    $type_extra = match ($row['print_type']) {
        'Matte' => 20,
        'Canvas' => 50,
        default => 0
    };
    $final_price = $row['price'] + $size_extra + $type_extra;
    $row['final_price'] = $final_price;
    $row['subtotal'] = $final_price * $row['quantity'];
    $subtotal += $row['subtotal'];
    $items[] = $row;
}

$grand_total = $subtotal + $delivery_charge;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | StreetGraphs</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php
    if (isset($_SESSION['transaction_msg'])) {
        echo $_SESSION['transaction_msg'];
        unset($_SESSION['transaction_msg']);
    }

    if (isset($_SESSION['validate_msg'])) {
        echo $_SESSION['validate_msg'];
        unset($_SESSION['validate_msg']);
    }
    ?>
    <div class="checkout-container">
        <h2 class="heading" style="text-align: center; margin-bottom: 30px;">Checkout</h2>

        <div class="checkout-grid">
            <!-- Order Summary -->
            <div class="summary-box">
                <h3>Order Summary</h3>
                <ul>
                    <?php foreach ($items as $item): ?>
                        <li>
                            <strong><?= htmlspecialchars($item['title']) ?></strong><br>
                            Size: <?= $item['print_size'] ?> |
                            Type: <?= $item['print_type'] ?><br>
                            Qty: <?= $item['quantity'] ?> × Rs. <?= number_format($item['final_price'], 2) ?><br>
                            Subtotal: Rs. <?= number_format($item['subtotal'], 2) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <hr>
                <p><strong>Subtotal:</strong> Rs. <?= number_format($subtotal, 2) ?></p>
                <p><strong>Delivery Charge:</strong> Rs. <?= number_format($delivery_charge, 2) ?></p>
                <p><strong>Grand Total:</strong> Rs. <strong><?= number_format($grand_total, 2) ?></strong></p>
            </div>

            <!-- Billing Info -->
            <div class="billing-box">
                <h3>Billing Information</h3>

                <!-- COD Form -->
                <form action="payment-request.php" method="POST">
                    <input type="hidden" name="grand_total" id="grand_total" value="<?= $grand_total ?>">
                    <input type="text" name="inputName" id="inputName" placeholder="Full Name" required>
                    <input type="email" name="inputEmail" id="inputEmail" placeholder="Email" required>
                    <textarea name="inputAddress" id="inputAddress" placeholder="Delivery Address" required></textarea>
                    <input type="text" name="inputPhone" placeholder="Phone Number" required>
                    
                    <!-- <button type="submit" class="btn" name="cash_on_delivery_submit">Place Order (Cash on Delivery)</button> -->
                    <!-- Pay with Khalti Button -->
                <hr style="margin: 5px 0;">
                <h4>Pay with Khalti</h4>
                
                <button type="submit" class="btn-khalti-btn" name="submit">    
                     <img src="uploads/khalti.png" alt="Khalti Logo"
                        style="height: 80px; width: 200px;"></button>
                </form>   
            </div>
        </div>
    </div>

<?php include "includes/footer.php"?>
