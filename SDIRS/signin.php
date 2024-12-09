<?php
// Include your database connection
include('config.php');
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
            // Start the session and store user info
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;

            // Redirect to home page
            header('Location: Home.php');
            exit();
        } else {
            // Handle invalid password
            echo "Invalid credentials!";
        }
    } else {
        // Handle user not found
        echo "No user found with this email!";
    }

    $stmt->close();
    $conn->close();
}
?>
