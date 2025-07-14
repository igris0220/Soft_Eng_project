<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT c.id, p.name, p.price, p.image, c.quantity, (p.price * c.quantity) AS total
          FROM cart c
          JOIN products p ON c.product_id = p.id
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
        body {
            background: linear-gradient(135deg, #5a2a83, #9b45d2);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #4a1a88;
            padding: 30px;
            display: flex;
            justify-content: center;
        }

        .cart-container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(90, 42, 131, 0.4);
            width: 90%;
            max-width: 900px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #6a2dbd;
            text-transform: uppercase;
            letter-spacing: 1.3px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th, td {
            padding: 15px 12px;
            text-align: center;
            border-bottom: 1.5px solid #d6d0e9;
            font-weight: 600;
        }

        th {
            background-color: #6a2dbd;
            color: white;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
        }

        td img {
            border-radius: 8px;
            max-width: 60px;
            height: auto;
        }

        a.remove-btn {
            background: #e63737;
            color: white;
            padding: 8px 14px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        a.remove-btn:hover {
            background: #b71c1c;
        }

        .total-section {
            text-align: right;
            font-size: 20px;
            font-weight: 700;
            color: #4a1a88;
            margin-bottom: 30px;
        }

        a.checkout-btn {
            display: block;
            width: 250px;
            margin: 0 auto;
            padding: 15px;
            background-color: #6a2dbd;
            color: white;
            font-weight: 700;
            text-align: center;
            border-radius: 15px;
            text-decoration: none;
            box-shadow: 0 6px 15px rgba(106, 45, 189, 0.6);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        a.checkout-btn:hover {
            background-color: #4a1a88;
            box-shadow: 0 10px 20px rgba(74, 26, 136, 0.8);
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <h2>Your Cart</h2>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grand_total = 0;
                if ($cart_items->num_rows > 0):
                    while ($row = $cart_items->fetch_assoc()):
                        $grand_total += $row['total'];
                ?>
                <tr>
                    <td><img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>"></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td>₱<?= number_format($row['price'], 2) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td>₱<?= number_format($row['total'], 2) ?></td>
                    <td><a class="remove-btn" href="remove_from_cart.php?id=<?= $row['id'] ?>">Remove</a></td>
                    
                </tr>
                <?php
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="6" style="padding: 20px; font-style: italic; color: #999;">Your cart is empty.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="total-section">
            Total: ₱<?= number_format($grand_total, 2) ?>
        </div>

        <a class="checkout-btn" href="checkout.php">Proceed to Checkout</a>
        <a class="dashboard-btn" href="dashboard.php">← Back to Dashboard</a>

    </div>
</body>
</html>
