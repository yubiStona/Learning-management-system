<?php 
     require_once('../configs/config.php');
     require_once('../classes/Contribution.php');
     require '../classes/User.php';
     session_start();
     $theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light';
     if(!isset($_SESSION['username']) && !isset($_SESSION['is_admin'])){
         header("Location:".BASE_PATH."/admin");
     }

     $info = new User();
     $username = $_SESSION['username'];
     $userData = $info->getData($username);
 
     // Split full name into first name
     $fullName = $userData['name'];
     $names = explode(' ', $fullName);
     $firstName = isset($names[0]) ? $names[0] : '';

    $getData=new Contribution();
    //get data to tabulate the form
    $listInfo=$getData->getContributed();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <!--STYLESHEET-->
    <link rel="stylesheet" href="./css/contribute.css" />
    <script src="./htmx.js"></script>
    <!--MATERIAL  CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" />
</head>

<body class="<?= $theme === 'dark' ? 'dark-theme-variables' : '' ?>">
    <div class="container">
    <?php include_once('includes/sidebar.php');?>
        <div class="main-section">
            <!-- Right section at the top -->
                <div class="right">
            <div class="top">
                <h1>Contributions</h1>
                <!-- Menu button, theme toggler, and profile on the right -->
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
                    <p>Hey, <b><?php echo htmlspecialchars($firstName); ?></b></p>
                    <small class="text-muted">Admin</small>
                    </div>
                    <div class="profile-photo">
                    <span class="material-icons-sharp">account_circle</span>
                    </div>
                </div>
                </div>
            </div>
            </div>
            <!--MAIN SECTION-->
            <main>
                    <!-- Study Material Table -->
                    <div class="table-container">
                        <div class="table-header">
                        <h2>Contribution content</h2>
                        <div>
                        <select id="statusFilter" class="filter-dropdown">
                        <option value="all">All</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        </select>
                        </div>
                        
                    </div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Semester</th>
                                        <th>Subject</th>
                                        <th>Type</th>
                                        <th>File_Name</th>
                                        <th>Description</th>
                                        <th>Upoaded_By</th>
                                        <th>Uploaded_At</th>
                                        <th>Status</th>
                                        <th>Action </th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-1">

                                    <?php foreach($listInfo as $list){?>
                                    <tr data-status="<?php echo strtolower($list['status']); ?>">
                                    <td><?php echo htmlspecialchars($list['course_id']);?></td>
                                        <td><?php echo htmlspecialchars($list['semester']);?></td>
                                        <td><?php echo htmlspecialchars($list['subject_name']);?></td>
                                        <td><?php echo htmlspecialchars($list['content_type']);?></td>
                                        <td><a href="../uploads/<?php echo htmlspecialchars($list['file_name']);?>" target="_blank"><?php echo htmlspecialchars($list['file_name']);?> </a></td>
                                        <td><?php echo htmlspecialchars($list['description']);?></td>
                                        <td><?php echo htmlspecialchars($list['email']);?></td>
                                        <td><?php echo htmlspecialchars($list['uploaded_at']);?></td>   
                                        <td class="status-cell">
                                        <span class="status-badge <?php echo strtolower($list['status']);?>">
                                        <?php echo htmlspecialchars($list['status']);?>
                                        </span>     
                                       </td>
                                       <!--Action column-->
                                       <td>
                                        <div class="button-container">  
                                            <?php if($list['status']=='pending'){?>
                                                <button class="approve-btn" data-id="<?php echo $list['id']; ?>" hx-get="./filterContribution.php?id=<?php echo $list['id'];?>&status=approved&subject_id=<?php echo $list['subject_id'];?>" hx-target="#tbody-1" hx-swap="outerHTML">Approve</button>  
                                                <button class="reject-btn" data-id="<?php echo $list['id'];?>" hx-get="./deleteContribution.php?id=<?php echo htmlspecialchars($list['id']);?>" hx-target="#tbody-1" hx-swap="outerHTML">Reject</button>
                                            <?php }else{ ?>
                                                <button class="delete-btn" data-id="<?php echo $list['id']; ?>" hx-get="./deleteContribution.php?id=<?php echo htmlspecialchars($list['id']);?>" hx-target="#tbody-1" hx-swap="outerHTML">Delete</button>
                                            <?php } ?>
                                        </div>
                                       </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                
            </main>
        </div>
    </div>
    <div id="descriptionModal" class="modal">
    <div class="modal-content">
        <p id="modalDescription"></p>
    </div>
</div>
    <script src="./index.js"></script>
    <script>
       
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('descriptionModal');
        const modalDescription = document.getElementById('modalDescription');
        

        // Open modal when "Read More" is clicked
        document.querySelectorAll('.read-more').forEach(link => {   
            link.addEventListener('click', (event) => {
                event.preventDefault(); // Prevent default link behavior
                const fullDescription = link.getAttribute('data-full-description');
                modalDescription.textContent = fullDescription;
                modal.style.display = 'block';
            });
        });

        // Close modal when clicking outside the modal
        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });

    </script>
    <script>
// Filter functionality
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const rowStatus = row.dataset.status;
        if(status === 'all' || rowStatus === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
</body>

</html>