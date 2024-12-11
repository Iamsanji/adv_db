<?php

    session_start();

    if(isset($_SESSION['account'])){
        if(!$_SESSION['account']['is_staff']){
            header('location: signin.php');
        }
    }else{
        header('location: signin.php');
    }

    require_once 'prescribe.class.php';
    require_once 'database.php'; 
    $db = new Database();

    $admin_id = $_SESSION['account']['id'];


    $sql = $db->connect()->query("SELECT COUNT(*) as total_patients FROM account WHERE role = 'customer'");
    $sql->bindParam(':admin_id', $admin_id);
    $patients = $sql->fetch();

    $sql = $db->connect()->query("SELECT COUNT(*) as total_admins FROM account WHERE role = 'admin'");
    $sql->bindParam(':admin_id', $admin_id);
    $total_admins = $sql->fetch();

    $sql = $db->connect()->query("SELECT COUNT(*) as total_prescriptions FROM prescribe");
    $sql->bindParam(':admin_id', $admin_id);
    $prescriptions = $sql->fetch();

    $sql = $db->connect()->query("SELECT COUNT(*) as not_done FROM prescribe WHERE status = 'Not Done'");
    $sql->bindParam(':admin_id', $admin_id);
    $notdone = $sql->fetch();

    //new dec11
    $sql = $db->connect()->query("SELECT COUNT(*) as done FROM prescribe WHERE status = 'Done'");
    $sql->bindParam(':admin_id', $admin_id);
    $done = $sql->fetch();

    $sql = $db->connect()->query("SELECT COUNT(DISTINCT user_id) as active_users FROM prescribe");
    $sql->bindParam(':admin_id', $admin_id);
    $active_users = $sql->fetch();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar container */
        .sidebar {
            width: 200px;
            background-color: #2c3e50;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px 0;
            height: 100vh;
            position: fixed;
        }

        .sidebar h2 {
            padding: 1rem;
            text-align: center;
        }

        /* Sidebar links */
        .sidebar a {
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            display: block;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        /* Active link */
        .sidebar a.active {
            background-color: #1abc9c;
        }

        /* Main content */
        .main-content {
            margin-left: 200px;
            padding: 20px;
            flex: 1;
        }


        /* Dashboard layout */
        .dashboard {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        /* Dashboard box */
        .dashboard-box {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 1px 2px 3px rgba(0, 0, 0, 0.5);
        }

        .dashboard-box h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .dashboard-box p {
            font-size: 20px;
            color: #16a085;
        }

        .dashboard-box img {
            height: 50px;
        }

        .dashboard-box {
            display: flex;
            justify-content: space-between;
        }

        

        /* Responsive layout */
        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    
<div class="sidebar">
    <h2><?= 'Welcome ' . $_SESSION['account']['first_name'] ?></h2>
    <br>
    <a href="dashboard.php">Dashboard</a>
    <a href="patients.php">Patients</a>
    <a href="admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'active' : '' ?>">Prescriptions</a>
    <?php if ($_SESSION['account']['role'] == 'superadmin'): ?>
        <a href="doctors.php" class="<?= basename($_SERVER['PHP_SELF']) == 'doctors.php' ? 'active' : '' ?>">Doctors</a>
    <?php endif; ?>
    <a href="admin-profile.php">Profile</a>
    <a href="logout.php">Logout</a>
</div>


    <div class="main-content">
    <h1><?= 'Dr.' . $_SESSION['account']['first_name'] ?> Dashboard</h1>
    <br>
    <br>

        <div class="dashboard">

            <div class="dashboard-box">
                <div class="aa">
                    <h3>Total Patients</h3>
                    <p><?= $patients['total_patients'] ?></p>
                </div>
                <div class="img-box">
                    <img src = "user.png">
                </div>
            </div>

            <div class="dashboard-box">
                <div class="aa">
                    <h3>Total Prescriptions</h3>
                    <p><?= $prescriptions['total_prescriptions'] ?></p>
                </div>
                <div class="img-box">
                    <img src = "prescription.png">
                </div>
            </div>

            <div class="dashboard-box">
                <div class="aa">
                    <h3>Active Users</h3>
                    <p><?= $active_users['active_users'] ?></p>
                </div>
                <div class="img-box">
                    <img src = "check.png">
                </div>
            </div>

            <div class="dashboard-box">
                <div class="aa">
                    <h3>Not Done</h3>
                    <p><?= $notdone['not_done'] ?></p>
                </div>
                <div class="img-box">
                    <img src = "cancel.png">
                </div>
            </div>

            <div class="dashboard-box">
                <div class="aa">
                    <h3>Done</h3>
                    <p><?= $done['done'] ?></p>
                </div>
                <div class="img-box">
                    <img src = "done.png">
                </div>
            </div>

            <div class="dashboard-box">
                <div class="aa">
                    <h3>Total Admin</h3>
                    <p><?= $total_admins['total_admins'] ?></p>
                </div>
                <div class="img-box">
                    <img src = "medical.png">
                </div>
            </div>

        </div>
    </div>

</body>
</html>
