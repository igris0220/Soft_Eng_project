<?php
session_start();
require 'db.php';
include 'check_admin.php';

if (!isset($_GET['id'])) {
    header("Location: manage_categories.php");
    exit;
}

$id = intval($_GET['id']);
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    if ($name != '') {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        if ($stmt->execute()) {
            header("Location: manage_categories.php");
            exit;
        } else {
            $error = "Failed to update category.";
        }
    } else {
        $error = "Category name cannot be empty.";
    }
}

// Fetch existing category
$stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($name);
if (!$stmt->fetch()) {
    header("Location: manage_categories.php");
    exit;
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head><title>Edit Category</title></head>
<body>
<h2>Edit Category</h2>

<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST" action="">
    <label>Name:</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required><br><br>
    <input type="submit" value="Update Category">
</form>

<p><a href="manage_categories.php">Back to Categories</a></p>
</body>
</html>
