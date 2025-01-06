<?php
// Include your database connection
include('../include/config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the admin exists in the database
    $sql = "SELECT id, name, password FROM admin_accounts WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $name, $hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Store admin info in the session
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_name'] = $name;
            $_SESSION['is_admin'] = true;

            // Redirect to the admin dashboard
            header('Location: admin_dashboard.php');
            exit();
        } else {
            // Invalid password
            header('Location: admin_login.php?error=invalid_password');
            exit();
        }
    } else {
        // Invalid credentials or empty fields
        if (empty($email) || empty($password)) {
            header('Location: admin_login.php?error=empty_fields');
        } else {
            header('Location: admin_login.php?error=invalid_credentials');
        }
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
