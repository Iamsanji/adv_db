<?php

    session_start();

    if(isset($_SESSION['account'])){
        if(!$_SESSION['account']['is_staff']){
            header('location: signin.php');
        }
    }else{
        header('location: signin.php');
    }

    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=account_circle" />
    <link rel = "stylesheet" href = "styles/landing.css">
    <title>Admin</title>
    <style>
        
    </style>    
</head>
<body>

<?php
        require_once 'prescribe.class.php';

        $prescribeObj = new Prescribe();

        $array = $prescribeObj->showAll();

    ?>

        <table border = 1>

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
                <th>Action</th>

            </tr>

            <?php
                $i = 1;
                foreach($array as $arr) {

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
                <a href="edit.php?id=<?= $arr['id'] ?>">Edit</a>
                <!-- Delete button with product name and ID as data attributes -->
                <?php
                    if ($_SESSION['account']['is_admin']){
                ?>



                <?php
                    }
                ?>
                </td>

            </tr>

            <?php
                $i++;
                }
            ?>

        </table>

        <h2><?= 'Welcome ' . $_SESSION['account']['first_name'] ?></h2>
    <a href="add.php">add</a>
    <a href="admin-view.php">view</a>
    <a href="logout.php">logout</a>

    
</body>
</html>

