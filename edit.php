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

$product_code = $name = $product_name = $description = $dosage = $duration = $discount = $quantity = $price = $date = '';
$product_codeErr = $nameErr = $product_nameErr = $descriptionErr = $dosageErr = $quantityErr = $priceErr = $dateErr = '';

$prescribeObj = new Prescribe();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $record = $prescribeObj->fetchRecord($id);

        if (!empty($record)) {
            $product_code = $record['product_code'];
            $name = $record['name'];
            $product_name = $record['product_name'];
            $description = $record['description'];
            $dosage = $record['dosage'];
            $quantity = $record['quantity'];
            $price = $record['price'];
            $duration = $record['duration'];
            $discount = $record['discount'];
            $date = $record['date'];
        } else {
            echo 'No prescription found or you do not have permission to edit this prescription.';
            exit;
        }
    } else {
        echo 'No prescription found';
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? clean_input($_POST['id']) : '';  // Default value if 'id' is not set
    $product_code = isset($_POST['product_code']) ? clean_input($_POST['product_code']) : '';
    $name = isset($_POST['name']) ? clean_input($_POST['name']) : '';
    $product_name = isset($_POST['product_name']) ? clean_input($_POST['product_name']) : '';
    $description = isset($_POST['description']) ? clean_input($_POST['description']) : '';
    $dosage = isset($_POST['dosage']) ? clean_input($_POST['dosage']) : '';
    $quantity = isset($_POST['quantity']) ? clean_input($_POST['quantity']) : '';
    $price = isset($_POST['price']) ? clean_input($_POST['price']) : '';
    $discount = isset($_POST['discount']) ? clean_input($_POST['discount']) : '';
    $date = isset($_POST['date']) ? clean_input($_POST['date']) : '';
    $patient_id = isset($_POST['patient_id']) ? clean_input($_POST['patient_id']) : '';
    $duration = isset($_POST['duration']) ? clean_input($_POST['duration']) : '';


    // Error checks
    if (empty($product_code)) { $product_codeErr = 'Product Code is required'; }
    if (empty($name)) { $nameErr = 'Name is required'; }
    if (empty($product_name)) { $product_nameErr = 'Product Name is required'; }
    if (empty($description)) { $descriptionErr = 'Description is required'; }
    if (empty($dosage)) { $dosageErr = 'Dosage is required'; }
    if (empty($quantity)) { $quantityErr = 'Quantity is required'; }
    if (empty($price)) { $priceErr = 'Price is required'; }
    if (empty($date)) { $dateErr = 'Date is required'; }

    if (empty($product_codeErr) && empty($nameErr) && empty($product_nameErr) && empty($descriptionErr) && empty($dosageErr) && empty($quantityErr) && empty($priceErr) && empty($dateErr)) {
        $prescribeObj->id = $id;
        $prescribeObj->product_code = $product_code;
        $prescribeObj->name = $name;
        $prescribeObj->product_name = $product_name;
        $prescribeObj->description = $description;
        $prescribeObj->dosage = $dosage;
        $prescribeObj->quantity = $quantity;
        $prescribeObj->price = $price;
        $prescribeObj->discount = $discount;
        $prescribeObj->date = $date;
        $prescribeObj->duration = $duration;
        $prescribeObj->user_id = $patient_id;
        $prescribeObj->admin_id = $_SESSION['account']['id'];

        if ($prescribeObj->edit()) {
            header('Location: admin.php');
            exit();
        } else {
            echo 'Something went wrong when updating the prescription.';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
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
    <form class="form-container" action="?id=<?= $id ?>" method="post">
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
                    <option value="1 g" <?= (isset($dosage) && $dosage == '1 g') ? 'selected=true' : '' ?>>1 g</option>
                    <option value="250 mg" <?= (isset($dosage) && $dosage == '250 mg') ? 'selected=true' : '' ?>>250 mg</option>
                    <option value="500 mg" <?= (isset($dosage) && $dosage == '500 mg') ? 'selected=true' : '' ?>>500 mg</option>
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

        

        <label for="duration">Duration (Days)</label>
        <input type="number" name="duration" value="<?= $duration ?>" required>

        <label for="discount">Discount (%)</label>
        <input type="number" id="discount" name="discount" value=" <?= $discount ?>">

        <div class="form-actions">
            <input type="submit" value="Update">
        </div>
    </form>
</body>
</html>