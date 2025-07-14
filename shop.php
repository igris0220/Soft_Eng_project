<?php
session_start();
include 'db.php';

$category_id = isset($_GET['category']) ? intval($_GET['category']) : 0;

// Fetch all categories
$cat_sql = "SELECT * FROM categories";
$cat_result = $conn->query($cat_sql);
if (!$cat_result) die("Error fetching categories: " . $conn->error);

// Fetch products
if ($category_id > 0) {
    $stmt = $conn->prepare("SELECT p.*, c.name AS category_name FROM products p 
                            LEFT JOIN categories c ON p.category_id = c.id 
                            WHERE p.category_id = ? AND p.stock > 0");
    $stmt->bind_param("i", $category_id);
} else {
    $stmt = $conn->prepare("SELECT p.*, c.name AS category_name FROM products p 
                            LEFT JOIN categories c ON p.category_id = c.id 
                            WHERE p.stock > 0");
}

if ($stmt) {
    $stmt->execute();
    $products = $stmt->get_result();
    $stmt->close();
} else {
    die("Error preparing product query: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(rgba(48, 47, 54, 0.9), rgba(48, 47, 54, 0.9)), 
                        url('backgrounds/shop-bg.jpg') center center/cover no-repeat fixed;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 60px 20px;
        }

        .shop-container {
            max-width: 1300px;
            background-color: rgba(255, 255, 255, 0.97);
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.25);
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #6a2dbd;
            font-size: 34px;
            margin-bottom: 35px;
            font-weight: bold;
        }

        .filter-bar {
            margin-bottom: 30px;
            display: flex;
            justify-content: flex-end;
        }

        .filter-bar select {
            padding: 12px 16px;
            border-radius: 10px;
            border: 2px solid #d6d0e9;
            font-size: 16px;
            outline: none;
            cursor: pointer;
        }

        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            justify-content: center;
        }

        .product-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 18px rgba(106, 45, 189, 0.12);
            padding: 20px;
            width: 270px;
            transition: 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 28px rgba(106, 45, 189, 0.2);
        }

        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 12px;
        }

        .product-card h3 {
            font-size: 20px;
            color: #6a2dbd;
            margin-bottom: 8px;
        }

        .product-card p {
            font-size: 14px;
            margin: 4px 0;
            color: #444;
        }

        .product-card a, .product-card button {
            margin-top: 10px;
            background-color: #6a2dbd;
            color: white;
            padding: 10px 14px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .product-card a:hover, .product-card button:hover {
            background-color: #4a1a88;
        }

        .back-link {
            margin-top: 50px;
            text-align: center;
        }

        .back-link a {
            background-color: #6a2dbd;
            padding: 12px 24px;
            border-radius: 30px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .back-link a:hover {
            background-color: #4a1a88;
        }

        .no-products {
            text-align: center;
            font-size: 18px;
            color: #555;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="shop-container">
        <h2>Explore Our Equipment</h2>

        <!-- Category Filter -->
        <form method="GET" action="shop.php" class="filter-bar">
            <select name="category" onchange="this.form.submit()">
                <option value="0">All Categories</option>
                <?php while ($cat = $cat_result->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $category_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <!-- Product Listing -->
        <?php if ($products->num_rows == 0): ?>
            <p class="no-products">No products available in this category.</p>
        <?php else: ?>
            <div class="product-grid">
                <?php while ($product = $products->fetch_assoc()): ?>
                    <div class="product-card">
                        <?php if ($product['image']): ?>
                            <img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p><strong>Category:</strong> <?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></p>
                        <p><strong>Price:</strong> ₱<?= number_format($product['price'], 2) ?></p>
                        <p><strong>Stock:</strong> <?= $product['stock'] ?></p>
                        <a href="product_detail.php?id=<?= $product['id'] ?>">View Details</a>

                        <!-- Add to Cart -->
                        <form action="add_to_cart.php" method="POST" style="margin-top: 8px;">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <button type="submit">Add to Cart</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <!-- Back to Dashboard -->
        <div class="back-link">
            <a href="dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
