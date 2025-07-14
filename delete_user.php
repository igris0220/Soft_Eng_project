<?php
session_start();
require 'db.php';
include 'check_admin.php';

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit;
}

$user_id = intval($_GET['id']);

// Prevent admin from deleting themselves (optional)
if ($_SESSION['user_id'] == $user_id) {
    header("Location: manage_users.php?error=cant_delete_self");
    exit;
}

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

header("Location: manage_users.php?deleted=1");
exit;
