<?php
// Include your database connection here
include('../include/config.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input and get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Insert the data into the admin_accounts table
    $sql = "INSERT INTO admin_accounts (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $name, $email, $password);

    if ($stmt->execute()) {
        // Redirect to login.php with a success message
        header('Location: login.php?signup=success');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
