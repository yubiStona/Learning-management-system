<?php 
  session_start();
  $theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light';
  require_once('../configs/config.php');
  require_once('../classes/User.php');
  require_once('../classes/Contribution.php');
  require_once('../classes/materialCRUD.php');
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

    // Create Contribution instance and fetch data
    $contribution = new Contribution();
    $result = $contribution->getRecent();

    // Create Material instance and fetch data
    $material=new materialCRUD();

    //count total of all
    $totalMaterial=$material->totalMaterials();
    $totalM=$totalMaterial['total'];

    $totalContribution=$contribution->totalContribution();
    $totalC=$totalContribution['total'];

    $totalUsers=$info->totalUsers();
    $totalU=$totalUsers['total'];

    // Define goals for progress calculation
    $goalUsers = 40;
    $goalMaterials = 200;
    $goalContributions = 200;

    // Calculate progress percentages
    $userProgress = ($totalU / $goalUsers) * 100;
    $materialProgress = ($totalM / $goalMaterials) * 100;
    $contributionProgress = ($totalC / $goalContributions) * 100;
  

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>admin dashboard</title>
  <!--STYLESHEET-->
  <link rel="stylesheet" href="/admin/css/style.css" />

  <!--MATERIAL  CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" />
</head>

<body class="<?= $theme === 'dark' ? 'dark-theme-variables' : '' ?>">
  <div class="container">
    <?php include_once('includes/sidebar.php'); ?>
    <div class="main-section">
      <!-- Right section at the top -->
      <div class="right">
        <div class="top">
          <!-- Dashboard heading on the left -->
          <h1>Dashboard</h1>

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
                <p>Hey, <b><?php echo htmlspecialchars($firstName);?></b></p>
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
     
      <!--MAIN SECTION-->
      <main>
        <div class="date">
          <input type="date" id="currentDate"/>
        </div>

        <div class="insights">
          <div class="users">
            <span class="material-icons-sharp">analytics</span>
            <div class="middle">
              <div class="left">
                <h3>Total users</h3>
                <h1><?php echo $totalU; ?></h1>
              </div>
              <div class="progress">
                <svg>
                  <!-- <circle cx="38" cy="38" r="36"></circle> -->
                  <circle cx="40" cy="40" r="35" style="stroke-dasharray: 220; stroke-dashoffset: <?php echo 220 - ($userProgress * 2.2); ?>;"></circle>
                </svg>
                <div class="number">
                  <p><?php echo round($userProgress, 1); ?>%</p>
                </div>
              </div>
            </div>
            <small class="text-muted">User growth towards <?php echo $goalUsers; ?> target</small>
          </div>
          <!--END OF USERS-->
          <div class="materials">
            <span class="material-icons-sharp">library_books</span>
            <div class="middle">
              <div class="left">
                <h3>Total Materials</h3>
                <h1><?php echo $totalM ?></h1>
              </div>
              <div class="progress">
                <svg>
                <circle cx="40" cy="40" r="35" style="stroke-dasharray: 220; stroke-dashoffset: <?php echo 220 - ($materialProgress * 2.2); ?>;"></circle>
                </svg>
                <div class="number">
                  <p><?php echo round($materialProgress, 1); ?>%</p>
                </div>
              </div>
            </div>
            <small class="text-muted">Material uploads reaching <?php echo $goalMaterials; ?> goal</small>
          </div>
          <!--END OF MATERIALS-->
          <div class="insight contribution">
            <span class="material-icons-sharp">volunteer_activism</span>
            <div class="middle">
              <div class="left">
                <h3>Total Contribution</h3>
                <h1><?php echo $totalC ?></h1>
              </div>
              <div class="progress">
                <svg>
                  <!-- <circle cx="38" cy="38" r="36"></circle> -->
                  <circle cx="40" cy="40" r="35" style="stroke-dasharray: 220; stroke-dashoffset: <?php echo 220 - ($contributionProgress * 2.2); ?>;"></circle>
                </svg>
                <div class="number">
                  <p><?php echo round($contributionProgress, 1); ?>%</p>
                </div>
              </div>
            </div>
            <small class="text-muted">Contributions progressing to <?php echo $goalContributions; ?> target</small>
          </div>
          <!--END OF DOWNLOADS-->
        </div>
        <!--End of insights-->

        <div class="recents">
          <h2>Recent updates</h2>
          <table>
            <thead>
              <tr>
                <th>SN.</th>
                <th>Material Name</th>
                <th>uploaded BY</th>
                <th>Date</th>
                <th>Status</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php
                $i=1;
                foreach($result as $row){?>
                  <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo $row['file_name'];?></td>
                    <td><?php echo $row['email'];?></td>
                    <td><?php echo $row['uploaded_at'];?></td>
                    <?php if($row['status'] === 'Approved'){?>
                      <td class="success"><?php echo $row['status'];?></td>
                    <?php }else{?>
                    <td class="warning"><?php echo $row['status'];?></td>
                    <?php }?>
                    <td class="primary"><a href="./contribute.php">Details</a></td>
                  </tr>
                <?php $i++;}?>
            </tbody>
          </table>
          <a href="./contribute.php">Show All</a>
        </div>
      </main>
    </div>
  </div>
  <script src="./index.js"></script>
  <script>
  document.getElementById('currentDate').valueAsDate = new Date();
</script>
</body>

</html>