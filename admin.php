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

$prescribeObj = new Prescribe();

$array = $prescribeObj->showAll();

$currentDate = date('Y-m-d');
//dec 8
foreach ($array as $arr) {
    $endDate = date('Y-m-d', strtotime($arr['date'] . ' + ' . $arr['duration'] . ' days'));
    if ($currentDate >= $endDate && $arr['status'] != 'Done') {

        $prescribeObj->updateStatus($arr['id'], 'Done');
    }
}

$array = $prescribeObj->showAll(); 


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

        .sidebar h2{
            padding: 1rem;
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

    </style>
</head>
<body>

    <div class="sidebar">
        <h2><?= 'Welcome ' . $_SESSION['account']['first_name'] ?></h2>
        <br>
        <a href="dashboard.php">Dashboard</a>
        <a href="patients.php">Patients</a>
        <a href="admin.php" class="active">Prescriptions</a>
        <a href="admin-profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <h1>Prescriptions</h1>
        
        <a href="add.php" class="add-button">Add</a>
        <table border = 1>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Product Code</th>
                    <th>Name</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Dosage</th>
                    <th>Quantity</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i = 1;
                    foreach($array as $arr) {


                        //new dec 8
                        $currentDate = date('Y-m-d');
                        $endDate = date('Y-m-d', strtotime($arr['date'] . ' + ' . $arr['duration'] . ' days'));
                        $status = ($currentDate >= $endDate) ? 'Done' : $arr['status']; 
                
                ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $arr['user_id'] ?></td>
                        <td><?= $arr['product_code'] ?></td>
                        <td><?= $arr['name'] ?></td>
                        <td><?= $arr['product_name'] ?></td>
                        <td><?= $arr['description'] ?></td>
                        <td><?= $arr['dosage'] ?></td>
                        <td><?= $arr['quantity'] ?></td>
                        <td><?= $arr['date'] ?></td>
                        <td>
                            <?= $status ?>
                        </td>
                        <td>
                            <a href="edit.php?id=<?= $arr['id'] ?>">Edit</a>

                        </td>
                    </tr>
                <?php
                    $i++;
                    }
                ?>
            </tbody>
        </table>

    </div>
</body>
</html>
