<?php 
     require_once('../configs/config.php');
     require_once('../classes/User.php');
     require '../classes/courseCRUD.php';
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
     $getInfo=new courseCRUD();
     $listCourse=$getInfo->list();
     $subjects=$getInfo->getSubject();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Faculty</title>
    <!--STYLESHEET-->
    <link rel="stylesheet" href="./css/course.css" />
    <script src="./htmx.js"></script>
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
            <h1>Courses</h1>
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
                <!-- Course Table -->
                <div class="table-container">
                    <div class="table-header">
                        <h2>Course List</h2>
                        <button class="add-btn" onclick="window.location.href='./addCourse.php'">Add Courses</button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Sn</th>
                                <th>Course ID</th>
                                <th>Course Name</th>
                                <th>Total Semester</th>
                                <th>Total Subject</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-1">
                            <?php 
                                $i=1;
                                foreach($listCourse as $course){
                            ?>
                            <tr>
                                <td><?php echo $i?></td>
                                <td><?php echo $course['course_id']?></td>
                                <td><?php echo $course['course_name']?></td>
                                <td><?php echo $course['total_semester']?></td>
                                <td><?php echo $course['total_subject']?></td>
                                <td>
                                <div class="button-container">
                                    <button class="edit-btn" onclick="window.location.href='./updateCourse.php?id=<?php echo $course['course_id'];?>'">Edit</button>
                                    <button class="delete-btn" hx-delete="deleteCourse.php?id=<?php echo $course['course_id'];?>" hx-target="#tbody-1" hx-swap="outerHTML"  hx-confirm="Are you sure you want to delete this row?">Delete</button>
                                </div>
                                </td>
                            </tr>
                            <?php $i++;}?>
                        </tbody>
                    </table>
                </div>

                <!-- Subject Table -->
                <div class="table-container">
               
                    <div class="table-header">
                        <h2>Subject List</h2>
                        <button class="add-btn" onclick="window.location.href='./addSubject.php'">Add Subject</button>
                    </div>
                     <!-- Search bar -->
                     <div class="search-container">
                            <input type="text" id="searchBox" placeholder="Search..." onkeyup="filterSubjects()">
                        </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Sn</th>
                                <th>Subject ID</th>
                                <th>Subject Name</th>
                                <th>Semester</th>
                                <th>Course</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-2">
                            <?php 
                            $i=1;
                            foreach($subjects as $subject){ 
                            ?>
                            <tr>
                                <td><?php echo $i ?></td>
                                <td><?php echo $subject['subject_id'] ?></td>
                                <td><?php echo $subject['subject_name'] ?></td>
                                <td><?php echo $subject['semester'] ?></td>
                                <td><?php echo $subject['course_id'] ?></td>
                                <td>
                                <div class="button-container">
                                    <button class="edit-btn" onclick="window.location.href='./updateSubject.php?id=<?php echo $subject['subject_id'];?>'">Edit</button>
                                    <button class="delete-btn" hx-delete="deleteSubject.php?id=<?php echo $subject['subject_id'];?>" hx-target="#tbody-2" hx-swap="outerHTML"  hx-confirm="Are you sure you want to delete this row?">Delete</button>
                                </div>
                                </td>
                            </tr>
                            <?php $i++;}?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
    <script src="./index.js"></script>
    <script>
        function filterSubjects() {
    // Get the search input value and convert it to lowercase for case-insensitive comparison
    const searchQuery = document.getElementById('searchBox').value.toLowerCase();

    // Get the table body containing the subject rows
    const tableBody = document.getElementById('tbody-2');

    // Get all the rows in the table body
    const rows = tableBody.getElementsByTagName('tr');

    // Loop through all the rows
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];

        // Get all the cells in the current row
        const cells = row.getElementsByTagName('td');

        // Assume the row should be hidden by default
        let shouldShow = false;

        // Loop through the cells (skip the first cell which is the serial number)
        for (let j = 1; j < cells.length - 1; j++) { // Skip the first (Sn) and last (Actions) cells
            const cellText = cells[j].textContent.toLowerCase();

            // If the cell text contains the search query, mark the row to be shown
            if (cellText.includes(searchQuery)) {
                shouldShow = true;
                break; // No need to check other cells in this row
            }
        }

        // Show or hide the row based on the search result
        if (shouldShow) {
            row.style.display = ''; // Show the row
        } else {
            row.style.display = 'none'; // Hide the row
        }
    }
}

// Attach the filterSubjects function to the search box's keyup event
document.getElementById('searchBox').addEventListener('keyup', filterSubjects);
    </script>
    
</body>

</html>