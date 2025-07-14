<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$name = htmlspecialchars($_SESSION['name']);
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 columns */
            grid-auto-rows: 180px; /* each row 180px height */
            gap: 30px;
            max-width: 960px;
            margin: 20px auto;
        }
        .dashboard-box {
            background: #6a2dbd;
            color: white;
            padding: 30px 25px;
            border-radius: 20px;
            text-decoration: none;
            box-shadow: 0 5px 20px rgba(106, 45, 189, 0.4);
            transition: background-color 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
        }
        .dashboard-box:hover {
            background: #4a1a88;
        }
        .dashboard-box h3 {
            margin: 0 0 15px 0;
            font-size: 1.8em;
        }
        .dashboard-box p {
            font-size: 1.1em;
            line-height: 1.3;
        }
        .logout-button {
            display: inline-block;
            background-color: #cc3333;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 5px 15px rgba(204, 51, 51, 0.4);
            transition: background-color 0.3s ease;
            padding: 10px 30px;
            margin: 20px auto;
            display: block;
            max-width: 200px;
        }
        .logout-button:hover {
            background-color: #990000;
        }
        .app-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="app-container">
    <h2>Welcome, <?= $name ?>!</h2>
    <p>Your role is: <strong><?= $role ?></strong></p>

    <?php if ($role === 'admin'): ?>
        <h3>Admin Panel</h3>
        <div class="dashboard-grid">
            <a href="admin/products.php" class="dashboard-box">
                <img src="images/shop.jpg" alt="Manage Products" class="dashboard-icon" />
                <h3>Manage Products</h3>
                <p>Add, edit or delete products</p>
            </a>
            <a href="admin/categories.php" class="dashboard-box">
                <h3>Manage Categories</h3>
                <p>Organize product categories</p>
            </a>
            <a href="admin/users.php" class="dashboard-box">
                <h3>Manage Users</h3>
                <p>View and control user accounts</p>
            </a>
            <a href="admin/inventory.php" class="dashboard-box">
                <h3>Manage Inventory</h3>
                <p>Track stock levels</p>
            </a>
            <a href="about_us.php" class="dashboard-box">
                <h3>About Us</h3>
                <p>Learn more about our company</p>
            </a>
            <a href="contact_us.php" class="dashboard-box">
                <h3>Contact Us</h3>
                <p>Get in touch with us</p>
            </a>
        </div>
    <?php else: ?>
        <h3>User Dashboard</h3>
        <div class="dashboard-grid">
            <a href="shop.php" class="dashboard-box">
                <h3>Shop</h3>
                <p>Browse and buy products</p>
            </a>
            <a href="cart.php" class="dashboard-box">
                <h3>Cart</h3>
                <p>Update your account details</p>
            </a>
            <a href="orders.php" class="dashboard-box">
                <h3>My Orders</h3>
                <p>View your purchase history</p>
            </a> 
            <a href="profile.php" class="dashboard-box">
                <h3>Profile</h3>
                <p>Manage your personal information</p>
            </a>
            <a href="settings.php" class="dashboard-box">
                <h3>Account Settings</h3>
                <p>Update your account details</p>
            </a>
            <a href="about_us.php" class="dashboard-box">
                <h3>About Us</h3>
                <p>Learn more about our company</p>
            </a>
            <a href="contact_us.php" class="dashboard-box">
                <h3>Contact Us</h3>
                <p>Get in touch with us</p>
            </a>
        </div>
    <?php endif; ?>

    <a href="logout.php" class="logout-button">Logout</a>
</div>

</body>
</html>
