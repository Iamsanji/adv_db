<?php
session_start();

if (isset($_SESSION['account'])) {
    
    if (!$_SESSION['account']['is_staff']) {
        header('location: signin.php');
    }
} else {
    header('location: signin.php');
}

require_once 'prescribe.class.php';
require_once 'account.class.php';

$prescribeObj = new Prescribe();

$currentDate = date('Y-m-d');

$isSuperAdmin = $_SESSION['account']['role'] == 'superadmin';

if ($isSuperAdmin) {
    $array = $prescribeObj->showAll();
} else {

    $adminId = $_SESSION['account']['id']; 
    $array = $prescribeObj->showByAdmin($adminId);
}

foreach ($array as $arr) {
    $endDate = date('Y-m-d', strtotime($arr['date'] . ' + ' . $arr['duration'] . ' days'));
    if ($currentDate >= $endDate && $arr['status'] != 'Done') {
        $prescribeObj->updateStatus($arr['id'], 'Done');
    }
}

if ($isSuperAdmin) {
    $array = $prescribeObj->showAll();
} else {
    $array = $prescribeObj->showByAdmin($adminId);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=account_circle" />
    <title>Prescription</title>
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

        /* Add button styles */
        .add-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2c3e50;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, transform 0.3s;
            margin-right: 10px;
        }

        /* Hover effect */
        .add-button:hover {
            background-color: #16a085;
            transform: translateY(-2px);
        }

        /* Active effect (when clicked) */
        .add-button:active {
            background-color: #1c7d6b;
            transform: translateY(1px);
        }

        /* Focus effect */
        .add-button:focus {
            outline: none;
            border: 2px solid #1abc9c;
        }

        .search {
            display: flex;
            justify-content: flex-end;
        }

        /* Container for the search form */
        .search {
            display: flex;
            justify-content: flex-start; /* Align items to the left */
            align-items: center;
            margin-bottom: 20px;
        }

        /* Style for the search input field */
        .search input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px; /* Rounded corners on the left */
            width: 300px; /* Adjust the width of the input field */
            outline: none;
            transition: border-color 0.3s;
        }

        /* Focus effect for the input field */
        .search input[type="text"]:focus {
            border-color: #1abc9c; /* Highlight border on focus */
        }

        /* Style for the search button */
        .search button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #1abc9c;
            color: white;
            border: none;
            border-radius: 0 5px 5px 0; /* Rounded corners on the right */
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        /* Hover and active effects for the button */
        .search button:hover {
            background-color: #16a085;
        }

        .search button:active {
            transform: scale(0.98);
        }

        @media screen and (max-width: 768px) {
            .search input[type="text"] {
                width: 100%; 
                border-radius: 5px; 
                margin-bottom: 10px; 
            }

            .search button {
                border-radius: 5px;
                width: 100%; 
            }

            .search {
                flex-direction: column; 
                align-items: stretch;
            }
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
    <h1>Prescriptions</h1>

    <div class="search">

        <?php if (!$isSuperAdmin): ?>
            <a href="add.php" class="add-button">Add</a>
        <?php endif; ?>

        <form method="GET" action="">
        <input type="text" name="search" id="search" placeholder="Search by name" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Search</button>
    </form>

    </div>

    <table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <?php if ($isSuperAdmin): ?>
                <th>User ID</th>
            <?php endif; ?>
            <th>Product Code</th>
            <th>Doctor Name</th>
            <th>Patient Name</th>
            <th>Product Name</th>
            <th>Description</th>
            <th>Dosage</th>
            <th>Quantity</th>
            <th>Date</th>
            <th>Duration</th>
            <th>Status</th>
            <?php if (!$isSuperAdmin): ?>
                <th>Action</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($array as $prescription) {
            
            $patientObj = new Account();
            $patient = $patientObj->showById($prescription['user_id']);
        ?>
            <tr>
                <td><?= $i++ ?></td>
                <?php if ($isSuperAdmin): ?>
                    <td><?= $prescription['user_id'] ?></td>
                <?php endif; ?>
                <td><?= $prescription['product_code'] ?></td>
                <td>Dr. <?= $prescription['name'] ?></td>
                <td><?= $patient['first_name'] . ' ' . $patient['last_name'] ?></td>
                <td><?= $prescription['product_name'] ?></td>
                <td><?= $prescription['description'] ?></td>
                <td><?= $prescription['dosage'] ?></td>
                <td><?= $prescription['quantity'] ?> pcs</td>
                <td><?= $prescription['date'] ?></td>
                <td><?= $prescription['duration'] ?> days</td>
                <td><?= $prescription['status'] ?></td>
                <?php if (!$isSuperAdmin): ?>
                    <td>
                        <a href="edit.php?id=<?= $prescription['id'] ?>"><img src = "edit.png" style = "height: 20px;"></a>
                        <a href="delete_prescribe.php?id=<?= $prescription['id']; ?>" onclick="return confirm('Are you sure you want to delete this prescription?');"><img src = "delete.png" style = "height: 20px;"></a>
                    </td>     
                <?php endif; ?>
            </tr>
        <?php } ?>
    </tbody>
    </table>

</div>

</body>
</html>
