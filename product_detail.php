<?php
session_start();
require 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid product ID.";
    exit;
}

$product_id = $_GET['id'];

$stmt = $conn->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Product not found.";
    exit;
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($product['name']) ?> - Product Details</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 40px;
        }

        .product-detail-container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
        }

        .product-image {
            flex: 1;
            min-width: 300px;
        }

        .product-image img {
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .product-info {
            flex: 2;
            min-width: 300px;
        }

        .product-info h2 {
            font-size: 32px;
            color: #6a2dbd;
            margin-bottom: 10px;
        }

        .product-info p {
            font-size: 16px;
            margin: 8px 0;
        }

        .product-info .price {
            font-size: 24px;
            font-weight: bold;
            color: #4a1a88;
            margin: 15px 0;
        }

        .add-cart-form {
            margin-top: 20px;
        }

        .add-cart-form input[type="number"] {
            padding: 10px;
            font-size: 16px;
            width: 80px;
            margin-right: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .add-cart-form button {
            padding: 12px 20px;
            background-color: #6a2dbd;
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-cart-form button:hover {
            background-color: #4a1a88;
        }

        .back-link {
            margin-top: 40px;
            text-align: center;
        }

        .back-link a {
            background-color: #6a2dbd;
            padding: 10px 18px;
            border-radius: 30px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .back-link a:hover {
            background-color: #4a1a88;
        }

        .login-prompt {
            margin-top: 20px;
        }

        .login-prompt a {
            color: #6a2dbd;
            font-weight: bold;
            text-decoration: none;
        }

        .login-prompt a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="product-detail-container">
    <div class="product-image">
        <img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    </div>

    <div class="product-info">
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <p><strong>Category:</strong> <?= htmlspecialchars($product['category_name']) ?></p>
        <p class="price">$<?= number_format($product['price'], 2) ?></p>
        <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($product['description'])) ?></p>
        <p><strong>Stock:</strong> <?= $product['stock'] ?></p>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form class="add-cart-form" action="add_to_cart.php" method="post">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <label>Quantity:</label>
                <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>">
                <button type="submit">Add to Cart</a></button>
            </form>
        <?php else: ?>
            <div class="login-prompt">
                <p><a href="login.php">Log in</a> to add to cart.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="back-link">
    <a href="shop.php">← Back to Shop</a>
</div>

</body>
</html>
