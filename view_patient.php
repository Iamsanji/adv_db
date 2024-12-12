<?php
session_start();
require_once 'database.php';

require_once 'account.class.php';
require_once 'prescribe.class.php';

if (!isset($_SESSION['account']) || !$_SESSION['account']['is_admin']) {
    header('location: signin.php');
    exit();
}

$db = new Database();

$user_id = $_GET['id'] ?? null;
if (!$user_id) {
    echo "No patient found!";
    exit();
}

$patient_query = $db->connect()->prepare("SELECT first_name, last_name, username FROM account WHERE id = :id");
$patient_query->bindParam(':id', $user_id, PDO::PARAM_INT);
$patient_query->execute();
$patient = $patient_query->fetch();

$prescription_query = $db->connect()->prepare(
    "SELECT product_code, product_name, name, description, dosage, quantity, price, date
     FROM prescribe 
     WHERE user_id = :user_id"
);
$prescription_query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$prescription_query->execute();
$array = $prescription_query->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient History - <?= $patient['first_name'] . ' ' . $patient['last_name'] ?></title>
    <style>
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
    a {
        text-decoration: none;
        color: #1abc9c;
    }
</style>

</head>
<body>

    <h1>Prescription History for <?= $patient['first_name'] . ' ' . $patient['last_name'] ?></h1>
    <table>
        <thead>
            <tr>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Doctor Name</th>
                <th>Description</th>
                <th>Dosage</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($array): ?>
                <?php foreach ($array as $arr): 
                     $total = $arr['quantity'] * $arr['price'];
                    ?>
                    <tr>
                        <td><?= $arr['product_code'] ?></td>
                        <td><?= $arr['product_name'] ?></td>
                        <td><?= $arr['name'] ?></td>
                        <td><?= $arr['description'] ?></td>
                        <td><?= $arr['dosage'] ?></td>
                        <td><?= $arr['quantity'] ?></td>
                        <td><?= $arr['price'] ?></td>
                        <td> <?= $total ?></td>
                        <td><?= $arr['date'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No prescription history found for this patient.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="patients.php">Back to Patients List</a>

</body>
</html>
