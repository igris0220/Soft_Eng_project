<?php
session_start();
require 'db.php';
include 'check_admin.php';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    $valid_statuses = ['pending', 'paid', 'shipped', 'cancelled'];
    if (in_array($status, $valid_statuses)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
        $stmt->close();
        $message = "Order #$order_id status updated to $status.";
    } else {
        $message = "Invalid status selected.";
    }
}

$sql = "SELECT orders.id, orders.user_id, orders.total_price, orders.status, orders.created_at, users.name AS user_name 
        FROM orders
        LEFT JOIN users ON orders.user_id = users.id
        ORDER BY orders.created_at DESC";
$orders_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
    <style>
        body {
            background: linear-gradient(135deg, #5a2a83, #9b45d2);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(90, 42, 131, 0.3);
            position: relative;
        }

        .back-link-top {
            position: absolute;
            top: 20px;
            right: 30px;
        }

        .back-link-top a {
            background: #6a2dbd;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .back-link-top a:hover {
            background: #4a1a88;
        }

        h2 {
            text-align: center;
            color: #6a2dbd;
            margin-bottom: 30px;
        }

        .message {
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .order-box {
            border: 2px solid #eee;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            background: #fdfcff;
        }

        .order-items {
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f1e9ff;
            color: #6a2dbd;
        }

        label, select, button {
            font-size: 14px;
            margin-top: 5px;
        }

        select {
            padding: 5px;
            margin-right: 10px;
        }

        button {
            background-color: #6a2dbd;
            color: white;
            padding: 6px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4a1a88;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-link-top">
            <a href="admin_dashboard.php">⬅ Back to Dashboard</a>
        </div>

        <h2>Manage Orders</h2>

        <?php if (!empty($message)): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if ($orders_result->num_rows === 0): ?>
            <p>No orders found.</p>
        <?php else: ?>
            <?php while ($order = $orders_result->fetch_assoc()): ?>
                <div class="order-box">
                    <strong>Order ID:</strong> <?= $order['id'] ?><br>
                    <strong>User:</strong> <?= htmlspecialchars($order['user_name']) ?><br>
                    <strong>Total Price:</strong> $<?= number_format($order['total_price'], 2) ?><br>
                    <strong>Status:</strong> <?= htmlspecialchars($order['status']) ?><br>
                    <strong>Created At:</strong> <?= $order['created_at'] ?><br><br>

                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <label>Update Status:</label>
                        <select name="status">
                            <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="paid" <?= $order['status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                            <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                            <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>

                    <div class="order-items">
                        <strong>Items:</strong>
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price (each)</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt = $conn->prepare("SELECT order_items.quantity, order_items.price, products.name 
                                                        FROM order_items
                                                        LEFT JOIN products ON order_items.product_id = products.id
                                                        WHERE order_items.order_id = ?");
                                $stmt->bind_param("i", $order['id']);
                                $stmt->execute();
                                $items_result = $stmt->get_result();

                                while ($item = $items_result->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['name']) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td>PHP<?= number_format($item['price'], 2) ?></td>
                                    <td>PHP<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                </tr>
                                <?php endwhile;
                                $stmt->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</body>
</html>
