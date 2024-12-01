<?php
require_once 'functions.php';
require_once 'account.class.php';

session_start();

$username = $password = '';
$accountObj = new Account();
$loginErr = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean_input($_POST['username']);
    $password = clean_input($_POST['password']);

    if ($accountObj->login($username, $password)) {
        $data = $accountObj->fetch($username);
        $_SESSION['account'] = $data;

        // Redirect based on role
        if ($data['role'] === 'customer') {
            header('location: user-landing.php'); // Redirect to customer page
        } else {
            header('location: admin.php'); // Redirect to staff dashboard
        }
    } else {
        $loginErr = 'Invalid username/password';
    }
} else {
    if (isset($_SESSION['account'])) {
        if ($_SESSION['account']['is_staff']) {
            header('location: admin.php'); // Redirect to staff dashboard
        } else {
            header('location: user-landing.php'); // Redirect to customer page
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link rel = "stylesheet" href = "styles/sign.css">
    
</head>
<body>
    <div class="container">

        <div class="card">
            
            <div class="sign-form">
                    <form action="signin.php" method="post">
                        <a href="landing.php" style = "text-decoration: none; color: black; font-size: 30px;"><h1>O<span>B</span></h1></a>
                        <h2>Sign in</h2>
                        <label for="username">Username/Email</label>
                        
                        <input type="text" name="username" id="username">
                        
                        <label for="password">Password</label>
                        
                        <input type="password" name="password" id="password">
                        
                        <input type="submit" value="Sign in" name="login">
                        <?php
                        if (!empty($loginErr)) {
                        ?>
                            <p class="error"><?= $loginErr ?></p>
                        <?php
                        }
                        ?>
                        <p>Doesn't have an account? <a href="signup.php">signup</a></p>
                    </form>
            </div>
        </div>
    </div>
</body>
</html>