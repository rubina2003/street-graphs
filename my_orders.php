<?php
include "includes/db.php";
include "includes/header.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch orders of the user
$stmt_orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$orders_result = $stmt_orders->get_result();
$stmt_orders->close();
?>

<div class="orders-container">
    <h2 class="heading">My Orders</h2>

    <?php if ($orders_result->num_rows > 0): ?>
        <?php while ($order = $orders_result->fetch_assoc()): ?>
            <div class="order-box">
                <h3>
                    Order #<?php echo htmlspecialchars($order['id']); ?> — Rs. <?php echo number_format($order['total_amount'], 2); ?>
                    <span class="order-status <?php echo strtolower($order['status']); ?>">
                        (<?php echo htmlspecialchars($order['status']); ?>)
                    </span>
                </h3>

                <p>Date: <?php echo date("F j, Y, g:i a", strtotime($order['order_date'])); ?></p>
                <p>Payment Method: <strong><?php echo htmlspecialchars($order['payment_method']); ?></strong></p>

                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Title</th>
                            <th>Size</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $order_id = $order['id'];
                        $stmt_items = $conn->prepare("
                            SELECT oi.*, p.title, p.image_path, p.price
                            FROM order_items oi
                            JOIN photos p ON oi.photo_id = p.id
                            WHERE oi.order_id = ?
                        ");
                        $stmt_items->bind_param("i", $order_id);
                        $stmt_items->execute();
                        $items_result = $stmt_items->get_result();
                        $stmt_items->close();

                        while ($item = $items_result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" style="height: 100px;"></td>
                            <td><?php echo htmlspecialchars($item['title']); ?></td>
                            <td><?php echo htmlspecialchars($item['print_size']); ?></td>
                            <td><?php echo htmlspecialchars($item['print_type']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>Rs. <?php echo number_format($item['subtotal'], 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center; margin-top: 50px; font-size: 17px;">You haven’t placed any orders yet.</p>
    <?php endif; ?>
</div>
