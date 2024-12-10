<?php
session_start();

if (!isset($_SESSION['account']) || !isset($_SESSION['account']['id'])) {
    header('Location: signin.php');
    exit();
}

require_once 'database.php';
$db = new Database();

if ($_SESSION['account']['role'] != 'superadmin' && !$_SESSION['account']['is_admin']) {
    header('Location: user-landing.php'); 
    exit();
}

if (isset($_GET['id'])) {
    $patient_id = $_GET['id'];

    $stmt = $db->connect()->prepare("DELETE FROM account WHERE id = :id AND role = 'customer'");
    $stmt->bindParam(':id', $patient_id);

    if ($stmt->execute()) {
        header('Location: patients.php?message=Patient deleted successfully');
    } else {
        header('Location: patients.php?error=Failed to delete patient');
    }
} else {
    header('Location: patients.php');
    exit();
}
?>
