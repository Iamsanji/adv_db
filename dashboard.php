<?php

    session_start();

    if(isset($_SESSION['account'])){
        if(!$_SESSION['account']['is_staff']){
            header('location: signin.php');
        }
    }else{
        header('location: signin.php');
    }

    require_once 'prescribe.class.php';
    require_once 'database.php'; // Assuming you have a database.php to handle the connection
    $db = new Database();

    $sql = $db->connect()->query("SELECT COUNT(*) as total_patients FROM account WHERE role = 'customer'");
    $patients = $sql->fetch();

    // Query for total prescriptions
    $sql = $db->connect()->query("SELECT COUNT(*) as total_prescriptions FROM prescribe");
    $prescriptions = $sql->fetch();

    // Query for active users (patients with active prescriptions)
    $sql = $db->connect()->query("SELECT COUNT(DISTINCT user_id) as active_users FROM prescribe");
    $active_users = $sql->fetch();

    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="patients.php">Patients</a>
        <a href="admin.php"">Prescriptions</a>
        <a href="admin-profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <h1>Admin Dashboard</h1>
<br>
<br>
        <!-- Dashboard boxes -->
        <div class="dashboard">
            <div class="dashboard-box">
                <h3>Total Patients</h3>
                <p><?= $patients['total_patients'] ?></p>
            </div>
            <div class="dashboard-box">
                <h3>Total Prescriptions</h3>
                <p><?= $prescriptions['total_prescriptions'] ?></p>
            </div>
            <div class="dashboard-box">
                <h3>Active Users</h3>
                <p><?= $active_users['active_users'] ?></p>
            </div>
        </div>
    </div>

</body>
</html>
