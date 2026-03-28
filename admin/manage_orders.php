<?php
session_start();
include "../includes/db.php"; // Adjust path as needed
include 'includes/header.php'; 

// Handle update status form submission
if (isset($_POST['update_status']) && isset($_POST['order_id']) && isset($_POST['new_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['new_status'];

    // Update order status in DB
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['msg'] = "Order #$order_id status updated to $new_status.";
    header("Location: manage_orders.php");
    exit;
}

// Handle cancel order (restock + update status)
if (isset($_POST['cancel_order']) && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);

    // Only cancel if not already cancelled
    $stmt_check = $conn->prepare("SELECT status FROM orders WHERE id = ?");
    $stmt_check->bind_param("i", $order_id);
    $stmt_check->execute();
    $stmt_check->bind_result($current_status);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($current_status !== 'Cancelled') {
        // Restock photos
        $stmt_items = $conn->prepare("SELECT photo_id, quantity FROM order_items WHERE order_id = ?");
        $stmt_items->bind_param("i", $order_id);
        $stmt_items->execute();
        $result_items = $stmt_items->get_result();
        while ($row = $result_items->fetch_assoc()) {
            $photo_id = $row['photo_id'];
            $qty = $row['quantity'];
            $conn->query("UPDATE photos SET stock = stock + $qty WHERE id = $photo_id");
        }
        $stmt_items->close();

        // Update order status to Cancelled
        $stmt_update = $conn->prepare("UPDATE orders SET status = 'Cancelled' WHERE id = ?");
        $stmt_update->bind_param("i", $order_id);
        $stmt_update->execute();
        $stmt_update->close();

        $_SESSION['msg'] = "Order #$order_id has been cancelled and stock updated.";
    } else {
        $_SESSION['msg'] = "Order #$order_id is already cancelled.";
    }
    header("Location: manage_orders.php");
    exit;
}

// Handle delete order (remove order + order_items)
if (isset($_POST['delete_order']) && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);

    // Delete order_items first
    $stmt_del_items = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
    $stmt_del_items->bind_param("i", $order_id);
    $stmt_del_items->execute();
    $stmt_del_items->close();

    // Delete order
    $stmt_del_order = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt_del_order->bind_param("i", $order_id);
    $stmt_del_order->execute();
    $stmt_del_order->close();

    $_SESSION['msg'] = "Order #$order_id deleted successfully.";
    header("Location: manage_orders.php");
    exit;
}

// Fetch all orders with user and billing info
$sql = "
SELECT 
    orders.*, 
    billing_info.name AS billing_name, 
    billing_info.email, 
    billing_info.phone, 
    billing_info.address 
FROM orders 
LEFT JOIN billing_info ON orders.id = billing_info.order_id
ORDER BY orders.id DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Manage Orders - Admin Panel</title>
<style>
   
    .btn {
        padding: 6px 12px;
        border: none;
        cursor: pointer;
        border-radius: 4px;
        font-weight: 600;
        font-size: 14px;
    }
    .btn-update {
        background-color: #28a745;
        color: white;
    }
    .btn-update:hover {
        background-color: #218838;
    }
 
    .btn-delete {
        background-color: #6c757d;
        color: white;
    }
    .btn-delete:hover {
        background-color: #5a6268;
    }
    .status-pending {
        color: #ffc107;
        font-weight: bold;
    }
    .status-completed {
        color: #28a745;
        font-weight: bold;
    }
    .status-cancelled {
        color: #dc3545;
        font-weight: bold;
    }
    .billing-info {
        background: #f8f9fa;
        padding: 10px;
        margin-top: 10px;
        font-size: 14px;
        border-radius: 4px;
    }
    .msg {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 10px 15px;
        margin-bottom: 20px;
        border-radius: 4px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }
    .btn-view-items {
    background-color: #0e76a8;
    color: white;
    padding: 6px 12px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 600;
    display: inline-block;
    }
    .btn-view-items:hover {
        background-color: #095a84;
    }

</style> 
<!-- <script>
function toggleItems(id) {
    const elem = document.getElementById('items-' + id);
    if (elem.style.display === 'none') {
        elem.style.display = 'block';
    } else {
        elem.style.display = 'none';
    }
}
</script> -->
</head>
<body>
    <div style="max-width: 1200px " class="admin-container">
        <h2>Manage Orders</h2>

        <?php if (isset($_SESSION['msg'])): ?>
            <div class="msg"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Billing Info</th>
                    <th>Order Items</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($order = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['user_id']; ?></td>
                    <td><?php echo date("Y-m-d H:i", strtotime($order['order_date'])); ?></td>
                    <td>Rs. <?php echo number_format($order['total_amount'], 2); ?></td>
                    <td class="status-<?php echo strtolower($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></td>
                    <td class="billing-info">
                        <strong><?php echo htmlspecialchars($order['billing_name'] ?? 'N/A'); ?></strong><br />
                        <?php echo nl2br(htmlspecialchars($order['address'] ?? 'N/A')); ?><br />
                        Email: <?php echo htmlspecialchars($order['email'] ?? 'N/A'); ?><br />
                        Phone: <?php echo htmlspecialchars($order['phone'] ?? 'N/A'); ?>
                    </td>
                    <td>
            <a href="order_items.php?order_id=<?php echo $order['id']; ?>" class="btn btn-view-items">View Items</a>

        </td>

                    <td>
                        <form method="post" style="margin-bottom:10px;">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <select name="new_status" required>
                                <option value="">Change Status</option>
                                <option value="Pending" <?php if(strtolower($order['status']) == 'pending') echo "selected"; ?>>Pending</option>
                                <option value="Processing" <?php if(strtolower($order['status']) == 'processing') echo "selected"; ?>>Processing</option>
                                <option value="Completed" <?php if(strtolower($order['status']) == 'completed') echo "selected"; ?>>Completed</option>
                                <option value="Cancelled" <?php if(strtolower($order['status']) == 'cancelled') echo "selected"; ?>>Cancelled</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-update">Update</button>
                        </form>

                        <form method="post" onsubmit="return confirm('Are you sure you want to cancel this order?');" style="margin-bottom:10px;">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <!-- <button type="submit" name="cancel_order" class="btn btn-cancel">Cancel Order</button> -->
                        </form>

                        <form method="post" onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.');">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <button type="submit" name="delete_order" class="btn btn-delete">Delete Order</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>

</body>
</html>
