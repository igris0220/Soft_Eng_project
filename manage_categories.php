<?php
session_start();
require 'db.php';
include 'check_admin.php';

// Fetch all categories
$result = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
    <style>
        body {
            background: linear-gradient(135deg, #5a2a83, #9b45d2);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 800px;
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

        a.button {
            display: inline-block;
            background-color: #6a2dbd;
            color: #fff;
            padding: 10px 20px;
            margin-bottom: 20px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        a.button:hover {
            background-color: #4a1a88;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        th {
            background-color: #f1e9ff;
            color: #6a2dbd;
        }

        td a {
            color: #6a2dbd;
            font-weight: bold;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #6a2dbd;
            font-weight: bold;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-link-top">
            <a href="admin_dashboard.php">⬅ Back to Dashboard</a>
        </div>

        <h2>Manage Categories</h2>

        <a href="add_category.php" class="button">➕ Add New Category</a>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td>
                    <a href="edit_category.php?id=<?= $row['id'] ?>">Edit</a> |
                    <a href="delete_category.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this category?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
