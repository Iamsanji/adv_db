<?php
session_start();

if (!isset($_SESSION['account']) || !isset($_SESSION['account']['id'])) {
    header('Location: signin.php');
    exit();
}

require_once 'database.php';
$db = new Database();

if ($_SESSION['account']['role'] != 'superadmin') {
    header('Location: user-landing.php'); 
    exit();
}

// Get the admin ID to delete
if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];

    if ($admin_id == $_SESSION['account']['id']) {
        header('Location: admins.php?error=Cannot delete the logged-in superadmin');
        exit();
    }

    $stmt = $db->connect()->prepare("DELETE FROM account WHERE id = :id AND role = 'admin'");
    $stmt->bindParam(':id', $admin_id);

    if ($stmt->execute()) {
        header('Location: doctors.php');
    } else {
        header('Location: admin.php?error=Failed to delete admin');
    }
} else {
    
    header('Location: admin.php');
    exit();
}
?>
