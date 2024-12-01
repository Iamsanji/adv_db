<?php
require_once 'functions.php';
require_once 'account.class.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['account']) || !isset($_SESSION['account']['id'])) {
    echo "User not found or invalid data.";
    header('Location: signin.php');
    exit(); // Exit if the user is not logged in
}

$user_id = $_SESSION['account']['id']; // Get logged-in user's ID
$accountObj = new Account();
$user_data = $accountObj->fetchById($user_id); // Fetch user data based on ID

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get updated data from form
    $first_name = clean_input($_POST['first_name']);
    $last_name = clean_input($_POST['last_name']);
    $sex = clean_input($_POST['sex']);
    $age = clean_input($_POST['age']);
    $contact_number = clean_input($_POST['contact_number']);
    $pwd_id = clean_input($_POST['pwd_id']);
    $address = clean_input($_POST['address']);
    $username = $user_data['username']; // Keep the existing username (do not allow updating)

    // Check if password is provided, if yes, hash it; otherwise, keep the existing password
    $password = clean_input($_POST['password']);
    if (!empty($password)) {
        // Only update password if a new one is provided
        $password = password_hash($password, PASSWORD_DEFAULT); // Hash the new password
    } else {
        // If no new password, keep the existing password
        $password = $user_data['password'];
    }

    // Set the object properties
    $accountObj->first_name = $first_name;
    $accountObj->last_name = $last_name;
    $accountObj->sex = $sex;
    $accountObj->age = $age;
    $accountObj->contact_number = $contact_number;
    $accountObj->pwd_id = $pwd_id;
    $accountObj->address = $address;
    $accountObj->username = $username; // Username stays the same
    $accountObj->password = $password; // Updated password or existing one

    // Perform the update in the database
    if ($accountObj->update($user_id)) {
        echo "Profile updated successfully.";
        // Optionally redirect after update
        // header('Location: profile.php');
    } else {
        echo "Error updating profile.";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=account_circle" />
    <link rel="stylesheet" href="styles/landing.css">
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
                <a href="profile.php"><span class="material-symbols-outlined" style="color: black;">account_circle</span></a>
            </nav>
        </nav>
    </header>

    <main>
        <div class="profile-container">
            <h2>Edit Profile</h2>

                <form action="profile.php" method="POST">
                    <label for="first_name">First Name:</label>
                    <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($user_data['first_name']) ?>">

                    <label for="last_name">Last Name:</label>
                    <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($user_data['last_name']) ?>">

                    <label for="sex">Sex:</label>
                    <select name="sex" id="sex">
                        <option value="Male" <?= $user_data['sex'] == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $user_data['sex'] == 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>

                    <label for="age">Age:</label>
                    <input type="number" name="age" id="age" value="<?= htmlspecialchars($user_data['age']) ?>">

                    <label for="contact_number">Contact Number:</label>
                    <input type="tel" name="contact_number" id="contact_number" value="<?= htmlspecialchars($user_data['contact_number']) ?>">

                    <label for="pwd_id">PWD ID:</label>
                    <input type="text" name="pwd_id" id="pwd_id" value="<?= htmlspecialchars($user_data['pwd_id']) ?>">

                    <label for="address">Address:</label>
                    <input type="text" name="address" id="address" value="<?= htmlspecialchars($user_data['address']) ?>">

                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" value="<?= htmlspecialchars($user_data['username']) ?>" disabled> <!-- Username is displayed but not editable -->

                    <label for="password">New Password (leave blank to keep current):</label>
                    <input type="password" name="password" id="password" value=""> <!-- Password is optional -->

                    <input type="submit" value="Update Profile">

                </form>


        </div>
    </main>

</body>
</html>
