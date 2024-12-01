<?php

    session_start(); // Ensure the session is started before accessing session variables

    if (!isset($_SESSION['account']) || !isset($_SESSION['account']['id'])) {
        header('Location: signin.php');
    // echo "<script type='text/javascript'>alert(' User is not logged in.');</script>";
        exit(); // Exit if the user is not logged in
    }
    $user_id = $_SESSION['account']['id']; // Retrieve user ID from session

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=account_circle" />
    <link rel = "stylesheet" href = "styles/landing.css">
    <title>User</title>
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
                <a href="profile.php"><span class="material-symbols-outlined" style = "color: black;">account_circle</span></a>
            </nav>
            
        </nav>
    </header>

    <main>
        <div class="main-title">
           
            <p>Landing Page</p>
        </div>

        <a href="add.php">add</a>
        <a href="logout.php">logout</a>
    </main>

    <footer>
    </footer>    
</body>
</html>