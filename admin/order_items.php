<?php
include "includes/db.php";
include 'includes/header.php';

$order_id = $_GET['order_id'] ?? 0;

// Retrieve order items for this order
$stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order <?= $order_id ?> - Items</title>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
}
h4 {
    margin-bottom: 20px;
}
.order-items-table {
    width: 100%;
    border-collapse: collapse;
}
.order-items-table th,
.order-items-table td {
    border: 1px solid #dddddd;
    padding: 12px;
    text-align: left;
}
.order-items-table th {
    background: #efefef;
    color: #555;
}
.order-items-table tbody tr:hover {
    background: #f5f5f5;
}
.back-btn{
     padding: 6px 12px;
        border: none;
        cursor: pointer;
        border-radius: 4px;
        font-weight: 600;
        font-size: 14px;
        background-color: #6c757d;
        color: white;
    }
    .back-btn:hover {
        background-color: #5a6268;
    }
</style>
</head>
<body>

<h2>Order <?= $order_id ?> - Items</h2>

<table class="order-items-table">
    <thead>
        <tr>
            <th>Product</th>
            <th>Picture</th>
            <th>Print Size</th>
            <th>Print Type</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($item = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= htmlspecialchars($item['title']); ?> </td>
                <td><img src="/StreetGraphs/uploads/<?= htmlspecialchars(basename($item['image_path'])); ?>" width="250" height="200" alt="Product Image"></td>
                <td><?= htmlspecialchars($item['print_size']); ?> </td>
                <td><?= htmlspecialchars($item['print_type']); ?> </td>
                <td><?= (int)$item['quantity']; ?> </td>
                <td>Rs <?= number_format($item['subtotal'], 2); ?> </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<a href="manage_orders.php"class="back-btn">Back to Orders</a>

</body>
</html>

<?php
$stmt->close();
?>
