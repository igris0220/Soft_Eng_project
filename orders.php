<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch orders for the logged-in user
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <style>
        /* Reusing your existing style for consistency */

        /* Reset */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Body background */
        body {
            background: linear-gradient(135deg, #5a2a83, #9b45d2);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Container */
        .orders-container {
            background: #fff;
            padding: 40px 50px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            max-width: 900px;
            width: 100%;
            text-align: center;
        }

        /* Heading */
        .orders-container h2 {
            color: #6a2dbd;
            font-weight: 700;
            margin-bottom: 30px;
            font-size: 28px;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        /* Table style */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        thead {
            background-color: #6a2dbd;
            color: white;
        }

        th, td {
            padding: 14px 18px;
            text-align: center;
            border-bottom: 2px solid #d6d0e9;
            font-size: 16px;
        }

        tr:nth-child(even) {
            background-color: #f8f5ff;
        }

        tr:hover {
            background-color: #e8dbff;
        }

        /* View link */
        .view-link {
            color: #6a2dbd;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .view-link:hover {
            color: #4a1a88;
            text-decoration: underline;
        }

        /* No orders message */
        .no-orders {
            font-weight: 600;
            color: #b71c1c;
            background-color: #ffdddd;
            border: 1.5px solid #e63737;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 28px;
        }

        /* Back button */
        .back-btn {
            display: inline-block;
            padding: 14px 30px;
            background: #6a2dbd;
            color: white;
            font-weight: 700;
            border-radius: 12px;
            text-decoration: none;
            box-shadow: 0 6px 15px rgba(106, 45, 189, 0.6);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .back-btn:hover {
            background-color: #4a1a88;
            box-shadow: 0 10px 20px rgba(74, 26, 136, 0.8);
        }

        /* Responsive */
        @media (max-width: 600px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            th, td {
                text-align: left;
                padding: 10px;
            }

            th {
                background-color: #6a2dbd;
                color: white;
            }

            td {
                border-bottom: 1px solid #ddd;
                position: relative;
                padding-left: 50%;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                top: 14px;
                font-weight: 700;
                color: #6a2dbd;
            }
        }
    </style>
</head>
<body>
    <div class="orders-container">
        <h2>My Orders</h2>

        <?php if ($orders->num_rows == 0): ?>
            <p class="no-orders">You have no orders yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $orders->fetch_assoc()): ?>
                    <tr>
                        <td data-label="Order ID"><?php echo $order['id']; ?></td>
                        <td data-label="Date"><?php echo $order['created_at']; ?></td>
                        <td data-label="Total Price">$<?php echo number_format($order['total_price'], 2); ?></td>
                        <td data-label="Status"><?php echo ucfirst($order['status']); ?></td>
                        <td data-label="Details"><a class="view-link" href="order_detail.php?order_id=<?php echo $order['id']; ?>">View Items</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a class="back-btn" href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
