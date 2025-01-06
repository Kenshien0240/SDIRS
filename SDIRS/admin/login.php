<?php
session_start();

// If admin is already logged in, redirect to admin dashboard
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header('Location: admin_dashboard.php'); // Change this to your admin dashboard
    exit();
}

// Display success message after sign-up
if (isset($_GET['signup']) && $_GET['signup'] === 'success') {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            alert("Sign-up successful! You can now log in.");
        });
    </script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/admin_login.css">
    <title>SDIRS | Admin Login Page</title>
    <style>
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action="signup.php" method="POST">
                <h1>Create Admin Account</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for registration</span>
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Sign Up</button>
            </form>
        </div>
    
        <div class="form-container sign-in">
            <form action="signin.php" method="POST">
                <h1>Sign In</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email and password</span>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                
                <!-- Error message -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="error-message">
                        <?php 
                        if ($_GET['error'] === 'invalid_password') {
                            echo "Invalid password. Please try again.";
                        } elseif ($_GET['error'] === 'user_not_found') {
                            echo "Invalid email";
                        } elseif ($_GET['error'] === 'invalid_credentials') {
                            echo "Invalid email and password. Please check your credentials.";
                        } elseif ($_GET['error'] === 'empty_fields') {
                            echo "Please fill in all fields.";
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <a href="#">Forget Your Password?</a>
                <button type="submit">Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your admin details to access the dashboard</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Admin!</h1>
                    <p>Register with your personal details to manage the system</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/login.js"></script>
</body>
</html>
