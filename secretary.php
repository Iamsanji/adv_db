<?php
session_start();

// Check if the user is logged in and has the correct role
if (isset($_SESSION['account']) && $_SESSION['account']['role'] == 'staff') {
    $user_id = $_SESSION['account']['id']; // Get the user's ID
} else {
    // Redirect if the user is not logged in or does not have the 'staff' role
    header('Location: signin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secretary Dashboard</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #ecf0f1;
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

        .sidebar a.active {
            background-color: #1abc9c;
        }

        /* Main content */
        .main-content {
            margin-left: 220px;
            padding: 20px;
            flex: 1;
        }

        /* Dashboard Box Styles */
        .dashboard-box {
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 5px;
        }

        .dashboard-box h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .dashboard-box p {
            color: #7f8c8d;
        }

        .dashboard-box .box-content {
            margin-top: 10px;
        }

        .sidebar h2 {
            padding: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Welcome <?= $_SESSION['account']['first_name'] ?></h2>
        <br>
        <a href="secretary.php" class="active">Dashboard</a>
        <a href="secretary-dashboard.php">Patients</a> <!-- Limited access to view patients -->
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <h1>Secretary Dashboard</h1>

        <!-- Dashboard Box 1: Overview -->
        <div class="dashboard-box">
            <h3>Overview</h3>
            <p>Welcome to your dashboard, where you can manage patient records and other tasks.</p>
        </div>

    </div>

</body>
</html>
