<?php
session_start();
require 'db.php';
include 'check_admin.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    if ($name != '') {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            header("Location: manage_categories.php");
            exit;
        } else {
            $error = "Failed to add category.";
        }
    } else {
        $error = "Category name cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
    <style>
        body {
            background: linear-gradient(135deg, #5a2a83, #9b45d2);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 600px;
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
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #6a2dbd;
            color: white;
            border: none;
            padding: 12px 24px;
            margin-top: 20px;
            border-radius: 12px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #4a1a88;
        }

        .error-message {
            color: #cc0000;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-link-top">
            <a href="manage_categories.php">⬅ Back to Categories</a>
        </div>

        <h2>Add New Category</h2>

        <?php if (!empty($error)) echo "<p class='error-message'>" . htmlspecialchars($error) . "</p>"; ?>

        <form method="POST" action="">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br>
            <input type="submit" value="Add Category">
        </form>
    </div>
</body>
</html>
