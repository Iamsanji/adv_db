<?php
session_start();

require_once 'account.class.php';

if ($_SESSION['account']['role'] != 'superadmin') {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'account.class.php';
    $accountObj = new Account();
    $result = $accountObj->addDoctor($_POST['first_name'], $_POST['last_name'], $_POST['username'], $_POST['password']);
    if ($result) {
        header('Location: doctors.php');
    } else {
        $error = "Failed to add doctor.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Doctor</title>
    <style>
        /* General body styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Form container styles */
        .form-container {
            background-color: #ffffff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        /* Form heading */
        .form-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333333;
            text-align: center;
        }

        /* Form labels */
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555555;
        }

        /* Input fields */
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        /* Submit button */
        input[type="submit"] {
            width: 100%;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* Hover effect for button */
        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Error message styles */
        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="form-container">
    <a href="doctors.php" style ="text-decoration: none;"><h2>Add Doctor</h2></a>
    <?php if (isset($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" placeholder="Enter first name" required>

        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" placeholder="Enter last name" required>

        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Enter username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter password" required>

        <input type="submit" value="Add Doctor">
    </form>
</div>

</body>
</html>
