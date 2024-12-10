<?php
require_once 'functions.php';
require_once 'account.class.php';

session_start();


if (!isset($_SESSION['account']) || !isset($_SESSION['account']['id'])) {
    echo "User not found or invalid data.";
    header('Location: signin.php');
    exit();
}



$user_id = $_SESSION['account']['id']; 
$accountObj = new Account();
$user_data = $accountObj->fetchById($user_id); 

$profilePicture = $accountObj->getProfilePicture($user_id);



/*if ($accountObj->update($user_id)) {
    echo "Profile updated successfully.";
    // Redirect to the same page to refresh the image
    header("Location: profile.php");
    exit();  // Make sure to call exit() to stop the script from continuing
}*/

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile']) ) {

     // Existing update logic
     $first_name = clean_input($_POST['first_name']);
     $last_name = clean_input($_POST['last_name']);
     // Other fields...

     if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $fileName = time() . '_' . basename($_FILES['profilePicture']['name']);
        $uploadFile = $uploadDir . $fileName;

        $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileType, $allowedTypes) && $_FILES['profilePicture']['size'] < 5000000) { // 5MB limit
            if (move_uploaded_file($_FILES['profilePicture']['tmp_name'], $uploadFile)) {
                $accountObj->updateProfilePicture($user_id, $uploadFile);
            } else {
                echo "Error uploading the file.";
            }
        } else {
            echo "Invalid file type or size.";
        }
    }


    $first_name = clean_input($_POST['first_name']);
    $last_name = clean_input($_POST['last_name']);
    $sex = clean_input($_POST['sex']);
    $age = clean_input($_POST['age']);
    $contact_number = clean_input($_POST['contact_number']);
    $pwd_id = clean_input($_POST['pwd_id']);
    $address = clean_input($_POST['address']);
    $username = $user_data['username']; 

    $password = clean_input($_POST['password']);
    if (!empty($password)) {
        
        $password = password_hash($password, PASSWORD_DEFAULT);
    } else {
        
        $password = $user_data['password'];
    }

    
    $accountObj->first_name = $first_name;
    $accountObj->last_name = $last_name;
    $accountObj->sex = $sex;
    $accountObj->age = $age;
    $accountObj->contact_number = $contact_number;
    $accountObj->pwd_id = $pwd_id;
    $accountObj->address = $address;
    $accountObj->username = $username; 
    $accountObj->password = $password;

    
    if ($accountObj->update($user_id)) {
        
        // Optionally redirect after update
         header('Location: profile.php');
         echo "<script>alert('Your profile has been updated successfully.');</script>";
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
    <style>

        .profile-picture-container {
            text-align: center;
            margin: 20px 0;
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 2px solid #ccc;
            object-fit: cover; /* Ensures the image scales properly without distortion */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease-in-out;
        }

        .profile-picture:hover {
            transform: scale(1.1); /* Slight zoom effect on hover */
        }

        /* General profile container */
.profile-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    width: 80%;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
}

/* Profile picture section */
.profile-left {
    width: 30%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.profile-picture-container {
    width: 100%;
    text-align: center;
}

.profile-picture {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #2c3e50;
}

/* Profile form section */
.profile-right {
    width: 65%;
}

h2 {
    text-align: left;
    font-size: 24px;
    color: #333;
    margin-bottom: 20px;
}

/* Form styling */
.profile-form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-gap: 15px;
}

/* Form group */
.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-size: 14px;
    color: #555;
    margin-bottom: 5px;
}

.form-group input,
.form-group select {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 4px;
    outline: none;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #3498db;
}

/* Adjust form layout for smaller screens */
@media (max-width: 768px) {
    .profile-container {
        flex-direction: column;
        width: 100%;
    }

    .profile-left, .profile-right {
        width: 100%;
    }

    .profile-form {
        grid-template-columns: 1fr;
    }
}

/* Submit button */
.form-actions {
    grid-column: span 2;
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.form-actions input[type="submit"] {
    padding: 10px 20px;
    font-size: 16px;
    color: white;
    background-color: #3498db;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.form-actions input[type="submit"]:hover {
    background-color: #2980b9;
}

/* Logout link styling */
.logout-link {
    text-align: center;
    margin-top: 20px;
}

.logout-link a {
    font-size: 16px;
    color: #e74c3c;
    text-decoration: none;
    font-weight: bold;
}

.logout-link a:hover {
    text-decoration: underline;
}




    </style>

</head>
<body>

    <?php
    // Display success message if the profile was updated
    if (isset($_SESSION['profile_updated']) && $_SESSION['profile_updated'] === true) {
        echo "<script>alert('Your profile has been updated successfully.');</script>";
        unset($_SESSION['profile_updated']); // Remove the flag
    }
    ?>

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
    <div class="profile-container">

        <!-- Profile Picture Section -->
        <div class="profile-left">
            <div class="profile-picture-container">
                <img src="<?= htmlspecialchars($profilePicture) ?>" alt="Profile Picture" class="profile-picture">
            </div>
        </div>

        

        <!-- Profile Form Section -->
        <div class="profile-right">
            <h2>Profile</h2>

            <form action="profile.php" method="POST" enctype="multipart/form-data" class="profile-form">

                <div class="form-group">
                    <label for="profilePicture">Profile Picture:</label>
                    <input type="file" name="profilePicture" id="profilePicture" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($user_data['first_name']) ?>">
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($user_data['last_name']) ?>">
                </div>

                <div class="form-group">
                    <label for="sex">Sex:</label>
                    <select name="sex" id="sex">
                        <option value="Male" <?= $user_data['sex'] == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $user_data['sex'] == 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" name="age" id="age" value="<?= htmlspecialchars($user_data['age']) ?>">
                </div>

                <div class="form-group">
                    <label for="contact_number">Contact Number:</label>
                    <input type="tel" name="contact_number" id="contact_number" value="<?= htmlspecialchars($user_data['contact_number']) ?>">
                </div>

                <div class="form-group">
                    <label for="pwd_id">PWD ID:</label>
                    <input type="text" name="pwd_id" id="pwd_id" value="<?= htmlspecialchars($user_data['pwd_id']) ?>">
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" name="address" id="address" value="<?= htmlspecialchars($user_data['address']) ?>">
                </div>

                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" value="<?= htmlspecialchars($user_data['username']) ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="password">New Password (leave blank to keep current):</label>
                    <input type="password" name="password" id="password" value=""> 
                </div>

                <div class="form-actions">
                    <input type="submit" value="Update Profile" name="update_profile">
                    
                </div>
                

            </form>

        </div>

        

    </div>

    
</main>



</body>
</html>
