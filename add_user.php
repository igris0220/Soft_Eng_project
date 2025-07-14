<?php
session_start();
require 'db.php';
include 'check_admin.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($name) || empty($email) || empty($password) || !in_array($role, ['user', 'admin'])) {
        $error = "Please fill all fields correctly.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);
            if ($stmt->execute()) {
                $success = "User added successfully!";
            } else {
                $error = "Failed to add user.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New User</title>
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
            display: block;
            margin-top: 15px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
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
            <a href="manage_users.php">⬅ Back to Manage Users</a>
        </div>

        <h2>Add New User</h2>

        <?php if ($error): ?>
            <p class="message error"><?= htmlspecialchars($error) ?></p>
        <?php elseif ($success): ?>
            <p class="message success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="">Select Role</option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <input type="submit" value="Add User">
        </form>
    </div>
</body>
</html>
