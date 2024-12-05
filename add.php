<?php

    
    session_start();

    if (!isset($_SESSION['account']) || !isset($_SESSION['account']['id'])) {
        header('Location: signin.php');

        exit();
    }
    $user_id = $_SESSION['account']['id']; 

    require_once('header.php');
    require_once('functions.php');
    require_once('prescribe.class.php');

    $product_code = $name = $product_name = $description = $dosage = $quantity = $price = $date = '';
    $product_codeErr = $nameErr = $product_nameErr = $descriptionErr = $dosageErr = $quantityErr = $priceErr = $dateErr = '';

    $prescribeObj = new Prescribe ();

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $product_code = clean_input($_POST['product_code']);
        $name = clean_input($_POST['name']);
        $product_name = clean_input($_POST['product_name']);
        $description = clean_input($_POST['description']);
        $dosage = clean_input($_POST['dosage']);
        $quantity = clean_input($_POST['quantity']);
        $price = clean_input($_POST['price']);
        $date = clean_input($_POST['date']);
        //new
        $patient_id = clean_input($_POST['patient_id']);

        if(empty($product_code)){
            $product_codeErr = 'Product Code is required';
        } 

        if(empty($name)){
            $nameErr = 'Name is required';
        }
        
        if(empty($product_name)){
            $product_nameErr = 'Product Name is required';
        }

        if(empty($description)){
            $descriptionErr = 'Description is required';
        }

        if(empty($dosage)){
            $dosageErr = 'Dosage is required';
        }

        if(empty($quantity)){
            $quantityErr = 'Quantity is required';
        }

        if(empty($price)){
            $priceErr = 'Price is required';
        }

        if(empty($date)){
            $dateErr = 'Date is required';
        }

        if(empty($product_codeErr) && empty($nameErr) && empty($product_nameErr) && empty($descriptionErr) && empty($dosageErr) && empty($quantityErr) && empty($priceErr) && empty($dateErr)) {

            $prescribeObj->product_code = $product_code;
            $prescribeObj->name = $name;
            $prescribeObj->product_name = $product_name;
            $prescribeObj->description = $description;
            $prescribeObj->dosage = $dosage;
            $prescribeObj->quantity = $quantity;
            $prescribeObj->price = $price;
            $prescribeObj->date = $date;

           // $prescribeObj->user_id = $user_id;

           //new
           $prescribeObj->user_id = $patient_id; // Set patient ID here, not the admin's

            // Assign admin ID (who is adding the prescription)
            $prescribeObj->admin_id = $user_id;

            if($prescribeObj->add()) {
                //user-view
                header('Location: admin.php');
            } else {
                echo 'Something went wrong when adding new prescription.';
            }

        } 

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=account_circle" />
    <link rel = "stylesheet" href = "styles/landing.css">
    <title>Add Prescribe</title>
    <style>
        .error {
            color: red;
        }
        
    </style>
</head>
<body>


        <form action="" method = "post">

            <label for="product_code">Product Code</label>
            <br>
            <input type="text" name = "product_code" value ="<?= $product_code ?>">
            
            <?php if(!empty($product_codeErr)): ?>
                <span class="error"><?= $product_codeErr ?></span><br>
            <?php endif; ?>
            <br>
            <label for="name">Name</label>
            <br>
            <input type="text" name = "name" value ="<?= $name ?>">
            
            <?php if(!empty($nameErr)): ?>
                <span class="error"><?= $nameErr ?></span><br>
            <?php endif; ?>
            <br>
            <label for="product_name">Product Name</label>
            <br>

            <input type="text" name = "product_name" value ="<?= $product_name ?>">
            
            <?php if(!empty($product_nameErr)): ?>
                <span class="error"><?= $product_nameErr ?></span><br>
            <?php endif; ?>
            <br>

            <label for="description">Description</label>
            <br>
            <input type="text" name = "description" value ="<?= $description ?>">
            
            <?php if(!empty($descriptionErr)): ?>
                <span class="error"><?= $descriptionErr ?></span><br>
            <?php endif; ?>
            <br>

            <label for="dosage">Dosage</label>
            <br>
            <select name="dosage" id="dosage">
                <option value="">--Select--</option>
                <option value="Grams(g)" <?= (isset($dosage) && $dosage == 'Grams(g)')? 'selected=true' : ''?>>Grams(g)</option>
                <option value="Milligrams(mg)" <?= (isset($dosage) && $dosage == 'Milligrams(mg)')? 'selected=true' : ''?>>Milligrams(mg)</option>
            </select>
            
            <?php if(!empty($dosageErr)): ?>
                <span class="error"><?= $dosageErr ?></span><br>
            <?php endif; ?>
            <br>
            <label for="quantity">Quantity</label>
            <br>
            <input type="number" name="quantity" value ="<?= $quantity ?>">
            
            <?php if(!empty($quantityErr)): ?>
                <span class="error"><?= $quantityErr ?></span><br>
            <?php endif; ?>
            <br>
            <label for="price">Price</label>
            <br>
            <input type="number" name ="price" value ="<?= $price ?>">
            
            <?php if(!empty($priceErr)): ?>
                <span class="error"><?= $priceErr ?></span><br>
            <?php endif; ?>
            <br>
            <label for="date">Date</label>
            <br>
            <input type="date" name="date" value ="<?= $date ?>">
            
            <?php if(!empty($dateErr)): ?>
                <span class="error"><?= $dateErr ?></span><br>
            <?php endif; ?>
            <br>


                <!-- Add a dropdown to select a user (patient) -->
        <label for="patient_id">Select Patient</label>
        <br>
        <select name="patient_id" id="patient_id">
            <option value="">--Select Patient--</option>
            <?php
            // Fetch all patients (users) from the database
            require_once('database.php');
            $db = new Database();
            $query = $db->connect()->query("SELECT id, first_name, last_name FROM account WHERE role = 'customer'");
            
            while ($row = $query->fetch()) {
                echo "<option value='{$row['id']}'>{$row['first_name']} {$row['last_name']}</option>";
            }
            ?>
        </select>

            <input type="submit" value="Add">
        </form>
    
</body>
</html>