<?php
session_start();
require 'db.php';
include 'check_admin.php';

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit;
}

$user_id = intval($_GET['id']);
$error = '';
$success = '';

// Fetch user data
$stmt = $conn->prepare("SELECT name, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    header("Location: manage_users.php");
    exit;
}

$stmt->bind_result($name, $email, $role);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email_new = trim($_POST['email']);
    $role_new = $_POST['role'];

    if (empty($name) || empty($email_new) || !in_array($role_new, ['user', 'admin'])) {
        $error = "Please fill all fields correctly.";
    } else {
        // Check if email is changed and if it exists
        if ($email_new !== $email) {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->bind_param("si", $email_new, $user_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $error = "Email already in use by another user.";
                $stmt->close();
            } else {
                $stmt->close();
                // Proceed with update
                $stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?");
                $stmt->bind_param("sssi", $name, $email_new, $role_new, $user_id);
                if ($stmt->execute()) {
                    $success = "User updated successfully!";
                    $email = $email_new; // Update current email var
                    $role = $role_new;
                } else {
                    $error = "Failed to update user.";
                }
                $stmt->close();
            }
        } else {
            // Email unchanged, update name & role
            $stmt = $conn->prepare("UPDATE users SET name=?, role=? WHERE id=?");
            $stmt->bind_param("ssi", $name, $role_new, $user_id);
            if ($stmt->execute()) {
                $success = "User updated successfully!";
                $role = $role_new;
            } else {
                $error = "Failed to update user.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
<h2>Edit User</h2>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php elseif ($success): ?>
    <p style="color:green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Name:</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required><br><br>

    <label>Role:</label><br>
    <select name="role" required>
        <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>User</option>
        <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
    </select><br><br>

    <input type="submit" value="Update User">
</form>

<p><a href="manage_users.php">Back to Manage Users</a></p>
</body>
</html>
