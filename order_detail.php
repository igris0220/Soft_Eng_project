<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['order_id'])) {
    header("Location: orders.php");
    exit;
}

$order_id = intval($_GET['order_id']);

// Verify order belongs to user
$sql = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows == 0) {
    echo "Order not found or you don't have permission.";
    exit;
}

$order = $order_result->fetch_assoc();

// Get order items and product names
$sql = "SELECT oi.*, p.name FROM order_items oi 
        LEFT JOIN products p ON oi.product_id = p.id 
        WHERE oi.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head><title>Order Details</title></head>
<body>
<h2>Order Details - Order #<?php echo $order['id']; ?></h2>
<p>Date: <?php echo $order['created_at']; ?></p>
<p>Status: <?php echo htmlspecialchars($order['status']); ?></p>
<p>Total: $<?php echo number_format($order['total_price'], 2); ?></p>

<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price Each</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($item = $items_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>PHP<?php echo number_format($item['price'], 2); ?></td>
                <td>PHP<?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<br>
<a href="orders.php">Back to My Orders</a>
</body>
</html>
