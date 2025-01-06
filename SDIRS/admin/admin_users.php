<?php
session_start();
// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php'); // Redirect to login page
    exit();
}
?>

<?php
// Start session and include configuration
include "../include/config.php";
session_start();

// Validate admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Fetch all users and their violations
$query = $conn->query("
    SELECT u.id AS user_id, u.name, u.email, u.role, u.profile_picture, 
           v.id AS violation_id, v.violation_type, v.violation_date, v.description, v.status
    FROM users u
    LEFT JOIN violations v ON u.id = v.user_id
    ORDER BY u.id ASC, v.violation_date DESC
");
$users = $query->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin - Manage Users</title>
        <link rel="stylesheet" href="../css/styles.css">
        <link rel="stylesheet" href="css/admin.css">
    </head>
    <body>
        <?php include 'header.php'; ?>

        <div class="container">
            <h1>Manage Users and Violations</h1>
            <a href="create_user.php" class="btn btn-success mb-3">Create New User</a>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Profile Picture</th>
                        <th>Violations</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($users as $user) {
                        echo "<tr>
                            <td>{$user['user_id']}</td>
                            <td>{$user['name']}</td>
                            <td>{$user['email']}</td>
                            <td>{$user['role']}</td>
                            <td><img src='../img/profile/{$user['profile_picture']}' width='50'></td>
                            <td>";
                        if (!empty($user['violation_id'])) {
                            echo "Type: {$user['violation_type']}<br>
                                  Date: {$user['violation_date']}<br>
                                  Status: {$user['status']}<br>
                                  <a href='edit_violation.php?id={$user['violation_id']}'>Edit</a>";
                        } else {
                            echo "No violations";
                        }
                        echo "</td>
                            <td>
                                <a href='edit_user.php?id={$user['user_id']}' class='btn btn-primary'>Edit</a>
                                <a href='delete_user.php?id={$user['user_id']}' class='btn btn-danger' 
                                   onclick='return confirm(\"Are you sure?\")'>Delete</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>
