<?php
// Include your database connection
include('include/config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists
    $sql = "SELECT id, name, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $name, $hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Store user info in the session
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;

            // Redirect to the subfolder's index.php
            header('Location: student_logged/index.php');
            exit();
        } else {
            // Invalid password
            header('Location: login.php?error=invalid_password');
            exit();
        }
    } else {
        // Invalid credentials or empty fields
        if (empty($email) || empty($password)) {
            header('Location: login.php?error=empty_fields');
        } else {
            header('Location: login.php?error=invalid_credentials');
        }
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
