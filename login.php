<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $is_admin_login = isset($_POST['admin_login']);  // Checkbox if admin login

    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $name, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            if ($is_admin_login) {
                // User must be admin to login here
                if ($role !== 'admin') {
                    $error = "You must be an admin to log in here.";
                } else {
                    // Admin login success
                    $_SESSION['user_id'] = $id;
                    $_SESSION['name'] = $name;
                    $_SESSION['role'] = $role;
                    header("Location: admin_dashboard.php");
                    exit;
                }
            } else {
                // Normal user login
                if ($role === 'admin') {
                    $error = "Please check 'Admin Login' to login as admin.";
                } else {
                    $_SESSION['user_id'] = $id;
                    $_SESSION['name'] = $name;
                    $_SESSION['role'] = $role;
                    header("Location: dashboard.php");
                    exit;
                }
            }
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Email not registered.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>

<div class="login-container">
    <h2>Login</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

  <form method="POST" action="">
    <label for="email">Email:</label>
    <input id="email" type="email" name="email" required>

    <label for="password">Password:</label>
    <input id="password" type="password" name="password" required>

    <input type="submit" value="Login" class="btn-primary">
</form>

    <p>No account? <a href="register.php">Sign up here</a></p>
</div>

</body>
</html>