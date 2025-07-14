<?php
session_start();
require 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ensure POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request method.";
    exit;
}

// Validate product_id
if (!isset($_POST['product_id'])) {
    echo "Missing product ID.";
    exit;
}

$product_id = intval($_POST['product_id']);
$user_id = $_SESSION['user_id'];

// Use quantity from POST if sent, otherwise default to 1
$quantity = isset($_POST['quantity']) ? max(1, intval($_POST['quantity'])) : 1;

// ... rest of your code ...


// Check product existence and stock
$stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Invalid product.";
    exit;
}

$product = $result->fetch_assoc();

// If out of stock
if ($product['stock'] <= 0) {
    echo "Product out of stock.";
    exit;
}

// Check if product already in cart
$check = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
$check->bind_param("ii", $user_id, $product_id);
$check->execute();
$check_result = $check->get_result();

if ($check_result->num_rows > 0) {
    $cart_item = $check_result->fetch_assoc();
    $new_quantity = $cart_item['quantity'] + $quantity;

    if ($new_quantity > $product['stock']) {
        echo "Cannot add more than available stock.";
        exit;
    }

    $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $update->bind_param("ii", $new_quantity, $cart_item['id']);
    $update->execute();
} else {
    $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $insert->bind_param("iii", $user_id, $product_id, $quantity);
    $insert->execute();
}

// Redirect to cart page
header("Location: cart.php");
exit;
?>
