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
    // Redirect to the admin logout page
    window.location.href = "logout.php"; // Make sure this points to the correct admin logout script
});

// Close modal when clicking outside the modal content
window.addEventListener("click", function (event) {
    if (event.target === logoutModal) {
        logoutModal.style.display = "none";
    }
});
