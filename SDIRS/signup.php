<?php
// Include your database connection here
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input from the form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password for security

    // Insert user data into the database
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $name, $email, $password);

    if ($stmt->execute()) {
        // Redirect to the signin page after successful signup
        header('Location: login.php');
        exit();
    } else {
        // Handle the error if signup fails
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
