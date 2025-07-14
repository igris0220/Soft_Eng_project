<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Invalid or missing id parameter
    header("Location: cart.php");
    exit;
}

$cart_id = intval($_GET['id']);

// Verify that the cart item belongs to the logged-in user before deleting
$stmt = $conn->prepare("SELECT id FROM cart WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $cart_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Safe to delete
    $del = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $del->bind_param("i", $cart_id);
    $del->execute();
}

// Redirect back to cart page
header("Location: cart.php");
exit;
?>
