<?php
session_start();

// Ensure the session is active
if (!isset($_SESSION['account']) || !isset($_SESSION['account']['id'])) {
    header('Location: signin.php');
    exit();
}

require_once 'database.php';
$db = new Database();

// Check if the user has permission to delete (Superadmin or Admin)
if ($_SESSION['account']['role'] != 'superadmin' && !$_SESSION['account']['is_admin']) {
    header('Location: user-landing.php'); // Redirect if they don't have permission
    exit();
}

// Get the patient ID to delete
if (isset($_GET['id'])) {
    $patient_id = $_GET['id'];

    // Prepare and execute the delete query
    $stmt = $db->connect()->prepare("DELETE FROM account WHERE id = :id AND role = 'customer'");
    $stmt->bindParam(':id', $patient_id);

    if ($stmt->execute()) {
        // Redirect after successful deletion
        header('Location: patients.php?message=Patient deleted successfully');
    } else {
        // Handle error case
        header('Location: patients.php?error=Failed to delete patient');
    }
} else {
    // If ID is not provided in URL
    header('Location: patients.php');
    exit();
}
?>
