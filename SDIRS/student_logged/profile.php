<?php
// Start session and include configuration
include "../include/config.php";
session_start();

// Initialize a message variable
$message = "";

// Validate if the user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $message = "Please login to access this page.";
    header('Location: ../login.php');
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$query2 = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$query2->bind_param('i', $user_id);
$query2->execute();
$result = $query2->get_result();
$row = $result->fetch_assoc();

// Check if the user exists in the database
if (!$row) {
    $message = "User not found. Please log in again.";
    header('Location: ../login.php');
    exit();
}

// Fetch user violations
$sql = "SELECT name, email, profile_picture, violation_description, violation_date FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Prepare violations for display
$violations = [];
if (!empty($row['violation_description']) && !empty($row['violation_date'])) {
    $violations[] = [
        'description' => $row['violation_description'],
        'date' => $row['violation_date']
    ];
}
// Update Profile Picture (if submitted)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $target_dir = "../img/profile/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an image
    if (getimagesize($_FILES["profile_picture"]["tmp_name"])) {
        // Move uploaded file to the uploads directory
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            // Update profile picture in database
            $update_query = $conn->prepare("UPDATE `users` SET profile_picture = ? WHERE id = ?");
            $update_query->bind_param('si', basename($_FILES["profile_picture"]["name"]), $user_id);
            if ($update_query->execute()) {
                $message = "Profile picture updated successfully!";
            } else {
                $message = "Error updating profile picture.";
            }
            $update_query->close();
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    } else {
        $message = "File is not an image.";
    }
}

$query2->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>User Profile - Student Disciplinary Infraction Recording System</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="../css/profile.css" />
    <link rel="stylesheet" href="../css/modal.css" />
    <style>
        .profile-picture {
            border-radius: 20%; /* Makes the image circular */
            border: 5px solid #f8f9fa; /* Adds a border with a light color */
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* Adds a subtle shadow */
            transition: transform 0.3s ease; /* Adds a hover effect */
            height: 300px;
        }

        .profile-picture:hover {
            transform: scale(1.05); /* Slight zoom on hover */
        }
    </style>
</head>
<body id="page-top">
    <!-- Navigation-->
    <?php include 'header.php'; ?>

    <!-- Display Message if Exists -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-info" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- Profile Section -->
    <section class="page-section mt-5" id="profile">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card mb-4 shadow p-3 mb-5 bg-body-tertiary rounded">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <!-- Profile Picture -->
                                <img src="../img/profile/<?php echo !empty($row['profile_picture']) ? htmlspecialchars($row['profile_picture']) : 'default-profile.jpg'; ?>" 
                                    class="img-fluid profile-picture" 
                                    alt="Profile Picture">
                                <div class="card-body">
                                    <h5 class="card-title"><span class="text-dark">Student Name:</span> <?php echo $row['name']; ?></h5>
                                    <p class="card-text">Email: <span class="email"><?php echo $row['email']; ?></span></p>

                                    <!-- Update Profile Picture Form -->
                                    <form action="profile.php" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="profile_picture" class="update-title">Update Profile Picture</label>
                                            <input type="file" class="form-control" name="profile_picture" id="profile_picture" required>
                                        </div>
                                        <button type="submit" class="btn btn-outline-danger mt-3">Update Picture</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Violations Section -->
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">Violations</h5>
                                    <?php if (!empty($violations)): ?>
                                        <ul class="list-group">
                                            <?php foreach ($violations as $violation): ?>
                                                <li class="list-group-item">
                                                    <strong>Date:</strong> <?php echo htmlspecialchars($violation['date']); ?> <br>
                                                    <strong>Description:</strong> <?php echo htmlspecialchars($violation['description']); ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p>No violations recorded.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
        
        <script>
    // Select modal and buttons
    const logoutModal = document.getElementById("logoutModal");
    const logoutYes = document.getElementById("logoutYes");
    const logoutNo = document.getElementById("logoutNo");

    // Function to open the modal
    function confirmLogout() {
        logoutModal.style.display = "block";
    }

    // Close modal when 'No' is clicked
    logoutNo.addEventListener("click", function () {
        logoutModal.style.display = "none";
    });

    // Logout when 'Yes' is clicked
    logoutYes.addEventListener("click", function () {
        window.location.href = "../include/logout.php"; // Adjust path if needed
    });

    // Close modal when clicking outside the modal content
    window.addEventListener("click", function (event) {
        if (event.target === logoutModal) {
            logoutModal.style.display = "none";
        }
    });
</script>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>

</body>
</html>
