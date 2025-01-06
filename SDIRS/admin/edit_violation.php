<?php
session_start();
include('../include/config.php');

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: signin.php');
    exit();
}

// Get the user ID from the query string
$user_id = $_GET['id'];

// Fetch the user's data
$sql = "SELECT id, violation_description FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $violation_description = $_POST['violation_description'];

    // Update the violation description
    $update_sql = "UPDATE users SET violation_description = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $violation_description, $user_id);
    $update_stmt->execute();

    header('Location: admin_dashboard.php'); // Redirect back to the dashboard
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Violation</title>
</head>
<body>
    <h1>Edit Violation Description</h1>
    <form method="POST">
        <textarea name="violation_description" rows="5" cols="40"><?php echo htmlspecialchars($user['violation_description']); ?></textarea><br><br>
        <input type="submit" value="Update Violation">
    </form>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
