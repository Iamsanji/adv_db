<?php

    session_start(); // Ensure the session is started before accessing session variables

    if (!isset($_SESSION['account']) || !isset($_SESSION['account']['id'])) {
        header('Location: signin.php');
    // echo "<script type='text/javascript'>alert(' User is not logged in.');</script>";
        exit(); // Exit if the user is not logged in
    }
    $user_id = $_SESSION['account']['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=account_circle" />
    <link rel = "stylesheet" href = "styles/landing.css">
    <title>User</title>
    <!-- Add some styles -->
<style>
    /* Main layout styles */
    * {
        margin: 0;
        padding: 0;
        
    }

    main {
        padding: 40px 20px;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        text-align: center;
    }

    /* Card styles */
    .card {
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        transition: transform 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .main-title {
        font-size: 3rem;
        color: #2c3e50;
        font-weight: bold;
    }

    .main-title span {
        color: #e74c3c;
    }

    h2 {
        font-size: 2rem;
        color: #34495e;
        margin: 20px 0;
    }

    p {
        font-size: 1.2rem;
        color: #7f8c8d;
        margin: 10px 0 30px;
    }

    .booklet a {
        text-decoration: none;
        font-size: 1.2rem;
        color: #fff;
        background-color: #3498db;
        padding: 15px 30px;
        border-radius: 30px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: background-color 0.3s;
    }

    .booklet a:hover {
        background-color: #2980b9;
    }

    /* Logo styles */
    .logo h3 {
        font-size: 3rem;
        font-weight: bold;
        color: #2c3e50;
    }

    .logo span {
        color: #e74c3c;
    }

    /* Footer styles */
    footer {
        background-color: #2c3e50;
        color: #fff;
        padding: 20px;
        margin-top: 40px;
    }

    .footer-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
    }

    .footer-container p {
        margin: 0;
    }

    .footer-links {
        display: flex;
        gap: 20px;
    }

    .footer-links a {
        text-decoration: none;
        color: #fff;
        font-size: 1rem;
        transition: color 0.3s;
    }

    .footer-links a:hover {
        color: #e74c3c;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container {
            padding: 20px;
        }

        .card {
            padding: 20px;
        }

        .footer-container {
            flex-direction: column;
            align-items: flex-start;
        }

        .footer-links {
            margin-top: 10px;
            justify-content: flex-start;
        }
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


    <main>
    <div class="container">
        <div class="card">
            <h1 class="main-title">O<span>B</span>:</h1>
            <h2>Senior & PWD Monitoring and Management System.</h2>
            <p>Streamlining discounts and monitoring for Seniors and PWDs with efficiency and care.</p>
            <div class="booklet">
                <a href="booklet.php" class="btn">BOOKLET</a>
            </div>
        </div>

        <div class="logo">
            <h3>O<span>B</span></h3>
        </div>
    </div>
    </main>

    <footer>
        <div class="footer-container">
            <p>&copy; 2024 O<span>B</span>. All rights reserved.</p>
            <div class="footer-links">
                <a href="privacy-policy.php">Privacy Policy</a>
                <a href="terms-of-service.php">Terms of Service</a>
            </div>
        </div>
    </footer>

     
</body>
</html>