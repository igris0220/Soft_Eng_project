<?php
require('check_admin.php'); // Starts session and checks admin
require('db.php');

// Get counts
$user_count = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$product_count = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$order_count = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            background: linear-gradient(135deg,rgb(3, 3, 3),rgb(184, 181, 186));
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color:rgb(64, 63, 65);
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            max-width: 900px;
            margin: 50px auto;
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(60, 59, 60, 0.4);
        }

        h1 {
            text-align: center;
            color:rgb(92, 91, 93);
            margin-bottom: 30px;
            font-size: 32px;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 40px;
        }

        .stat-card {
            background: #f1e9ff;
            padding: 25px 30px;
            border-radius: 15px;
            text-align: center;
            width: 28%;
            box-shadow: 0 4px 15px rgba(106, 45, 189, 0.2);
        }

        .stat-card h2 {
            font-size: 20px;
            margin-bottom: 10px;
            color:rgb(81, 80, 84);
        }

        .stat-card p {
            font-size: 24px;
            font-weight: bold;
            color:rgb(154, 151, 159);
        }

        nav ul {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            list-style: none;
            padding: 0;
        }

        nav ul li {
            text-align: center;
        }

        nav a {
            display: block;
            background-color:rgb(93, 92, 95);
            color: white;
            padding: 15px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color:rgb(95, 94, 97);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Admin Dashboard</h1>

        <div class="stats">
            <div class="stat-card">
                <h2>Total Users</h2>
                <p><?= $user_count ?></p>
            </div>
            <div class="stat-card">
                <h2>Total Products</h2>
                <p><?= $product_count ?></p>
            </div>
            <div class="stat-card">
                <h2>Total Orders</h2>
                <p><?= $order_count ?></p>
            </div>
        </div>

        <nav>
            <ul>
                <li><a href="manage_products.php">Manage Products</a></li>
                <li><a href="manage_categories.php">Manage Categories</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_orders.php">Manage Orders</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>
