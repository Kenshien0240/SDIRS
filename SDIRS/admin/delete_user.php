<?php
session_start();
include('../include/config.php');

// Check if the user is logged in as admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: signin.php');
    exit();
}

$user_id = $_GET['id'];

// Fetch the user's profile picture to delete it from the server
$sql = "SELECT profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['profile_picture']) {
    $file_path = '../uploads/' . $user['profile_picture'];
    if (file_exists($file_path)) {
        unlink($file_path); // Delete the file
    }
}

// Delete the user from the database
$delete_sql = "DELETE FROM users WHERE id = ?";
$delete_stmt = $conn->prepare($delete_sql);
$delete_stmt->bind_param("i", $user_id);
$delete_stmt->execute();

// Redirect back to the dashboard with a success message
header('Location: admin_dashboard.php?delete_success=true');
exit();
?>
