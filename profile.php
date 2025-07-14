<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = trim($_POST['name']);
    $new_email = trim($_POST['email']);

    if (empty($new_name) || empty($new_email)) {
        $error = "Name and Email cannot be empty.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $new_name, $new_email, $user_id);
        if ($stmt->execute()) {
            $success = "Profile updated successfully.";
            $name = $new_name;
            $email = $new_email;
        } else {
            $error = "Failed to update profile. Try again.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile Settings</title>
    <style>
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
        .register-container {
          background: #fff;
          padding: 40px 50px;
          border-radius: 15px;
          box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
          max-width: 420px;
          width: 100%;
          text-align: center;
        }

        /* Heading */
        .register-container h2 {
          color: #6a2dbd;
          font-weight: 700;
          margin-bottom: 30px;
          font-size: 28px;
          letter-spacing: 1.2px;
          text-transform: uppercase;
        }

        /* Labels */
        .register-container label {
          display: block;
          text-align: left;
          margin-bottom: 8px;
          font-weight: 600;
          color: #333;
          font-size: 14px;
        }

        /* Inputs */
        .register-container input[type="text"],
        .register-container input[type="email"] {
          width: 100%;
          padding: 14px 18px;
          margin-bottom: 22px;
          border: 2px solid #d6d0e9;
          border-radius: 10px;
          font-size: 16px;
          transition: border-color 0.3s ease, box-shadow 0.3s ease;
          outline: none;
        }

        .register-container input[type="text"]:focus,
        .register-container input[type="email"]:focus {
          border-color: #6a2dbd;
          box-shadow: 0 0 8px #6a2dbdaa;
        }

        /* Submit button */
        .register-container .btn-primary {
          background: #6a2dbd;
          color: white;
          font-weight: 700;
          font-size: 18px;
          padding: 14px 0;
          border: none;
          border-radius: 12px;
          width: 100%;
          cursor: pointer;
          box-shadow: 0 6px 15px rgba(106, 45, 189, 0.6);
          transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .register-container .btn-primary:hover {
          background: #4a1a88;
          box-shadow: 0 10px 20px rgba(74, 26, 136, 0.8);
        }

        /* Error message */
        .register-container .error {
          background-color: #ffdddd;
          border: 1.5px solid #e63737;
          padding: 14px;
          border-radius: 8px;
          color: #b71c1c;
          font-weight: 600;
          margin-bottom: 28px;
        }

        /* Success message */
        .register-container .success {
          background-color: #d4edda;
          border: 1.5px solid #3c9a41;
          padding: 14px;
          border-radius: 8px;
          color: #155724;
          font-weight: 600;
          margin-bottom: 28px;
        }

        /* Paragraph link */
        .register-container p {
          margin-top: 18px;
          font-size: 14px;
          color: #555;
        }

        .register-container p a {
          color: #6a2dbd;
          font-weight: 700;
          text-decoration: none;
          transition: color 0.3s ease;
        }

        .register-container p a:hover {
          color: #4a1a88;
          text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Profile Settings</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

            <input type="submit" class="btn-primary" value="Update Profile">
        </form>

        <p><a href="dashboard.php">← Back to Dashboard</a></p>
    </div>
</body>
</html>
