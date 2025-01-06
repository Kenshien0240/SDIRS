<?php
session_start(); // Start the session

// Check if the user is an admin
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    // Destroy the session
    session_unset();
    session_destroy();

    // Redirect to the admin login page
    header("Location: login.php"); // Redirect to the admin login page
    exit();
}

// If not an admin, redirect to a fallback page (optional)
header("Location: login.php"); // Redirect to the login page (in case of unexpected behavior)
exit();
