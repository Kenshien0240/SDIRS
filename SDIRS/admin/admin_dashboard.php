<?php
session_start(); // Start the session

// Include your database connection
include('../include/config.php');

// Initialize $success_message to avoid undefined variable warning
$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}


// Check if the user is logged in as admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // Redirect to login page if not logged in
    header('Location: signin.php');
    exit();
}

// Fetch all user accounts
$sql = "SELECT id, name, email, profile_picture, violation_date, violation_description FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin_dashboard.css">
    <link rel="stylesheet" href="css/admin_edit_success.css">
    <link rel="stylesheet" href="css/user_delete.css">
    <title>Admin Dashboard</title>
</head>
<body>
        <div id="deleteModal" class="custom-modal" style="display: none;">
            <div class="custom-modal-content">
                <h3>Are you sure you want to delete this user?</h3>
                <div class="modal-actions">
                    <button id="deleteYes" class="btn btn-danger">Yes</button>
                    <button id="deleteNo" class="btn btn-secondary">No</button>
                </div>
            </div>
        </div>

        <!-- Success Message Modal -->
        <div id="successModal" class="custom-modal" style="display: none;">
            <div class="custom-modal-content">
                <h3>User successfully deleted!</h3>
                <button id="successClose" class="btn btn-primary">Close</button>
            </div>
        </div>

    <div class="navbar">
        <h1 class="page-title">Admin Dashboard</h1>
        <div>
            <a href="admin_users.php" class="btn">Manage Users</a>
            <a href="#" class="btn btn-danger" onclick="confirmLogout()">Logout</a>
            
           


        </div>
    </div>
               
    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="custom-modal">
        <div class="custom-modal-content">
            <h3>Are you sure you want to logout?</h3>
            <div class="modal-actions">
                <button id="logoutYes" class="btn btn-danger">Yes</button>
                <button id="logoutNo" class="btn btn-secondary">No</button>
            </div>
        </div>
    </div>



    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</h1>
        <div class="table-container">
            <h2>User Accounts</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Profile Picture</th>
                        <th>Violation Date</th>
                        <th>Violation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>
                                <?php if ($row['profile_picture']): ?>
                                    <img src="../img/profile/<?php echo htmlspecialchars($row['profile_picture']); ?>" alt="Profile Picture" width="100">
                                <?php else: ?>
                                    No Profile Picture
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['violation_date']); ?></td> 
                            <td><?php echo htmlspecialchars($row['violation_description']); ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                                <td>
                                    <a href="#" class="btn btn-danger" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</a>
                                </td>

                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="js/admin_dashboard.js" ></script>                           
    <script src="js/logout_modal.js" ></script>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
