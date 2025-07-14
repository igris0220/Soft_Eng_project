<?php
session_start();
require 'db.php';
include 'check_admin.php';

// Fetch categories for dropdown
$categories = $conn->query("SELECT id, name FROM categories");

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $description = trim($_POST['description']);
    $stock = intval($_POST['stock']);

    if (empty($name) || $price <= 0 || $category_id <= 0 || $stock < 0) {
        $error = "Please fill all required fields with valid values.";
    } else {
        // Insert new product with stock
        $stmt = $conn->prepare("INSERT INTO products (name, price, category_id, description, stock) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdisi", $name, $price, $category_id, $description, $stock);

        if ($stmt->execute()) {
            $success = "Product added successfully!";
        } else {
            $error = "Failed to add product. Try again.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Product</title>
    <style>
        body {
            background: linear-gradient(135deg, #5a2a83, #9b45d2);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 700px;
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

        form label {
            font-weight: 600;
            display: block;
            margin-top: 15px;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
            resize: vertical;
        }

        textarea {
            font-family: inherit;
        }

        input[type="submit"] {
            background-color: #6a2dbd;
            color: white;
            border: none;
            padding: 12px 24px;
            margin-top: 25px;
            border-radius: 12px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
            display: block;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #4a1a88;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .error {
            color: #cc0000;
        }

        .success {
            color: #007700;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-link-top">
            <a href="manage_products.php">⬅ Back to Manage Products</a>
        </div>

        <h2>Add New Product</h2>

        <?php if ($error): ?>
            <p class="message error"><?= htmlspecialchars($error) ?></p>
        <?php elseif ($success): ?>
            <p class="message success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" min="0.01" required>

            <label for="category_id">Category:</label>
            <select id="category_id" name="category_id" required>
                <option value="">Select Category</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endwhile; ?>
            </select>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4"></textarea>

            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" min="0" value="0" required>

            <input type="submit" value="Add Product">
        </form>
    </div>
</body>
</html>
