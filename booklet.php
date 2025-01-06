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

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            box-shadow: 1px 2px 3px rgba(0,0,0,0.5);
            z-index: 10;
            border-radius: 10px;
        }

        /* Receipt popup styling */
    .receipt {
        width: 300px;
        font-family: "Courier New", Courier, monospace; /* Monospace font for a receipt-like look */
        font-size: 14px;
        color: #333;
        border: 2px dashed #333; /* Dashed border for receipt style */
        padding: 20px;
        text-align: left;
        line-height: 1.5;
        background-color: #fff;
        box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.3);
        border-radius: 8px;
    }

    .receipt-header {
        text-align: center;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .receipt-info {
        margin-bottom: 15px;
    }

    .receipt-info h5 {
        margin: 5px 0;
    }

    .receipt-total {
        text-align: center;
        font-weight: bold;
        margin-top: 15px;
        border-top: 2px dashed #333;
        padding-top: 10px;
    }

    /* Overlay styling for the background dimming effect */
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 5;
    }

    .close-btn {
        display: block;
        margin: 10px auto 0;
        padding: 5px 15px;
        background: #2c3e50;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
    }

    .close-btn:hover {
        background: #34495e;
    }

    .btn-view {
      background: linear-gradient(90deg, #4caf50, #81c784);
      color: white;
      font-size: 10px;
      font-weight: 700;
      padding: 8px 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .btn-view:hover {
      transform: scale(1.1);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-view:active {
      transform: scale(1.05);
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
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

        $array = $prescribeObj->getPrescriptionsWithContact();

        
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
                <th>Discount</th>
                <th>Total</th>
                <th>Date</th>
                <th>Receipt</th>
                <th>Prescription</th>

            </tr>

            <?php
                $i = 1;
                foreach($array as $arr) {

                //$total = $arr['quantity'] * $arr['price'];
               // $total_after_discount = $arr['total_after_discount'];
                $total_after_discount = $arr['quantity'] * $arr['price'];

                if ($arr['discount'] > 0) {
                    $total_after_discount = $total_after_discount * (1 - $arr['discount'] / 100);
                }
                
            ?>

            <tr>

                <td><?= $i ?></td>
                <td><?= $arr['product_code'] ?></td>
                <td><?= $arr['name'] ?></td>
                <td><?= $arr['product_name'] ?></td>
                <td><?= $arr['description'] ?></td>
                <td><?= $arr['dosage'] ?></td>
                <td><?= $arr['quantity'] ?></td>
                <td>₱ <?= $arr['price'] ?></td>
                <td><?= $arr['discount'] ?>%</td>
                <td><?= number_format($total_after_discount, 2) ?></td>
                <td><?= $arr['date'] ?></td>
                <td><button class ="btn-view" onclick="showPop(<?= htmlspecialchars(json_encode($arr)) ?>)">View</button></td>
                <td><button class ="btn-view" onclick="showPop1(<?=htmlspecialchars(json_encode($arr)) ?>)">View</td>

            </tr>

            <?php
                $i++;
                }
            ?>

        </table>
        <div class="overlay" id="overlay"></div>

        <div class="popup" id="popup">
        <div id="popup-content" class="receipt">
            <!-- Content will be dynamically added here -->
        </div>
        <button class="close-btn" onclick="closePopup()">Close</button>
        </div>

        <div class="popup" id="popUp">
        <div id="popUp-content" class="receipt">
            <!-- Content will be dynamically added here -->
        </div>
        <button class="close-btn" onclick="closePop1()">Close</button>
        </div>

        <script>

            function showPop(data) {
                const popupContent = document.getElementById('popup-content');
                popupContent.innerHTML = `
                    <h1 style="text-align: center;">RECEIPT</h1>
                    <h5 style="display:flex; justify-content: flex-end;">Date: ${data.date}</h5>
                    <h5 style="display:flex; justify-content: flex-end;">Receipt ID: ${data.id}</h5>
                    <br>
                    <h5>Product Name: ${data.product_name}</h5>
                    <h5>Product Code: ${data.product_code}</h5>
                    <h5>Dosage: ${data.dosage}</h5>
                    <h5>Quantity: ${data.quantity}</h5>
                    <h5>Price: ₱ ${data.price}</h5>
                    <h5>Discount: ${data.discount}%</h5>
                    <h5>=============================================</h5>
                    <h5>Total: ₱ ${data.quantity * data.price * (1 - data.discount / 100).toFixed(2)}</h5>
                `;
                document.getElementById('popup').style.display = 'block';
                document.getElementById('overlay').style.display = 'block';
            }

                function closePopup() {
                    document.getElementById('popup').style.display = 'none';
                    document.getElementById('overlay').style.display = 'none';
                }

                function showPop1(data) {
                const popUpContent = document.getElementById('popUp-content');
                popUpContent.innerHTML = `
                    <h5 style="display:flex; justify-content: flex-end;">Date: ${data.date}</h5>
                    <br>
                    <h5>Doctor Name: ${data.name}</h5>
                    <h5>Contact Number: ${data.contact_number}</h5>
                    <h5>Address: ${data.address}</h5>
                    <h5>=============================================</h5>
                    <h5>Description: ${data.description}</h5>
                    <h5>Quantity: ${data.quantity}</h5>
                    <h5>Dosage: ${data.dosage}</h5>
                    <h5>=============================================</h5>

                    <br>
                    <h5 style ="text-align: center;">Please follow the prescribed dosage carefully. If symptoms persist or worsen, contact us at <u>${data.contact_number}</u> or visit our office at <u>${data.address}</u> for further evaluation.</h5>
                    <br>
                    <p style="font-family: 'Brush Script MT', cursive; display: flex; justify-content: flex-end;">${data.name}</p>


                `;
                document.getElementById('popUp').style.display = 'block';
                document.getElementById('overlay').style.display = 'block';
            }

                function closePop1() {
                    document.getElementById('popUp').style.display = 'none';
                    document.getElementById('overlay').style.display = 'none';
                }

        </script>
</body>
</html>