<?php 
session_start();
$theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light';
require_once('../classes/User.php');
require_once('../configs/config.php');
if(!isset($_SESSION['username']) && !isset($_SESSION['is_admin'])){
    header("Location:".BASE_PATH."/admin");
  }

   // Create User instance and fetch data
   $info = new User();
   $userData = $info->getData($_SESSION['username']);

   // Split full name into first name
   $fullName = $userData['name'];
   $names = explode(' ', $fullName);
   $firstName = isset($names[0]) ? $names[0] : '';

   $result=$info->getFeedback();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Contact Data</title>
    <link rel="stylesheet" href="/admin/css/message.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp">
    
</head>
<body class="<?= $theme === 'dark' ? 'dark-theme-variables' : '' ?>">
    <div class="container">
        <?php include_once('includes/sidebar.php'); ?>
        <div class="main-section">
            <!-- Right section at the top -->
    
      <div class="right">
        <div class="top">
          <!-- Dashboard heading on the left -->
          <h1>Feedback</h1>

          <!-- Menu button, theme toggler, and profile on the right -->
                    <div class="right-elements">
                        <button id="menu-btn">
                        <span class="material-icons-sharp">menu</span>
                        </button>
                        <div class="theme-toggler">
                        <!-- <span class="material-icons-sharp active">light_mode</span>
                        <span class="material-icons-sharp">dark_mode</span> -->
                        <span class="<?= $theme === 'dark' ? 'active' : '' ?> material-icons-sharp">dark_mode</span>
                        <span class="<?= $theme === 'light' ? 'active' : '' ?> material-icons-sharp">light_mode</span>
                        </div>
                        <div class="profile">
                        <div class="info">
                            <p>Hey, <b><?php echo $firstName;?></b></p>
                            <small class="text-muted">Admin</small>
                        </div>
                        <div class="profile-photo">
                            <span class="material-icons-sharp">
                            account_circle
                            </span>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            <main>
                <h2>Contact Us Data</h2>
                <table class="feedback-table">
                    <thead>
                        <tr>
                            <th>SN.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Feedback</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i=1;
                        foreach($result as $row){ ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $row['name']; ?></td>
                            <td><?= $row['email']; ?></td>
                            <td><?= $row['message']; ?></td>
                            <td><?= $row['created_at']; ?></td>
                        </tr>
                       <?php $i++; } ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
    <script src="./index.js"></script>
</body>
</html>
