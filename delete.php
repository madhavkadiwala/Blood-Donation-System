<?php

$conn = new mysqli('localhost', 'root', '', 'blood_donation_system');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// feedback message
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_ids']) && !empty($_POST['delete_ids'])) {
        // Sanitize and prepare IDs for deletion
        $ids_to_delete = array_map('intval', $_POST['delete_ids']);
        $ids_to_delete = implode(",", $ids_to_delete);

        // Delete query
        $sql = "DELETE FROM donors WHERE id IN ($ids_to_delete)";

        if ($conn->query($sql) === TRUE) {
            $message = "Records deleted successfully.";
        } else {
            $message = "Error deleting records: " . $conn->error;
        }
    } else {
        $message = "No records selected.";
    }
}

$conn->close();

// back to the view page with message
header("Location: view.php?message=" . urlencode($message));
exit;
