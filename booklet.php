<?php
    session_start();

    // Check if the user is logged in
    if(!isset($_SESSION['account'])) {
        // If not logged in, redirect to the login page
        header('location: signin.php');
        exit(); // Prevent further code execution
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
            color: #333;
            font-weight: bold;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        .container {
            padding: 1rem;
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
                <a href="profile.php"><span class="material-symbols-outlined" style = "color: black;">account_circle</span></a>
            </nav>
            
        </nav>
    </header>

    <?php
        require_once 'prescribe.class.php';

        $prescribeObj = new Prescribe();

        $array = $prescribeObj->showAll();

    ?>

    <main class="container"> 
        <table border = 1>

            <tr>

                <th>No.</th>
                <th>Product Code</th>
                <th>Name</th>
                <th>Product Name</th>
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
    </main>   
</body>
</html>