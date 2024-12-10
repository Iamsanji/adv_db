<?php
require_once 'functions.php';
require_once 'account.class.php';

$signupErr = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = clean_input($_POST['first_name']);
    $last_name = clean_input($_POST['last_name']);
    $sex = clean_input($_POST['sex']);
    $age = clean_input($_POST['age']);
    $contact_number = clean_input($_POST['contact_number']);
    $pwd_id = clean_input($_POST['pwd_id']);
    $address = clean_input($_POST['address']);
    $username = clean_input($_POST['username']);
    $password = clean_input($_POST['password']);
    $confirm_password = clean_input($_POST['confirm_password']);
    $role = 'customer'; 


    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$_!%*?&])[A-Za-z\d@$!_%*?&]{8,}$/', $password)) {
        $signupErr = "Password must be at least 8 characters long and <br> contain at least one lowercase letter, one uppercase <br> letter, one number, and one special character.";
    } elseif ($password !== $confirm_password) {
        $signupErr = "Passwords do not match.";
    } else {
     
        $accountObj = new Account();
        if ($accountObj->usernameExist($username)) {
            $signupErr = "Username already exists.";
        } else {
          
            $newAccount = new Account();
            $newAccount->first_name = $first_name;
            $newAccount->last_name = $last_name;
            $newAccount->sex = $sex;
            $newAccount->age = $age;
            $newAccount->contact_number = $contact_number;
            $newAccount->pwd_id = $pwd_id;
            $newAccount->address = $address;
            $newAccount->username = $username;
            $newAccount->password = $password;
            $newAccount->role = $role;

            if ($newAccount->add()) {
                echo "Registration successful!";
                header('location: user-landing.php');
            } else {
                $signupErr = "Registration failed.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .error {
            color: red;
        }

        .container {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: #fff;
            box-shadow: 1px 2px 3px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            border-radius: 10px;
            width: 400px;
        }

        .form-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-title a h1 {
            font-size: 40px;
            margin: 0;
            color: black;
        }

        .form-title a h1 span {
            color: red;
        }

        .form-title h2 {
            margin-top: 5px;
            font-size: 24px;
            color: #333;
        }

        .justify {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 15px;
        }

        .cc {
            flex: 1;
        }

        .cc label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .cc input, .cc select, .abc input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .abc {
            margin-top: 20px;
        }

        .abc input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }

        .abc input[type="submit"]:hover {
            background: #0056b3;
        }

        a {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="card">
            <div class="sign-form">
                <form action="signup.php" method="post">
                    <div class="form-title">
                        <a href="landing.php">
                            <h1>O<span>B</span></h1>
                        </a>
                        <h2>Sign Up</h2>
                    </div>
                    
                    <div class="justify">
                        <div class="cc">
                            <label for="first_name">First Name:</label>
                            <input type="text" name="first_name" id="first_name">
                        </div>

                        <div class="cc">
                            <label for="last_name">Last Name:</label>
                            <input type="text" name="last_name" id="last_name">
                        </div>
                    </div>

                    <div class="justify">
                        <div class="cc">
                            <label for="sex">Sex:</label>
                            <select name="sex" id="sex">
                                <option value="">--Select--</option>
                                <option value="Male" <?= (isset($sex) && $sex == 'Male') ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= (isset($sex) && $sex == 'Female') ? 'selected' : '' ?>>Female</option>
                            </select>
                        </div>

                        <div class="cc">
                            <label for="age">Age:</label>
                            <input type="number" name="age" id="age">
                        </div>
                    </div>

                   <div class="justify">
                        <div class="cc">
                            <label for="contact_number">Contact Number:</label>
                            <input type="tel" name="contact_number" id="contact_number">
                        </div>

                        <div class="cc">
                            <label for="pwd_id">PWD ID:</label>
                            <input type="text" name="pwd_id" id="pwd_id">
                        </div>
                   </div>

                    <div class="abc">
                        <label for="address">Address:</label>
                        <input type="text" name="address" id="address">

                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username">

                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password">

                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" name="confirm_password" id="confirm_password">
                        
                        <input type="submit" value="Sign Up" name="signup">

                        <?php if (!empty($signupErr)) { ?>
                            <p class="error"><?= $signupErr ?></p>
                        <?php } ?>
                    </div>
                </form>
            </div>    
        </div>
    </div>

</body>
</html>
