<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get cart items
$cartQuery = $conn->prepare("
    SELECT c.*, p.name, p.price, p.stock 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$cartQuery->bind_param("i", $user_id);
$cartQuery->execute();
$cartItems = $cartQuery->get_result();

if ($cartItems->num_rows === 0) {
    echo "<div class='message-container'><p>Your cart is empty. <a href='shop.php'>Go shopping</a></p></div>";
    exit;
}

// Calculate total
$total = 0;
$items = [];
while ($item = $cartItems->fetch_assoc()) {
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
    $items[] = $item;
}

// Insert order
$orderStmt = $conn->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
$orderStmt->bind_param("id", $user_id, $total);
$orderStmt->execute();
$order_id = $orderStmt->insert_id;

// Insert order items & update stock
foreach ($items as $item) {
    $itemStmt = $conn->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, price) 
        VALUES (?, ?, ?, ?)
    ");
    $itemStmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    $itemStmt->execute();

    $stockUpdate = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
    $stockUpdate->bind_param("ii", $item['quantity'], $item['product_id']);
    $stockUpdate->execute();
}

// Clear cart
$clearCart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$clearCart->bind_param("i", $user_id);
$clearCart->execute();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout Complete</title>
    <style>
        body {
            background: linear-gradient(135deg, #5a2a83, #9b45d2);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #4a1a88;
        }

        .success-container {
            background: #fff;
            padding: 40px 50px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(90, 42, 131, 0.4);
            max-width: 600px;
            width: 90%;
        }

        .success-container h3 {
            color: #6a2dbd;
            font-size: 28px;
            margin-bottom: 15px;
        }

        .success-container p {
            font-size: 16px;
            margin-bottom: 30px;
        }

        .success-container a {
            display: inline-block;
            margin: 10px;
            padding: 12px 20px;
            background-color: #6a2dbd;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .success-container a:hover {
            background-color: #4a1a88;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <h3>🎉 Order Placed Successfully!</h3>
        <p>Thank you for your purchase. Your order ID is <strong>#<?= $order_id ?></strong>.</p>
        <a href="orders.php">View My Orders</a>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
