<?php

    /*session_start();

    if(isset($_SESSION['account'])){
        if(!$_SESSION['account']['is_staff']){
            header('location: signin.php');
        }
    }else{
        header('location: signin.php');
    }*/

    require_once('functions.php');
    require_once('prescribe.class.php');

    $product_code = $name = $product_name = $dosage = $quantity = $price = $date = '';
    $product_codeErr = $nameErr = $product_nameErr = $dosageErr = $quantityErr = $priceErr = $dateErr = '';

    $prescribeObj = new Prescribe ();

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $product_code = clean_input($_POST['product_code']);
        $name = clean_input($_POST['name']);
        $product_name = clean_input($_POST['product_name']);
        $dosage = clean_input($_POST['dosage']);
        $quantity = clean_input($_POST['quantity']);
        $price = clean_input($_POST['price']);
        $date = clean_input($_POST['date']);

        if(empty($product_code)){
            $product_codeErr = 'Product Code is required';
        } else if ($prescribeObj->codeExist($product_code)){
            $product_codeErr = 'Product Code already exists';
        }

        if(empty($name)){
            $nameErr = 'Name is required';
        }
        
        if(empty($product_name)){
            $product_nameErr = 'Product Name is required';
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

        if(empty($product_codeErr) && empty($nameErr) && empty($product_nameErr) && empty($dosageErr) && empty($quantityErr) && empty($priceErr) && empty($dateErr)) {

            $prescribeObj->product_code = $product_code;
            $prescribeObj->name = $name;
            $prescribeObj->product_name = $product_name;
            $prescribeObj->dosage = $dosage;
            $prescribeObj->quantity = $quantity;
            $prescribeObj->price = $price;
            $prescribeObj->date = $date;

            if($prescribeObj->add()) {
                //user-view
                header('Location: booklet.php');
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
    <title>Add Prescribe</title>
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <form action="" method = "post">

        <label for="product_code">Product Code</label><span class = "error">*</span>
        <br>
        <input type="text" name = "product_code" value ="<?= $product_code ?>">
        <br>
        <?php if(!empty($product_codeErr)): ?>
            <span class="error"><?= $product_codeErr ?></span><br>
        <?php endif; ?>

        <label for="name">Name</label><span class = "error">*</span>
        <br>
        <input type="text" name = "name" value ="<?= $name ?>">
        <br>
        <?php if(!empty($nameErr)): ?>
            <span class="error"><?= $nameErr ?></span><br>
        <?php endif; ?>

        <label for="product_name">Product Name</label><span class = "error">*</span>
        <br>
        <input type="text" name = "product_name" value ="<?= $product_name ?>">
        <br>
        <?php if(!empty($product_nameErr)): ?>
            <span class="error"><?= $product_nameErr ?></span><br>
        <?php endif; ?>

        <label for="dosage">Dosage</label><span class="error">*</span>
        <br>
        <select name="dosage" id="dosage">
            <option value="">--Select--</option>
            <option value="Grams(g)" <?= (isset($dosage) && $dosage == 'Grams(g)')? 'selected=true' : ''?>>Grams(g)</option>
            <option value="Milligrams(mg)" <?= (isset($dosage) && $dosage == 'Milligrams(mg)')? 'selected=true' : ''?>>Milligrams(mg)</option>
        </select>
        <br>
        <?php if(!empty($dosageErr)): ?>
            <span class="error"><?= $dosageErr ?></span><br>
        <?php endif; ?>

        <label for="quantity">Quantity</label><span class="error">*</span>
        <br>
        <input type="number" name="quantity" value ="<?= $quantity ?>">
        <br>
        <?php if(!empty($quantityErr)): ?>
            <span class="error"><?= $quantityErr ?></span><br>
        <?php endif; ?>

        <label for="price">Price</label><span class="error">*</span>
        <br>
        <input type="number" name ="price" value ="<?= $price ?>">
        <br>
        <?php if(!empty($priceErr)): ?>
            <span class="error"><?= $priceErr ?></span><br>
        <?php endif; ?>

        <label for="date">Date</label><span class="error">*</span>
        <br>
        <input type="date" name="date" value ="<?= $date ?>">
        <br>
        <?php if(!empty($dateErr)): ?>
            <span class="error"><?= $dateErr ?></span><br>
        <?php endif; ?>
        <br>

        <input type="submit" value="Add">
    </form>
</body>
</html>