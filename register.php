<?php
session_start();
include 'db.php';

$secret_admin_key = "MySecretAdminKey123"; // Change this to something more secure in production
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $admin_key = $_POST['admin_key'] ?? '';
    $role = 'user'; // Default role

    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Check if admin key matches
        if (!empty($admin_key) && $admin_key === $secret_admin_key) {
            $role = 'admin';
        }

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $stmt->close();

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                // Save user data to session
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['name'] = $name;
                $_SESSION['role'] = $role;

                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
            $stmt->close();
        }
    }
}

// If error exists, show it (you can include this in your HTML)
if (!empty($error)) {
    echo "<p style='color: red;'>$error</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Register</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>

<div class="register-container">
    <h2>Sign Up</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="name">Name:</label><br>
        <input id="name" type="text" name="name" required><br>

        <label for="email">Email:</label><br>
        <input id="email" type="email" name="email" required><br>

        <label for="password">Password:</label><br>
        <input id="password" type="password" name="password" required><br>

        <label for="admin_key">Admin Key (optional):</label><br>
        <input id="admin_key" type="text" name="admin_key"><br><br>

        <input type="submit" value="Register" class="btn-primary">
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>
