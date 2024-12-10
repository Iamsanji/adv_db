<?php
    session_start();

    
    if(!isset($_SESSION['account'])) {
        
        header('location: signin.php');
        exit(); 
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=account_circle" />
    <link rel = "stylesheet" href = "styles/landing.css">
    <title>Booklet</title>
    <style>

        * {
            padding: 0;
            margin: 0;
        }

                    /* Table styles */
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .styled-table th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
        }

        .styled-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .styled-table tr:hover {
            background-color: #f1f1f1;
        }

        .styled-table td {
            color: black;
            font-size: 14px;
        }

        .styled-table td, .styled-table th {
            text-align: center;
        }

        .styled-table td:last-child {
            color: #27ae60;  /* Add a green color for total values */
            
        }

        .styled-table td, .styled-table th {
            border-bottom: 1px solid #ddd;
        }

        /* Price and Total columns with specific styles */
        .styled-table td:nth-child(8),
        .styled-table td:nth-child(9) {
            color: #e74c3c; /* Red color for price and total */
            font-weight: bold;
        }

        /* Optional: Adjusting number format for price and total */
        .styled-table td:nth-child(8), .styled-table td:nth-child(9) {
            text-align: right;
        }

    </style>    
</head>
<body>
     
    <header>
        <nav class="header">
            <nav class="title">
                <h1>O<span>B</span></h1>
            </nav>
            <nav class="menu">
                <a href="user-landing.php">Home</a>
                <a href="booklet.php">Booklet</a>

                <!-- Profile with dropdown -->
                <div class="profile-dropdown">
                    <a href="profile.php">
                        <span class="material-symbols-outlined" style="color: black;">account_circle</span>
                    </a>
                    <div class="dropdown-content">
                        <a href="profile.php">Profile</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            </nav>
        </nav>
    </header>


    <?php
        require_once 'prescribe.class.php';

        $prescribeObj = new Prescribe();

        $array = $prescribeObj->showAll();

    ?>

        <table class="styled-table">

            <tr>

                <th>No.</th>
                <th>Product Code</th>
                <th>Name</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Dosage</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Date</th>

            </tr>

            <?php
                $i = 1;
                foreach($array as $arr) {

                $total = $arr['quantity'] * $arr['price'];
            ?>

            <tr>

                <td><?= $i ?></td>
                <td><?= $arr['product_code'] ?></td>
                <td><?= $arr['name'] ?></td>
                <td><?= $arr['product_name'] ?></td>
                <td><?= $arr['description'] ?></td>
                <td><?= $arr['dosage'] ?></td>
                <td><?= $arr['quantity'] ?></td>
                <td><?= $arr['price'] ?></td>
                <td><?= $total ?></td>
                <td><?= $arr['date'] ?></td>

            </tr>

            <?php
                $i++;
                }
            ?>

        </table>
</body>
</html>