<?php
session_start();
require 'db.php';
include 'check_admin.php';

if (!isset($_GET['id'])) {
    header("Location: manage_products.php");
    exit;
}

$product_id = intval($_GET['id']);
$error = '';
$success = '';

// Fetch categories for dropdown
$categories = $conn->query("SELECT id, name FROM categories");

// Fetch product details with stock
$stmt = $conn->prepare("SELECT name, price, category_id, description, stock FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    header("Location: manage_products.php");
    exit;
}

$stmt->bind_result($name, $price, $category_id, $description, $stock);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $new_category_id = intval($_POST['category_id']);
    $description = trim($_POST['description']);
    $stock = intval($_POST['stock']);

    if (empty($name) || $price <= 0 || $new_category_id <= 0 || $stock < 0) {
        $error = "Please fill all required fields with valid values.";
    } else {
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, category_id=?, description=?, stock=? WHERE id=?");
        $stmt->bind_param("sdisii", $name, $price, $new_category_id, $description, $stock, $product_id);

        if ($stmt->execute()) {
            $success = "Product updated successfully!";
        } else {
            $error = "Failed to update product. Try again.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
</head>
<body>
<h2>Edit Product</h2>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php elseif ($success): ?>
    <p style="color:green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Product Name:</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required><br><br>

    <label>Price:</label><br>
    <input type="number" name="price" step="0.01" min="0.01" value="<?= htmlspecialchars($price) ?>" required><br><br>

    <label>Category:</label><br>
    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php while ($cat = $categories->fetch_assoc()): ?>
            <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $category_id) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Description:</label><br>
    <textarea name="description" rows="4" cols="50"><?= htmlspecialchars($description) ?></textarea><br><br>

    <label>Stock:</label><br>
    <input type="number" name="stock" min="0" value="<?= htmlspecialchars($stock) ?>" required><br><br>

    <input type="submit" value="Update Product">
</form>

<p><a href="manage_products.php">Back to Manage Products</a></p>
</body>
</html>
