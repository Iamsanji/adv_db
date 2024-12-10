<?php
session_start();

// Restrict access to superadmin only
if ($_SESSION['account']['role'] != 'superadmin') {
    header('Location: dashboard.php');
    exit();
}

require_once 'account.class.php';

$accountObj = new Account();
$doctors = $accountObj->getDoctors(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors</title>
    <style>
        /* Add table and button styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
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
            background-color: #f9f9f9;
        }
        .add-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2c3e50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .add-button:hover {
            background-color: #16a085;
        }

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

        .main-content {
            margin-left: 200px;
            padding: 20px;
            flex: 1;
        }
    </style>
</head>
<body>

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

    <div class="main-content">
    <h1>Admin Accounts</h1>
    <a href="add-doctor.php" class="add-button">Add Doctor</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($doctors as $doctor): ?>
                <tr>
                    <td><?= htmlspecialchars($doctor['id']) ?></td>
                    <td><?= htmlspecialchars($doctor['first_name']) ?></td>
                    <td><?= htmlspecialchars($doctor['last_name']) ?></td>
                    <td><?= htmlspecialchars($doctor['username']) ?></td>
                    <td>
                        <a href="delete-doctor.php?id=<?= $doctor['id'] ?>" onclick="return confirm('Are you sure?')"><img src = "delete.png" style ="height: 24px;"></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</body>
</html>
