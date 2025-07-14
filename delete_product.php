<?php
session_start();
require 'db.php';
include 'check_admin.php';

if (!isset($_GET['id'])) {
    header("Location: manage_products.php");
    exit;
}

$product_id = intval($_GET['id']);

// Delete product
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: manage_products.php?msg=Product deleted successfully");
    exit;
} else {
    $stmt->close();
    header("Location: manage_products.php?error=Failed to delete product");
    exit;
}
?>
