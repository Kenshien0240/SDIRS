<?php
session_start();
include "../include/config.php"; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        header('Location: admin_login.php?error=empty_fields');
        exit();
    }

    // Query the admin_accounts table
    $stmt = $conn->prepare("SELECT * FROM admin_accounts WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $admin['password'])) {
            // Set admin session
            $_SESSION['is_admin'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];

            header('Location: admin_dashboard.php'); // Redirect to admin dashboard
            exit();
        } else {
            header('Location: admin_login.php?error=invalid_password');
            exit();
        }
    } else {
        header('Location: admin_login.php?error=user_not_found');
        exit();
    }
}
?>
