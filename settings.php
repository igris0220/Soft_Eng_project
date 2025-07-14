<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Fetch current user info
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = trim($_POST['name']);
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($new_name)) {
        $error = "Name cannot be empty.";
    } else {
        if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                $error = "To change password, fill all password fields.";
            } elseif ($new_password !== $confirm_password) {
                $error = "New password and confirmation do not match.";
            } else {
                $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $stmt->bind_result($hashed_password);
                $stmt->fetch();
                $stmt->close();

                if (!password_verify($current_password, $hashed_password)) {
                    $error = "Current password is incorrect.";
                }
            }
        }

        if (empty($error)) {
            if (!empty($new_password)) {
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET name = ?, password = ? WHERE id = ?");
                $stmt->bind_param("ssi", $new_name, $new_hashed_password, $user_id);
            } else {
                $stmt = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
                $stmt->bind_param("si", $new_name, $user_id);
            }

            if ($stmt->execute()) {
                $success = "Profile updated successfully.";
                $name = $new_name;
            } else {
                $error = "Failed to update profile. Try again.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Account Settings</title>
    <style>
        /* Reset */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #5a2a83, #9b45d2);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .settings-container {
            background: #fff;
            padding: 40px 50px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            max-width: 500px;
            width: 100%;
            color: #6a2dbd;
        }

        h2 {
            text-align: center;
            font-weight: 700;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #6a2dbd;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #4a1a88;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 2px solid #d6d0e9;
            border-radius: 10px;
            font-size: 16px;
            color: #4a1a88;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #6a2dbd;
            outline: none;
        }

        input[disabled] {
            background: #eee;
            color: #999;
            cursor: not-allowed;
        }

        input[type="submit"] {
            width: 100%;
            background: #6a2dbd;
            border: none;
            padding: 15px;
            font-weight: 700;
            font-size: 18px;
            border-radius: 12px;
            color: white;
            cursor: pointer;
            box-shadow: 0 6px 15px rgba(106, 45, 189, 0.6);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #4a1a88;
            box-shadow: 0 10px 20px rgba(74, 26, 136, 0.8);
        }

        .message {
            text-align: center;
            font-weight: 700;
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 10px;
            font-size: 16px;
        }

        .error {
            background-color: #ffdddd;
            border: 1.5px solid #e63737;
            color: #b71c1c;
        }

        .success {
            background-color: #ddffdd;
            border: 1.5px solid #3ecf3e;
            color: #2a7a2a;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            font-weight: 600;
            color: #6a2dbd;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #4a1a88;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="settings-container">
        <h2>Account Settings</h2>

        <?php if ($error): ?>
            <p class="message error"><?= htmlspecialchars($error) ?></p>
        <?php elseif ($success): ?>
            <p class="message success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>

            <label for="email">Email (cannot change):</label>
            <input type="email" id="email" value="<?= htmlspecialchars($email) ?>" disabled>

            <h3 style="color:#6a2dbd; margin-bottom:15px;">Change Password</h3>

            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" placeholder="Enter current password">

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" placeholder="Enter new password">

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password">

            <input type="submit" value="Update Profile">
        </form>

        <a class="back-link" href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
