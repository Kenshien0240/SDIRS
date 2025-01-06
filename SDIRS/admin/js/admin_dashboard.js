let userIdToDelete = null;

function confirmDelete(userId) {
    userIdToDelete = userId;
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.style.display = 'block';
}

// Close Delete Modal
document.getElementById('deleteNo').addEventListener('click', () => {
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.style.display = 'none';
    userIdToDelete = null;
});

// Confirm Delete and Show Success Message
document.getElementById('deleteYes').addEventListener('click', () => {
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.style.display = 'none';

    // Perform the delete operation
    fetch(`delete_user.php?id=${userIdToDelete}`)
        .then(response => {
            if (response.ok) {
                // Show success message modal
                const successModal = document.getElementById('successModal');
                successModal.style.display = 'block';
            }
        });

    userIdToDelete = null;
});

// Close Success Modal
document.getElementById('successClose').addEventListener('click', () => {
    const successModal = document.getElementById('successModal');
    successModal.style.display = 'none';

    // Reload the page to reflect changes
    window.location.reload();
});
