<?php

    session_start();

    if(isset($_SESSION['account'])){
        if(!$_SESSION['account']['is_staff']){
            header('location: signin.php');
        }
    }else{
        header('location: signin.php');
    }

    // Include the database connection file
    require_once 'database.php';
    $db = new Database();

    // Get the logged-in admin's ID from the session
    $admin_id = $_SESSION['account']['id'];

    // Query to fetch admin profile details
    $stmt = $db->connect()->prepare("SELECT id, first_name, last_name, username, contact_number, address FROM account WHERE id = :id");
    $stmt->execute(['id' => $admin_id]);

    // Fetch the admin's profile details
    $admin = $stmt->fetch();

    if (!$admin) {
        // Redirect if no admin is found
        header('location: dashboard.php');
        exit();
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

        /* Dashboard layout */
        .dashboard {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        /* Dashboard box */
        .dashboard-box {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .dashboard-box h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .dashboard-box p {
            font-size: 20px;
            color: #16a085;
        }

        /* Responsive layout */
        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
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
        <a href="admin.php">Prescriptions</a>
        <a href="admin-profile.php"  class="active">Profile</a>
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
            <p><strong>Email:</strong> <?= htmlspecialchars($admin['username']) ?></p>
            <p><strong>Contact Number:</strong> <?= htmlspecialchars($admin['contact_number']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($admin['address']) ?></p>
        </div>
    </div>

</body>
</html>
