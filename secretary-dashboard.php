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

// Your database code here (for example, fetching prescriptions or patient data)
require_once 'database.php';
$db = new Database();

// Fetch limited data if needed
$query = $db->connect()->query("SELECT id, first_name, last_name, username FROM account WHERE role = 'customer'");
$patients = $query->fetchAll();  // Fetch all patients for the secretary
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
            margin-left: 200px;
            padding: 20px;
            flex: 1;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
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
        <a href="secretary.php">Dashboard</a>
        <a href="patients.php" class="active">Patients</a> <!-- Limited access to view patients -->
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <h1>Secretary Dashboard</h1>

        <h3>Patient List</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($patients as $patient) {
                ?>
                    <tr>
                        <td><?= $patient['id'] ?></td>
                        <td><?= $patient['first_name'] ?></td>
                        <td><?= $patient['last_name'] ?></td>
                        <td><?= $patient['username'] ?></td>
                    </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
