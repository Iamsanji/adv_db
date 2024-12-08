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
    <title>Stylish Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #e3f2fd, #fce4ec);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            width: 90%;
            max-width: 1000px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-row label {
            flex: 1;
            display: flex;
            flex-direction: column;
            font-weight: bold;
        }

        input, select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 5px;
        }

        .form-row .error {
            color: red;
            font-size: 0.9em;
        }

        .form-actions {
            text-align: center;
            margin-top: 20px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #42a5f5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #1e88e5;
        }
    </style>
</head>
<body>
    <form class="form-container" action="" method="post">
        <div class="form-row">
            <label for="product_code">Product Code
                <input type="text" name="product_code" value="<?= $product_code ?>">
                <?php if (!empty($product_codeErr)): ?>
                    <span class="error"><?= $product_codeErr ?></span>
                <?php endif; ?>
            </label>
            <label for="name">Name
                <input type="text" name="name" value="<?= $name ?>">
                <?php if (!empty($nameErr)): ?>
                    <span class="error"><?= $nameErr ?></span>
                <?php endif; ?>
            </label>
        </div>

        <div class="form-row">
            <label for="product_name">Product Name
                <input type="text" name="product_name" value="<?= $product_name ?>">
                <?php if (!empty($product_nameErr)): ?>
                    <span class="error"><?= $product_nameErr ?></span>
                <?php endif; ?>
            </label>
            <label for="description">Description
                <input type="text" name="description" value="<?= $description ?>">
                <?php if (!empty($descriptionErr)): ?>
                    <span class="error"><?= $descriptionErr ?></span>
                <?php endif; ?>
            </label>
        </div>

        <div class="form-row">
            <label for="dosage">Dosage
                <select name="dosage" id="dosage">
                    <option value="">--Select--</option>
                    <option value="Grams(g)" <?= (isset($dosage) && $dosage == 'Grams(g)') ? 'selected=true' : '' ?>>Grams(g)</option>
                    <option value="Milligrams(mg)" <?= (isset($dosage) && $dosage == 'Milligrams(mg)') ? 'selected=true' : '' ?>>Milligrams(mg)</option>
                </select>
                <?php if (!empty($dosageErr)): ?>
                    <span class="error"><?= $dosageErr ?></span>
                <?php endif; ?>
            </label>
            <label for="quantity">Quantity
                <input type="number" name="quantity" value="<?= $quantity ?>">
                <?php if (!empty($quantityErr)): ?>
                    <span class="error"><?= $quantityErr ?></span>
                <?php endif; ?>
            </label>
        </div>

        <div class="form-row">
            <label for="price">Price
                <input type="number" name="price" value="<?= $price ?>">
                <?php if (!empty($priceErr)): ?>
                    <span class="error"><?= $priceErr ?></span>
                <?php endif; ?>
            </label>
            <label for="date">Date
                <input type="date" name="date" value="<?= $date ?>">
                <?php if (!empty($dateErr)): ?>
                    <span class="error"><?= $dateErr ?></span>
                <?php endif; ?>
            </label>
        </div>

        <div class="form-row">
            <label for="patient_id">Select Patient
                <select name="patient_id" id="patient_id">
                    <option value="">--Select Patient--</option>
                    <?php
                    require_once('database.php');
                    $db = new Database();
                    $query = $db->connect()->query("SELECT id, first_name, last_name FROM account WHERE role = 'customer'");
                    while ($row = $query->fetch()) {
                        echo "<option value='{$row['id']}'>{$row['first_name']} {$row['last_name']}</option>";
                    }
                    ?>
                </select>
            </label>
        </div>

        <div class="form-actions">
            <input type="submit" value="Add">
        </div>
    </form>
</body>
</html>
