<?php
    session_start();

    // Check if the user is logged in and has admin privileges
    if (isset($_SESSION['account'])) {
        if (!$_SESSION['account']['is_admin']) {
            header('location: signin.php');
        }
    } else {
        header('location: signin.php');
    }

    // Include the database connection file
    require_once 'database.php';
    $db = new Database();

    // Query to fetch all patients (role = 'customer')
    $query = $db->connect()->query("SELECT id, first_name, last_name, address, pwd_id, sex, age, contact_number, username FROM account WHERE role = 'customer'");
    $patients = $query->fetchAll();  // Fetch all the patients as an array

    // Check if the logged-in user is a superadmin
    $isSuperAdmin = $_SESSION['account']['role'] == 'superadmin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=visibility" />
    <title>Patients List</title>
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

        .td {
            text-align: center;
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
        <h1>Patients List</h1>

        <!-- Patients table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Address</th>
                    <th>Pwd/Senior ID</th>
                    <th>Sex</th>
                    <th>Age</th>
                    <th>Contact Number</th>
                    <?php if ($isSuperAdmin): ?>
                        <th>Email</th>
                    <?php endif; ?>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Loop through each patient and display them in the table
                    foreach($patients as $patient) {
                ?>
                    <tr>
                        <td><?= $patient['id'] ?></td>
                        <td><?= $patient['first_name'] ?></td>
                        <td><?= $patient['last_name'] ?></td>
                        <td><?= $patient['address'] ?></td>
                        <td><?= $patient['pwd_id'] ?></td>
                        <td><?= $patient['sex'] ?></td>
                        <td><?= $patient['age'] ?> years old</td>
                        <td><?= $patient['contact_number'] ?></td>
                        <?php if ($isSuperAdmin): ?>
                            <td><?= $patient['username'] ?></td>  <!-- Email (username) for Superadmin -->
                        <?php endif; ?>
                        
                        <td class = "td">   
                            <a href="view_patient.php?id=<?= $patient['id'] ?>" ><img src = "view.png" style = "height: 24px;"></a>
                            <?php if ($_SESSION['account']['role'] == 'superadmin' || $_SESSION['account']['is_admin']): ?>
                                <!-- Admin and Superadmin have the Delete option -->
                                <a href="delete_patient.php?id=<?= $patient['id'] ?>" onclick="return confirm('Are you sure you want to delete this patient?');">
                                    <img src = "delete.png" style = "height: 24px;"></a>
                            <?php endif; ?>
                        </td>
                        
                    </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
