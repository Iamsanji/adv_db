<?php

    
    session_start(); // Ensure the session is started before accessing session variables

    if (!isset($_SESSION['account']) || !isset($_SESSION['account']['id'])) {
        header('Location: signin.php');
       // echo "<script type='text/javascript'>alert(' User is not logged in.');</script>";
        exit(); // Exit if the user is not logged in
    }
    $user_id = $_SESSION['account']['id']; // Retrieve user ID from session

    /*// Check if the user is logged in
    if(!isset($_SESSION['account'])) {
        // If not logged in, redirect to the login page
        header('location: signin.php');
        exit(); // Prevent further code execution
    }*/

    /*session_start();  // Ensure session is started

    // Debug: Display user session details
    if (isset($_SESSION['account']['id'])) {
        echo "User ID from session: " . $_SESSION['account']['id']; 
    } else {
        echo "User is not logged in.";
    }

    require_once('header.php');  // This already starts the session

    // Check if the user is logged in
    if (!isset($_SESSION['account']['id'])) {
        echo "User is not logged in.";
        exit();  // Exit the script if the user is not logged in
    } else {
        $user_id = $_SESSION['account']['id'];  // Get the user ID from session
        echo "User ID from session: " . $user_id;
    }*/
    require_once('header.php');
    require_once('functions.php');
    require_once('prescribe.class.php');

    $product_code = $name = $product_name = $dosage = $quantity = $price = $date = '';
    $product_codeErr = $nameErr = $product_nameErr = $dosageErr = $quantityErr = $priceErr = $dateErr = '';

    $prescribeObj = new Prescribe ();

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        /*if (isset($_SESSION['account']['id'])) {
            $user_id = $_SESSION['account']['id'];  // Get user ID from session
            $prescribeObj->user_id = $user_id;  // Assign user_id to the object
        } else {
            echo "User is not logged in.";
            exit();
        }*/

        $product_code = clean_input($_POST['product_code']);
        $name = clean_input($_POST['name']);
        $product_name = clean_input($_POST['product_name']);
        $dosage = clean_input($_POST['dosage']);
        $quantity = clean_input($_POST['quantity']);
        $price = clean_input($_POST['price']);
        $date = clean_input($_POST['date']);

        if(empty($product_code)){
            $product_codeErr = 'Product Code is required';
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

            $prescribeObj->user_id = $user_id;

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

        * {
            margin: 0;
            padding: 0;
        }

                /* Style for the entire form */
        form {
            max-width: 500px;
            margin: 1rem auto;
            padding: 1rem;
            background-color: transparent;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            
        }

        /* Style for labels */
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        /* Style for input fields */
        input[type="text"],
        input[type="number"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Style for the error messages */
        .error {
            color: red;
            font-size: 0.9em;
        }

        /* Style for the submit button */
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            form {
                padding: 15px;
            }

            input[type="submit"] {
                font-size: 0.9em;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <form action="" method = "post">

            <label for="product_code">Product Code</label>
            
            <input type="text" name = "product_code" value ="<?= $product_code ?>">
            
            <?php if(!empty($product_codeErr)): ?>
                <span class="error"><?= $product_codeErr ?></span><br>
            <?php endif; ?>

            <label for="name">Name</label>
            
            <input type="text" name = "name" value ="<?= $name ?>">
            
            <?php if(!empty($nameErr)): ?>
                <span class="error"><?= $nameErr ?></span><br>
            <?php endif; ?>

            <label for="product_name">Product Name</label>
            <input type="text" name = "product_name" value ="<?= $product_name ?>">
            
            <?php if(!empty($product_nameErr)): ?>
                <span class="error"><?= $product_nameErr ?></span><br>
            <?php endif; ?>

            <label for="dosage">Dosage</label>

            <select name="dosage" id="dosage">
                <option value="">--Select--</option>
                <option value="Grams(g)" <?= (isset($dosage) && $dosage == 'Grams(g)')? 'selected=true' : ''?>>Grams(g)</option>
                <option value="Milligrams(mg)" <?= (isset($dosage) && $dosage == 'Milligrams(mg)')? 'selected=true' : ''?>>Milligrams(mg)</option>
            </select>
            
            <?php if(!empty($dosageErr)): ?>
                <span class="error"><?= $dosageErr ?></span><br>
            <?php endif; ?>

            <label for="quantity">Quantity</label>
            
            <input type="number" name="quantity" value ="<?= $quantity ?>">
            
            <?php if(!empty($quantityErr)): ?>
                <span class="error"><?= $quantityErr ?></span><br>
            <?php endif; ?>

            <label for="price">Price</label>
            
            <input type="number" name ="price" value ="<?= $price ?>">
            
            <?php if(!empty($priceErr)): ?>
                <span class="error"><?= $priceErr ?></span><br>
            <?php endif; ?>

            <label for="date">Date</label>
            
            <input type="date" name="date" value ="<?= $date ?>">
            
            <?php if(!empty($dateErr)): ?>
                <span class="error"><?= $dateErr ?></span><br>
            <?php endif; ?>
            

            <input type="submit" value="Add">
        </form>
    </div>
</body>
</html>