<?php
session_start();

if(isset($_SESSION['account'])){
    if(!$_SESSION['account']['is_staff']){
        header('location: signin.php');
    }
}else{
    header('location: signin.php');
}

require_once 'database.php';
$db = new Database();

$admin_id = $_SESSION['account']['id'];

$stmt = $db->connect()->prepare("SELECT id, first_name, last_name, username, contact_number, address FROM account WHERE id = :id");
$stmt->execute(['id' => $admin_id]);

$admin = $stmt->fetch();

if (!$admin) {
    header('location: dashboard.php');
    exit();
}

// Handle the form submission to update profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];

    try {
        // Prepare the update query
        $updateStmt = $db->connect()->prepare("UPDATE account SET first_name = :first_name, last_name = :last_name, username = :email, contact_number = :contact_number, address = :address WHERE id = :id");
        
        // Execute the query
        $updateStmt->execute([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'contact_number' => $contact_number,
            'address' => $address,
            'id' => $admin_id
        ]);

        // Check if the update was successful
        if ($updateStmt->rowCount() > 0) {
            // Redirect to the profile page after successful update
            header('Location: admin-profile.php');
            exit();
        } else {
            // If no rows were updated, display an error message
            echo "<p style='color: red;'>Error: No changes were made. Please check the form fields.</p>";
        }
    } catch (PDOException $e) {
        // Catch any errors and display the error message
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <style>
        /* General styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar container */
        .sidebar {
            width: 200px;
            background-color: #2c3e50;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px 0;
            height: 100vh;
            position: fixed;
        }

        .sidebar h2 {
            padding: 1rem;
            text-align: center;
        }

        /* Sidebar links */
        .sidebar a {
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            display: block;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        /* Active link */
        .sidebar a.active {
            background-color: #1abc9c;
        }

        /* Main content */
        .main-content {
            margin-left: 200px;
            padding: 20px;
            flex: 1;
        }

        /* Profile card */
        .profile-card {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-card h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .profile-card p {
            font-size: 18px;
            color: #16a085;
        }

        /* Edit form */
        .edit-form {
            margin-top: 20px;
        }

        .edit-form input, .edit-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .edit-form button {
            background-color: #1abc9c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .edit-form button:hover {
            background-color: #16a085;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
    <h2><?= 'Welcome ' . $_SESSION['account']['first_name'] ?></h2>
    <br>
    <a href="dashboard.php">Dashboard</a>
    <a href="patients.php">Patients</a>
    <a href="admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'active' : '' ?>">Prescriptions</a>
    <?php if ($_SESSION['account']['role'] == 'superadmin'): ?>
        <a href="doctors.php" class="<?= basename($_SERVER['PHP_SELF']) == 'doctors.php' ? 'active' : '' ?>">Doctors</a>
    <?php endif; ?>
    <a href="admin-profile.php">Profile</a>
    <a href="logout.php">Logout</a>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <h1><?= $_SESSION['account']['first_name'] ?> Profile</h1>
        <!-- Profile card -->
        <div class="profile-card">
            <h3>Profile Information</h3>
            <p><strong>First Name:</strong> <?= htmlspecialchars($admin['first_name']) ?></p>
            <p><strong>Last Name:</strong> <?= htmlspecialchars($admin['last_name']) ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($admin['username']) ?></p>
            <p><strong>Contact Number:</strong> <?= htmlspecialchars($admin['contact_number']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($admin['address']) ?></p>
        </div>

        <!-- Edit Profile Form -->
        <div class="edit-form">
            <h3>Edit Profile</h3>
            <form method="POST" action="admin-profile.php">
                <input type="text" name="first_name" placeholder="First Name" value="<?= htmlspecialchars($admin['first_name']) ?>" required>
                <input type="text" name="last_name" placeholder="Last Name" value="<?= htmlspecialchars($admin['last_name']) ?>" required>
                <input type="text" name="contact_number" placeholder="Contact Number" value="<?= htmlspecialchars($admin['contact_number']) ?>" required>
                <textarea name="address" placeholder="Address" rows="4" required><?= htmlspecialchars($admin['address']) ?></textarea>
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

</body>
</html>
