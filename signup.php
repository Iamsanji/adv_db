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
    <link rel = "stylesheet" href = "styles/sign.css">
    <style>
        .error {
            color: red;
        }

        
    </style>
</head>
<body>
    <div class="container">

        <div class="card">
            <div class = "sign-form">
                <form action="signup.php" method="post">
                <a href="landing.php" style = "text-decoration: none; color: black; font-size: 30px;"><h1>O<span>B</span></h1></a>
                    <h2>Sign up</h2>
                    <label for="first_name">First Name:</label>
                    
                    <input type="text" name="first_name" id="first_name">
                
                    <label for="last_name">Last Name:</label>
                    
                    <input type="text" name="last_name" id="last_name">

                    <label for="sex">Sex:</label>
                    <select name="sex" id="sex">
                        <option value="">--Select--</option>
                        <option value="Male" <?= (isset($sex) && $sex == 'Male')? 'selected=true' : ''?>>Male</option>
                        <option value="Female" <?= (isset($sex) && $sex == 'Female')? 'selected=true' : ''?>>Female</option>
                    </select>

                    <label for="age">Age:</label>
                    <input type="number" name="age" id="age">

                    <label for="contact_number">Contact Number:</label>
                    <input type="tel" name="contact_number" id="contact_number">

                    <label for="pwd_id"> PWD ID:</label>
                    <input type="text" name="pwd_id" id="pwd_id">

                    <label for="address">Address:</label>
                    <input type="text" name="address" id="address">
                    
                    <label for="username">Username:</label>
                    
                    <input type="text" name="username" id="username">
                    
                    <label for="password">Password:</label>
                    
                    <input type="password" name="password" id="password">
                    
                    <label for="confirm_password">Confirm Password:</label>
                    
                    <input type="password" name="confirm_password" id="confirm_password">
                    
                    <input type="submit" value="Sign Up" name="signup">
                    <?php
                    if (!empty($signupErr)) {
                    ?>
                        <p class="error"><?= $signupErr ?></p>
                    <?php
                    }
                    ?>
                </form>
            </div>    
        </div>

    </div>
</body>
</html>