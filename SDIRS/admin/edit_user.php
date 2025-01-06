<?php
session_start();
include('../include/config.php');

// Check if the user is logged in as admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: signin.php');
    exit();
}

// Get the user ID from the query string
$user_id = $_GET['id'];

// Fetch the user's data
$sql = "SELECT id, name, email, profile_picture, violation_description, violation_date FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $violation_description = $_POST['violation_description'];
    $violation_date = $_POST['violation_date'];

    // Handle file upload for profile picture
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $profile_picture = $_FILES['profile_picture']['name'];
        $upload_dir = '../uploads/';
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_dir . $profile_picture);
    } else {
        $profile_picture = $user['profile_picture']; // Keep the existing profile picture if not uploading a new one
    }

    // Update user data in the database
    $update_sql = "UPDATE users SET name = ?, email = ?, profile_picture = ?, violation_description = ?, violation_date = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssi", $name, $email, $profile_picture, $violation_description, $violation_date, $user_id);
    $update_stmt->execute();

    // Set a success message in the session
    $_SESSION['success_message'] = 'User details updated successfully.';

    header('Location: admin_dashboard.php'); // Redirect back to the dashboard
    exit();

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/edit_user.css">
    <title>Edit User</title>
</head>
<body>
<div class="container">
        <h1>Edit User Details</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">

            <label for="violation_description">Violation Description:</label>
            <textarea name="violation_description" rows="5" cols="40"><?php echo htmlspecialchars($user['violation_description']); ?></textarea>

            <label for="violation_date">Violation Date:</label>
            <input type="date" name="violation_date" value="<?php echo htmlspecialchars($user['violation_date']); ?>">

            <label for="profile_picture">Profile Picture:</label>
            <input type="file" name="profile_picture">

            <input type="submit" value="Update User">
        </form>
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
