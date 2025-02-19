<?php 
session_start();
$theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light';

require_once('../configs/config.php');
require_once('../classes/User.php');

if (!isset($_SESSION['username']) && !isset($_SESSION['is_admin'])) {
    header("Location:" . BASE_PATH . "/admin");
}

$info = new User();
$userData = $info->getData($_SESSION['username']);
$fullName = $userData['name'];
$email=$userData['email'];
$names = explode(' ', $fullName);
$firstName = isset($names[0]) ? $names[0] : '';

if($_POST){
    $password=$_POST['password'];
    $confirm=$_POST['confirm_password'];
    if($password===$confirm){
        $res=$info->changePassword($password,$email);
        if($res){
            echo "<script>alert('Password changed successfully')</script>";
        }else{
            echo "<script>alert('Password change failed')</script>";
        }
    }else{
        echo "<script>alert('Passwords do not match')</script>";
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/admin/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp">
    <style>
        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        form {
            background: var(--color-white);
            padding: 2rem;
            border-radius: var(--border-radius-2);
            box-shadow: var(--box-shadow);
            width: 100%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
        }
        form label {
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: var(--color-dark);
        }
        form input {
            padding: 0.8rem;
            margin-bottom: 1.2rem;
            border-radius: var(--border-radius-1);
            border: 1px solid var(--color-info-light);
            font-size: 1rem;
            width: 100%;
        }
        form button {
            background: var(--color-primary);
            color: var(--color-white);
            padding: 0.8rem;
            border-radius: var(--border-radius-1);
            font-size: 1rem;
            font-weight:bold;
            cursor: pointer;
            transition: 0.3s;
        }
        form button:hover {
            background: var(--color-primary-variant);
        }
    </style>
</head>
<body class="<?= $theme === 'dark' ? 'dark-theme-variables' : '' ?>">
    <div class="container">
        <?php include_once('includes/sidebar.php'); ?>
        <div class="main-section">
            <div class="right">
                <div class="top">
                    <h1></h1>
                    <div class="right-elements">
                        <button id="menu-btn">
                            <span class="material-icons-sharp">menu</span>
                        </button>
                        <div class="theme-toggler">
                        <span class="<?= $theme === 'dark' ? 'active' : '' ?> material-icons-sharp">dark_mode</span>
                        <span class="<?= $theme === 'light' ? 'active' : '' ?> material-icons-sharp">light_mode</span>
                        </div>
                        <div class="profile">
                            <div class="info">
                                <p>Hey, <b><?= htmlspecialchars($firstName); ?></b></p>
                                <small class="text-muted">Admin</small>
                            </div>
                            <div class="profile-photo">
                                <span class="material-icons-sharp">account_circle</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <main>
                <h2>Change Password</h2>
                <form action="" method="POST">
                    <label for="password">New Password:</label>
                    <input type="password" id="password" name="password" required>
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <button type="submit">Change Password</button>
                </form>
            </main>
        </div>
    </div>
    <script src="./index.js"></script>
</body>
</html>
